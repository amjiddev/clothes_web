<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StitchingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display all invoices for receptionist
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'payments'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(20);

        return view('receptionist.invoices.index', compact('invoices'));
    }

    /**
     * Show a single invoice
     */
    public function show(Order $order)
    {
        $this->authorizeReceptionist($order);
        
        return view('receptionist.invoices.show', compact('order'));
    }

    /**
     * Download invoice as PDF
     */
    public function download(Order $order)
    {
        $this->authorizeReceptionist($order);

        $order->load(['customer', 'items', 'payments', 'stitchingOrders']);

        $pdf = Pdf::loadView('receptionist.invoices.pdf', compact('order'))
            ->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0);

        return $pdf->download('invoice-' . $order->id . '.pdf');
    }

    /**
     * Print invoice
     */
    public function print(Order $order)
    {
        $this->authorizeReceptionist($order);

        $order->load(['customer', 'items', 'payments', 'stitchingOrders']);

        return view('receptionist.invoices.print', compact('order'));
    }

    /**
     * Generate invoice for stitching order
     */
    public function stitchingInvoice(StitchingOrder $stitchingOrder)
    {
        $order = $stitchingOrder->order;
        $this->authorizeReceptionist($order);

        $stitchingOrder->load(['tailor', 'measurement', 'order.customer']);

        $pdf = Pdf::loadView('receptionist.invoices.stitching-pdf', compact('stitchingOrder'))
            ->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0);

        return $pdf->download('stitching-invoice-' . $stitchingOrder->id . '.pdf');
    }

    /**
     * Authorize that receptionist can access this order
     */
    private function authorizeReceptionist(Order $order)
    {
        // Receptionist can access all orders in their system
        if (!Auth::user()->hasRole('receptionist')) {
            abort(403, 'Unauthorized access');
        }
    }
}
