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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/event/new', [App\Http\Controllers\CreateEventController::class, 'renderCreateEventPage'])->name('event.new.view');
Route::post('/event/new', [App\Http\Controllers\CreateEventController::class, 'createNewEvent'])->name('event.new.post');

Route::post('/event/join', [App\Http\Controllers\EventController::class, 'registerParticipant'])->name('event.join');

Route::get('/event/{eventId}', [App\Http\Controllers\EventController::class, 'renderViewEventPage'])->name('event.view');
Route::post('/event/{eventId}', [App\Http\Controllers\EventController::class, 'updateEvent'])->name('event.update');

Route::get('/participant/{participantId}', [App\Http\Controllers\EventController::class, 'deleteParticipant'])->name('event.disjoin');



