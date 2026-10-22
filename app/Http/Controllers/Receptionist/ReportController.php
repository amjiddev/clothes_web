<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StitchingOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Receptionist Report Controller
 * 
 * Generate reports for receptionist operations
 * Includes orders, payments, stitching status, and performance metrics
 */
class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('receptionist.only');
    }

    /**
     * Receptionist Reports Dashboard
     */
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisMonthEnd = $thisMonth->copy()->endOfMonth();

        $data = [
            'todayOrders' => Order::whereDate('created_at', $today)->count(),
            'monthlyOrders' => Order::whereBetween('created_at', [$thisMonth, $thisMonthEnd])->count(),
            'todayRevenue' => Order::whereDate('created_at', $today)
                ->where('status', '!=', 'cancelled')
                ->sum('total'),
            'monthlyRevenue' => Order::whereBetween('created_at', [$thisMonth, $thisMonthEnd])
                ->where('status', '!=', 'cancelled')
                ->sum('total'),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'completedOrders' => Order::where('status', 'completed')->count(),
            'readyForDelivery' => Order::where('status', 'ready_for_delivery')->count(),
            'pendingStitching' => StitchingOrder::where('stitching_status', 'pending')->count(),
            'inProgressStitching' => StitchingOrder::where('stitching_status', 'in_progress')->count(),
            'completedStitching' => StitchingOrder::where('stitching_status', 'completed')->count(),
            'collectedPayments' => 0,
            'pendingPayments' => 0,
        ];

        return view('receptionist.reports.index', $data);
    }

    /**
     * Daily Orders Report
     */
    public function dailyOrdersReport(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $date = Carbon::createFromFormat('Y-m-d', $date);
        $orderType = $request->get('order_type', 'all');
        $status = $request->get('status', 'all');

        $orders = Order::whereDate('created_at', $date)
            ->with('user', 'orderItems')
            ->orderByDesc('created_at')
            ->get();

        // Filter by order type if specified
        if ($orderType && $orderType !== 'all') {
            $orders = $orders->where('type', $orderType);
        }

        // Filter by status if specified
        if ($status && $status !== 'all') {
            $orders = $orders->where('status', $status);
        }

        $summary = [
            'total_orders' => $orders->count(),
            'completed' => $orders->where('status', 'completed')->count(),
            'pending' => $orders->where('status', 'pending')->count(),
            'in_progress' => $orders->where('status', 'in_progress')->count(),
            'confirmed' => $orders->where('status', 'confirmed')->count(),
            'ready' => $orders->where('status', 'ready')->count(),
            'cancelled' => $orders->where('status', 'cancelled')->count(),
            'total_revenue' => $orders->where('status', '!=', 'cancelled')->sum('total'),
            'total_collected' => 0,
            'pending_payment_amount' => 0,
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
        ];

        $chartData = $this->getDailyOrdersChart($date);

        return view('receptionist.reports.daily-orders', compact('orders', 'date', 'summary', 'chartData', 'orderType', 'status'));
    }

    /**
     * Monthly Sales Report
     */
    public function monthlySalesReport(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $orderType = $request->get('order_type', 'all');
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $query = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled');

        // Filter by order type
        if ($orderType !== 'all') {
            $query->where('type', $orderType);
        }

        $orders = $query->with('user')
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'total_sales' => $orders->sum('total'),
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'in_progress_orders' => $orders->where('status', 'in_progress')->count(),
            'confirmed_orders' => $orders->where('status', 'confirmed')->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            'total_collected' => 0,
            'daily_average' => $startDate->diffInDays($endDate) + 1 > 0 ? $orders->sum('total') / ($startDate->diffInDays($endDate) + 1) : 0,
        ];

        $chartData = $this->getMonthlySalesChart($startDate, $endDate);

        return view('receptionist.reports.monthly-sales', compact('orders', 'startDate', 'endDate', 'month', 'orderType', 'summary', 'chartData'));
    }

    /**
     * Pending Stitching Report
     */
    public function pendingStitchingReport(Request $request)
    {
        $status = $request->get('status', 'all');
        $fromDate = $request->get('from_date') ? Carbon::createFromFormat('Y-m-d', $request->from_date) : Carbon::now()->subDays(30);
        $toDate = $request->get('to_date') ? Carbon::createFromFormat('Y-m-d', $request->to_date) : Carbon::now();

        $query = StitchingOrder::with(['order', 'order.user', 'tailor', 'measurement']);

        if ($status && $status !== 'all') {
            $query->where('stitching_status', $status);
        }

        $stitchingOrders = $query->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at')
            ->get();

        $summary = [
            'total_stitching' => $stitchingOrders->count(),
            'pending' => $stitchingOrders->where('stitching_status', 'pending')->count(),
            'assigned' => $stitchingOrders->where('stitching_status', 'assigned')->count(),
            'in_progress' => $stitchingOrders->where('stitching_status', 'in_progress')->count(),
            'ready_for_fitting' => $stitchingOrders->where('stitching_status', 'ready_for_fitting')->count(),
            'in_fitting' => $stitchingOrders->where('stitching_status', 'in_fitting')->count(),
            'ready' => $stitchingOrders->where('stitching_status', 'ready')->count(),
            'completed' => $stitchingOrders->where('stitching_status', 'completed')->count(),
            'cancelled' => $stitchingOrders->where('stitching_status', 'cancelled')->count(),
        ];

        $chartData = $this->getStitchingStatusChart($fromDate, $toDate);

        return view('receptionist.reports.pending-stitching', compact(
            'stitchingOrders',
            'fromDate',
            'toDate',
            'status',
            'summary',
            'chartData'
        ));
    }

    /**
     * Completed Orders Report
     */
    public function completedOrdersReport(Request $request)
    {
        $orderType = $request->get('order_type', 'all');
        $fromDate = $request->get('from_date') ? Carbon::createFromFormat('Y-m-d', $request->from_date) : Carbon::now()->subDays(30);
        $toDate = $request->get('to_date') ? Carbon::createFromFormat('Y-m-d', $request->to_date) : Carbon::now();

        $query = Order::where('status', 'completed')
            ->whereBetween('created_at', [$fromDate, $toDate]);

        // Filter by order type
        if ($orderType !== 'all') {
            $query->where('type', $orderType);
        }

        $orders = $query->with('user', 'orderItems')
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'total_completed' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'total_stitching_charges' => $orders->sum('stitching_charge'),
            'total_collected' => 0,
            'paid_count' => $orders->where('payment_status', 'paid')->count(),
            'pending_payment_count' => $orders->where('payment_status', 'pending')->count(),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            'total_items' => $orders->sum(function($order) { 
                return $order->orderItems->count(); 
            }),
        ];

        $chartData = $this->getCompletedOrdersChart($fromDate, $toDate);

        return view('receptionist.reports.completed-orders', compact(
            'orders',
            'fromDate',
            'toDate',
            'orderType',
            'summary',
            'chartData'
        ));
    }

    /**
     * Helper: Get Daily Orders Chart Data
     */
    private function getDailyOrdersChart($date)
    {
        $orders = Order::whereDate('created_at', $date)
            ->select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $orders->pluck('status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))->toArray(),
            'data' => $orders->pluck('count')->toArray(),
        ];
    }

    /**
     * Helper: Get Monthly Sales Chart Data
     */
    private function getMonthlySalesChart($startDate, $endDate)
    {
        $dailySales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('SUM(total) as total'), \DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $dailySales->pluck('date')->toArray(),
            'sales' => $dailySales->pluck('total')->toArray(),
            'orders' => $dailySales->pluck('count')->toArray(),
        ];
    }

    /**
     * Helper: Get Stitching Status Chart Data
     */
    private function getStitchingStatusChart($fromDate, $toDate)
    {
        $statuses = StitchingOrder::whereBetween('created_at', [$fromDate, $toDate])
            ->select('stitching_status', \DB::raw('count(*) as count'))
            ->groupBy('stitching_status')
            ->get();

        return [
            'labels' => $statuses->pluck('stitching_status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))->toArray(),
            'data' => $statuses->pluck('count')->toArray(),
        ];
    }

    /**
     * Helper: Get Completed Orders Chart Data
     */
    private function getCompletedOrdersChart($fromDate, $toDate)
    {
        $dailyCompleted = Order::where('status', 'completed')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('SUM(total) as total'), \DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $dailyCompleted->pluck('date')->toArray(),
            'sales' => $dailyCompleted->pluck('total')->toArray(),
            'orders' => $dailyCompleted->pluck('count')->toArray(),
        ];
    }

}
