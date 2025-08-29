<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Calendar;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

        // 認証 URL を生成してリダイレクト
        return redirect()->away($client->createAuthUrl());
    }

    public function handleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

        $code = $request->get('code');
        if (!$code) {
            return 'Authorization code not found';
        }

        $token = $client->fetchAccessTokenWithAuthCode($code);

        $client->setAccessToken($token);

        session(['google_token' => $token]);

        return redirect('/');
    }
}