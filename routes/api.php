<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Login APIs
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

// User APIs
Route::group(['middleware' => 'auth:api'], function(){
  Route::get('details', [UserController::class, 'details']);
});

// Event APIs
Route::get('events', [EventController::class, 'getEventList']);
Route::group(['middleware' => 'auth:api'], function() {
  Route::post('events', [EventController::class, 'createEvent'])->name('event.new.post');
  Route::get('events/{eventId}/view', [EventController::class, 'getEventDetails'])->name('event.details');
  Route::post('events/{eventId}', [EventController::class, 'updateEvent'])->name('event.update');
});
Route::post('events/{eventId}/join', [EventController::class, 'registerParticipant'])->name('event.join');



Route::post('events/{eventId}/team/new', [App\Http\Controllers\EventController::class, 'createTeam'])->name('event.team.create');
Route::post('events/{eventId}/team', [App\Http\Controllers\EventController::class, 'selectTeam'])->name('event.team.select');
Route::post('events/{eventId}/team/random', [App\Http\Controllers\EventController::class, 'randomSelectTeam'])->name('event.team.select.random');
Route::get('events/{eventId}/participant/{participantId}', [App\Http\Controllers\EventController::class, 'deleteParticipant'])->name('event.disjoin');
