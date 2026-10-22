<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display all invoices (Financial View)
     */
    public function index(Request $request)
    {
        try {
            $query = Invoice::with(['order.user', 'payments'])
                ->orderBy('invoice_date', 'desc');

            // Search by invoice number, order number, or customer name
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                      ->orWhereHas('order', function($order) use ($search) {
                          $order->where('order_number', 'like', "%{$search}%")
                                ->orWhereHas('user', function($user) use ($search) {
                                    $user->where('name', 'like', "%{$search}%");
                                });
                      });
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('invoice_date', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('invoice_date', '<=', $request->date_to);
            }

            $invoices = $query->paginate(20)->appends($request->query());

            return view('receptionist.invoices.index', compact('invoices'));
            
        } catch (\Exception $e) {
            // If table doesn't exist, show error message
            if (str_contains($e->getMessage(), "doesn't exist") || str_contains($e->getMessage(), 'SQLSTATE')) {
                return view('receptionist.invoices.index', [
                    'invoices' => collect(),
                    'error' => 'The invoices table does not exist. Please run migrations first.'
                ]);
            }
            throw $e;
        }
    }

    /**
     * Show a single invoice
     */
    public function show(Invoice $invoice)
    {
        $this->authorizeReceptionist($invoice->order);
        
        $invoice->load(['order.user', 'order.orderItems', 'order.stitchingOrder', 'payments']);

        return view('receptionist.invoices.show', compact('invoice'));
    }

    /**
     * Create invoice for an order
     */
    public function create(Order $order)
    {
        $this->authorizeReceptionist($order);

        // Check if invoice already exists
        if ($order->invoice) {
            return redirect()->route('receptionist.invoices.show', $order->invoice)
                ->with('info', 'Invoice already exists for this order.');
        }

        return view('receptionist.invoices.create', compact('order'));
    }

    /**
     * Store a new invoice
     */
    public function store(Request $request, Order $order)
    {
        $this->authorizeReceptionist($order);

        // Check if invoice already exists
        if ($order->invoice) {
            return redirect()->route('receptionist.invoices.show', $order->invoice)
                ->with('error', 'Invoice already exists for this order.');
        }

        $request->validate([
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create invoice from order data
            $invoice = Invoice::create([
                'order_id' => $order->id,
                'invoice_date' => $request->invoice_date ?? now(),
                'due_date' => $request->due_date,
                'subtotal' => $order->subtotal,
                'tax_amount' => $order->tax,
                'discount_amount' => $order->discount,
                'total_amount' => $order->total,
                'amount_paid' => 0,
                'balance_due' => $order->total,
                'status' => 'unpaid',
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('receptionist.invoices.show', $invoice)
                ->with('success', 'Invoice created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    /**
     * Show payment form
     */
    public function paymentForm(Invoice $invoice)
    {
        $this->authorizeReceptionist($invoice->order);

        return view('receptionist.invoices.payment', compact('invoice'));
    }

    /**
     * Record a payment
     */
    public function recordPayment(Request $request, Invoice $invoice)
    {
        $this->authorizeReceptionist($invoice->order);

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->balance_due,
            'payment_method' => 'required|in:cash,card,bank_transfer,mobile_money,other',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'transaction_id' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'order_id' => $invoice->order_id,
                'payment_reference' => 'PAY-' . date('YmdHis') . '-' . $invoice->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date ?? now(),
                'notes' => $request->notes,
                'transaction_id' => $request->transaction_id,
                'received_by' => auth()->id(),
            ]);

            // Update invoice
            $invoice->amount_paid += $request->amount;
            $invoice->save(); // This will trigger the model's updating event to recalculate status

            // Update order payment status to match invoice
            $invoice->order->payment_status = $invoice->status;
            $invoice->order->save();

            DB::commit();

            return redirect()->route('receptionist.invoices.show', $invoice)
                ->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    /**
     * Print invoice
     */
    public function print(Invoice $invoice)
    {
        $this->authorizeReceptionist($invoice->order);

        $invoice->load(['order.user', 'order.orderItems', 'payments']);

        return view('receptionist.invoices.print', compact('invoice'));
    }

    /**
     * Download invoice as PDF (auto-triggers print dialog)
     */
    public function download(Invoice $invoice)
    {
        $this->authorizeReceptionist($invoice->order);

        $invoice->load(['order.user', 'order.orderItems', 'payments']);

        return view('receptionist.invoices.print-auto', compact('invoice'));
    }

    /**
     * Delete an invoice
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorizeReceptionist($invoice->order);

        try {
            // Delete associated payments first
            $invoice->payments()->delete();

            // Delete the invoice
            $invoice->delete();

            return redirect()->route('receptionist.invoices.index')
                            ->with('success', 'Invoice deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
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
