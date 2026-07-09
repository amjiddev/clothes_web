<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_customers', ['only' => ['index', 'show']]);
        $this->middleware('permission:edit_customers', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_customers', ['only' => ['destroy']]);
        $this->middleware('permission:block_customers', ['only' => ['block', 'unblock']]);
    }

    /**
     * Display a listing of customers with search and filter
     */
    public function index(Request $request)
    {
        $query = User::where(function ($query) {
            $query->whereDoesntHave('roles')
                ->orWhereHas('roles', function ($q) {
                    $q->where('name', 'customer');
                });
        });

        // Search by name, email, or phone
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->where('is_blocked', false);
            } elseif ($request->status === 'blocked') {
                $query->where('is_blocked', true);
            }
        }

        // Filter by registration date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get customers with counts
        $customers = $query->withCount('orders')
            ->paginate(15)
            ->appends($request->query());

        // Add total spending to each customer
        $customers->each(function ($customer) {
            $customer->total_spending = $customer->orders()
                ->where('payment_status', 'paid')
                ->sum('total');
        });

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified customer profile with all details
     */
    public function show(User $customer)
    {
        // Verify this is actually a customer
        if (!$customer->isCustomer()) {
            abort(403, 'This user is not a customer.');
        }

        // Load relationships
        $customer->load(['orders' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }, 'measurements', 'addresses']);

        // Get stitching orders through orders
        $stitchingOrders = \App\Models\StitchingOrder::whereHas('order', function ($q) use ($customer) {
            $q->where('user_id', $customer->id);
        })->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spending' => $customer->orders()->where('payment_status', 'paid')->sum('total'),
            'pending_orders' => $customer->orders()->where('status', 'pending')->count(),
            'completed_orders' => $customer->orders()->where('status', 'delivered')->count(),
            'total_measurements' => $customer->measurements()->count(),
            'stitching_orders' => $stitchingOrders->count(),
        ];

        // Calculate average order value
        $stats['average_order_value'] = $stats['total_orders'] > 0 
            ? $stats['total_spending'] / $stats['total_orders'] 
            : 0;

        return view('admin.customers.show', compact('customer', 'stitchingOrders', 'stats'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(User $customer)
    {
        // Verify this is actually a customer
        if (!$customer->isCustomer()) {
            abort(403, 'This user is not a customer.');
        }

        $customer->load('addresses');
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in database
     */
    public function update(Request $request, User $customer)
    {
        // Verify this is actually a customer
        if (!$customer->isCustomer()) {
            abort(403, 'This user is not a customer.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile photo
        if ($request->hasFile('profile_photo_path')) {
            if ($customer->profile_photo_path) {
                Storage::disk('public')->delete($customer->profile_photo_path);
            }
            $validated['profile_photo_path'] = $request->file('profile_photo_path')->store('customers', 'public');
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Customer updated successfully!');
    }

    /**
     * Remove the specified customer from database
     */
    public function destroy(User $customer)
    {
        // Verify this is actually a customer
        if (!$customer->isCustomer()) {
            abort(403, 'This user is not a customer.');
        }

        // Delete profile photo if exists
        if ($customer->profile_photo_path) {
            Storage::disk('public')->delete($customer->profile_photo_path);
        }

        // Delete customer and related data
        $customer->measurements()->delete();
        $customer->addresses()->delete();
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully!');
    }

    /**
     * Block a customer
     */
    public function block(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(403, 'This user is not a customer.');
        }

        $customer->update(['is_blocked' => true]);

        return redirect()->back()->with('success', 'Customer blocked successfully!');
    }

    /**
     * Unblock a customer
     */
    public function unblock(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(403, 'This user is not a customer.');
        }

        $customer->update(['is_blocked' => false]);

        return redirect()->back()->with('success', 'Customer unblocked successfully!');
    }

    /**
     * Get customer orders
     */
    public function getOrderHistory(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(403, 'This user is not a customer.');
        }

        $orders = $customer->orders()
            ->with('orderItems', 'orderItems.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    /**
     * Get customer measurements
     */
    public function getMeasurements(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(403, 'This user is not a customer.');
        }

        $measurements = $customer->measurements()->get();

        return response()->json($measurements);
    }

    /**
     * Get customer stitching orders
     */
    public function getStitchingOrders(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(403, 'This user is not a customer.');
        }

        $stitchingOrders = \App\Models\StitchingOrder::whereHas('order', function ($q) use ($customer) {
            $q->where('user_id', $customer->id);
        })->orderBy('created_at', 'desc')->get();

        return response()->json($stitchingOrders);
    }
}
