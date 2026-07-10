<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments
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

        // Filter by date range
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
     * Record payment for an order
     */
    public function recordPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank_transfer,online',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

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

            // Get total paid amount
            $totalPaid = $order->payments()->where('status', 'completed')->sum('amount');

            // Update order payment status
            if ($totalPaid >= $order->total) {
                $order->update(['payment_status' => 'paid']);
            } else {
                $order->update(['payment_status' => 'pending']);
            }

            // Update order payment method
            $order->update(['payment_method' => $validated['payment_method']]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'payment' => $payment->load('order'),
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error recording payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark payment as paid
     */
    public function markPaid(Payment $payment)
    {
        try {
            DB::beginTransaction();

            $payment->update(['status' => 'completed']);

            // Check if order is fully paid
            $order = $payment->order;
            $totalPaid = $order->payments()
                ->where('status', 'completed')
                ->sum('amount');

            if ($totalPaid >= $order->total) {
                $order->update(['payment_status' => 'paid']);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Payment marked as paid successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error marking payment: ' . $e->getMessage());
        }
    }

    /**
     * Collect payment
     */
    public function collectPayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank_transfer,online',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $payment->update([
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'],
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Update order payment status
            $order = $payment->order;
            $totalPaid = $order->payments()
                ->where('status', 'completed')
                ->sum('amount');

            if ($totalPaid >= $order->total) {
                $order->update(['payment_status' => 'paid']);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Payment collected successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error collecting payment: ' . $e->getMessage());
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
            'total_amount' => $order->total,
            'paid_amount' => $totalPaid,
            'remaining_amount' => $remaining,
            'payment_status' => $order->payment_status,
            'payments' => $payments,
        ]);
    }

    /**
     * Export payment summary
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

        $csv = "Order ID,Customer Name,Payment Date,Payment Method,Amount,Status\n";
        foreach ($payments as $payment) {
            $csv .= "{$payment->order->order_number}," .
                    "{$payment->order->user->name}," .
                    "{$payment->processed_at->format('M d, Y')}," .
                    ucfirst(str_replace('_', ' ', $payment->payment_method)) . "," .
                    number_format($payment->amount, 2) . "," .
                    ucfirst($payment->status) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=payments_' . date('Y-m-d') . '.csv');
    }
}
