<?php
namespace App\Services;

use App\CalendarServiceInterface;
use Google\Client;
use Google\Service\Calendar;

class GoogleCalendarService implements CalendarServiceInterface
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

    public function listEvents(): array
    {
        $events = [];
        $calendarId = 'primary';

        try {
            $response = $this->calendarService->events->listEvents($calendarId);
            $events = $response->getItems();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve events');
        }
        return $events;
    }
}