<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StitchingOrder;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_all_orders', ['only' => ['index', 'show']]);
        $this->middleware('permission:create_orders', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_orders', ['only' => ['edit', 'update', 'updateStatus']]);
        $this->middleware('permission:delete_orders', ['only' => ['destroy']]);
    }

    /**
     * Display all orders with search, filter, and pagination
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'orderItems');

        // Search by order number or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        // Filter by order type
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filter by order status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate(15)->appends($request->query());

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $products = Product::where('is_active', true)->where('stock_quantity', '>', 0)->get();
        return view('admin.orders.create', compact('users', 'products'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:ready_made,stitching,combined',
            'items' => 'required_if:type,ready_made,combined|array',
            'items.*.product_id' => 'exists:products,id',
            'items.*.quantity' => 'integer|min:1',
            'stitching_charge' => 'required_if:type,stitching,combined|numeric|min:0',
            'garment_type' => 'required_if:type,stitching,combined|string',
            'fabric_details' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'discount' => 'numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $order = new Order();
        $order->user_id = $validated['user_id'];
        $order->type = $validated['type'];
        $order->payment_method = $validated['payment_method'];
        $order->notes = $validated['notes'] ?? null;
        $order->status = 'pending';
        $order->payment_status = 'pending';

        $subtotal = 0;

        // Add order items for ready_made and combined orders
        if (in_array($validated['type'], ['ready_made', 'combined'])) {
            $order->save();

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $product->final_price;
                $orderItem->save();

                $subtotal += $product->final_price * $item['quantity'];

                // Decrease stock
                $product->decrement('stock_quantity', $item['quantity']);
            }
        } else {
            $order->save();
        }

        // Calculate totals
        $order->subtotal = $subtotal;
        $order->stitching_charge = $validated['stitching_charge'] ?? 0;
        $order->discount = $validated['discount'] ?? 0;
        $order->tax = (($subtotal + ($validated['stitching_charge'] ?? 0)) - ($validated['discount'] ?? 0)) * 0.18; // 18% tax
        $order->total = $order->subtotal + $order->stitching_charge + $order->tax - $order->discount;
        $order->save();

        // Create stitching order if type is stitching or combined
        if (in_array($validated['type'], ['stitching', 'combined'])) {
            StitchingOrder::create([
                'order_id' => $order->id,
                'garment_type' => $validated['garment_type'],
                'fabric_details' => $validated['fabric_details'] ?? null,
                'special_instructions' => $validated['special_instructions'] ?? null,
                'stitching_status' => 'pending',
            ]);
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product', 'stitchingOrder.tailor', 'stitchingOrder.measurement');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $products = Product::where('is_active', true)->get();
        $tailors = User::whereHas('roles', function ($q) {
            $q->where('name', 'tailor');
        })->get();
        
        return view('admin.orders.edit', compact('order', 'users', 'products', 'tailors'));
    }

    /**
     * Update the specified order status
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,ready,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'delivery_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully.');
    }

    /**
     * Update order status via API
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,ready,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Order status updated successfully.',
            'status' => $order->status,
            'badge' => $order->status_badge,
        ]);
    }

    /**
     * Delete the specified order
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of pending/cancelled orders
        if (!in_array($order->status, ['pending', 'cancelled'])) {
            return redirect()->back()->with('error', 'Cannot delete orders that are already confirmed.');
        }

        // Restore stock if order items exist
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock_quantity', $item->quantity);
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    /**
     * Get order statistics
     */
    public function getStats()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return response()->json([
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'total_revenue' => number_format($totalRevenue, 2),
            'average_order_value' => number_format($averageOrderValue, 2),
        ]);
    }

}
