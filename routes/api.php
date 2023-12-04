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

Route::get('result', [CategoryController::class, 'result']);
Route::get('random', [CategoryController::class, 'generateLetter']);
Route::get('create-room', [RoomController::class, 'createRoom']);
Route::get('join-room/{pin}/{user}', [RoomController::class, 'joinRoom']);
Route::get('stop/{user}', [RoomController::class, 'stop']);
