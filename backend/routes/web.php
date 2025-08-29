<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $token = session('google_token');

    if (!$token) return view('welcome');

    $client = new Google_Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setAccessToken($token);

    // 必要なら期限切れチェックして refreshToken で更新
    if ($client->isAccessTokenExpired() && isset($token['refresh_token'])) {
        $client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
        session(['google_token' => $client->getAccessToken()]);
    }

    $service = new Google_Service_Calendar($client);
    $calendarList = $service->calendarList->listCalendarList();

    return view('welcome', ['calendarItems' => $calendarList->getItems()]);
})->name('welcome');
Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleCallback']);