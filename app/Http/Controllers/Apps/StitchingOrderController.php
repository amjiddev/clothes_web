<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\StitchingOrder;
use App\Models\User;
use Illuminate\Http\Request;

class StitchingOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_stitching_orders', ['only' => ['index', 'show']]);
        $this->middleware('permission:assign_stitching_orders', ['only' => ['assignTailor', 'updateAssignment']]);
        $this->middleware('permission:update_stitching_status', ['only' => ['updateStatus']]);
        $this->middleware('permission:delete_orders', ['only' => ['destroy']]);
    }

    /**
     * Display all stitching orders with search, filter, and pagination
     */
    public function index(Request $request)
    {
        $query = StitchingOrder::with('order.user', 'tailor', 'measurement');

        // Tailor can only see their own stitching orders
        if (auth()->user()->isTailor()) {
            $query->where('tailor_id', auth()->id());
        }

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
        if ($request->has('service_type') && $request->service_type !== '') {
            $query->where('garment_type', $request->service_type);
        }

        // Filter by tailor
        if ($request->has('tailor_id') && $request->tailor_id !== '') {
            $query->where('tailor_id', $request->tailor_id);
        }

        // Filter by stitching status
        if ($request->has('status') && $request->status !== '') {
            $query->where('stitching_status', $request->status);
        }

        // Filter by measurement/fitting status
        if ($request->has('measurement') && $request->measurement !== '') {
            if ($request->measurement === 'assigned') {
                $query->whereNotNull('measurement_id');
            } elseif ($request->measurement === 'unassigned') {
                $query->whereNull('measurement_id');
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $stitchingOrders = $query->paginate(15)->appends($request->query());

        return view('admin.stitching-orders.index', compact('stitchingOrders'));
    }

    /**
     * Display the specified stitching order
     */
    public function show(StitchingOrder $stitchingOrder)
    {
        $stitchingOrder->load('order.user', 'tailor', 'measurement');
        return view('admin.stitching-orders.show', compact('stitchingOrder'));
    }

    /**
     * Show the form for assigning a tailor
     */
    public function assignTailor(StitchingOrder $stitchingOrder)
    {
        if (!$stitchingOrder->canAssignToTailor()) {
            return back()->with('error', 'This stitching order cannot be assigned.');
        }

        $tailors = User::role('tailor')->get();
        return view('admin.stitching-orders.assign-tailor', compact('stitchingOrder', 'tailors'));
    }

    /**
     * Update tailor assignment
     */
    public function updateAssignment(Request $request, StitchingOrder $stitchingOrder)
    {
        $validated = $request->validate([
            'tailor_id' => 'required|exists:users,id',
            'assigned_date' => 'nullable|date',
        ]);

        $stitchingOrder->update([
            'tailor_id' => $validated['tailor_id'],
            'assigned_date' => $validated['assigned_date'] ?? now(),
            'stitching_status' => 'assigned',
        ]);

        return redirect()->route('admin.stitching-orders.show', $stitchingOrder)
                        ->with('success', 'Stitching order assigned to tailor successfully.');
    }

    /**
     * Update stitching order status
     */
    public function updateStatus(Request $request, StitchingOrder $stitchingOrder)
    {
        $validated = $request->validate([
            'stitching_status' => 'required|in:pending,assigned,in_progress,ready_for_fitting,in_fitting,ready,completed,cancelled',
            'tailor_notes' => 'nullable|string|max:1000',
            'fitting_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
        ]);

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

        return redirect()->route('admin.stitching-orders.show', $stitchingOrder)
                        ->with('success', 'Stitching order status updated successfully.');
    }

    /**
     * Delete the specified stitching order
     */
    public function destroy(StitchingOrder $stitchingOrder)
    {
        if ($stitchingOrder->stitching_status !== 'pending') {
            return back()->with('error', 'Cannot delete a stitching order that has been started.');
        }

        $stitchingOrder->delete();

        return redirect()->route('admin.stitching-orders.index')
                        ->with('success', 'Stitching order deleted successfully.');
    }

    /**
     * Get stitching order statistics
     */
    public function getStats()
    {
        $totalOrders = StitchingOrder::count();
        $pendingOrders = StitchingOrder::where('stitching_status', 'pending')->count();
        $inProgressOrders = StitchingOrder::where('stitching_status', 'in_progress')->count();
        $completedOrders = StitchingOrder::where('stitching_status', 'completed')->count();
        $averageCost = StitchingOrder::avg('estimated_cost') ?? 0;

        return response()->json([
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'in_progress_orders' => $inProgressOrders,
            'completed_orders' => $completedOrders,
            'average_cost' => number_format($averageCost, 2),
        ]);
    }
}
