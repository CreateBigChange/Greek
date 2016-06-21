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



                $order = DB::table('orders')
                    ->whereNotIn('status' , array(
                        Config::get('orderstatus.no_pay')['status'],
                        Config::get('orderstatus.cancel')['status']
                    ))
                    ->where('created_at' , 'like' , $year.'-'.$month.'-'.$day.' '.$hour .'%')->get();

                foreach ($order as $o){
                    $count = DB::table('store_date_counts')
                        ->where('year' , $year)
                        ->where('month' , $month)
                        ->where('day' , $day)
                        ->where('hour' , $hour)
                        ->where('store_id' , $o->store_id)
                        ->first();
                    if(!$count){
                        DB::table($this->_store_date_counts_table)->insert(array(
                            'date'                  => date('Y-m-d H:i:s' , time()),
                            'year'                  => $year,
                            'month'                 => $month,
                            'day'                   => $day,
                            'hour'                  => $hour,
                            'store_id'              => $o->store_id,
                            'buy_number'            => 1,
                            'turnover'              => $o->total,
                            'order_num'             => 1,
                            'out_point'             => $o->out_point,
                            'in_points'             => $o->in_pointss
                        ));
                    }else{
                        DB::table($this->_store_date_counts_table)
                            ->where('year' , $year)
                            ->where('month' , $month)
                            ->where('day' , $day)
                            ->where('hour' , $hour)
                            ->where('store_id' , $o->store_id)
                            ->update(array(
                                'buy_number'    => $count->buy_number + 1,
                                'turnover'      => $count->turnover + $o->total,
                                'order_num'     => $count->order_num + 1,
                                'out_point'     => $count->out_point + $o->out_point,
                                'in_points'     => $count->in_points + $o->in_points
                            ));
                    }
                }




            })->everyMinute()->appendOutputTo(storage_path().'/log/store_count.log');
    }
}
