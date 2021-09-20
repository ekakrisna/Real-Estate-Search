<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendCustomerNewsEmail::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // [NOTE](2021/8/27 Abe) Scheduler is not using currently. Cron process is defined on "/etc/cron.d/real-estate-bash" .
        // $schedule->command('inspire')
        //          ->hourly();
        // schedule will run at 18.55, and email will be sent with sendGrid 5 min later
        // sent at 19.00
        // $schedule->command('email:sendCustomerNewsEmail')->dailyAt('18:55');
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
