<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\StitchingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'orderItems');

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

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(15)->appends($request->query());

        return view('receptionist.orders.index', compact('orders'));
    }

    /**
     * Show order creation form - Single Page
     * Fetch ALL required data for the form
     */
    public function create()
    {
        // Get all customers (existing and potential)
        $customers = User::where(function($q) {
            $q->doesntHave('roles')
              ->orWhereHas('roles', function($role) {
                  $role->where('name', 'customer');
              });
        })->orderBy('name')->get();

        // Get all active products with prices
        $products = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'discount_price', 'stock_quantity', 'color', 'size', 'category_id']);

        // Get categories for organization
        $categories = Category::active()->orderBy('name')->get();

        // Pass product prices as JSON for Alpine.js calculations
        $productPrices = $products->pluck('price', 'id');

        return view('receptionist.orders.create', compact('customers', 'products', 'categories', 'productPrices'));
    }

    /**
     * Store the order - Single page form submission
     * Handles all validation and creation in one method
     */
    public function store(Request $request)
    {
        // Comprehensive validation
        $validated = $request->validate([
            // Customer information - either customer_id OR new customer fields
            'customer_id' => 'nullable|exists:users,id',
            'new_customer_name' => 'nullable|string|max:255',
            'new_customer_email' => 'nullable|email|unique:users,email',
            'new_customer_phone' => 'nullable|string|max:20',
            'new_customer_address' => 'nullable|string|max:500',

            // Order type and products
            'order_type' => 'required|in:cloth_only,stitching_only,cloth_stitching',
            'products' => 'nullable|array',
            'products.*.id' => 'nullable|exists:products,id',
            'products.*.qty' => 'nullable|integer|min:1',

            // Stitching details (if applicable)
            'fabric_type' => 'nullable|string|max:100',
            'fabric_color' => 'nullable|string|max:100',
            'design_image' => 'nullable|image|max:5120', // 5MB
            'stitching_instructions' => 'nullable|string|max:500',
            'measurement_id' => 'nullable|exists:customer_measurements,id',

            // Charges and payment
            'stitching_charge' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'order_notes' => 'nullable|string|max:1000',
            'delivery_date' => 'nullable|date|after:today',
        ]);

        try {
            DB::beginTransaction();

            // Step 1: Determine customer
            if (!empty($validated['customer_id'])) {
                // Use existing customer
                $customerId = $validated['customer_id'];
                $customer = User::findOrFail($customerId);
            } elseif (!empty($validated['new_customer_phone'])) {
                // Create new customer only if phone is provided
                $customer = User::create([
                    'name' => $validated['new_customer_name'] ?? 'Walk-in Customer',
                    'email' => $validated['new_customer_email'] ?? null,
                    'phone' => $validated['new_customer_phone'],
                    'address' => $validated['new_customer_address'] ?? null,
                    'password' => bcrypt('temporary-password-' . time()),
                ]);
                // Assign customer role
                $customer->assignRole('customer');
                $customerId = $customer->id;
            } else {
                return back()->withInput()->with('error', 'Please select a customer or enter phone number');
            }

            // Step 2: Calculate subtotal from products (server-side verification)
            $subtotal = 0;
            $productsToProccess = [];

            if ($validated['order_type'] !== 'stitching_only' && !empty($validated['products'])) {
                foreach ($validated['products'] as $item) {
                    if (!empty($item['id']) && !empty($item['qty'])) {
                        $product = Product::findOrFail($item['id']);
                        $qty = intval($item['qty']);
                        $lineTotal = $product->price * $qty;
                        $subtotal += $lineTotal;

                        $productsToProccess[] = [
                            'product_id' => $product->id,
                            'quantity' => $qty,
                            'price' => $product->price,
                        ];
                    }
                }
            }

            // Step 3: Calculate total
            $stitchingCharge = floatval($validated['stitching_charge'] ?? 0);
            $discount = floatval($validated['discount'] ?? 0);
            $total = max(0, $subtotal + $stitchingCharge - $discount);

            // Step 4: Create order
            $order = Order::create([
                'user_id' => $customerId,
                'type' => $validated['order_type'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'stitching_charge' => $stitchingCharge,
                'discount' => $discount,
                'total' => $total,
                'advance_payment' => floatval($validated['advance_payment'] ?? 0),
                'notes' => $validated['order_notes'] ?? null,
                'delivery_date' => $validated['delivery_date'] ?? null,
            ]);

            // Step 5: Create order items and decrement stock
            foreach ($productsToProccess as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Decrement product stock
                Product::findOrFail($item['product_id'])->decrement('stock_quantity', $item['quantity']);
            }

            // Step 6: Create stitching order if applicable
            if (in_array($validated['order_type'], ['stitching_only', 'cloth_stitching'])) {
                $stitchingData = [
                    'order_id' => $order->id,
                    'user_id' => $customerId,
                    'fabric_type' => $validated['fabric_type'] ?? null,
                    'color' => $validated['fabric_color'] ?? null,
                    'design_details' => $validated['stitching_instructions'] ?? null,
                    'measurement_id' => $validated['measurement_id'] ?? null,
                    'status' => 'pending',
                ];

                $stitchingOrder = StitchingOrder::create($stitchingData);

                // Handle design image upload
                if ($request->hasFile('design_image')) {
                    $path = $request->file('design_image')->store('designs', 'public');
                    $stitchingOrder->update(['design_image' => $path]);
                }
            }

            DB::commit();

            return redirect()->route('receptionist.orders.show', $order)
                            ->with('success', 'Order created successfully! Order ID: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product', 'stitchingOrder');

        return view('receptionist.orders.show', compact('order'));
    }

    /**
     * Get product details (AJAX)
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
            'stock_quantity' => $product->stock_quantity,
            'color' => $product->color,
            'size' => $product->size,
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,assigned_to_tailor,stitching_started,completed,quality_check,ready_for_delivery,delivered,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $previousStatus = $order->status;

            $order->update(['status' => $validated['status']]);

            // Restore inventory if cancelling
            if ($validated['status'] === 'cancelled' && $previousStatus !== 'cancelled') {
                foreach ($order->orderItems as $item) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            // Decrement inventory if restoring from cancelled
            if ($previousStatus === 'cancelled' && $validated['status'] !== 'cancelled') {
                foreach ($order->orderItems as $item) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            DB::commit();

            return back()->with('success', 'Order status updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating order status: ' . $e->getMessage());
        }
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, Order $order)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            if (in_array($order->status, ['delivered', 'cancelled'])) {
                return back()->with('error', 'Cannot cancel ' . $order->status . ' orders');
            }

            foreach ($order->orderItems as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }

            $order->update([
                'status' => 'cancelled',
                'notes' => ($order->notes ? $order->notes . "\n\n" : '') . 'Cancelled: ' . $validated['reason'],
            ]);

            DB::commit();

            return back()->with('success', 'Order cancelled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error cancelling order: ' . $e->getMessage());
        }
    }

    /**
     * Edit order (basic details only)
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
            'status' => 'nullable|in:pending,confirmed,in_progress,ready,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed',
            'delivery_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('receptionist.orders.show', $order)
                        ->with('success', 'Order updated successfully');
    }

    /**
     * Download invoice
     */
    public function downloadInvoice(Order $order)
    {
        // Implementation for downloading invoice
        return back()->with('info', 'Invoice download feature coming soon');
    }

    /**
     * Print invoice
     */
    public function printInvoice(Order $order)
    {
        // Implementation for printing invoice
        return back()->with('info', 'Invoice print feature coming soon');
    }
}
