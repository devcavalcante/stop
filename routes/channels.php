<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
//Broadcast::channel('sala.{pin}', function ($user, $pin) {
//    // Verifique se o usuário está associado à sala correta usando a lógica do seu aplicativo
//    // Neste exemplo, estou assumindo que os usuários associados ao PIN estão armazenados na sessão
//    $rooms = Session::get('rooms', []);
//
//    return isset($rooms[$pin]['users'][$user->id]);
//});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

