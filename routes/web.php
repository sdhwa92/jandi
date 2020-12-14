<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// View routes
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/event/new', [App\Http\Controllers\CreateEventController::class, 'renderCreateEventPage'])->name('event.new.view');
Route::get('/event/{eventId}/view', [App\Http\Controllers\EventController::class, 'renderViewEventPage'])->name('event.view');

// API routes
Route::post('/event/new', [App\Http\Controllers\CreateEventController::class, 'createNewEvent'])->name('event.new.post');
Route::post('/event/{eventId}/join', [App\Http\Controllers\EventController::class, 'registerParticipant'])->name('event.join');
Route::post('/event/{eventId}', [App\Http\Controllers\EventController::class, 'updateEvent'])->name('event.update');
Route::post('/event/{eventId}/team/new', [App\Http\Controllers\EventController::class, 'createTeam'])->name('event.team.create');
Route::post('/event/{eventId}/team', [App\Http\Controllers\EventController::class, 'selectTeam'])->name('event.team.select');
Route::get('/event/{eventId}/participant/{participantId}', [App\Http\Controllers\EventController::class, 'deleteParticipant'])->name('event.disjoin');



