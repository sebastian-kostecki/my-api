<?php

namespace App\Console;

use App\Console\Commands\Scheduled\CleanModulesGardenMailbox;
use App\Console\Commands\Scheduled\CleanPanelalphaMailbox;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('email:report-daily-tasks')->dailyAt('19:00');
        $schedule->command('email:manage-panelalpha-mailbox')->daily();
        $schedule->command(CleanPanelalphaMailbox::class)->daily();
        $schedule->command(CleanModulesGardenMailbox::class)->daily();

        $schedule->command('panelalpha:watch-issues')
            ->between('8:00', '18:00')
            ->quarterly();
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
