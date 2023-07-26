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
        // $schedule->command('inspire')->hourly();

        $schedule->command('auth:clear-resets')->everyMinute()->timezone('Africa/Cairo');

        $schedule->command('delete:unverified-accounts')->everyMinute()->timezone('Africa/Cairo');

        $schedule->command('delete:personal-access-tokens')->everyMinute()->timezone('Africa/Cairo');

        $schedule->command('delete:profile-pics')->everyMinute()->timezone('Africa/Cairo');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
