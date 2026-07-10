<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StitchingOrder;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
        // Only receptionist can access these reports
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasRole('receptionist')) {
                abort(403, 'Unauthorized access to receptionist reports');
            }
            return $next($request);
        });
    }

    /**
     * Reports Dashboard Index
     * Shows summary cards and quick access to all reports
     */
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $data = [
            'todayOrders' => Order::whereDate('created_at', $today)->count(),
            'monthlyOrders' => Order::whereBetween('created_at', [
                $thisMonth,
                $thisMonth->copy()->endOfMonth()
            ])->count(),
            'todayRevenue' => Order::whereDate('created_at', $today)
                ->where('status', '!=', 'cancelled')
                ->sum('total'),
            'monthlyRevenue' => Order::whereBetween('created_at', [
                $thisMonth,
                $thisMonth->copy()->endOfMonth()
            ])->where('status', '!=', 'cancelled')->sum('total'),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'completedOrders' => Order::where('status', 'completed')->count(),
            'readyForDelivery' => Order::where('status', 'ready_for_delivery')->count(),
            'pendingStitching' => StitchingOrder::whereIn('stitching_status', ['pending', 'assigned', 'in_progress'])->count(),
            'completedStitching' => StitchingOrder::where('stitching_status', 'completed')->count(),
            'pendingPayments' => Payment::where('status', 'pending')->sum('amount'),
            'collectedPayments' => Payment::where('status', 'completed')->sum('amount'),
        ];

        return view('receptionist.reports.index', $data);
    }

    /**
     * Daily Orders Report
     * Shows all orders placed on a specific date with breakdown by status
     */
    public function dailyOrdersReport(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $date = Carbon::createFromFormat('Y-m-d', $date);
        $orderType = $request->get('order_type', 'all');
        $status = $request->get('status', 'all');

        $query = Order::whereDate('created_at', $date);

        // Apply order type filter
        if ($orderType && $orderType !== 'all') {
            $query->where('type', $orderType);
        }

        // Apply status filter
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->with('user')
            ->orderByDesc('created_at')
            ->get();

        $chartData = $this->getDailyOrdersChartData($date, $orderType);
        
        $summary = [
            'total_orders' => $orders->count(),
            'completed' => $orders->where('status', 'completed')->count(),
            'pending' => $orders->where('status', 'pending')->count(),
            'confirmed' => $orders->where('status', 'confirmed')->count(),
            'in_progress' => $orders->where('status', 'in_progress')->count(),
            'ready' => $orders->where('status', 'ready')->count(),
            'cancelled' => $orders->where('status', 'cancelled')->count(),
            'total_revenue' => $orders->sum('total'),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            'paid_orders' => $orders->where('payment_status', 'paid')->count(),
            'pending_payment_amount' => $orders->where('payment_status', 'pending')->sum('total'),
        ];

        return view('receptionist.reports.daily-orders', compact('orders', 'date', 'chartData', 'summary', 'orderType', 'status'));
    }

    /**
     * Monthly Sales Report
     * Shows sales trends throughout the month
     */
    public function monthlySalesReport(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $orderType = $request->get('order_type', 'all');

        $query = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled');

        if ($orderType && $orderType !== 'all') {
            $query->where('type', $orderType);
        }

        $orders = $query->with('user')
            ->orderByDesc('created_at')
            ->get();

        $chartData = $this->getMonthlySalesChartData($startDate, $endDate, $orderType);
        
        $summary = [
            'total_sales' => $orders->sum('total'),
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'confirmed_orders' => $orders->where('status', 'confirmed')->count(),
            'in_progress_orders' => $orders->where('status', 'in_progress')->count(),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            'daily_average' => $startDate->diffInDays($endDate) + 1 > 0 ? $orders->sum('total') / ($startDate->diffInDays($endDate) + 1) : 0,
            'total_items' => $orders->sum(function($order) { return $order->orderItems()->count() ?? 0; }),
        ];

        return view('receptionist.reports.monthly-sales', compact('orders', 'startDate', 'endDate', 'month', 'chartData', 'summary', 'orderType'));
    }

    /**
     * Pending Stitching Report
     * Monitor stitching orders by status and tailor assignments
     */
    public function pendingStitchingReport(Request $request)
    {
        $status = $request->get('status', 'all');
        $fromDate = $request->get('from_date') ? Carbon::createFromFormat('Y-m-d', $request->get('from_date')) : Carbon::now()->subDays(30);
        $toDate = $request->get('to_date') ? Carbon::createFromFormat('Y-m-d', $request->get('to_date')) : Carbon::now();

        $query = StitchingOrder::with(['order', 'order.user', 'tailor', 'measurement']);

        // Apply status filter
        if ($status && $status !== 'all') {
            $query->where('stitching_status', $status);
        } else {
            // By default show pending, assigned, and in_progress
            $query->whereIn('stitching_status', ['pending', 'assigned', 'in_progress']);
        }

        $stitchingOrders = $query->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at')
            ->get();

        $chartData = $this->getStitchingStatusChartData($fromDate, $toDate);
        
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
            'total_estimated_cost' => $stitchingOrders->sum('estimated_cost'),
        ];

        return view('receptionist.reports.pending-stitching', compact('stitchingOrders', 'fromDate', 'toDate', 'status', 'chartData', 'summary'));
    }

    /**
     * Completed Orders Report
     * View all completed orders with revenue and fulfillment details
     */
    public function completedOrdersReport(Request $request)
    {
        $fromDate = $request->get('from_date') ? Carbon::createFromFormat('Y-m-d', $request->get('from_date')) : Carbon::now()->subDays(30);
        $toDate = $request->get('to_date') ? Carbon::createFromFormat('Y-m-d', $request->get('to_date')) : Carbon::now();
        $orderType = $request->get('order_type', 'all');

        $query = Order::where('status', 'completed')
            ->whereBetween('created_at', [$fromDate, $toDate]);

        if ($orderType && $orderType !== 'all') {
            $query->where('type', $orderType);
        }

        $orders = $query->with('user', 'stitchingOrder')
            ->orderByDesc('created_at')
            ->get();

        $chartData = $this->getCompletedOrdersChartData($fromDate, $toDate, $orderType);
        
        $summary = [
            'total_completed' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            'total_items' => $orders->sum(function($order) { return $order->orderItems()->count() ?? 0; }),
            'paid_count' => $orders->where('payment_status', 'paid')->count(),
            'pending_payment_count' => $orders->where('payment_status', 'pending')->count(),
            'total_stitching_charges' => $orders->sum('stitching_charge'),
        ];

        return view('receptionist.reports.completed-orders', compact('orders', 'fromDate', 'toDate', 'chartData', 'summary', 'orderType'));
    }

    /**
     * Payment Collection Report
     * Track payment collections, pending amounts, and transaction status
     */
    public function paymentCollectionReport(Request $request)
    {
        $fromDate = $request->get('from_date') ? Carbon::createFromFormat('Y-m-d', $request->get('from_date')) : Carbon::now()->subDays(30);
        $toDate = $request->get('to_date') ? Carbon::createFromFormat('Y-m-d', $request->get('to_date')) : Carbon::now();
        $paymentStatus = $request->get('payment_status', 'all');

        $query = Payment::with(['order', 'order.user'])
            ->whereBetween('created_at', [$fromDate, $toDate]);

        if ($paymentStatus && $paymentStatus !== 'all') {
            $query->where('status', $paymentStatus);
        }

        $payments = $query->orderByDesc('created_at')->get();

        $chartData = $this->getPaymentCollectionChartData($fromDate, $toDate);
        
        $summary = [
            'total_transactions' => $payments->count(),
            'total_collected' => $payments->where('status', 'completed')->sum('amount'),
            'total_pending' => $payments->where('status', 'pending')->sum('amount'),
            'total_failed' => $payments->where('status', 'failed')->sum('amount'),
            'average_payment' => $payments->count() > 0 ? $payments->sum('amount') / $payments->count() : 0,
            'completed_count' => $payments->where('status', 'completed')->count(),
            'pending_count' => $payments->where('status', 'pending')->count(),
            'failed_count' => $payments->where('status', 'failed')->count(),
            'refunded_count' => $payments->where('status', 'refunded')->count(),
            'collection_rate' => $payments->count() > 0 ? ($payments->where('status', 'completed')->count() / $payments->count()) * 100 : 0,
        ];

        return view('receptionist.reports.payment-collection', compact('payments', 'fromDate', 'toDate', 'paymentStatus', 'chartData', 'summary'));
    }

    // ============================================================================
    // CHART DATA HELPERS
    // ============================================================================

    /**
     * Get chart data for daily orders report
     */
    private function getDailyOrdersChartData($date, $orderType = 'all')
    {
        $query = Order::whereDate('created_at', $date);

        if ($orderType && $orderType !== 'all') {
            $query->where('type', $orderType);
        }

        $statuses = $query->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $statuses->pluck('status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))->toArray(),
            'data' => $statuses->pluck('count')->toArray(),
            'backgroundColor' => ['#28a745', '#ffc107', '#17a2b8', '#007bff', '#6610f2', '#dc3545'],
        ];
    }

    /**
     * Get chart data for monthly sales report
     */
    private function getMonthlySalesChartData($startDate, $endDate, $orderType = 'all')
    {
        $query = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled');

        if ($orderType && $orderType !== 'all') {
            $query->where('type', $orderType);
        }

        $dailySales = $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $dailySales->pluck('date')->toArray(),
            'sales' => $dailySales->pluck('total')->map(fn($s) => (float)$s)->toArray(),
            'orders' => $dailySales->pluck('count')->toArray(),
        ];
    }

    /**
     * Get chart data for stitching status report
     */
    private function getStitchingStatusChartData($fromDate, $toDate)
    {
        $statuses = StitchingOrder::whereBetween('created_at', [$fromDate, $toDate])
            ->select('stitching_status', DB::raw('count(*) as count'))
            ->groupBy('stitching_status')
            ->get();

        $statusColors = [
            'pending' => '#ffc107',
            'assigned' => '#17a2b8',
            'in_progress' => '#007bff',
            'ready_for_fitting' => '#6610f2',
            'in_fitting' => '#e83e8c',
            'ready' => '#28a745',
            'completed' => '#20c997',
            'cancelled' => '#dc3545',
        ];

        return [
            'labels' => $statuses->pluck('stitching_status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))->toArray(),
            'data' => $statuses->pluck('count')->toArray(),
            'backgroundColor' => $statuses->pluck('stitching_status')->map(fn($s) => $statusColors[$s] ?? '#6c757d')->toArray(),
        ];
    }

    /**
     * Get chart data for completed orders report
     */
    private function getCompletedOrdersChartData($fromDate, $toDate, $orderType = 'all')
    {
        $query = Order::where('status', 'completed')
            ->whereBetween('created_at', [$fromDate, $toDate]);

        if ($orderType && $orderType !== 'all') {
            $query->where('type', $orderType);
        }

        $dailyCompleted = $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $dailyCompleted->pluck('date')->toArray(),
            'sales' => $dailyCompleted->pluck('total')->map(fn($s) => (float)$s)->toArray(),
            'orders' => $dailyCompleted->pluck('count')->toArray(),
        ];
    }

    /**
     * Get chart data for payment collection report
     */
    private function getPaymentCollectionChartData($fromDate, $toDate)
    {
        $dailyPayments = Payment::whereBetween('created_at', [$fromDate, $toDate])
            ->select(
                'status',
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        $dates = $dailyPayments->pluck('date')->unique()->values()->toArray();

        return [
            'labels' => $dates,
            'completed' => $this->getPaymentsByStatus($dailyPayments, 'completed', $dates),
            'pending' => $this->getPaymentsByStatus($dailyPayments, 'pending', $dates),
            'failed' => $this->getPaymentsByStatus($dailyPayments, 'failed', $dates),
            'refunded' => $this->getPaymentsByStatus($dailyPayments, 'refunded', $dates),
        ];
    }

    /**
     * Helper to get payments by status for each date
     */
    private function getPaymentsByStatus($payments, $status, $dates)
    {
        $result = [];
        foreach ($dates as $date) {
            $amount = $payments->where('date', $date)->where('status', $status)->sum('total');
            $result[] = (float)$amount;
        }
        return $result;
    }
}
