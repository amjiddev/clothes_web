<?php

namespace App\Http\Controllers\Tailor;

use App\Http\Controllers\Controller;
use App\Models\StitchingOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CompletedOrderController extends Controller
{
    /**
     * Display completed orders
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = StitchingOrder::where('tailor_id', $user->id)
            ->where('stitching_status', 'completed')
            ->with(['order.customer', 'measurement']);

        // Search by order ID or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('completion_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('completion_date', '<=', $request->to_date);
        }

        $orders = $query->orderBy('completion_date', 'desc')
            ->paginate(15)
            ->appends($request->query());

        // Get all completed orders for statistics (unfiltered)
        $allCompletedOrders = StitchingOrder::where('tailor_id', $user->id)
            ->where('stitching_status', 'completed')
            ->get();

        // Statistics
        $thisMonthCompleted = $allCompletedOrders
            ->filter(function ($order) {
                return $order->completion_date && 
                       $order->completion_date->year === now()->year && 
                       $order->completion_date->month === now()->month;
            })
            ->count();

        $thisYearCompleted = $allCompletedOrders
            ->filter(function ($order) {
                return $order->completion_date && 
                       $order->completion_date->year === now()->year;
            })
            ->count();

        $allTimeCompleted = $allCompletedOrders->count();

        // Calculate average completion time in days
        $averageCompletionTime = 0;
        if ($allCompletedOrders->count() > 0) {
            $totalDays = 0;
            $validOrders = 0;

            foreach ($allCompletedOrders as $order) {
                if ($order->assigned_date && $order->completion_date) {
                    $days = $order->completion_date->diffInDays($order->assigned_date);
                    $totalDays += $days;
                    $validOrders++;
                }
            }

            $averageCompletionTime = $validOrders > 0 ? round($totalDays / $validOrders, 1) : 0;
        }

        return view('tailor.completed-orders.index', [
            'orders' => $orders,
            'thisMonthCompleted' => $thisMonthCompleted,
            'thisYearCompleted' => $thisYearCompleted,
            'allTimeCompleted' => $allTimeCompleted,
            'averageCompletionTime' => $averageCompletionTime,
            'searchQuery' => $request->get('search'),
            'fromDate' => $request->get('from_date'),
            'toDate' => $request->get('to_date'),
        ]);
    }

    /**
     * Display a completed order detail
     */
    public function show($id)
    {
        $user = Auth::user();

        $order = StitchingOrder::where('tailor_id', $user->id)
            ->where('stitching_status', 'completed')
            ->with(['order.customer', 'measurement'])
            ->findOrFail($id);

        return view('tailor.completed-orders.show', ['order' => $order]);
    }
}
