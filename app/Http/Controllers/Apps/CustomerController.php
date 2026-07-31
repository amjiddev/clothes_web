<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\CustomerMeasurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
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
                  ->orWhere('email', 'like', "%{$search}%");
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

        // Paginate results
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
            'phone' => 'required|string|max:20',
            'city' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'notes' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Create user (customer)
        $customer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
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

        // Store additional customer info in a custom column or notes (if available)
        // For now, we'll store phone and gender in the model if columns exist
        // Otherwise, you may need to add these columns to the users table

        return redirect()->route('receptionist.customers.show', $customer)
                        ->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified customer
     */
    public function show(User $customer)
    {
        // Load relationships
        $customer->load('orders', 'addresses', 'measurements');

        // Get customer's orders with related data
        $orders = Order::where('user_id', $customer->id)
            ->with('orderItems')
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

        return view('receptionist.customers.show', compact(
            'customer',
            'orders',
            'measurements',
            'totalOrders',
            'completedOrders',
            'totalSpent'
        ));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(User $customer)
    {
        $customer->load('addresses');
        return view('receptionist.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in database
     */
    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'notes' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Update user
        $customer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
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
    }

    /**
     * Get customer measurements
     */
    public function showMeasurements(User $customer)
    {
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
        $orders = Order::where('user_id', $customer->id)
            ->with('orderItems')
            ->latest()
            ->paginate(10);

        return view('receptionist.customers.orders', compact('customer', 'orders'));
    }

    /**
     * Block a customer
     */
    public function block(User $customer)
    {
        $customer->update(['is_blocked' => true]);
        return redirect()->back()->with('success', 'Customer blocked successfully!');
    }

    /**
     * Unblock a customer
     */
    public function unblock(User $customer)
    {
        $customer->update(['is_blocked' => false]);
        return redirect()->back()->with('success', 'Customer unblocked successfully!');
    }
}
