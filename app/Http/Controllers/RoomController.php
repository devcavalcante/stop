<?php

namespace App\Http\Controllers;

use App\Events\JoinRoomMessage;
use App\Events\StopMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class RoomController extends Controller
{
    private array $rooms = [];

    public function createRoom(): JsonResponse
    {
        $pin = $this->generatePin();
        $this->rooms[$pin] = ['users' => []];
        Session::put('rooms', $this->rooms);
        return response()->json(['pin' => $pin]);
    }

    public function joinRoom($pin, $user): JsonResponse
    {
        $this->rooms = Session::get('rooms', []);
        if (isset($this->rooms[$pin])) {
            $this->rooms[$pin]['users'][] = $user;
            Session::put('rooms', $this->rooms);
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
