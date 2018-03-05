<?php

namespace App\Listeners\Session;

use App\Events\Session\Cancel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelNotify
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
     * @param  Cancel  $event
     * @return void
     */
    public function handle(Cancel $event)
    {
        //
    }
}
