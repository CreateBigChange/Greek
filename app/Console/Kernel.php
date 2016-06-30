<?php

namespace App\Console;

use DB , Config , Log;
use App\Libs\BLogger;
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
            //Commands\Inspire::class,
            //Commands\StoreCount::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//            $schedule->command('inspire')
//                ->dailyAt('14:37');

//        echo '11111';
//
//            $schedule->call(function(){
//
//
//                $order = DB::table('orders')->get();
//
//                foreach ($order as $o){
//                    $time = explode(' ', $o->created_at);
//                    $date = $time[0];
//                    $time = $time[1];
//
//                    $data = array(
//                        'year'      => explode('-', $date)[0],
//                        'month'     => explode('-', $date)[1],
//                        'day'       => explode('-', $date)[2],
//                        'hour'      => explode(':', $time)[0],
//                        'minutes'   => explode(':', $time)[1],
//                        'second'    => explode(':', $time)[2],
//                    );
//
//                    DB::table('orders')->where('id' , $o->id)->update($data);
//                }
//
//
//
//
//            })->everyMinute()->appendOutputTo(storage_path().'/logs/store_count.log');
    }
}
