<?php

namespace App\Listeners\Batch;

use App\Events\Batch\ExtraSession;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExtraSessionAdded
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
     * @param  ExtraSession  $event
     * @return void
     */
    public function handle(ExtraSession $event)
    {
        //
    }
}
