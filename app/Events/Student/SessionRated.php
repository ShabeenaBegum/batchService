<?php

namespace App\Events\Student;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SessionRated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sessionId;
    public $rating;
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sessionId, $rating, $comment)
    {
        $this->sessionId = $sessionId;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
