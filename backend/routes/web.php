<?php

use App\Http\Controllers\GoogleController;
use App\Services\GoogleService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user(); // Assign the authenticated user
        $googleService = new GoogleService($user);
        $calendarList = $googleService->getCalendarService()->calendarList->listCalendarList();
        return view('welcome', compact('calendarList'));
    } else {
        return view('welcome');
    }
})->name('welcome');
Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleCallback']);
Route::get('/logout', [GoogleController::class, 'logout'])->name('logout');