<?php

namespace App\Events\Session;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RatingUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session;
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($session, $comment)
    {
        $this->session = $session;
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
