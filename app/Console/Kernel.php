<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Send loan reminders daily at 8 AM
        $schedule->command('loans:send-reminders')
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta');

        // Send overdue notifications daily at 9 AM
        $schedule->command('loans:send-overdue')
            ->dailyAt('09:00')
            ->timezone('Asia/Jakarta');

        // Check overdue loans (existing command)
        $schedule->command('loans:check-overdue')
            ->dailyAt('01:00')
            ->timezone('Asia/Jakarta');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}