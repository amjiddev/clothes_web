<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\CustomerMeasurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Show shopping cart
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $item['product'] = $product;
                $item['line_total'] = $product->final_price * $item['quantity'];
                $total += $item['line_total'];
                $cartItems[] = $item;
            }
        }

        return view('frontend.cart', compact('cartItems', 'total', 'cart'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request, $productId)
    {
        $product = Product::find($productId);

        if (!$product || !$product->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Product not available'
            ], 404);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock_quantity,
            'size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        
        // Use product ID with size/color combo as unique key
        $cartKey = $productId . '-' . ($validated['size'] ?? 'default') . '-' . ($validated['color'] ?? 'default');

        if (isset($cart[$cartKey])) {
            // If already in cart, increase quantity
            $cart[$cartKey]['quantity'] += $validated['quantity'];
        } else {
            // Add new item to cart
            $cart[$cartKey] = [
                'product_id' => $productId,
                'quantity' => $validated['quantity'],
                'size' => $validated['size'] ?? null,
                'color' => $validated['color'] ?? null,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cartCount' => count($cart),
            'total' => $this->calculateTotal($cart)
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $cartKey)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $product = Product::find($cart[$cartKey]['product_id']);
            
            if ($validated['quantity'] > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity exceeds stock'
                ], 422);
            }

            $cart[$cartKey]['quantity'] = $validated['quantity'];
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'total' => $this->calculateTotal($cart),
                'itemTotal' => $product->final_price * $validated['quantity']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ], 404);
    }

    /**
     * Remove item from cart
     */
    public function remove($cartKey)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cartCount' => count($cart),
                'total' => $this->calculateTotal($cart)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found'
        ], 404);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cartCount' => 0
        ]);
    }

    /**
     * Get cart count
     */
    public function getCount()
    {
        $cart = session()->get('cart', []);
        
        return response()->json([
            'count' => count($cart)
        ]);
    }

    /**
     * Show checkout page
     */
    public function checkout()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $cartKey => $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $lineTotal = $product->final_price * $item['quantity'];
                $cartItems[] = [
                    'cart_key' => $cartKey,
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'price' => $product->final_price,
                    'line_total' => $lineTotal
                ];
                $subtotal += $lineTotal;
            }
        }

        if (empty($cartItems)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        $user = Auth::user();
        $addresses = $user->addresses;
        $measurements = $user->measurements;
        $tax = round($subtotal * 0.05, 2); // 5% tax
        $total = $subtotal + $tax;

        return view('frontend.checkout', compact(
            'cartItems',
            'subtotal',
            'tax',
            'total',
            'user',
            'addresses',
            'measurements'
        ));
    }

    /**
     * Process order
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'order_type' => 'required|in:cloth_only,cloth_stitching,stitching_only',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'payment_method' => 'required|in:cash_on_delivery,bank_transfer',
            'measurement_id' => 'nullable|exists:customer_measurements,id',
            'measurement_title' => 'nullable|string|max:255',
            // Measurement fields for new stitching orders
            'chest' => 'nullable|numeric|min:20|max:200',
            'shoulder' => 'nullable|numeric|min:20|max:100',
            'sleeve_length' => 'nullable|numeric|min:10|max:100',
            'shirt_length' => 'nullable|numeric|min:40|max:150',
            'neck' => 'nullable|numeric|min:10|max:50',
            'waist' => 'nullable|numeric|min:20|max:200',
            'trouser_length' => 'nullable|numeric|min:60|max:120',
            'bottom' => 'nullable|numeric|min:10|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        // Validate stitching measurements if stitching is selected
        if (in_array($validated['order_type'], ['cloth_stitching', 'stitching_only'])) {
            if (!$validated['measurement_id'] && !$validated['chest']) {
                return back()->with('error', 'Please provide measurements for stitching service');
            }

            if ($validated['chest'] && !($validated['shoulder'] && $validated['sleeve_length'] && 
                $validated['shirt_length'] && $validated['neck'] && $validated['waist'] && 
                $validated['trouser_length'] && $validated['bottom'])) {
                return back()->with('error', 'All measurements are required for stitching service');
            }
        }

        try {
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                return redirect()->route('cart')->with('error', 'Your cart is empty');
            }

            // Calculate totals
            $subtotal = 0;
            $stitchingCharge = 0;

            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $subtotal += $product->final_price * $item['quantity'];
                }
            }

            // Calculate stitching charge based on order type
            if ($validated['order_type'] === 'cloth_stitching') {
                $stitchingCharge = 1500;
            } elseif ($validated['order_type'] === 'stitching_only') {
                $stitchingCharge = 300;
            }

            $tax = round(($subtotal + $stitchingCharge) * 0.05, 2);
            $total = $subtotal + $stitchingCharge + $tax;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'type' => $validated['order_type'],
                'status' => 'pending',
                'subtotal' => $subtotal,
                'stitching_charge' => $stitchingCharge,
                'tax' => $tax,
                'discount' => 0,
                'total' => $total,
                'payment_status' => $validated['payment_method'] === 'cash_on_delivery' ? 'pending' : 'pending',
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Add order items
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->final_price,
                        'notes' => implode(', ', array_filter([
                            $item['size'] ? 'Size: ' . $item['size'] : null,
                            $item['color'] ? 'Color: ' . $item['color'] : null,
                        ])),
                    ]);

                    // Reduce stock
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }

            // Handle stitching order if applicable
            if (in_array($validated['order_type'], ['cloth_stitching', 'stitching_only'])) {
                $measurement = null;

                if ($validated['measurement_id']) {
                    $measurement = CustomerMeasurement::find($validated['measurement_id']);
                } else {
                    // Create new measurement
                    $measurement = CustomerMeasurement::create([
                        'user_id' => Auth::id(),
                        'title' => $validated['measurement_title'] ?? 'Order #' . $order->order_number,
                        'chest' => $validated['chest'],
                        'shoulder' => $validated['shoulder'],
                        'sleeve_length' => $validated['sleeve_length'],
                        'torso_length' => $validated['shirt_length'],
                        'neck' => $validated['neck'],
                        'waist' => $validated['waist'],
                        'inseam' => $validated['trouser_length'],
                        'notes' => 'Stitching order from checkout',
                    ]);
                }

                // Create stitching order
                \App\Models\StitchingOrder::create([
                    'order_id' => $order->id,
                    'measurement_id' => $measurement->id,
                    'garment_type' => 'Order Item',
                    'service_option' => $validated['order_type'],
                    'stitching_status' => 'pending',
                    'special_instructions' => $validated['notes'] ?? null,
                    'estimated_cost' => $stitchingCharge,
                    'service_request_date' => now(),
                ]);
            }

            // Clear cart
            session()->forget('cart');

            return redirect()->route('order.confirmation', $order->id)
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error processing order: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Show order confirmation
     */
    public function confirmation($orderId)
    {
        $order = Order::with('orderItems.product', 'stitchingOrder.measurement')->findOrFail($orderId);

        // Verify user owns this order
        if ($order->user_id !== Auth::id() && !Auth::user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized');
        }

        return view('frontend.order-confirmation', compact('order'));
    }

    /**
     * Calculate cart total
     */
    private function calculateTotal($cart)
    {
        $total = 0;

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $total += $product->final_price * $item['quantity'];
            }
        }

        return round($total, 2);
    }

    /**
     * Get cart summary (for navbar, etc.)
     */
    public function getSummary()
    {
        $cart = session()->get('cart', []);
        $count = count($cart);
        $total = $this->calculateTotal($cart);

        return response()->json([
            'count' => $count,
            'total' => $total,
            'formatted_total' => '₹' . number_format($total, 2)
        ]);
    }
}
