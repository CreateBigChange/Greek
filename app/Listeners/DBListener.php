<?php

namespace App\Listeners;

use App\Events\DBEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DBListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DBEvent  $event
     * @return void
     */
    public function handle(DBEvent $event)
    {
        //
		Log::info($event);
    }
}
