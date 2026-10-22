<?php

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Temporary routes for fixing data issues
Route::middleware(['auth'])->prefix('temp-fix')->group(function () {
    
    // Generate invoices for all orders without invoices
    Route::get('/generate-invoices', function () {
        $orders = Order::whereDoesntHave('invoice')
            ->with('user')
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No orders found without invoices.',
                'created' => 0
            ]);
        }

        $created = 0;
        $failed = 0;
        $errors = [];

        foreach ($orders as $order) {
            try {
                DB::beginTransaction();

                // Determine initial payment status
                $amountPaid = 0;
                $status = 'unpaid';

                if ($order->payment_status === 'paid') {
                    $amountPaid = $order->total;
                    $status = 'paid';
                }

                $invoice = Invoice::create([
                    'order_id' => $order->id,
                    'invoice_date' => $order->created_at,
                    'due_date' => $order->delivery_date,
                    'subtotal' => $order->subtotal,
                    'tax_amount' => $order->tax,
                    'discount_amount' => $order->discount,
                    'total_amount' => $order->total,
                    'amount_paid' => $amountPaid,
                    'balance_due' => $order->total - $amountPaid,
                    'status' => $status,
                    'paid_at' => $status === 'paid' ? $order->updated_at : null,
                ]);

                DB::commit();
                $created++;

            } catch (\Exception $e) {
                DB::rollBack();
                $failed++;
                $errors[] = "Order {$order->order_number}: {$e->getMessage()}";
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Invoice generation complete!",
            'total_orders' => $orders->count(),
            'created' => $created,
            'failed' => $failed,
            'errors' => $errors
        ]);
    });
});
