<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;
use Google\Client;
use Google\Service\Calendar;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user(); // Assign the authenticated user

        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setAccessToken($user->google_token);
        // dd($client);
        // Refresh the access token if expired and refresh token exists
        if ($client->isAccessTokenExpired() && $user->google_refresh_token) {
            $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            // Save the new token to DB or session as needed
        }

        $service = new Calendar($client);
        // dd($service);
        $calendarList = $service->calendarList->listCalendarList();
        return view('welcome', compact('calendarList'));
    } else {
        return view('welcome');
    }
})->name('welcome');
Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleCallback']);
Route::get('/logout', [GoogleController::class, 'logout'])->name('logout');