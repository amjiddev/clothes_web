<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\CustomerMeasurement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Receptionist Measurement Controller
 * 
 * Manage customer measurements from receptionist perspective
 */
class MeasurementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('receptionist.only');
    }

    /**
     * Display a listing of all customer measurements
     */
    public function index(Request $request)
    {
        $query = CustomerMeasurement::with('user');

        // Search by customer name or profile name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('profile_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by customer
        if ($request->has('customer_id') && $request->customer_id) {
            $query->where('user_id', $request->customer_id);
        }

        // Filter by default status (receptionist specific)
        if ($request->has('is_default') && $request->is_default !== '') {
            $query->where('is_default', (bool)$request->is_default);
        }

        // Filter by date range (receptionist specific)
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $measurements = $query->latest()->paginate(15)->appends($request->query());
        $customers = User::where(function($q) {
            $q->doesntHave('roles')
              ->orWhereHas('roles', function($role) {
                  $role->where('name', 'customer');
              });
        })->where('is_blocked', false)
          ->orderBy('name')
          ->get();

        return view('receptionist.measurements.index', compact('measurements', 'customers'));
    }

    /**
     * Show the form for creating a new measurement
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

        return view('receptionist.measurements.create', compact('customers'));
    }

    /**
     * Store a newly created measurement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'profile_name' => 'required|string|max:100',
            
            // Upper measurements
            'chest' => 'nullable|numeric|min:0|max:999.99',
            'shoulder' => 'nullable|numeric|min:0|max:999.99',
            'sleeve_length' => 'nullable|numeric|min:0|max:999.99',
            'shirt_length' => 'nullable|numeric|min:0|max:999.99',
            'neck' => 'nullable|numeric|min:0|max:999.99',
            
            // Lower measurements
            'waist' => 'nullable|numeric|min:0|max:999.99',
            'trouser_length' => 'nullable|numeric|min:0|max:999.99',
            'bottom' => 'nullable|numeric|min:0|max:999.99',
            'thigh' => 'nullable|numeric|min:0|max:999.99',
            'cuff_size' => 'nullable|numeric|min:0|max:999.99',
            
            // Extra fields
            'design_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'special_instructions' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $data = $validated;

            // Handle image upload
            if ($request->hasFile('design_image')) {
                $path = $request->file('design_image')->store('measurements/designs', 'public');
                $data['design_image'] = $path;
            }

            // Create measurement
            $measurement = CustomerMeasurement::create([
                'user_id' => $validated['customer_id'],
                'profile_name' => $validated['profile_name'],
                'chest' => $validated['chest'] ?? null,
                'shoulder' => $validated['shoulder'] ?? null,
                'sleeve_length' => $validated['sleeve_length'] ?? null,
                'shirt_length' => $validated['shirt_length'] ?? null,
                'neck' => $validated['neck'] ?? null,
                'waist' => $validated['waist'] ?? null,
                'trouser_length' => $validated['trouser_length'] ?? null,
                'bottom' => $validated['bottom'] ?? null,
                'thigh' => $validated['thigh'] ?? null,
                'cuff_size' => $validated['cuff_size'] ?? null,
                'design_image' => $data['design_image'] ?? null,
                'special_instructions' => $validated['special_instructions'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'is_default' => $validated['is_default'] ?? false,
            ]);

            DB::commit();

            return redirect()->route('receptionist.measurements.show', $measurement)
                            ->with('success', 'Measurement profile created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating measurement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified measurement
     */
    public function show(CustomerMeasurement $measurement)
    {
        $measurement->load('user', 'stitchingOrders');

        return view('receptionist.measurements.show', compact('measurement'));
    }

    /**
     * Show the form for editing a measurement
     */
    public function edit(CustomerMeasurement $measurement)
    {
        $customers = User::where(function($q) {
            $q->doesntHave('roles')
              ->orWhereHas('roles', function($role) {
                  $role->where('name', 'customer');
              });
        })->where('is_blocked', false)
          ->orderBy('name')
          ->get();

        return view('receptionist.measurements.edit', compact('measurement', 'customers'));
    }

    /**
     * Update the specified measurement
     */
    public function update(Request $request, CustomerMeasurement $measurement)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'profile_name' => 'required|string|max:100',
            
            // Upper measurements
            'chest' => 'nullable|numeric|min:0|max:999.99',
            'shoulder' => 'nullable|numeric|min:0|max:999.99',
            'sleeve_length' => 'nullable|numeric|min:0|max:999.99',
            'shirt_length' => 'nullable|numeric|min:0|max:999.99',
            'neck' => 'nullable|numeric|min:0|max:999.99',
            
            // Lower measurements
            'waist' => 'nullable|numeric|min:0|max:999.99',
            'trouser_length' => 'nullable|numeric|min:0|max:999.99',
            'bottom' => 'nullable|numeric|min:0|max:999.99',
            'thigh' => 'nullable|numeric|min:0|max:999.99',
            'cuff_size' => 'nullable|numeric|min:0|max:999.99',
            
            // Extra fields
            'design_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'special_instructions' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $data = $validated;

            // Handle image upload
            if ($request->hasFile('design_image')) {
                // Delete old image if exists
                if ($measurement->design_image && Storage::disk('public')->exists($measurement->design_image)) {
                    Storage::disk('public')->delete($measurement->design_image);
                }
                
                $path = $request->file('design_image')->store('measurements/designs', 'public');
                $data['design_image'] = $path;
            } else {
                // Keep existing image if not uploading new one
                $data['design_image'] = $measurement->design_image;
            }

            // Update measurement
            $measurement->update([
                'user_id' => $validated['customer_id'],
                'profile_name' => $validated['profile_name'],
                'chest' => $validated['chest'] ?? null,
                'shoulder' => $validated['shoulder'] ?? null,
                'sleeve_length' => $validated['sleeve_length'] ?? null,
                'shirt_length' => $validated['shirt_length'] ?? null,
                'neck' => $validated['neck'] ?? null,
                'waist' => $validated['waist'] ?? null,
                'trouser_length' => $validated['trouser_length'] ?? null,
                'bottom' => $validated['bottom'] ?? null,
                'thigh' => $validated['thigh'] ?? null,
                'cuff_size' => $validated['cuff_size'] ?? null,
                'design_image' => $data['design_image'],
                'special_instructions' => $validated['special_instructions'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'is_default' => $validated['is_default'] ?? false,
            ]);

            DB::commit();

            return redirect()->route('receptionist.measurements.show', $measurement)
                            ->with('success', 'Measurement profile updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating measurement: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified measurement
     */
    public function destroy(CustomerMeasurement $measurement)
    {
        try {
            DB::beginTransaction();

            // Delete image if exists
            if ($measurement->design_image && Storage::disk('public')->exists($measurement->design_image)) {
                Storage::disk('public')->delete($measurement->design_image);
            }

            // Delete measurement
            $measurement->delete();

            DB::commit();

            return redirect()->route('receptionist.measurements.index')
                            ->with('success', 'Measurement profile deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting measurement: ' . $e->getMessage());
        }
    }

    /**
     * View customer's measurement history
     */
    public function customerMeasurements(User $customer)
    {
        // Verify customer exists
        if ($customer->hasAnyRole(['admin', 'receptionist', 'tailor'])) {
            abort(404, 'Customer not found');
        }

        $measurements = $customer->measurements()->latest()->paginate(15);

        return view('receptionist.measurements.customer-history', compact('customer', 'measurements'));
    }

    /**
     * Set measurement as default
     */
    public function setDefault(CustomerMeasurement $measurement)
    {
        try {
            DB::beginTransaction();

            // Remove default from other measurements for this customer
            CustomerMeasurement::where('user_id', $measurement->user_id)
                ->where('id', '!=', $measurement->id)
                ->update(['is_default' => false]);

            // Set this as default
            $measurement->update(['is_default' => true]);

            DB::commit();

            return back()->with('success', 'Measurement set as default!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error setting default: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate a measurement
     */
    public function duplicate(CustomerMeasurement $measurement)
    {
        try {
            DB::beginTransaction();

            $newMeasurement = $measurement->replicate();
            $newMeasurement->profile_name = $measurement->profile_name . ' (Copy)';
            $newMeasurement->is_default = false;
            $newMeasurement->save();

            DB::commit();

            return redirect()->route('receptionist.measurements.edit', $newMeasurement)
                            ->with('success', 'Measurement duplicated! Edit and save as needed.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error duplicating measurement: ' . $e->getMessage());
        }
    }
}
