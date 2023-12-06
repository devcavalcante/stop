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

    public function users($pin): JsonResponse
    {
        $room = User::where(['pin' => $pin])->get();
        return response()->json($room);
    }

    public function joinRoom($pin, $user): JsonResponse
    {
        $room = Room::where(['pin' => (int) $pin])->first();
        $userEntry = User::where(['pin' => $pin])->first();
        if(!is_null($userEntry) && $userEntry->count() >= 5){
            return response()->json(['message' => 'Atingiu o maximo de usuários']);
        }
        if(!is_null($room)) {
            $created = User::create(['pin' => $pin, 'name' => $user]);
            event(new JoinRoomMessage($user, $pin));
            return response()->json($created);
        }
        return response()->json(['message' => 'Sala não encontrada']);
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
