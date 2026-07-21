<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StitchingOrder;
use App\Models\TailorNotification;
use App\Http\Controllers\Tailor\NotificationController;
use Carbon\Carbon;

class NotifyApproachingDeliveries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:approaching-deliveries {--days=1 : Number of days to check ahead}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Send notifications to tailors about orders approaching delivery date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysAhead = $this->option('days');
        $today = Carbon::today();
        $targetDate = $today->copy()->addDays($daysAhead);

        $this->info("Checking for orders with delivery dates between {$today->toDateString()} and {$targetDate->toDateString()}...");

        // Find orders approaching delivery
        $approachingOrders = StitchingOrder::where('stitching_status', 'ready')
            ->whereBetween('completion_date', [$today, $targetDate->copy()->endOfDay()])
            ->with(['tailor', 'order'])
            ->get();

        if ($approachingOrders->isEmpty()) {
            $this->info('No orders approaching delivery date.');
            return Command::SUCCESS;
        }

        $notificationCount = 0;

        foreach ($approachingOrders as $stitchingOrder) {
            // Check if notification already sent today
            $alreadyNotified = TailorNotification::where('user_id', $stitchingOrder->tailor_id)
                ->where('type', TailorNotification::TYPE_ORDER_APPROACHING)
                ->where('data->order_id', $stitchingOrder->id)
                ->whereDate('created_at', $today)
                ->exists();

            if ($alreadyNotified) {
                $this->line("  - Order #{$stitchingOrder->order->order_number}: Already notified");
                continue;
            }

            $daysLeft = $stitchingOrder->completion_date->diffInDays($today);

            // Send notification
            NotificationController::notifyOrderApproaching(
                $stitchingOrder->tailor_id,
                $stitchingOrder->id,
                $stitchingOrder->order->order_number,
                $daysLeft
            );

            $this->line("  ✓ Order #{$stitchingOrder->order->order_number}: Notification sent ({$daysLeft} day(s) remaining)");
            $notificationCount++;
        }

        $this->info("Total notifications sent: {$notificationCount}");
        return Command::SUCCESS;
    }
}
