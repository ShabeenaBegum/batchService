<?php

namespace App\Listeners\Student;

use App\Events\Student\Submission;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Submission
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
     * @param  Submission  $event
     * @return void
     */
    public function handle(Submission $event)
    {
        //
    }
}
