<?php

namespace App\Http\Controllers;

use App\Events\JoinRoomMessage;
use App\Events\StopMessage;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    public function createRoom(): JsonResponse
    {
        $pin = $this->generatePin();
        $pin = Room::create(['pin' => $pin]);
        return response()->json($pin);
    }

    public function joinRoom($pin, $user): JsonResponse
    {
        $room = Room::where(['pin' => (int) $pin])->first();
        $created = User::create(['room_id' => $room->id, 'name' => $user]);
        JoinRoomMessage::dispatch($user, $pin);
        return response()->json($created);
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
