<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateInvoicesForOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate {--all : Generate invoices for all orders without invoices}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate invoices for existing orders that do not have invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting invoice generation...');

        $orders = Order::whereDoesntHave('invoice')
            ->with('user')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No orders found without invoices.');
            return 0;
        }

        $this->info("Found {$orders->count()} orders without invoices.");

        $bar = $this->output->createProgressBar($orders->count());
        $bar->start();

        $created = 0;
        $failed = 0;

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
                $this->error("\nFailed to create invoice for order {$order->order_number}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Invoice generation complete!");
        $this->info("Created: {$created}");
        if ($failed > 0) {
            $this->warn("Failed: {$failed}");
        }

        return 0;
    }
}
