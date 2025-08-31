<?php

use App\CalendarServiceInterface;
use App\Http\Controllers\AggregateController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\KeywordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (?CalendarServiceInterface $calendarServiceInterface) {
    return view('dashboard');
})->name('dashboard');

Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleCallback']);
Route::get('/logout', [GoogleController::class, 'logout'])->name('logout');

Route::post('/aggregate', [AggregateController::class, 'aggregateEvents'])->name('aggregate');

Route::resource('keywords', KeywordController::class);