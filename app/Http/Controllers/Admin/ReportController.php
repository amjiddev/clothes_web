<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\StitchingOrder;
use App\Models\User;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_reports', ['only' => ['index', 'salesReport', 'orderReport', 'stitchingReport', 'customerReport', 'tailorReport']]);
        $this->middleware('permission:export_reports', ['only' => ['exportPdf', 'exportExcel']]);
    }

    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $this->getStartDate($period, $request->get('from'));
        $endDate = $this->getEndDate($period, $request->get('to'));

        // Dashboard statistics
        $stats = [
            'total_sales' => Order::whereBetween('created_at', [$startDate, $endDate])->sum('total'),
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_stitching' => StitchingOrder::whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed_stitching' => StitchingOrder::where('stitching_status', 'completed')
                                        ->whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        return view('admin.reports.index', compact('stats', 'period', 'startDate', 'endDate'));
    }

    /**
     * Sales Report
     */
    public function salesReport(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $this->getStartDate($period, $request->get('from'));
        $endDate = $this->getEndDate($period, $request->get('to'));

        $sales = Order::whereBetween('created_at', [$startDate, $endDate])
                    ->select('created_at', 'order_number', 'total', 'payment_status', 'type')
                    ->orderByDesc('created_at')
                    ->paginate(50);

        // Calculate totals
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Group by type
        $byType = Order::whereBetween('created_at', [$startDate, $endDate])
                    ->selectRaw('type, COUNT(*) as count, SUM(total) as total')
                    ->groupBy('type')
                    ->get();

        // Daily breakdown
        $dailyData = Order::whereBetween('created_at', [$startDate, $endDate])
                        ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as total')
                        ->groupByRaw('DATE(created_at)')
                        ->orderByRaw('DATE(created_at)')
                        ->get();

        return view('admin.reports.sales', compact('sales', 'totalRevenue', 'totalOrders', 'avgOrderValue', 'byType', 'dailyData', 'period', 'startDate', 'endDate'));
    }

    /**
     * Order Report
     */
    public function orderReport(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $this->getStartDate($period, $request->get('from'));
        $endDate = $this->getEndDate($period, $request->get('to'));

        $orders = Order::with('user')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderByDesc('created_at')
                    ->paginate(50);

        // Status breakdown
        $byStatus = Order::whereBetween('created_at', [$startDate, $endDate])
                    ->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->get();

        // Payment status breakdown
        $byPaymentStatus = Order::whereBetween('created_at', [$startDate, $endDate])
                        ->selectRaw('payment_status, COUNT(*) as count')
                        ->groupBy('payment_status')
                        ->get();

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedOrders = Order::where('status', 'delivered')->whereBetween('created_at', [$startDate, $endDate])->count();
        $pendingOrders = Order::where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count();

        return view('admin.reports.orders', compact('orders', 'byStatus', 'byPaymentStatus', 'totalOrders', 'completedOrders', 'pendingOrders', 'period', 'startDate', 'endDate'));
    }

    /**
     * Stitching Report
     */
    public function stitchingReport(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $this->getStartDate($period, $request->get('from'));
        $endDate = $this->getEndDate($period, $request->get('to'));

        $stitching = StitchingOrder::with(['order', 'tailor'])
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->orderByDesc('created_at')
                            ->paginate(50);

        // Status breakdown
        $byStatus = StitchingOrder::whereBetween('created_at', [$startDate, $endDate])
                        ->selectRaw('stitching_status, COUNT(*) as count')
                        ->groupBy('stitching_status')
                        ->get();

        $totalStitching = StitchingOrder::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedStitching = StitchingOrder::where('stitching_status', 'completed')
                                ->whereBetween('created_at', [$startDate, $endDate])->count();
        $avgCost = StitchingOrder::whereBetween('created_at', [$startDate, $endDate])
                        ->avg('estimated_cost') ?? 0;

        return view('admin.reports.stitching', compact('stitching', 'byStatus', 'totalStitching', 'completedStitching', 'avgCost', 'period', 'startDate', 'endDate'));
    }

    /**
     * Customer Report
     */
    public function customerReport(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $this->getStartDate($period, $request->get('from'));
        $endDate = $this->getEndDate($period, $request->get('to'));

        // Customers with orders
        $customers = User::whereHas('orders', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->withCount(['orders' => function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    }])
                    ->withSum(['orders' => function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    }], 'total')
                    ->orderByDesc('orders_count')
                    ->paginate(50);

        $totalCustomers = $customers->total();
        $totalSpent = Order::whereBetween('created_at', [$startDate, $endDate])
                        ->whereIn('user_id', User::pluck('id'))
                        ->sum('total');

        return view('admin.reports.customers', compact('customers', 'totalCustomers', 'totalSpent', 'period', 'startDate', 'endDate'));
    }

    /**
     * Tailor Performance Report
     */
    public function tailorReport(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $this->getStartDate($period, $request->get('from'));
        $endDate = $this->getEndDate($period, $request->get('to'));

        // Tailors with stitching orders
        $tailors = User::whereHas('stitchingOrders', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->withCount(['stitchingOrders' => function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    }])
                    ->with(['stitchingOrders' => function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate])
                          ->orderByDesc('created_at');
                    }])
                    ->orderByDesc('stitchingOrders_count')
                    ->paginate(50);

        // Calculate performance metrics
        $tailorStats = [];
        foreach ($tailors as $tailor) {
            $total = $tailor->stitchingOrders->count();
            $completed = $tailor->stitchingOrders->where('stitching_status', 'completed')->count();
            $pending = $tailor->stitchingOrders->where('stitching_status', 'pending')->count();
            $inProgress = $tailor->stitchingOrders->where('stitching_status', 'in_progress')->count();
            
            $tailorStats[$tailor->id] = [
                'total' => $total,
                'completed' => $completed,
                'pending' => $pending,
                'in_progress' => $inProgress,
                'completion_rate' => $total > 0 ? ($completed / $total) * 100 : 0,
            ];
        }

        $topTailors = User::role('tailor')
                    ->withCount(['stitchingOrders' => function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    }])
                    ->orderByDesc('stitchingOrders_count')
                    ->limit(10)
                    ->get();

        return view('admin.reports.tailors', compact('tailors', 'tailorStats', 'topTailors', 'period', 'startDate', 'endDate'));
    }

    /**
     * Export reports to PDF
     */
    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'sales');
        $period = $request->get('period', 'monthly');
        $startDate = $this->getStartDate($period, $request->get('from'));
        $endDate = $this->getEndDate($period, $request->get('to'));

        $data = [];

        switch ($type) {
            case 'sales':
                $data = $this->getSalesData($startDate, $endDate);
                $view = 'admin.reports.export.sales_pdf';
                $filename = 'Sales_Report_' . now()->format('Y-m-d') . '.pdf';
                break;
            case 'orders':
                $data = $this->getOrderData($startDate, $endDate);
                $view = 'admin.reports.export.orders_pdf';
                $filename = 'Order_Report_' . now()->format('Y-m-d') . '.pdf';
                break;
            case 'stitching':
                $data = $this->getStitchingData($startDate, $endDate);
                $view = 'admin.reports.export.stitching_pdf';
                $filename = 'Stitching_Report_' . now()->format('Y-m-d') . '.pdf';
                break;
            default:
                return redirect()->back()->with('error', 'Invalid report type');
        }

        $pdf = PDF::loadView($view, $data);
        return $pdf->download($filename);
    }

    /**
     * Export reports to Excel
     */
    public function exportExcel(Request $request)
    {
        // Requires Laravel Excel package
        // Implementation depends on maatwebsite/excel package
        return redirect()->back()->with('error', 'Excel export coming soon');
    }

    // Helper methods

    private function getStartDate($period, $from = null)
    {
        if ($from) {
            return Carbon::parse($from)->startOfDay();
        }

        return match($period) {
            'daily' => Carbon::now()->startOfDay(),
            'weekly' => Carbon::now()->startOfWeek(),
            'monthly' => Carbon::now()->startOfMonth(),
            default => Carbon::now()->startOfMonth(),
        };
    }

    private function getEndDate($period, $to = null)
    {
        if ($to) {
            return Carbon::parse($to)->endOfDay();
        }

        return match($period) {
            'daily' => Carbon::now()->endOfDay(),
            'weekly' => Carbon::now()->endOfWeek(),
            'monthly' => Carbon::now()->endOfMonth(),
            default => Carbon::now()->endOfMonth(),
        };
    }

    private function getSalesData($startDate, $endDate)
    {
        return [
            'title' => 'Sales Report',
            'period' => $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'),
            'sales' => Order::whereBetween('created_at', [$startDate, $endDate])
                        ->get(['order_number', 'total', 'payment_status', 'created_at']),
            'totalRevenue' => Order::whereBetween('created_at', [$startDate, $endDate])->sum('total'),
            'totalOrders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];
    }

    private function getOrderData($startDate, $endDate)
    {
        return [
            'title' => 'Order Report',
            'period' => $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'),
            'orders' => Order::whereBetween('created_at', [$startDate, $endDate])
                        ->with('user')
                        ->get(['id', 'order_number', 'user_id', 'status', 'total', 'created_at']),
            'totalOrders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];
    }

    private function getStitchingData($startDate, $endDate)
    {
        return [
            'title' => 'Stitching Report',
            'period' => $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'),
            'stitching' => StitchingOrder::whereBetween('created_at', [$startDate, $endDate])
                        ->get(['id', 'order_id', 'stitching_status', 'estimated_cost', 'created_at']),
            'totalStitching' => StitchingOrder::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];
    }
}
