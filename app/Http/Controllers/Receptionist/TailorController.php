<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StitchingOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Receptionist Tailor Controller
 * 
 * Tailor management from receptionist perspective
 * Handles assignments, workload tracking, and tailor status
 */
class TailorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('receptionist.only');
    }

    /**
     * Display a listing of all tailors
     */
    public function index(Request $request)
    {
        $query = User::role('tailor')->with('tailor');

        // Search by tailor name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Filter by status (receptionist specific)
        if ($request->has('status') && $request->status !== '') {
            $status = $request->status;
            $query->whereHas('tailor', function($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $tailors = $query->latest()->paginate(15)->appends($request->query());

        return view('receptionist.tailors.index', compact('tailors'));
    }

    /**
     * Show tailor details and workload
     */
    public function show(User $tailor)
    {
        // Verify user is a tailor
        if (!$tailor->hasRole('tailor')) {
            abort(404, 'Tailor not found');
        }

        $tailor->load('tailor');

        // Get stitching orders for this tailor
        $stitchingOrders = StitchingOrder::where('tailor_id', $tailor->id)
            ->with('order.user', 'measurement')
            ->latest()
            ->get();

        // Calculate statistics
        $stats = [
            'total_assigned' => $stitchingOrders->count(),
            'pending' => $stitchingOrders->where('stitching_status', 'pending')->count(),
            'assigned' => $stitchingOrders->where('stitching_status', 'assigned')->count(),
            'in_progress' => $stitchingOrders->where('stitching_status', 'in_progress')->count(),
            'completed' => $stitchingOrders->where('stitching_status', 'completed')->count(),
            'ready' => $stitchingOrders->where('stitching_status', 'ready')->count(),
        ];

        // Get recent orders
        $recentOrders = $stitchingOrders->take(10);

        return view('receptionist.tailors.show', compact(
            'tailor',
            'stats',
            'recentOrders',
            'stitchingOrders'
        ));
    }

    /**
     * Show form for assigning orders to tailor
     */
    public function assignForm()
    {
        // Get pending unassigned stitching orders
        $pendingOrders = StitchingOrder::where('stitching_status', 'pending')
            ->whereNull('tailor_id')
            ->with('order.user', 'measurement')
            ->latest()
            ->get();

        // Get active tailors
        $tailors = User::role('tailor')
            ->with('tailor')
            ->orderBy('name')
            ->get();

        return view('receptionist.tailors.assign-form', compact('pendingOrders', 'tailors'));
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

            // Verify order is pending and unassigned
            if ($stitchingOrder->stitching_status !== 'pending' || !is_null($stitchingOrder->tailor_id)) {
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
        $stitchingOrder->load('order.user', 'tailor', 'measurement');

        return view('receptionist.tailors.assignment-details', compact('stitchingOrder'));
    }

    /**
     * Get tailor workload (AJAX)
     */
    public function getWorkload(User $tailor)
    {
        // Verify user is a tailor
        if (!$tailor->hasRole('tailor')) {
            return response()->json(['error' => 'User is not a tailor'], 404);
        }

        $orders = StitchingOrder::where('tailor_id', $tailor->id)->get();

        $workload = [
            'active' => $orders->whereIn('stitching_status', ['assigned', 'in_progress'])->count(),
            'pending' => $orders->where('stitching_status', 'pending')->count(),
            'completed' => $orders->where('stitching_status', 'completed')->count(),
            'total' => $orders->count(),
        ];

        return response()->json($workload);
    }

    /**
     * Get tailor availability (AJAX)
     */
    public function getAvailability(User $tailor)
    {
        // Verify user is a tailor
        if (!$tailor->hasRole('tailor')) {
            return response()->json(['error' => 'User is not a tailor'], 404);
        }

        $activeOrders = StitchingOrder::where('tailor_id', $tailor->id)
            ->whereIn('stitching_status', ['assigned', 'in_progress', 'ready_for_fitting', 'in_fitting'])
            ->count();

        $maxCapacity = 15; // Maximum concurrent orders
        $availableSlots = $maxCapacity - $activeOrders;

        return response()->json([
            'active_orders' => $activeOrders,
            'max_capacity' => $maxCapacity,
            'available_slots' => max(0, $availableSlots),
            'is_available' => $availableSlots > 0,
            'utilization_percentage' => round(($activeOrders / $maxCapacity) * 100, 2),
        ]);
    }

    /**
     * Get all orders assigned to tailor
     */
    public function getTailorOrders(User $tailor, Request $request)
    {
        // Verify user is a tailor
        if (!$tailor->hasRole('tailor')) {
            abort(404, 'Tailor not found');
        }

        $status = $request->query('status', 'all');

        $query = StitchingOrder::where('tailor_id', $tailor->id)
            ->with('order.user', 'measurement');

        // Filter by status
        if ($status !== 'all') {
            $query->where('stitching_status', $status);
        }

        // Filter by date range (receptionist specific)
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->latest()->paginate(15);

        return view('receptionist.tailors.tailor-orders', compact('tailor', 'orders', 'status'));
    }

    /**
     * View tailor dashboard (workload breakdown and recent orders)
     */
    public function viewDashboard(User $tailor)
    {
        // Verify user is a tailor
        if (!$tailor->hasRole('tailor')) {
            abort(404, 'Tailor not found');
        }

        $tailor->load('tailor');

        // Get workload breakdown
        $workloadBreakdown = [
            'pending' => StitchingOrder::where('tailor_id', $tailor->id)->where('stitching_status', 'pending')->count(),
            'assigned' => StitchingOrder::where('tailor_id', $tailor->id)->where('stitching_status', 'assigned')->count(),
            'in_progress' => StitchingOrder::where('tailor_id', $tailor->id)->where('stitching_status', 'in_progress')->count(),
            'ready_for_fitting' => StitchingOrder::where('tailor_id', $tailor->id)->where('stitching_status', 'ready_for_fitting')->count(),
            'in_fitting' => StitchingOrder::where('tailor_id', $tailor->id)->where('stitching_status', 'in_fitting')->count(),
            'ready' => StitchingOrder::where('tailor_id', $tailor->id)->where('stitching_status', 'ready')->count(),
            'completed' => StitchingOrder::where('tailor_id', $tailor->id)->where('stitching_status', 'completed')->count(),
        ];

        // Get recent orders
        $recentOrders = StitchingOrder::where('tailor_id', $tailor->id)
            ->with('order.user', 'measurement')
            ->latest()
            ->take(15)
            ->get();

        // Calculate performance metrics
        $totalAssigned = array_sum($workloadBreakdown);
        $completionRate = $totalAssigned > 0 ? round(($workloadBreakdown['completed'] / $totalAssigned) * 100, 2) : 0;

        return view('receptionist.tailors.tailor-dashboard', compact(
            'tailor',
            'workloadBreakdown',
            'recentOrders',
            'completionRate',
            'totalAssigned'
        ));
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
     * Get tailor performance statistics (receptionist view)
     */
    public function getPerformance(User $tailor, Request $request)
    {
        // Verify user is a tailor
        if (!$tailor->hasRole('tailor')) {
            return response()->json(['error' => 'User is not a tailor'], 404);
        }

        $fromDate = $request->get('from_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->from_date) : \Carbon\Carbon::now()->subDays(30);
        $toDate = $request->get('to_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->to_date) : \Carbon\Carbon::now();

        $orders = StitchingOrder::where('tailor_id', $tailor->id)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        $completedOrders = $orders->where('stitching_status', 'completed');
        $inProgressOrders = $orders->where('stitching_status', 'in_progress');
        $delayedOrders = $orders->filter(function($order) {
            return $order->fitting_date && $order->fitting_date->isPast() && !in_array($order->stitching_status, ['completed', 'ready']);
        });

        return response()->json([
            'total_orders' => $orders->count(),
            'completed_orders' => $completedOrders->count(),
            'in_progress_orders' => $inProgressOrders->count(),
            'delayed_orders' => $delayedOrders->count(),
            'completion_rate' => $orders->count() > 0 ? round(($completedOrders->count() / $orders->count()) * 100, 2) : 0,
            'average_completion_time' => $this->calculateAverageCompletionTime($completedOrders),
        ]);
    }

    /**
     * Calculate average time to complete orders
     */
    private function calculateAverageCompletionTime($orders)
    {
        if ($orders->count() === 0) {
            return 0;
        }

        $totalDays = $orders->sum(function($order) {
            if ($order->assigned_date && $order->completion_date) {
                return $order->completion_date->diffInDays($order->assigned_date);
            }
            return 0;
        });

        return round($totalDays / $orders->count(), 1);
    }
}
