<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StitchingOrder;
use App\Http\Controllers\Tailor\NotificationController;
use Carbon\Carbon;

class SendApproachingOrderNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:order-approaching';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Send notifications for orders approaching delivery date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find orders that are due in 1 day or less but not yet delivered
        $ordersDueIn24Hours = StitchingOrder::where(function ($query) {
                $query->whereNotIn('stitching_status', ['delivered', 'cancelled'])
                      ->where('completion_date', '!=', null);
            })
            ->whereDate('completion_date', '<=', Carbon::now()->addDay())
            ->whereDate('completion_date', '>', Carbon::now())
            ->get();

        $count = 0;
        foreach ($ordersDueIn24Hours as $order) {
            if ($order->tailor_id) {
                $daysLeft = Carbon::now()->diffInDays($order->completion_date, false);
                
                // Check if notification already sent today (optional - to avoid duplicate notifications)
                $alreadyNotified = $order->notifications()
                    ->where('type', 'order_approaching')
                    ->whereDate('created_at', Carbon::today())
                    ->exists();

                if (!$alreadyNotified) {
                    NotificationController::notifyOrderApproaching(
                        $order->tailor_id,
                        $order->id,
                        $order->order->order_number ?? "Order #" . $order->id,
                        max(1, $daysLeft)
                    );
                    $count++;
                }
            }
        }

        $this->info("Sent $count order approaching notifications.");
        return 0;
    }
}
