<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StitchingOrder;
use App\Models\User;
use App\Models\Payment;
use App\Models\CustomerMeasurement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Order Report
     */
    public function orderReport(Request $request)
    {
        $query = Order::with(['customer', 'payments']);

        // Date Range Filter
        if ($request->has('from_date') && $request->has('to_date')) {
            $fromDate = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay();
            $toDate = Carbon::createFromFormat('Y-m-d', $request->to_date)->endOfDay();
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        // Status Filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Order Type Filter
        if ($request->has('order_type') && $request->order_type) {
            $query->where('order_type', $request->order_type);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Calculate totals
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('receptionist.reports.orders', compact('orders', 'totalOrders', 'totalRevenue', 'averageOrderValue'));
    }

    /**
     * Stitching Report
     */
    public function stitchingReport(Request $request)
    {
        $query = StitchingOrder::with(['tailor', 'order.customer']);

        // Date Range Filter
        if ($request->has('from_date') && $request->has('to_date')) {
            $fromDate = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay();
            $toDate = Carbon::createFromFormat('Y-m-d', $request->to_date)->endOfDay();
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        // Status Filter
        if ($request->has('status') && $request->status) {
            $query->where('stitching_status', $request->status);
        }

        // Tailor Filter
        if ($request->has('tailor_id') && $request->tailor_id) {
            $query->where('tailor_id', $request->tailor_id);
        }

        $stitchingOrders = $query->orderBy('created_at', 'desc')->get();

        // Calculate stats
        $totalOrders = $stitchingOrders->count();
        $completedOrders = $stitchingOrders->where('stitching_status', 'completed')->count();
        $pendingOrders = $stitchingOrders->where('stitching_status', 'pending')->count();
        $totalCost = $stitchingOrders->sum('estimated_cost');

        $tailors = User::role('tailor')->select('id', 'name')->get();

        return view('receptionist.reports.stitching', compact('stitchingOrders', 'totalOrders', 'completedOrders', 'pendingOrders', 'totalCost', 'tailors'));
    }

    /**
     * Customer Report
     */
    public function customerReport(Request $request)
    {
        $customers = User::role('customer')
            ->with(['orders', 'measurements'])
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'total_orders' => $customer->orders->count(),
                    'total_spent' => $customer->orders->sum('total'),
                    'total_measurements' => $customer->measurements->count(),
                ];
            });

        // Sort
        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by;
            $customers = collect($customers)->sortByDesc($sortBy)->values();
        }

        return view('receptionist.reports.customers', compact('customers'));
    }

    /**
     * Payment Report
     */
    public function paymentReport(Request $request)
    {
        $query = Payment::with('order.customer');

        // Date Range Filter
        if ($request->has('from_date') && $request->has('to_date')) {
            $fromDate = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay();
            $toDate = Carbon::createFromFormat('Y-m-d', $request->to_date)->endOfDay();
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        // Status Filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        // Calculate totals
        $totalPayments = $payments->count();
        $totalAmount = $payments->sum('amount');
        $completedAmount = $payments->where('status', 'completed')->sum('amount');
        $pendingAmount = $payments->where('status', 'pending')->sum('amount');

        return view('receptionist.reports.payments', compact('payments', 'totalPayments', 'totalAmount', 'completedAmount', 'pendingAmount'));
    }

    /**
     * Export Report
     */
    public function export(Request $request)
    {
        $reportType = $request->input('type');
        $format = $request->input('format', 'csv');

        // Validate inputs
        if (!in_array($reportType, ['orders', 'stitching', 'customers', 'payments'])) {
            return redirect()->back()->with('error', 'Invalid report type');
        }

        if ($format === 'csv') {
            return $this->exportToCSV($reportType);
        }

        return redirect()->back()->with('error', 'Unsupported export format');
    }

    /**
     * Export to CSV
     */
    private function exportToCSV($reportType)
    {
        $fileName = 'report-' . $reportType . '-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($reportType) {
            $file = fopen('php://output', 'w');

            if ($reportType === 'orders') {
                $this->exportOrdersCSV($file);
            } elseif ($reportType === 'stitching') {
                $this->exportStitchingCSV($file);
            } elseif ($reportType === 'customers') {
                $this->exportCustomersCSV($file);
            } elseif ($reportType === 'payments') {
                $this->exportPaymentsCSV($file);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export orders to CSV
     */
    private function exportOrdersCSV($file)
    {
        fputcsv($file, ['Order ID', 'Customer', 'Type', 'Amount', 'Status', 'Date']);

        Order::with('customer')->get()->each(function ($order) use ($file) {
            fputcsv($file, [
                $order->id,
                $order->customer?->name ?? 'N/A',
                $order->order_type,
                $order->total,
                $order->status,
                $order->created_at->format('Y-m-d H:i:s'),
            ]);
        });
    }

    /**
     * Export stitching orders to CSV
     */
    private function exportStitchingCSV($file)
    {
        fputcsv($file, ['Order ID', 'Customer', 'Tailor', 'Garment Type', 'Status', 'Cost', 'Date']);

        StitchingOrder::with(['order.customer', 'tailor'])->get()->each(function ($order) use ($file) {
            fputcsv($file, [
                $order->id,
                $order->order?->customer?->name ?? 'N/A',
                $order->tailor?->name ?? 'Unassigned',
                $order->garment_type,
                $order->stitching_status,
                $order->estimated_cost,
                $order->created_at->format('Y-m-d H:i:s'),
            ]);
        });
    }

    /**
     * Export customers to CSV
     */
    private function exportCustomersCSV($file)
    {
        fputcsv($file, ['Customer ID', 'Name', 'Email', 'Phone', 'Total Orders', 'Total Spent']);

        User::role('customer')->with('orders')->get()->each(function ($customer) use ($file) {
            fputcsv($file, [
                $customer->id,
                $customer->name,
                $customer->email,
                $customer->phone ?? 'N/A',
                $customer->orders->count(),
                $customer->orders->sum('total'),
            ]);
        });
    }

    /**
     * Export payments to CSV
     */
    private function exportPaymentsCSV($file)
    {
        fputcsv($file, ['Payment ID', 'Order ID', 'Customer', 'Amount', 'Status', 'Date']);

        Payment::with('order.customer')->get()->each(function ($payment) use ($file) {
            fputcsv($file, [
                $payment->id,
                $payment->order_id,
                $payment->order?->customer?->name ?? 'N/A',
                $payment->amount,
                $payment->status,
                $payment->created_at->format('Y-m-d H:i:s'),
            ]);
        });
    }
}
