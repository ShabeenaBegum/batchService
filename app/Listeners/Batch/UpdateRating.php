<?php

namespace App\Listeners\Batch;

use App\Batch\Models\Batch;
use App\Events\Session\RatingUpdated;
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
     * @param  RatingUpdated  $event
     * @return void
     */
    public function handle(RatingUpdated $event)
    {
        $batch = $batch = Batch::where("sessions._id", $event->session->_id)->firstOrFail();
        $batch->increment('rating', $event->session->rating_avg);
        $batch->increment("rating_count", 1);
        $batch->increment("comment_count", $event->comment ? $event->session->comment_count: 0);
        $batch->rating_avg = round($batch->rating / $batch->rating_count, 1);
        $batch->save();
    }
}
