<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes([
                'openid',
                'profile',
                'email',
                'https://www.googleapis.com/auth/calendar'
            ])
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect();
    }

    public function handleCallback(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();
        // dd($googleUser);

        $user = User::where('google_id', $googleUser?->id)->first();
        // dd($user);
        if (!$user) {
            $user = User::updateOrCreate([
                'google_id' => $googleUser?->id,
            ], [
                'name' => $googleUser?->name,
                'email' => $googleUser?->email,
                'google_token' => $googleUser?->token,
                'google_refresh_token' => $googleUser?->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}