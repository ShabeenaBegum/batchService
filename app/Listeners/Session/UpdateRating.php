<?php

namespace App\Listeners\Session;

use App\Batch\Models\Batch;
use App\Events\Session\RatingUpdated;
use App\Events\Student\SessionRated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateRating
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
     * @param  SessionRated  $event
     * @return void
     */
    public function handle(SessionRated $event)
    {
        $batch = Batch::where("sessions._id", $event->sessionId)->firstOrFail();
        $session = $batch->sessions->where("_id", $event->sessionId)->first();

        $session->increment('rating', $event->rating);
        $session->increment("rating_count",1);

        if($event->comment){
            $session->increment("comment_count", 1);
        }else{
            $session->increment("comment_count", 0);
        }

        $session->rating_avg = round($session->rating / $session->rating_count, 1);

        $session->save();

        event(new RatingUpdated($session, $event->comment));
    }
}
