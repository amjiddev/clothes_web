<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\CustomerMeasurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Receptionist Customer Controller
 * 
 * Wrapper around Apps\CustomerController with receptionist authorization
 * and receptionist-specific query filtering
 */
class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('receptionist.only');
    }

    /**
     * Display a listing of customers with receptionist-specific filtering
     */
    public function index(Request $request)
    {
        $query = User::where(function($q) {
            $q->doesntHave('roles')
              ->orWhereHas('roles', function($role) {
                  $role->where('name', 'customer');
              });
        });

        // Search by name, email, or phone
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $status = $request->status;
            if ($status === 'active') {
                $query->where('is_blocked', false);
            } elseif ($status === 'blocked') {
                $query->where('is_blocked', true);
            }
        }

        // Filter by registration date
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Receptionist-specific: Filter by those who have placed orders
        if ($request->has('with_orders') && $request->with_orders) {
            $query->whereHas('orders');
        }

        $customers = $query->latest()->paginate(15)->appends($request->query());

        return view('receptionist.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('receptionist.customers.create');
    }

    /**
     * Store a newly created customer in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'notes' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            // Create user (customer)
            $customer = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make('password123'), // Default password
                'email_verified_at' => now(),
            ]);

            // Store address if provided
            if ($validated['address'] ?? null) {
                $customer->addresses()->create([
                    'address' => $validated['address'],
                    'city' => $validated['city'] ?? null,
                    'is_default' => true,
                ]);
            }

            return redirect()->route('receptionist.customers.show', $customer)
                            ->with('success', 'Customer created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating customer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified customer with their order history
     */
    public function show(User $customer)
    {
        // Verify this is a customer
        if (!$this->isCustomer($customer)) {
            abort(404, 'Customer not found');
        }

        $customer->load('orders', 'addresses', 'measurements');

        // Get customer's orders with related data
        $orders = Order::where('user_id', $customer->id)
            ->with('orderItems', 'stitchingOrder', 'payments')
            ->latest()
            ->get();

        // Get customer's measurements
        $measurements = CustomerMeasurement::where('user_id', $customer->id)
            ->latest()
            ->get();

        // Calculate statistics
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $totalSpent = $orders->where('payment_status', 'paid')->sum('total');
        $pendingOrders = $orders->where('status', 'pending')->count();
        $totalDue = $orders->sum('total') - $orders->sum(function($order) {
            return $order->payments->where('status', 'completed')->sum('amount');
        });

        return view('receptionist.customers.show', compact(
            'customer',
            'orders',
            'measurements',
            'totalOrders',
            'completedOrders',
            'totalSpent',
            'pendingOrders',
            'totalDue'
        ));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(User $customer)
    {
        // Verify this is a customer
        if (!$this->isCustomer($customer)) {
            abort(404, 'Customer not found');
        }

        $customer->load('addresses');
        return view('receptionist.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in database
     */
    public function update(Request $request, User $customer)
    {
        // Verify this is a customer
        if (!$this->isCustomer($customer)) {
            abort(404, 'Customer not found');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'notes' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            // Update user
            $customer->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? $customer->phone,
            ]);

            // Update or create address
            if ($validated['address'] ?? null) {
                $customer->addresses()->updateOrCreate(
                    ['is_default' => true],
                    [
                        'address' => $validated['address'],
                        'city' => $validated['city'] ?? null,
                    ]
                );
            }

            return redirect()->route('receptionist.customers.show', $customer)
                            ->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating customer: ' . $e->getMessage());
        }
    }

    /**
     * Get customer measurements
     */
    public function showMeasurements(User $customer)
    {
        // Verify this is a customer
        if (!$this->isCustomer($customer)) {
            abort(404, 'Customer not found');
        }

        $measurements = CustomerMeasurement::where('user_id', $customer->id)
            ->latest()
            ->paginate(10);

        return view('receptionist.customers.measurements', compact('customer', 'measurements'));
    }

    /**
     * Get customer orders
     */
    public function showOrders(User $customer)
    {
        // Verify this is a customer
        if (!$this->isCustomer($customer)) {
            abort(404, 'Customer not found');
        }

        $orders = Order::where('user_id', $customer->id)
            ->with('orderItems', 'stitchingOrder', 'payments')
            ->latest()
            ->paginate(10);

        return view('receptionist.customers.orders', compact('customer', 'orders'));
    }

    /**
     * Block a customer (receptionist can block customers)
     */
    public function block(User $customer)
    {
        // Verify this is a customer
        if (!$this->isCustomer($customer)) {
            abort(404, 'Customer not found');
        }

        try {
            $customer->update(['is_blocked' => true]);
            return redirect()->back()->with('success', 'Customer blocked successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error blocking customer: ' . $e->getMessage());
        }
    }

    /**
     * Unblock a customer
     */
    public function unblock(User $customer)
    {
        // Verify this is a customer
        if (!$this->isCustomer($customer)) {
            abort(404, 'Customer not found');
        }

        try {
            $customer->update(['is_blocked' => false]);
            return redirect()->back()->with('success', 'Customer unblocked successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error unblocking customer: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Check if user is a customer
     */
    private function isCustomer(User $user): bool
    {
        return !$user->hasAnyRole(['admin', 'receptionist', 'tailor']) || 
               $user->hasRole('customer');
    }
}
