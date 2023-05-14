<?php

namespace App\Console;

use App\Jobs\Cost;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Controllers\Admin\ServerController;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            (new ServerController())->checkServer();
        })->everyMinute()->name('FrpServer')->withoutOverlapping()->onOneServer();

        $schedule->job(new Cost())->hourly()->name('FrpServerCost')->withoutOverlapping()->onOneServer();

        // every three days
        // $schedule->job(new ReviewWebsiteJob())->daily()->name('reviewWebsiteJob')->withoutOverlapping()->onOneServer();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
