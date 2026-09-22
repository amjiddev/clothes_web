<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
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
        $query = Order::with('user', 'orderItems', 'stitchingOrder');

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
     * Show order creation form - Single Page
     * Fetch ALL required data for the single-page form
     */
    public function create()
    {
        // Get all non-blocked customers
        $customers = User::where(function($q) {
            $q->doesntHave('roles')
              ->orWhereHas('roles', function($role) {
                  $role->where('name', 'customer');
              });
        })->where('is_blocked', false)
          ->orderBy('name')
          ->get();

        // Get all active products
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get all categories
        $categories = Category::active()->orderBy('name')->get();

        return view('receptionist.orders.create', compact('customers', 'products', 'categories'));
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
     * Supports both existing and new customer creation
     */
    public function store(Request $request)
    {
        $this->normalizeOrderType($request);
        $this->normalizeProductPayload($request);

        // Log the incoming request data
        \Log::warning('========= ORDER STORE REQUEST START =========', [
            'order_type_received' => $request->input('order_type'),
            'order_type_hidden' => $request->input('order_type_hidden'),
            'new_measurement_profile_name' => $request->input('new_measurement_profile_name'),
            'customer_id' => $request->input('customer_id'),
            'all_inputs' => $request->all(),
        ]);

        $validated = $request->validate([
            // Customer - either existing customer_id OR new customer fields
            'customer_id' => 'nullable|exists:users,id',
            'new_customer_name' => 'nullable|string|max:255',
            'new_customer_email' => 'nullable|email|unique:users,email',
            'new_customer_phone' => 'nullable|string|max:20',
            'new_customer_city' => 'nullable|string|max:255',
            'new_customer_address' => 'nullable|string|max:500',

            // Order details
            'order_type' => 'required|in:ready_made,stitching,combined',
            'subtotal' => 'required|numeric|min:0',
            'stitching_charge' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:500',
            'delivery_date' => 'nullable|date|after:today',

            // Products
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'nullable|exists:products,id',
            'quantities' => 'nullable|array',
            'quantities.*' => 'nullable|integer|min:1',

            // Stitching details
            'fabric_type' => 'nullable|string|max:100',
            'fabric_color' => 'nullable|string|max:100',
            'garment_type' => 'nullable|string|max:100',
            'measurement_id' => 'nullable|exists:customer_measurements,id',
            'stitching_instructions' => 'nullable|string|max:500',
            'design_image' => 'nullable|image|max:5120', // 5MB
            
            // New measurement fields
            'new_measurement_profile_name' => 'nullable|string|max:255',
            'new_measurement_chest' => 'nullable|numeric|min:0',
            'new_measurement_shoulder' => 'nullable|numeric|min:0',
            'new_measurement_sleeve_length' => 'nullable|numeric|min:0',
            'new_measurement_shirt_length' => 'nullable|numeric|min:0',
            'new_measurement_neck' => 'nullable|numeric|min:0',
            'new_measurement_waist' => 'nullable|numeric|min:0',
            'new_measurement_trouser_length' => 'nullable|numeric|min:0',
            'new_measurement_bottom' => 'nullable|numeric|min:0',
            'new_measurement_thigh' => 'nullable|numeric|min:0',
            'new_measurement_cuff_size' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Step 1: Determine or create customer
            if (!empty($validated['customer_id'])) {
                // Use existing customer
                $customer = User::findOrFail($validated['customer_id']);
                if ($customer->is_blocked) {
                    return back()->with('error', 'Cannot create order for blocked customer.');
                }
                $customerId = $validated['customer_id'];
            } elseif (!empty($validated['new_customer_phone'])) {
                // Create new customer only if phone is provided
                $customer = User::create([
                    'name' => $validated['new_customer_name'] ?? 'Customer',
                    'email' => $validated['new_customer_email'] ?? null,
                    'contact_number' => $validated['new_customer_phone'],
                    'password' => bcrypt('temp-' . time()),
                    'email_verified_at' => now(),
                ]);
                
                // Assign customer role
                $customer->assignRole('customer');
                $customerId = $customer->id;
            } else {
                return back()->withInput()->with('error', 'Please select a customer or enter customer phone number for new customer.');
            }

            // Step 1.5: Create new measurement if provided
            if (!empty($validated['new_measurement_profile_name'])) {
                $newMeasurement = CustomerMeasurement::create([
                    'user_id' => $customerId,
                    'profile_name' => $validated['new_measurement_profile_name'],
                    'chest' => $validated['new_measurement_chest'] ?? 0,
                    'shoulder' => $validated['new_measurement_shoulder'] ?? 0,
                    'sleeve_length' => $validated['new_measurement_sleeve_length'] ?? 0,
                    'shirt_length' => $validated['new_measurement_shirt_length'] ?? 0,
                    'neck' => $validated['new_measurement_neck'] ?? 0,
                    'waist' => $validated['new_measurement_waist'] ?? 0,
                    'trouser_length' => $validated['new_measurement_trouser_length'] ?? 0,
                    'bottom' => $validated['new_measurement_bottom'] ?? 0,
                    'thigh' => $validated['new_measurement_thigh'] ?? 0,
                    'cuff_size' => $validated['new_measurement_cuff_size'] ?? 0,
                    'is_default' => false,
                ]);
                
                // Use the newly created measurement
                if (empty($validated['measurement_id'])) {
                    $validated['measurement_id'] = $newMeasurement->id;
                }
            }

            // Step 2: Recalculate subtotal from fresh product prices (server-side validation)
            $subtotal = 0;
            $productsToProccess = [];

            \Log::warning('PROCESSING PRODUCTS', [
                'order_type' => $validated['order_type'],
                'product_ids' => $validated['product_ids'] ?? null,
                'quantities' => $validated['quantities'] ?? null,
            ]);

            // For ready_made and combined orders, require at least one product with valid quantity
            if ($validated['order_type'] !== 'stitching') {
                $hasProducts = false;
                
                if (!empty($validated['product_ids']) && is_array($validated['product_ids'])) {
                    foreach ($validated['product_ids'] as $index => $productId) {
                        if (!empty($productId)) {
                            $qty = intval($validated['quantities'][$index] ?? 0);
                            if ($qty > 0) {
                                $hasProducts = true;
                                $product = Product::findOrFail($productId);
                                
                                // Validate sufficient stock
                                if ($product->stock_quantity < $qty) {
                                    throw new \Exception("Insufficient stock for product '{$product->name}'. Available: {$product->stock_quantity}, Requested: {$qty}");
                                }
                                
                                $lineTotal = $product->final_price * $qty;
                                $subtotal += $lineTotal;

                                $productsToProccess[] = [
                                    'product_id' => $product->id,
                                    'quantity' => $qty,
                                    'price' => $product->final_price,
                                ];
                                
                                \Log::warning('PRODUCT ADDED', [
                                    'product_id' => $product->id,
                                    'quantity' => $qty,
                                    'price' => $product->final_price,
                                ]);
                            }
                        }
                    }
                }
                
                if (!$hasProducts && $validated['order_type'] === 'ready_made') {
                    throw new \Exception('Ready-made orders must include at least one product with quantity greater than 0');
                }
                
                if (!$hasProducts && $validated['order_type'] === 'combined' && empty($validated['fabric_type'])) {
                    throw new \Exception('Combined orders must include either products or stitching details');
                }
            }

            // Step 3: Recalculate total with fresh values
            $stitchingCharge = floatval($validated['stitching_charge'] ?? 0);
            $discount = floatval($validated['discount'] ?? 0);
            $tax = floatval($validated['tax'] ?? 0);
            $total = max(0, $subtotal + $stitchingCharge + $tax - $discount);

            // Step 4: Create order
            $order = Order::create([
                'user_id' => $customerId,
                'type' => $validated['order_type'],
                'status' => 'pending',
                'subtotal' => $subtotal,
                'stitching_charge' => $stitchingCharge,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
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
            if (in_array($validated['order_type'], ['stitching', 'combined'])) {
                \Log::warning('Creating stitching order for type: ' . $validated['order_type']);
                $this->createStitchingOrder($request, $order, $validated);
                \Log::warning('Stitching order creation completed');
            }

            DB::commit();
            \Log::warning('========= ORDER STORE COMPLETED SUCCESSFULLY =========', ['order_id' => $order->id, 'type' => $order->type]);

            return redirect()->route('receptionist.orders.show', $order)
                            ->with('success', 'Order created successfully! Order ID: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('========= ORDER CREATION FAILED =========', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'order_type' => $validated['order_type'] ?? 'unknown',
                'stack_trace' => $e->getTraceAsString(),
            ]);
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
            'status' => 'nullable|in:pending,confirmed,in_progress,ready,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed',
            'delivery_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Auto-update payment_status based on order status if not explicitly provided
            if (isset($validated['status']) && $validated['status'] === 'cancelled' && !$request->has('payment_status')) {
                $validated['payment_status'] = 'failed';
            }

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
     * Get customer measurements (AJAX API)
     */
    public function getCustomerMeasurements($customerId)
    {
        $measurements = CustomerMeasurement::where('user_id', $customerId)
            ->latest()
            ->get(['id', 'profile_name', 'created_at']);

        return response()->json($measurements);
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
    private function addOrderItems($request, $order, $validated = [])
    {
        // Use validated data if provided, otherwise fall back to request input
        $productIds = $validated['product_ids'] ?? $request->input('product_ids', []);
        $quantities = $validated['quantities'] ?? $request->input('quantities', []);

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
    private function createStitchingOrder($request, $order, $validated = [])
    {
        \Log::warning('========= CREATE STITCHING ORDER START =========');
        \Log::warning('Validated data:', $validated);
        
        // Use validated data if provided, otherwise fall back to request input
        $fabricType = $validated['fabric_type'] ?? $request->input('fabric_type');
        $fabricColor = $validated['fabric_color'] ?? $request->input('fabric_color');
        $garmentType = $validated['garment_type'] ?? $request->input('garment_type', 'custom');
        $measurementId = $validated['measurement_id'] ?? $request->input('measurement_id');
        $stitchingInstructions = $validated['stitching_instructions'] ?? $request->input('stitching_instructions');
        $stitchingCharge = floatval($validated['stitching_charge'] ?? $request->input('stitching_charge', 0));

        \Log::warning('Stitching order data being created:', [
            'order_id' => $order->id,
            'fabric_type' => $fabricType,
            'color' => $fabricColor,
            'garment_type' => $garmentType,
            'measurement_id' => $measurementId,
            'design_details' => $stitchingInstructions,
            'estimated_cost' => $stitchingCharge,
            'stitching_status' => 'pending',
        ]);

        $stitchingOrderData = [
            'order_id' => $order->id,
            'fabric_type' => $fabricType,
            'color' => $fabricColor,
            'garment_type' => $garmentType,
            'measurement_id' => $measurementId,
            'design_details' => $stitchingInstructions,
            'estimated_cost' => $stitchingCharge,
            'stitching_status' => 'pending',
        ];

        // Handle design image upload if provided
        if ($request->hasFile('design_image')) {
            $path = $request->file('design_image')->store('designs', 'public');
            $stitchingOrderData['design_image'] = $path;
        }

        try {
            $stitchingOrder = StitchingOrder::create($stitchingOrderData);
            \Log::warning('========= STITCHING ORDER CREATED SUCCESSFULLY =========', ['id' => $stitchingOrder->id]);
            return $stitchingOrder;
        } catch (\Exception $e) {
            \Log::error('========= STITCHING ORDER CREATION FAILED =========', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Normalize legacy request values to the canonical backend contract.
     * Supported values are exactly: ready_made, stitching, combined.
     */
    private function normalizeOrderType(Request $request): void
    {
        $legacyType = (string) ($request->input('order_type') ?? $request->input('order_type_hidden') ?? '');
        $canonicalType = strtolower(trim($legacyType));

        $map = [
            'cloth' => 'ready_made',
            'cloth_only' => 'ready_made',
            'stitching_only' => 'stitching',
            'cloth_stitching' => 'combined',
        ];

        $normalizedType = $map[$canonicalType] ?? $canonicalType;

        if (!in_array($normalizedType, ['ready_made', 'stitching', 'combined'], true)) {
            return;
        }

        $request->merge([
            'order_type' => $normalizedType,
            'order_type_hidden' => $normalizedType,
        ]);
    }

    /**
     * Normalize legacy product payloads to the canonical product_ids/quantities arrays.
     */
    private function normalizeProductPayload(Request $request): void
    {
        $productIds = $request->input('product_ids', []);
        $quantities = $request->input('quantities', []);

        if (!is_array($productIds)) {
            $productIds = $productIds !== null ? [$productIds] : [];
        }

        if (!is_array($quantities)) {
            $quantities = $quantities !== null ? [$quantities] : [];
        }

        $normalizedProductIds = [];
        $normalizedQuantities = [];

        foreach ($productIds as $index => $productId) {
            if ($productId === null || trim((string) $productId) === '') {
                continue;
            }

            $qty = isset($quantities[$index]) ? (int) $quantities[$index] : 0;

            if ($qty <= 0) {
                continue;
            }

            $normalizedProductIds[] = $productId;
            $normalizedQuantities[] = $qty;
        }

        if (!empty($normalizedProductIds)) {
            $request->merge([
                'product_ids' => $normalizedProductIds,
                'quantities' => $normalizedQuantities,
            ]);
        }
    }
}
