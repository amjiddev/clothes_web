<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\CustomerMeasurement;
use App\Models\StitchingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Receptionist Order Controller
 * 
 * Wrapper around Apps\OrderController for receptionist workflows
 * Handles order type selection, stitching order creation, and payment handling
 */
class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('receptionist.only');
    }

    /**
     * Display a listing of orders for receptionist
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'orderItems', 'stitchingOrder', 'payments');

        // Search by order number or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by order type (receptionist specific)
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range (receptionist specific)
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Filter by pending payment (receptionist specific)
        if ($request->has('pending_payment') && $request->pending_payment) {
            $query->where('payment_status', 'pending');
        }

        $orders = $query->latest()->paginate(15)->appends($request->query());

        return view('receptionist.orders.index', compact('orders'));
    }

    /**
     * Show order creation wizard - Step 1: Select Customer
     */
    public function create()
    {
        $customers = User::where(function($q) {
            $q->doesntHave('roles')
              ->orWhereHas('roles', function($role) {
                  $role->where('name', 'customer');
              });
        })->where('is_blocked', false)
          ->orderBy('name')
          ->get();

        return view('receptionist.orders.create.step1-customer', compact('customers'));
    }

    /**
     * Step 2: Select Order Type
     */
    public function selectOrderType(Request $request)
    {
        $customerId = $request->query('customer_id');
        $customer = User::findOrFail($customerId);

        // Verify customer is not blocked
        if ($customer->is_blocked) {
            return back()->with('error', 'Cannot create order for blocked customer.');
        }

        // Get customer's measurements for stitching orders
        $measurements = CustomerMeasurement::where('user_id', $customerId)
            ->latest()
            ->get();

        // Get products by category
        $categories = Category::with('products')->active()->get();
        $products = Product::where('is_active', true)->get();

        return view('receptionist.orders.create.step2-type', compact(
            'customer',
            'customerId',
            'measurements',
            'categories',
            'products'
        ));
    }

    /**
     * Step 3: Order Summary
     */
    public function orderSummary(Request $request)
    {
        $orderType = $request->input('order_type');
        $customerId = $request->input('customer_id');
        $customer = User::findOrFail($customerId);

        // Verify customer is not blocked
        if ($customer->is_blocked) {
            return back()->with('error', 'Cannot create order for blocked customer.');
        }

        // Validate order type
        if (!in_array($orderType, ['ready_made', 'stitching', 'combined'])) {
            return back()->with('error', 'Invalid order type selected.');
        }

        // Get products and calculate totals
        $items = $this->processOrderItems($request, $orderType);

        // Calculate totals
        $subtotal = collect($items['products'])->sum('total');
        $stitchingCharge = floatval($request->input('stitching_charge', 0));
        $discount = floatval($request->input('discount', 0));
        $tax = floatval($request->input('tax', 0));
        $total = $subtotal + $stitchingCharge + $tax - $discount;

        return view('receptionist.orders.create.step3-summary', compact(
            'customer',
            'customerId',
            'orderType',
            'items',
            'subtotal',
            'stitchingCharge',
            'discount',
            'tax',
            'total'
        ));
    }

    /**
     * Step 4: Payment & Generate Order
     */
    public function paymentStep(Request $request)
    {
        $customerId = $request->input('customer_id');
        $customer = User::findOrFail($customerId);
        $total = floatval($request->input('total'));

        return view('receptionist.orders.create.step4-payment', compact(
            'customer',
            'customerId',
            'total'
        ));
    }

    /**
     * Store the order (create order with all details)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'order_type' => 'required|in:ready_made,stitching,combined',
            'subtotal' => 'required|numeric|min:0',
            'stitching_charge' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank_transfer,online',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'delivery_date' => 'nullable|date|after:today',
        ]);

        // Verify customer exists and is not blocked
        $customer = User::findOrFail($validated['customer_id']);
        if ($customer->is_blocked) {
            return back()->with('error', 'Cannot create order for blocked customer.');
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => $validated['customer_id'],
                'type' => $validated['order_type'],
                'status' => 'pending',
                'subtotal' => $validated['subtotal'],
                'stitching_charge' => $validated['stitching_charge'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'total' => $validated['total'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['paid_amount'] >= $validated['total'] ? 'paid' : 'pending',
                'notes' => $validated['notes'],
                'delivery_date' => $validated['delivery_date'],
            ]);

            // Add order items
            $this->addOrderItems($request, $order);

            // Handle stitching order if needed
            if (in_array($validated['order_type'], ['stitching', 'combined'])) {
                $this->createStitchingOrder($request, $order);
            }

            // Record initial payment if paid amount > 0
            if ($validated['paid_amount'] > 0) {
                Payment::create([
                    'order_id' => $order->id,
                    'user_id' => auth()->id(),
                    'amount' => $validated['paid_amount'],
                    'payment_method' => $validated['payment_method'],
                    'status' => 'completed',
                    'processed_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('receptionist.orders.show', $order)
                            ->with('success', 'Order created successfully! Order ID: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product', 'stitchingOrder', 'payments');

        // Calculate payment status
        $paidAmount = $order->payments ? $order->payments->where('status', 'completed')->sum('amount') : 0;
        $remainingAmount = max(0, $order->total - $paidAmount);

        return view('receptionist.orders.show', compact(
            'order',
            'paidAmount',
            'remainingAmount'
        ));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        $order->load('user', 'orderItems', 'stitchingOrder');

        return view('receptionist.orders.edit', compact('order'));
    }

    /**
     * Update order (basic details only)
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'delivery_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $order->update($validated);

            return redirect()->route('receptionist.orders.show', $order)
                            ->with('success', 'Order updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    /**
     * Get products by category (AJAX)
     */
    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
            ->where('is_active', true)
            ->get(['id', 'name', 'price', 'discount_price', 'stock_quantity', 'color', 'size']);

        return response()->json($products);
    }

    /**
     * Get product details (AJAX)
     */
    public function getProductDetails($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'final_price' => $product->final_price,
            'stock_quantity' => $product->stock_quantity,
            'color' => $product->color,
            'size' => $product->size,
        ]);
    }

    /**
     * Process order items from request
     */
    private function processOrderItems($request, $orderType)
    {
        $items = [
            'products' => [],
            'stitching' => null,
        ];

        if ($orderType === 'ready_made' || $orderType === 'combined') {
            // Get products from request
            $productIds = $request->input('product_ids', []);
            $quantities = $request->input('quantities', []);

            foreach ($productIds as $index => $productId) {
                if (!empty($productId) && !empty($quantities[$index])) {
                    $product = Product::find($productId);
                    if ($product) {
                        $items['products'][] = [
                            'product_id' => $productId,
                            'product_name' => $product->name,
                            'quantity' => (int)$quantities[$index],
                            'price' => $product->final_price,
                            'total' => $product->final_price * (int)$quantities[$index],
                        ];
                    }
                }
            }
        }

        if ($orderType === 'stitching' || $orderType === 'combined') {
            // Get stitching details from request
            $items['stitching'] = [
                'fabric_type' => $request->input('fabric_type'),
                'color' => $request->input('fabric_color'),
                'design_image' => $request->input('design_image'),
                'instructions' => $request->input('stitching_instructions'),
                'measurement_id' => $request->input('measurement_id'),
            ];
        }

        return $items;
    }

    /**
     * Add order items to the order
     */
    private function addOrderItems($request, $order)
    {
        $productIds = $request->input('product_ids', []);
        $quantities = $request->input('quantities', []);

        foreach ($productIds as $index => $productId) {
            if (!empty($productId) && !empty($quantities[$index])) {
                $product = Product::find($productId);
                if ($product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => (int)$quantities[$index],
                        'price' => $product->final_price,
                    ]);

                    // Reduce stock
                    $product->decrement('stock_quantity', (int)$quantities[$index]);
                }
            }
        }
    }

    /**
     * Create stitching order for receptionist workflow
     */
    private function createStitchingOrder($request, $order)
    {
        $stitchingOrder = StitchingOrder::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'fabric_type' => $request->input('fabric_type'),
            'color' => $request->input('fabric_color'),
            'garment_type' => $request->input('garment_type', 'custom'),
            'measurement_id' => $request->input('measurement_id'),
            'design_details' => $request->input('stitching_instructions'),
            'stitching_status' => 'pending',
            'status' => 'pending',
        ]);

        // Handle design image upload if provided
        if ($request->hasFile('design_image')) {
            $path = $request->file('design_image')->store('designs', 'public');
            $stitchingOrder->update(['design_image' => $path]);
        }
    }
}
