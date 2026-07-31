<?php

namespace App\Http\Controllers\Tailor;

use App\Http\Controllers\Controller;
use App\Models\StitchingOrder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the tailor dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $tailor = $user->tailor;

        // Get all stitching orders assigned to this tailor
        $allOrders = StitchingOrder::where('tailor_id', $user->id)->get();

        // Total Assigned Orders
        $totalAssignedOrders = $allOrders->count();

        // Pending Stitching Orders (not yet assigned/started)
        $pendingOrders = $allOrders->where('stitching_status', 'pending')->count();

        // Orders In Progress
        $inProgressOrders = $allOrders->whereIn('stitching_status', [
            'assigned',
            'in_progress',
            'ready_for_fitting',
            'in_fitting'
        ])->count();

        // Completed Orders
        $completedOrders = $allOrders->where('stitching_status', 'completed')->count();

        // Today's Delivery Orders
        $today = Carbon::today();
        $todayDeliveries = $allOrders
            ->where('stitching_status', 'ready')
            ->filter(function ($order) use ($today) {
                return $order->completion_date && 
                       $order->completion_date->toDateString() === $today->toDateString();
            })
            ->count();

        // Recent Assigned Orders (last 5)
        $recentOrders = StitchingOrder::where('tailor_id', $user->id)
            ->with(['order.customer', 'measurement'])
            ->latest('assigned_date')
            ->limit(5)
            ->get();

        // Orders by Status
        $ordersByStatus = $allOrders->groupBy('stitching_status')->map->count();

        // This month's completed orders
        $thisMonthCompleted = $allOrders
            ->where('stitching_status', 'completed')
            ->filter(function ($order) {
                return $order->completion_date && 
                       $order->completion_date->month === now()->month &&
                       $order->completion_date->year === now()->year;
            })
            ->count();

        // Calculate workload percentage (active orders vs capacity)
        $activeOrders = $allOrders->whereIn('stitching_status', [
            'assigned',
            'in_progress',
            'ready_for_fitting',
            'in_fitting'
        ])->count();

        return view('tailor.dashboard', [
            'totalAssignedOrders' => $totalAssignedOrders,
            'pendingOrders' => $pendingOrders,
            'inProgressOrders' => $inProgressOrders,
            'completedOrders' => $completedOrders,
            'todayDeliveries' => $todayDeliveries,
            'recentOrders' => $recentOrders,
            'ordersByStatus' => $ordersByStatus,
            'thisMonthCompleted' => $thisMonthCompleted,
            'activeOrders' => $activeOrders,
            'tailor' => $tailor,
        ]);
    }
}
