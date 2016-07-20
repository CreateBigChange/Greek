<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use DB;
use Log;
use App\Libs\BLogger;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\DBEvent' => [
            'App\Listeners\DBListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {

        parent::boot($events);
        DB::listen(function($sql) {
            $sql->sql = preg_replace('/\s{2,}/' , ' ' ,$sql->sql);
            BLogger::setSqlLogger(BLogger::LOG_SQL)->info(json_encode($sql));
        });



    }
}
