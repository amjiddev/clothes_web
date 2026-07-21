<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Send notifications for orders approaching delivery date (run every hour)
        $schedule->command('notify:approaching-deliveries')->hourly();

        // Send notifications for recently uploaded designs (run every 30 minutes)
        $schedule->command('notify:design-uploads')->everyThirtyMinutes();

        // Clean up old read notifications (older than 90 days, run daily at 2 AM)
        $schedule->command('notifications:cleanup')->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
