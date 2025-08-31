<?php

use App\CalendarServiceInterface;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (?CalendarServiceInterface $calendarServiceInterface) {
    if (auth()->check()) {
        $events = $calendarServiceInterface->listEvents();
        return view('welcome', compact('events'));
    } else {
        return view('welcome');
    }
})->name('welcome');
Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleCallback']);
Route::get('/logout', [GoogleController::class, 'logout'])->name('logout');