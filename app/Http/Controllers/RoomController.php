<?php

namespace App\Http\Controllers;

use App\Events\JoinRoomMessage;
use App\Events\StopMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class RoomController extends Controller
{
    public function createRoom(): JsonResponse
    {
        $pin = $this->generatePin();
        return response()->json(['pin' => $pin]);
    }

    public function joinRoom($pin, $user): JsonResponse
    {
        if (isset($this->rooms[$pin])) {
            JoinRoomMessage::dispatch($user, $pin);
            return response()->json(['pin' => $pin, 'users' => $this->rooms[$pin]['users']]);
        }
        return response()->json(['error' => 'Sala não encontrada'], 404);
    }

    public function stop($user): JsonResponse
    {
        StopMessage::dispatch($user);
        return response()->json(['message' => sprintf('Usuario apertou stop => %s', $user)]);
    }

    private function generatePin(): int
    {
        return rand(1000, 9999);
    }
}
