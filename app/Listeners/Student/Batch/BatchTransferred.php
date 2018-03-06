<?php

namespace App\Listeners\Student\Batch;

use App\Events\Student\Batch\BatchTransferred as Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BatchTransferred
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
     * @param  BatchTransferred  $event
     * @return void
     */
    public function handle(Event $event)
    {
        //
    }
}
