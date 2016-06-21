<?php

namespace App\Console;

use DB , Config;
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
            Commands\StoreCount::class,
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

            $schedule->call(function(){
                $year   = date('Y');
                $month  = date('m');
                $day    = date('d');
                $hour   = date('H');

                $count = DB::table('store_date_counts')
                    ->where('year' , $year)
                    ->where('month' , $month)
                    ->where('day' , $day)
                    ->where('hour' , $hour)
                    ->first();

                $order = DB::table('orders')
                    ->whereNotIn('status' , array(
                        Config::get('orderstatus.no_pay')['status'],
                        Config::get('orderstatus.cancel')['status']
                    ))
                    ->where('created_at' , 'like' , $year.'-'.$month.'-'.$day.' '.$hour .'%')->get();

                print_r($year.'-'.$month.'-'.$day.' '.$hour .'%');
//                if(!$count){
//                    DB::table($this->_store_date_counts_table)->insert(array(
//                        'date'                  => date('Y-m-d H:i:s' , time()),
//                        'year'                  => $year,
//                        'month'                 => $month,
//                        'day'                   => $day,
//                        'hour'                  => $hour,
//                        'store_id'              => $storeId,
//                        'visiting_number'       => 1
//                    ));
//                }



            })->everyMinute();
    }
}
