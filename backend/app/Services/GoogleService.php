<?php
namespace App\Services;

use Google\Client;
use Google\Service\Calendar;

class GoogleService
{
    protected $client;
    protected $calendarService;

    public function __construct($user)
    {
        $this->client = new Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setAccessToken($user->google_token);

        if ($this->client->isAccessTokenExpired() && $user->google_refresh_token) {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            if (isset($newToken['access_token'])) {
                $user->google_token = $newToken['access_token'];
                // 必要ならrefresh_tokenも更新
                if (isset($newToken['refresh_token'])) {
                    $user->google_refresh_token = $newToken['refresh_token'];
                }
                $user->save();
                $this->client->setAccessToken($newToken['access_token']);
            }
        }

        $this->calendarService = new Calendar($this->client);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getCalendarService()
    {
        return $this->calendarService;
    }
}