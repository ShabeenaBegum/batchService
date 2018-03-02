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
            $record = $enroll->where("sessions.session_id", $event->session->_id)->exists();
            if(!$record){
                $enroll->sessions()->create([
                    "session_id" => $event->session->_id,
                    "session_status" => $event->session->status,
                    "attendance" => "PENDING",
                    "quiz" => [],
                    "assignments" => [],
                    "projects" => [],
                ]);
            }
        }
    }
}
