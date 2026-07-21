<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TailorNotification;
use Carbon\Carbon;

class CleanupNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup {--days=90 : Delete notifications older than this many days}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Clean up old read notifications to keep database clean';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Deleting read notifications older than {$cutoffDate->toDateString()}...");

        $deletedCount = TailorNotification::where('read_at', '!=', null)
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        $this->info("✓ Deleted {$deletedCount} old notifications");
        return Command::SUCCESS;
    }
}
