<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StitchingOrder;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Receptionist Reports Dashboard
     */
    public function receptionistReports()
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
            'pendingPayments' => Payment::where('status', 'pending')->sum('amount'),
            'completedPayments' => Payment::where('status', 'completed')->sum('amount'),
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

        $orders = Order::whereDate('created_at', $date)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $chartData = $this->getDailyOrdersChart($date);
        $summary = [
            'total_orders' => $orders->count(),
            'completed' => $orders->where('status', 'completed')->count(),
            'pending' => $orders->where('status', 'pending')->count(),
            'total_revenue' => $orders->sum('total'),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
        ];

        return view('receptionist.reports.daily-orders', compact('orders', 'date', 'chartData', 'summary'));
    }

    /**
     * Monthly Sales Report
     */
    public function monthlySalesReport(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $chartData = $this->getMonthlySalesChart($startDate, $endDate);
        $summary = [
            'total_sales' => $orders->sum('total'),
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            'daily_average' => $startDate->diffInDays($endDate) + 1 > 0 ? $orders->sum('total') / ($startDate->diffInDays($endDate) + 1) : 0,
        ];

        return view('receptionist.reports.monthly-sales', compact('orders', 'startDate', 'endDate', 'month', 'chartData', 'summary'));
    }

    /**
     * Pending Stitching Report
     */
    public function pendingStitchingReport(Request $request)
    {
        $status = $request->get('status', 'pending');
        $fromDate = $request->get('from_date') ? Carbon::createFromFormat('Y-m-d', $request->get('from_date')) : Carbon::now()->subDays(30);
        $toDate = $request->get('to_date') ? Carbon::createFromFormat('Y-m-d', $request->get('to_date')) : Carbon::now();

        $query = StitchingOrder::with(['order', 'order.user', 'tailor', 'measurement']);

        if ($status && $status !== 'all') {
            $query->where('stitching_status', $status);
        }

        $stitchingOrders = $query->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at')
            ->get();

        $chartData = $this->getStitchingStatusChart($fromDate, $toDate);
        $summary = [
            'total_stitching' => $stitchingOrders->count(),
            'pending' => $stitchingOrders->where('stitching_status', 'pending')->count(),
            'in_progress' => $stitchingOrders->where('stitching_status', 'in_progress')->count(),
            'completed' => $stitchingOrders->where('stitching_status', 'completed')->count(),
            'on_hold' => $stitchingOrders->where('stitching_status', 'on_hold')->count(),
        ];

        return view('receptionist.reports.pending-stitching', compact('stitchingOrders', 'fromDate', 'toDate', 'status', 'chartData', 'summary'));
    }

    /**
     * Completed Orders Report
     */
    public function completedOrdersReport(Request $request)
    {
        $fromDate = $request->get('from_date') ? Carbon::createFromFormat('Y-m-d', $request->get('from_date')) : Carbon::now()->subDays(30);
        $toDate = $request->get('to_date') ? Carbon::createFromFormat('Y-m-d', $request->get('to_date')) : Carbon::now();

        $orders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $chartData = $this->getCompletedOrdersChart($fromDate, $toDate);
        $summary = [
            'total_completed' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0,
            'total_items' => $orders->sum(function($order) { return $order->orderItems->count(); }),
        ];

        return view('receptionist.reports.completed-orders', compact('orders', 'fromDate', 'toDate', 'chartData', 'summary'));
    }

    /**
     * Payment Collection Report
     */
    public function paymentCollectionReport(Request $request)
    {
        $fromDate = $request->get('from_date') ? Carbon::createFromFormat('Y-m-d', $request->get('from_date')) : Carbon::now()->subDays(30);
        $toDate = $request->get('to_date') ? Carbon::createFromFormat('Y-m-d', $request->get('to_date')) : Carbon::now();
        $status = $request->get('status', 'all');

        $query = Payment::with(['order', 'user'])->whereBetween('created_at', [$fromDate, $toDate]);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $payments = $query->orderByDesc('created_at')->get();

        $chartData = $this->getPaymentCollectionChart($fromDate, $toDate);
        $summary = [
            'total_transactions' => $payments->count(),
            'total_collected' => $payments->where('status', 'completed')->sum('amount'),
            'total_pending' => $payments->where('status', 'pending')->sum('amount'),
            'average_payment' => $payments->count() > 0 ? $payments->sum('amount') / $payments->count() : 0,
            'completed_count' => $payments->where('status', 'completed')->count(),
            'pending_count' => $payments->where('status', 'pending')->count(),
            'failed_count' => $payments->where('status', 'failed')->count(),
        ];

        return view('receptionist.reports.payment-collection', compact('payments', 'fromDate', 'toDate', 'status', 'chartData', 'summary'));
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

    /**
     * Helper: Get Payment Collection Chart Data
     */
    private function getPaymentCollectionChart($fromDate, $toDate)
    {
        $dailyPayments = Payment::whereBetween('created_at', [$fromDate, $toDate])
            ->select('status', \DB::raw('DATE(created_at) as date'), \DB::raw('SUM(amount) as total'), \DB::raw('COUNT(*) as count'))
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $dailyPayments->pluck('date')->unique()->values()->toArray(),
            'completed' => $dailyPayments->where('status', 'completed')->pluck('total')->toArray(),
            'pending' => $dailyPayments->where('status', 'pending')->pluck('total')->toArray(),
        ];
    }
}
