<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Tailor;
use App\Models\User;
use App\Models\StitchingOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TailorController extends Controller
{
    /**
     * Display a listing of all tailors
     */
    public function index(Request $request)
    {
        $query = Tailor::with('user')->where('status', 'active');

        // Search by tailor name or phone
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by specialization
        if ($request->has('specialization') && $request->specialization) {
            $specialization = $request->specialization;
            $query->where('specialization', 'like', "%{$specialization}%");
        }

        $tailors = $query->latest()->paginate(15)->appends($request->query());

        // Get unique specializations for filter
        $specializations = Tailor::where('status', 'active')
            ->distinct()
            ->pluck('specialization');

        return view('receptionist.tailors.index', compact('tailors', 'specializations'));
    }

    /**
     * Show tailor details
     */
    public function show(Tailor $tailor)
    {
        $tailor->load('user', 'stitchingOrders');

        // Get statistics
        $activeOrders = $tailor->getActiveOrders();
        $completedOrders = $tailor->getCompletedOrders();
        $pendingOrders = $tailor->getPendingOrders();

        // Get recent assignments
        $recentAssignments = StitchingOrder::where('tailor_id', $tailor->user_id)
            ->with('order')
            ->latest()
            ->take(10)
            ->get();

        return view('receptionist.tailors.show', compact(
            'tailor',
            'activeOrders',
            'completedOrders',
            'pendingOrders',
            'recentAssignments'
        ));
    }

    /**
     * Show assign stitching order form
     */
    public function assignForm()
    {
        // Get pending stitching orders
        $stitchingOrders = StitchingOrder::where('stitching_status', 'pending')
            ->with('order', 'order.user')
            ->latest()
            ->get();

        // Get active tailors
        $tailors = Tailor::where('status', 'active')
            ->with('user')
            ->orderBy('specialization')
            ->get();

        return view('receptionist.tailors.assign-form', compact('stitchingOrders', 'tailors'));
    }

    /**
     * Assign stitching order to tailor
     */
    public function assignOrder(Request $request)
    {
        $validated = $request->validate([
            'stitching_order_id' => 'required|exists:stitching_orders,id',
            'tailor_id' => 'required|exists:users,id',
            'delivery_date' => 'required|date|after:today',
            'instructions' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Get stitching order
            $stitchingOrder = StitchingOrder::findOrFail($validated['stitching_order_id']);

            // Verify order is pending
            if ($stitchingOrder->stitching_status !== 'pending') {
                return back()->with('error', 'This order has already been assigned.');
            }

            // Get tailor
            $tailor = User::findOrFail($validated['tailor_id']);

            // Verify tailor role
            if (!$tailor->hasRole('tailor')) {
                return back()->with('error', 'Selected user is not a tailor.');
            }

            // Update stitching order
            $stitchingOrder->update([
                'tailor_id' => $validated['tailor_id'],
                'stitching_status' => 'assigned',
                'assigned_date' => now(),
                'additional_instructions' => $validated['instructions'] ?? null,
            ]);

            // Update related order delivery date if needed
            $order = $stitchingOrder->order;
            if ($order && !$order->delivery_date) {
                $order->update(['delivery_date' => $validated['delivery_date']]);
            }

            DB::commit();

            return redirect()->route('receptionist.tailors.show-assignment', $stitchingOrder)
                            ->with('success', 'Stitching order assigned to ' . $tailor->name . ' successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error assigning order: ' . $e->getMessage());
        }
    }

    /**
     * Show assignment details
     */
    public function showAssignment(StitchingOrder $stitchingOrder)
    {
        $stitchingOrder->load('order', 'tailor', 'measurement', 'order.user');

        return view('receptionist.tailors.assignment-details', compact('stitchingOrder'));
    }

    /**
     * Get tailor workload statistics
     */
    public function getWorkload(Tailor $tailor)
    {
        $workload = [
            'active' => $tailor->getActiveOrders(),
            'pending' => $tailor->getPendingOrders(),
            'completed' => $tailor->getCompletedOrders(),
            'total' => $tailor->getTotalAssignedOrders(),
        ];

        return response()->json($workload);
    }

    /**
     * Get availability for tailor
     */
    public function getAvailability(Tailor $tailor)
    {
        $activeOrders = $tailor->getActiveOrders();
        $maxCapacity = 10; // Maximum concurrent orders

        $availableSlots = $maxCapacity - $activeOrders;

        return response()->json([
            'active_orders' => $activeOrders,
            'max_capacity' => $maxCapacity,
            'available_slots' => max(0, $availableSlots),
            'is_available' => $availableSlots > 0,
        ]);
    }

    /**
     * Reassign order to different tailor
     */
    public function reassignOrder(Request $request, StitchingOrder $stitchingOrder)
    {
        $validated = $request->validate([
            'tailor_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Get new tailor
            $newTailor = User::findOrFail($validated['tailor_id']);

            // Verify tailor role
            if (!$newTailor->hasRole('tailor')) {
                return back()->with('error', 'Selected user is not a tailor.');
            }

            // Get old tailor info
            $oldTailor = $stitchingOrder->tailor;

            // Update stitching order
            $stitchingOrder->update([
                'tailor_id' => $validated['tailor_id'],
                'additional_instructions' => ($stitchingOrder->additional_instructions ? $stitchingOrder->additional_instructions . '\n\n' : '') .
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
     * Get orders assigned to tailor
     */
    public function getTailorOrders(Tailor $tailor, Request $request)
    {
        $status = $request->query('status', 'all');

        $query = StitchingOrder::where('tailor_id', $tailor->user_id)
            ->with('order', 'order.user', 'measurement');

        if ($status !== 'all') {
            $query->where('stitching_status', $status);
        }

        $orders = $query->latest()->paginate(15);

        return view('receptionist.tailors.tailor-orders', compact('tailor', 'orders', 'status'));
    }

    /**
     * View tailor dashboard data (for receptionist to see tailor's workload)
     */
    public function viewDashboard(Tailor $tailor)
    {
        $tailor->load('user');

        // Get workload breakdown
        $workloadBreakdown = [
            'assigned' => StitchingOrder::where('tailor_id', $tailor->user_id)->where('stitching_status', 'assigned')->count(),
            'accepted' => StitchingOrder::where('tailor_id', $tailor->user_id)->where('stitching_status', 'accepted')->count(),
            'started' => StitchingOrder::where('tailor_id', $tailor->user_id)->where('stitching_status', 'in_progress')->count(),
            'in_progress' => StitchingOrder::where('tailor_id', $tailor->user_id)->whereIn('stitching_status', ['ready_for_fitting', 'in_fitting'])->count(),
            'completed' => StitchingOrder::where('tailor_id', $tailor->user_id)->where('stitching_status', 'completed')->count(),
        ];

        // Get recent orders
        $recentOrders = StitchingOrder::where('tailor_id', $tailor->user_id)
            ->with('order', 'order.user')
            ->latest()
            ->take(10)
            ->get();

        return view('receptionist.tailors.tailor-dashboard', compact(
            'tailor',
            'workloadBreakdown',
            'recentOrders'
        ));
    }
}
