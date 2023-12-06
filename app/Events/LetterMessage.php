<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LetterMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $letter;

    public function __construct($letter)
    {
        $this->letter = $letter;
    }

    public function broadcastOn()
    {
        return ['letter'];
    }

    public function broadcastAs()
    {
        return 'letter';
    }

    public function broadcastWith()
    {
        return [
            'letter' => $this->letter
        ];
    }
}
