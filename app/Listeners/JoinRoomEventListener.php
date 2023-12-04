<?php

namespace App\Listeners;

use App\Events\JoinRoomMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class JoinRoomEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(JoinRoomMessage $event): void
    {
        Log::info('Evento JoinRoomMessage recebido:', [
            'userName' => $event->userName,
            'pin' => $event->pin,
        ]);
    }
}
