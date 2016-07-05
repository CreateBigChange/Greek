<?php

namespace App\Console;

use DB , Config ;
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
        Commands\WithdrawMoney::class,
        Commands\OrderComplete::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('WithdrawMoney')->everyMinute()->appendOutputTo('/tmp/script.log');
        $schedule->command('OrderComplete')->everyFiveMinutes()->appendOutputTo('/tmp/script.log');
    }
}
