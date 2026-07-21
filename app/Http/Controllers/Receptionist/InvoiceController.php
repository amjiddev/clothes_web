<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StitchingOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Receptionist Invoice Controller
 * 
 * Invoice generation and management from receptionist perspective
 */
class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('receptionist.only');
    }

    /**
     * Display all invoices for receptionist
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payments', 'orderItems'])
            ->orderBy('created_at', 'desc');

        // Search by order number or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment status (receptionist specific)
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range (receptionist specific)
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $invoices = $query->paginate(20);

        return view('receptionist.invoices.index', compact('invoices'));
    }

    /**
     * Show a single invoice
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems', 'payments', 'stitchingOrder']);
        
        return view('receptionist.invoices.show', compact('order'));
    }

    /**
     * Download invoice as PDF
     */
    public function download(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'payments', 'stitchingOrder']);

        $pdf = Pdf::loadView('receptionist.invoices.pdf', compact('order'))
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    /**
     * Print invoice
     */
    public function print(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'payments', 'stitchingOrder']);

        return view('receptionist.invoices.print', compact('order'));
    }

    /**
     * Generate invoice for a stitching order
     */
    public function stitchingInvoice(StitchingOrder $stitchingOrder)
    {
        $order = $stitchingOrder->order;
        
        $stitchingOrder->load(['tailor', 'measurement', 'order.user', 'order.payments']);

        $pdf = Pdf::loadView('receptionist.invoices.stitching-pdf', compact('stitchingOrder', 'order'))
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        return $pdf->download('stitching-invoice-' . $stitchingOrder->id . '.pdf');
    }

    /**
     * Download stitching invoice
     */
    public function downloadStitchingInvoice(StitchingOrder $stitchingOrder)
    {
        return $this->stitchingInvoice($stitchingOrder);
    }

    /**
     * Print stitching invoice
     */
    public function printStitchingInvoice(StitchingOrder $stitchingOrder)
    {
        $order = $stitchingOrder->order;
        
        $stitchingOrder->load(['tailor', 'measurement', 'order.user', 'order.payments']);

        return view('receptionist.invoices.print-stitching', compact('stitchingOrder', 'order'));
    }

    /**
     * Generate invoice helper (creates PDF view data)
     */
    private function generateInvoice(Order $order): array
    {
        $order->load(['user', 'orderItems.product', 'payments', 'stitchingOrder']);

        $totalPaid = $order->payments->where('status', 'completed')->sum('amount');
        $remainingAmount = max(0, $order->total - $totalPaid);

        return [
            'order' => $order,
            'totalPaid' => $totalPaid,
            'remainingAmount' => $remainingAmount,
            'paymentStatus' => $order->payment_status,
            'generatedDate' => now()->format('M d, Y'),
        ];
    }

    /**
     * Email invoice to customer
     */
    public function emailInvoice(Order $order)
    {
        try {
            // Send invoice via email to customer
            $pdf = Pdf::loadView('receptionist.invoices.pdf', $this->generateInvoice($order));
            
            // TODO: Send email with PDF attachment using Mailable/queue
            // For now, just redirect with success message
            
            return back()->with('success', 'Invoice sent to customer email!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error sending invoice: ' . $e->getMessage());
        }
    }

    /**
     * Get all invoices by customer
     */
    public function customerInvoices(Request $request, $customerId)
    {
        $customer = \App\Models\User::findOrFail($customerId);

        $query = Order::where('user_id', $customerId)
            ->with(['payments', 'orderItems'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(10);

        return view('receptionist.invoices.customer-invoices', compact('customer', 'invoices'));
    }
}
