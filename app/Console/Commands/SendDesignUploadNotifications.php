<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StitchingOrder;
use App\Models\TailorNotification;
use App\Http\Controllers\Tailor\NotificationController;
use Carbon\Carbon;

class SendDesignUploadNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:design-uploads';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Send notifications to tailors about recently uploaded designs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for recently uploaded designs...');

        // Get stitching orders with design images uploaded in the last hour
        $recentlyUploaded = StitchingOrder::where('stitching_status', '!=', 'completed')
            ->whereNotNull('design_image')
            ->where('updated_at', '>=', Carbon::now()->subHour())
            ->with(['tailor', 'order.user'])
            ->get();

        if ($recentlyUploaded->isEmpty()) {
            $this->info('No recent design uploads found.');
            return Command::SUCCESS;
        }

        $notificationCount = 0;

        foreach ($recentlyUploaded as $stitchingOrder) {
            // Check if notification already sent
            $alreadyNotified = TailorNotification::where('user_id', $stitchingOrder->tailor_id)
                ->where('type', TailorNotification::TYPE_DESIGN_UPLOADED)
                ->where('data->order_id', $stitchingOrder->id)
                ->where('created_at', '>=', Carbon::now()->subHour())
                ->exists();

            if ($alreadyNotified) {
                $this->line("  - Order #{$stitchingOrder->order->order_number}: Already notified");
                continue;
            }

            // Send notification
            NotificationController::notifyDesignUploaded(
                $stitchingOrder->tailor_id,
                $stitchingOrder->id,
                $stitchingOrder->order->order_number,
                $stitchingOrder->order->user->name
            );

            $this->line("  ✓ Order #{$stitchingOrder->order->order_number}: Design upload notification sent");
            $notificationCount++;
        }

        $this->info("Total notifications sent: {$notificationCount}");
        return Command::SUCCESS;
    }
}
