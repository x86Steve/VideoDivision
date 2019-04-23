<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckExpiredSubscriptions;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        '\App\Console\Commands\CheckExpiredSubscriptions',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        
        // check for expired subscriptions every minute
        // (this actually only runs every 5 minutes because that's how often
        // the cron job on the server is run)
        $schedule->command(CheckExpiredSubscriptions::class)
            ->everyMinute()
            ->appendOutputTo(base_path('expired_subscriptions_output.txt'));
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
