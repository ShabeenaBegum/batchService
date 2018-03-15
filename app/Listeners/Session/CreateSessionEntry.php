<?php

namespace App\Listeners\Session;

use App\Events\Session\Completed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateSessionEntry
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
     * @param  Completed  $event
     * @return void
     */
    public function handle(Completed $event)
    {

        $enrolls = $event->session->enrolls();

        foreach ($enrolls as $enroll){
            $record = collect($enroll->sessions)->firstWhere("session_id", $event->session->_id);
            if(!$record){
                $enroll->sessions()->create([
                    "session_id" => $event->session->_id,
                    "session_status" => $event->session->status,
                    "attendance" => config('constant.session.attendance.pending'),
                    "quiz" => [],
                    "assignments" => [],
                    "projects" => [],
                ]);
            }
        }
    }
}
