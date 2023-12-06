<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JoinRoomMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $userName;
    public $pin;

    public function __construct($userName, $pin)
    {
        $this->userName = $userName;
        $this->pin = $pin;
    }

    public function broadcastOn()
    {
        return new Channel('join-room');
    }

    public function broadcastAs()
    {
        return 'join-room';
    }

    public function broadcastWith()
    {
        return [
            'userName' => $this->userName,
            'pin' => $this->pin,
        ];
    }
}
