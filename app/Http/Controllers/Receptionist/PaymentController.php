<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Receptionist Payment Controller
 * 
 * Payment management from receptionist side
 * Handles payment collection, tracking, and summary
 */
class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('receptionist.only');
    }

    /**
     * Display a listing of all payments
     */
    public function index(Request $request)
    {
        $query = Payment::with(['order', 'order.user', 'user'])
            ->latest();

        // Search by order ID or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('order', function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by payment status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('method') && $request->method) {
            $query->where('payment_method', $request->method);
        }

        // Filter by date range (receptionist specific)
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('processed_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('processed_at', '<=', $request->to_date);
        }

        $payments = $query->paginate(15)->appends($request->query());

        return view('receptionist.payments.index', compact('payments'));
    }

    /**
     * Show a single payment
     */
    public function show(Payment $payment)
    {
        $payment->load(['order', 'order.user', 'user']);

        return view('receptionist.payments.show', compact('payment'));
    }

    /**
     * Show form for collecting payment on an order
     */
    public function collectForm(Order $order)
    {
        $order->load('user', 'payments');

        // Calculate remaining amount
        $paidAmount = $order->payments->where('status', 'completed')->sum('amount');
        $remainingAmount = max(0, $order->total - $paidAmount);

        return view('receptionist.payments.collect-form', compact('order', 'remainingAmount', 'paidAmount'));
    }

    /**
     * Collect payment for an order
     */
    public function collectPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank_transfer,online',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Verify amount doesn't exceed order total
        $paidAmount = $order->payments->where('status', 'completed')->sum('amount');
        $totalAfterPayment = $paidAmount + $validated['amount'];

        if ($totalAfterPayment > $order->total) {
            return back()->with('error', 'Payment amount exceeds order total. Maximum: ₹' . number_format($order->total - $paidAmount, 2));
        }

        try {
            DB::beginTransaction();

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'],
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Update order payment status
            if ($totalAfterPayment >= $order->total) {
                $order->update(['payment_status' => 'paid']);
            } else {
                $order->update(['payment_status' => 'partial']);
            }

            DB::commit();

            return redirect()->route('receptionist.orders.show', $order)
                            ->with('success', 'Payment collected successfully! Amount: ₹' . number_format($validated['amount'], 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error collecting payment: ' . $e->getMessage());
        }
    }

    /**
     * Mark payment as paid/completed
     */
    public function markPaid(Payment $payment)
    {
        try {
            DB::beginTransaction();

            // Only update if status is not already completed
            if ($payment->status !== 'completed') {
                $payment->update(['status' => 'completed', 'processed_at' => now()]);
            }

            // Check if order is fully paid
            $order = $payment->order;
            $totalPaid = $order->payments()
                ->where('status', 'completed')
                ->sum('amount');

            if ($totalPaid >= $order->total) {
                $order->update(['payment_status' => 'paid']);
            } else {
                $order->update(['payment_status' => 'partial']);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Payment marked as completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error marking payment: ' . $e->getMessage());
        }
    }

    /**
     * Get payment summary for a specific order
     */
    public function getPaymentSummary(Order $order)
    {
        $payments = $order->payments()->where('status', 'completed')->get();
        $totalPaid = $payments->sum('amount');
        $remaining = max(0, $order->total - $totalPaid);

        return response()->json([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'total_amount' => $order->total,
            'paid_amount' => $totalPaid,
            'remaining_amount' => $remaining,
            'payment_status' => $order->payment_status,
            'payment_percentage' => $order->total > 0 ? round(($totalPaid / $order->total) * 100, 2) : 0,
            'payments' => $payments->map(function($p) {
                return [
                    'id' => $p->id,
                    'amount' => $p->amount,
                    'method' => $p->payment_method,
                    'date' => $p->processed_at->format('M d, Y H:i'),
                ];
            }),
        ]);
    }

    /**
     * Export payment summary (CSV)
     */
    public function exportSummary(Request $request)
    {
        $query = Payment::with(['order', 'order.user'])
            ->where('status', 'completed');

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('processed_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('processed_at', '<=', $request->to_date);
        }

        $payments = $query->get();

        // Generate CSV
        $csv = "Order ID,Customer Name,Payment Date,Payment Method,Amount,Status\n";
        foreach ($payments as $payment) {
            $csv .= '"' . $payment->order->order_number . '",' .
                    '"' . $payment->order->user->name . '",' .
                    '"' . $payment->processed_at->format('M d, Y') . '",' .
                    '"' . ucfirst(str_replace('_', ' ', $payment->payment_method)) . '",' .
                    '"₹' . number_format($payment->amount, 2) . '",' .
                    '"' . ucfirst($payment->status) . '"' . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename=payments_' . date('Y-m-d_His') . '.csv');
    }

    /**
     * Show payment collection report
     */
    public function collectionReport(Request $request)
    {
        $fromDate = $request->get('from_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->from_date) : \Carbon\Carbon::now()->subDays(30);
        $toDate = $request->get('to_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->to_date) : \Carbon\Carbon::now();

        $query = Payment::with(['order', 'user'])
            ->whereBetween('created_at', [$fromDate, $toDate]);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $payments = $query->orderByDesc('created_at')->get();

        // Calculate summary
        $summary = [
            'total_transactions' => $payments->count(),
            'total_collected' => $payments->where('status', 'completed')->sum('amount'),
            'total_pending' => $payments->where('status', 'pending')->sum('amount'),
            'average_payment' => $payments->count() > 0 ? $payments->sum('amount') / $payments->count() : 0,
            'completed_count' => $payments->where('status', 'completed')->count(),
            'pending_count' => $payments->where('status', 'pending')->count(),
            'failed_count' => $payments->where('status', 'failed')->count(),
        ];

        // Group by payment method
        $byMethod = $payments->groupBy('payment_method')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->where('status', 'completed')->sum('amount'),
            ];
        });

        return view('receptionist.payments.collection-report', compact(
            'payments',
            'fromDate',
            'toDate',
            'summary',
            'byMethod'
        ));
    }
}
