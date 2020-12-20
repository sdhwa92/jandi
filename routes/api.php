<?php

use App\Http\Controllers\Api\EventController;
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

Route::get('event/list', [EventController::class, 'getAllEvents']);

Route::post('event/new', [EventController::class, 'createNewEvent'])->name('event.new.post');
Route::get('event/{eventId}/view', [EventController::class, 'getEventDetails'])->name('event.details');
Route::post('event/{eventId}', [EventController::class, 'updateEvent'])->name('event.update');
Route::post('event/{eventId}/join', [EventController::class, 'registerParticipant'])->name('event.join');

Route::post('event/{eventId}/team/new', [App\Http\Controllers\EventController::class, 'createTeam'])->name('event.team.create');
Route::post('event/{eventId}/team', [App\Http\Controllers\EventController::class, 'selectTeam'])->name('event.team.select');
Route::post('event/{eventId}/team/random', [App\Http\Controllers\EventController::class, 'randomSelectTeam'])->name('event.team.select.random');
Route::get('event/{eventId}/participant/{participantId}', [App\Http\Controllers\EventController::class, 'deleteParticipant'])->name('event.disjoin');
