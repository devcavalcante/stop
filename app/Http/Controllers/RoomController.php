<?php

namespace App\Http\Controllers;

use App\Events\JoinRoomMessage;
use App\Events\StopMessage;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    public function createRoom(): JsonResponse
    {
        $pin = $this->generatePin();
        return response()->json(['pin' => $pin]);
    }

    public function joinRoom($pin, $user): JsonResponse
    {
        JoinRoomMessage::dispatch($user, $pin);
        return response()->json(['pin' => $pin, 'users' => $user]);
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
