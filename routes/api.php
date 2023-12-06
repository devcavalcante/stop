<?php

use App\Events\JoinRoomMessage;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('', function () {
    return response('ok');
});

Route::group(['prefix' => '/categories'], function () {
    Route::get('calculate/{pin}', [CategoryController::class, 'calculate']);
    Route::get('calculate-total/{pin}', [CategoryController::class, 'calculateTotal']);
    Route::get('results', [CategoryController::class, 'getResultsByCategory']);
    Route::get('delete', [CategoryController::class, 'deleteSession']);
    Route::get('random', [CategoryController::class, 'generateLetter']);
});

Route::group(['prefix' => '/room'], function (){
    Route::get('create', [RoomController::class, 'createRoom']);
    Route::get('users', [RoomController::class, 'users']);
    Route::get('join/{pin}/{user}', [RoomController::class, 'joinRoom']);
    Route::get('stop/{user}', [RoomController::class, 'stop']);
});

