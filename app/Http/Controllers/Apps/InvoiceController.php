<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StitchingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $query = Order::with(['user'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
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
     * Download invoice as PDF (auto-triggers print dialog)
     */
    public function download(Order $order)
    {
        $this->authorizeReceptionist($order);

        $order->load(['user', 'orderItems']);

        // Return print view with auto-print script
        return view('receptionist.invoices.print-auto', compact('order'));
    }

    /**
     * Print invoice
     */
    public function print(Order $order)
    {
        $this->authorizeReceptionist($order);

        $order->load(['user', 'orderItems']);

        return view('receptionist.invoices.print', compact('order'));
    }

    /**
     * Generate invoice for stitching order
     */
    public function stitchingInvoice(StitchingOrder $stitchingOrder)
    {
        $order = $stitchingOrder->order;
        $this->authorizeReceptionist($order);

        $stitchingOrder->load(['tailor', 'measurement', 'order.user']);

        // Return view for printing - users can use browser print to PDF
        return view('receptionist.invoices.stitching-print', compact('stitchingOrder'));
    }

    /**
     * Authorize that receptionist can access this order
     */
    private function authorizeReceptionist(Order $order)
    {
        // Super Admin and Receptionist can access all orders
        if (!Auth::user()->hasAnyRole(['receptionist', 'super_admin'])) {
            abort(403, 'Unauthorized access');
        }
    }
}
