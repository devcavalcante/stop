<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StopMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $userName;

    public function __construct($userName)
    {
        $this->userName = $userName;
    }

    public function broadcastOn()
    {
        return ['stop'];
    }

    public function broadcastAs()
    {
        return 'stop';
    }
}
