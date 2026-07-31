<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\StitchingOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Receptionist Stitching Order Controller
 * 
 * Manage stitching orders from receptionist perspective
 * Handles assignment flow, status updates, and receptionist authorization
 */
class StitchingOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('receptionist.only');
    }

    /**
     * Display all stitching orders with search, filter, and pagination
     */
    public function index(Request $request)
    {
        $query = StitchingOrder::with('order.user', 'tailor', 'measurement');

        // Search by order number or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by service type/garment type
        if ($request->has('garment_type') && $request->garment_type !== '') {
            $query->where('garment_type', $request->garment_type);
        }

        // Filter by tailor
        if ($request->has('tailor_id') && $request->tailor_id !== '') {
            $query->where('tailor_id', $request->tailor_id);
        }

        // Filter by stitching status
        if ($request->has('status') && $request->status !== '') {
            $query->where('stitching_status', $request->status);
        }

        // Filter by assignment status (receptionist specific)
        if ($request->has('assignment') && $request->assignment !== '') {
            if ($request->assignment === 'assigned') {
                $query->whereNotNull('tailor_id');
            } elseif ($request->assignment === 'unassigned') {
                $query->whereNull('tailor_id');
            }
        }

        // Filter by measurement/fitting status
        if ($request->has('measurement') && $request->measurement !== '') {
            if ($request->measurement === 'assigned') {
                $query->whereNotNull('measurement_id');
            } elseif ($request->measurement === 'unassigned') {
                $query->whereNull('measurement_id');
            }
        }

        // Filter by date range (receptionist specific)
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $stitchingOrders = $query->paginate(15)->appends($request->query());

        return view('receptionist.stitching-orders.index', compact('stitchingOrders'));
    }

    /**
     * Display the specified stitching order
     */
    public function show(StitchingOrder $stitchingOrder)
    {
        $stitchingOrder->load('order.user', 'tailor', 'measurement');
        return view('receptionist.stitching-orders.show', compact('stitchingOrder'));
    }

    /**
     * Show the form for creating a stitching order (rarely used by receptionist)
     */
    public function create()
    {
        // This is typically created through order creation
        return redirect()->route('receptionist.orders.create')
                       ->with('info', 'Create stitching order through order creation process');
    }

    /**
     * Store a stitching order (rarely used directly by receptionist)
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id|unique:stitching_orders,order_id',
            'fabric_type' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
            'measurement_id' => 'nullable|exists:customer_measurements,id',
            'design_details' => 'nullable|string|max:1000',
            'design_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Create stitching order
            $stitchingOrder = StitchingOrder::create([
                'order_id' => $validated['order_id'],
                'user_id' => \App\Models\Order::find($validated['order_id'])->user_id,
                'fabric_type' => $validated['fabric_type'],
                'color' => $validated['color'],
                'measurement_id' => $validated['measurement_id'],
                'design_details' => $validated['design_details'],
                'stitching_status' => 'pending',
                'status' => 'pending',
            ]);

            // Handle design image upload
            if ($request->hasFile('design_image')) {
                $path = $request->file('design_image')->store('designs', 'public');
                $stitchingOrder->update(['design_image' => $path]);
            }

            DB::commit();

            return redirect()->route('receptionist.stitching-orders.show', $stitchingOrder)
                            ->with('success', 'Stitching order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating stitching order: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a stitching order
     */
    public function edit(StitchingOrder $stitchingOrder)
    {
        $stitchingOrder->load('order', 'tailor', 'measurement');
        return view('receptionist.stitching-orders.edit', compact('stitchingOrder'));
    }

    /**
     * Update the specified stitching order
     */
    public function update(Request $request, StitchingOrder $stitchingOrder)
    {
        $validated = $request->validate([
            'fabric_type' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
            'measurement_id' => 'nullable|exists:customer_measurements,id',
            'design_details' => 'nullable|string|max:1000',
            'design_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Update stitching order
            $stitchingOrder->update([
                'fabric_type' => $validated['fabric_type'],
                'color' => $validated['color'],
                'measurement_id' => $validated['measurement_id'],
                'design_details' => $validated['design_details'],
            ]);

            // Handle design image upload
            if ($request->hasFile('design_image')) {
                $path = $request->file('design_image')->store('designs', 'public');
                $stitchingOrder->update(['design_image' => $path]);
            }

            DB::commit();

            return redirect()->route('receptionist.stitching-orders.show', $stitchingOrder)
                            ->with('success', 'Stitching order updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating stitching order: ' . $e->getMessage());
        }
    }

    /**
     * Show form for assigning tailor to stitching order
     */
    public function assignForm(StitchingOrder $stitchingOrder)
    {
        if (!$stitchingOrder->canAssignToTailor()) {
            return back()->with('error', 'This stitching order cannot be assigned.');
        }

        $tailors = User::role('tailor')
            ->with('tailor')
            ->get();

        return view('receptionist.stitching-orders.assign', compact('stitchingOrder', 'tailors'));
    }

    /**
     * Assign tailor to stitching order
     */
    public function assign(Request $request, StitchingOrder $stitchingOrder)
    {
        $validated = $request->validate([
            'tailor_id' => 'required|exists:users,id',
            'assigned_date' => 'nullable|date',
            'additional_instructions' => 'nullable|string|max:500',
        ]);

        if (!$stitchingOrder->canAssignToTailor()) {
            return back()->with('error', 'This stitching order cannot be assigned.');
        }

        try {
            DB::beginTransaction();

            // Update stitching order with tailor assignment
            $stitchingOrder->update([
                'tailor_id' => $validated['tailor_id'],
                'assigned_date' => $validated['assigned_date'] ?? now(),
                'stitching_status' => 'assigned',
                'additional_instructions' => $validated['additional_instructions'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('receptionist.stitching-orders.show', $stitchingOrder)
                            ->with('success', 'Stitching order assigned to tailor successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error assigning order: ' . $e->getMessage());
        }
    }

    /**
     * Show form for status update
     */
    public function updateStatusForm(StitchingOrder $stitchingOrder)
    {
        $stitchingOrder->load('tailor', 'order');
        return view('receptionist.stitching-orders.update-status', compact('stitchingOrder'));
    }

    /**
     * Update stitching order status
     */
    public function updateStatus(Request $request, StitchingOrder $stitchingOrder)
    {
        $validated = $request->validate([
            'stitching_status' => 'required|in:pending,assigned,in_progress,ready_for_fitting,in_fitting,ready,completed,cancelled',
            'tailor_notes' => 'nullable|string|max:500',
            'fitting_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            if ($validated['stitching_status'] === 'in_progress' && !$stitchingOrder->canStartStitching()) {
                return back()->with('error', 'This order must be assigned to a tailor first.');
            }

            $updateData = [
                'stitching_status' => $validated['stitching_status'],
            ];

            if (isset($validated['tailor_notes'])) {
                $updateData['tailor_notes'] = $validated['tailor_notes'];
            }

            if ($validated['stitching_status'] === 'in_progress' && !$stitchingOrder->start_date) {
                $updateData['start_date'] = now();
            }

            if ($validated['stitching_status'] === 'ready_for_fitting') {
                $updateData['fitting_date'] = $validated['fitting_date'] ?? now();
            }

            if ($validated['stitching_status'] === 'completed') {
                $updateData['completion_date'] = $validated['completion_date'] ?? now();
            }

            $stitchingOrder->update($updateData);

            // Update related order status if completed
            if ($validated['stitching_status'] === 'completed') {
                $stitchingOrder->order->update(['status' => 'ready']);
            }

            DB::commit();

            return redirect()->route('receptionist.stitching-orders.show', $stitchingOrder)
                            ->with('success', 'Stitching order status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Reassign stitching order to different tailor
     */
    public function reassign(Request $request, StitchingOrder $stitchingOrder)
    {
        $validated = $request->validate([
            'tailor_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $oldTailor = $stitchingOrder->tailor;
            $newTailor = User::findOrFail($validated['tailor_id']);

            if (!$newTailor->hasRole('tailor')) {
                return back()->with('error', 'Selected user is not a tailor.');
            }

            $stitchingOrder->update([
                'tailor_id' => $validated['tailor_id'],
                'additional_instructions' => ($stitchingOrder->additional_instructions ? $stitchingOrder->additional_instructions . "\n\n" : '') .
                    'Reassigned from ' . ($oldTailor->name ?? 'Previous Tailor') . ': ' . $validated['reason'],
            ]);

            DB::commit();

            return back()->with('success', 'Order reassigned to ' . $newTailor->name . ' successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error reassigning order: ' . $e->getMessage());
        }
    }

    /**
     * Delete stitching order (only if pending)
     */
    public function destroy(StitchingOrder $stitchingOrder)
    {
        if ($stitchingOrder->stitching_status !== 'pending') {
            return back()->with('error', 'Cannot delete a stitching order that has been started.');
        }

        try {
            DB::beginTransaction();

            $stitchingOrder->delete();

            DB::commit();

            return redirect()->route('receptionist.stitching-orders.index')
                            ->with('success', 'Stitching order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting stitching order: ' . $e->getMessage());
        }
    }

    /**
     * Get stitching order statistics (receptionist view)
     */
    public function getStats()
    {
        $totalOrders = StitchingOrder::count();
        $pendingOrders = StitchingOrder::where('stitching_status', 'pending')->count();
        $unassignedOrders = StitchingOrder::whereNull('tailor_id')->count();
        $inProgressOrders = StitchingOrder::where('stitching_status', 'in_progress')->count();
        $readyOrders = StitchingOrder::where('stitching_status', 'ready')->count();
        $completedOrders = StitchingOrder::where('stitching_status', 'completed')->count();

        return response()->json([
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'unassigned_orders' => $unassignedOrders,
            'in_progress_orders' => $inProgressOrders,
            'ready_orders' => $readyOrders,
            'completed_orders' => $completedOrders,
        ]);
    }
}
