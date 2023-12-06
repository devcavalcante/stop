<?php

namespace App\Listeners;

use App\Events\JoinRoomMessage;
use App\Events\StopMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class StopEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(): void
    {
        Log::info('Evento Stop recebido:');
    }
}
