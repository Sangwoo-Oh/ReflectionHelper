<?php

namespace App\Services;

use App\CalendarServiceInterface;
use DateTime;
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

    public function getCalendarEtag(): string
    {
        return $this->calendarService->calendars->get('primary')->getEtag();
    }

    public function getListEventsEtag(): string
    {
        $response = $this->calendarService->events->listEvents('primary', ['maxResults' => 1]);
        return $response->getEtag();
    }
    public function listEvents(
        string $query = '',
        DateTime $startDate = null,
        DateTime $endDate = null,
        string $calendarId = 'primary'
    ): array {
        $params = [];
        if ($query) {
            $params['q'] = $query;
        }
        if ($startDate) {
            $params['timeMin'] = $startDate->format(DateTime::RFC3339);
        }
        if ($endDate) {
            $params['timeMax'] = $endDate->format(DateTime::RFC3339);
        }
        $events = [];
        try {
            $response = $this->calendarService->events->listEvents($calendarId, $params);
            $events = $response->getItems();

        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch events from Google Calendar: ' . $e->getMessage());
        }
        return $events;
    }

    public function loadAllEvents(): void
    {
        // google calendar API で primaryのカレンダー情報取得
        $calendar = $this->calendarService->calendars->get('primary');
        // dd($calendar);
        $savedCalendar = \App\Models\Calendar::create([
            'user_id' => auth()->id(),
            'calendar_id' => $calendar->getId(),
            'summary' => $calendar->getSummary(),
            'description' => $calendar->getDescription(),
            'time_zone' => $calendar->getTimeZone(),
            'calendar_etag' => $calendar->getEtag(),
            'list_events_etag' => $this->getListEventsEtag(),
        ]);

        // google calendar API でprimaryのイベント全件取得
        $calendarId = 'primary';
        
        $nextPageToken = null;
        do {
            $response = $this->calendarService->events->listEvents(
                $calendarId, 
                [
                    'maxResults' => 500,
                    'pageToken' => $nextPageToken,
                    'singleEvents' => true,
                ]
            );
            \App\Models\Event::insert(array_map(function($event) {
                return [
                    'calendar_id' => auth()->user()->calendar->id,
                    'event_id' => $event->getId(),
                    'summary' => $event->getSummary(),
                    'description' => $event->getDescription(),
                    'start_time' => $event->getStart() ? ($event->getStart()->getDateTime() ?? $event->getStart()->getDate()) : null,
                    'end_time' => $event->getEnd() ? ($event->getEnd()->getDateTime() ?? $event->getEnd()->getDate()) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $response->getItems()));
            $nextPageToken = $response->getNextPageToken();
        } while ($nextPageToken);
        // dd($response);
        \App\Models\CalendarSyncToken::create([
            'calendar_id' => $savedCalendar->id,
            'sync_token' => $response->getNextSyncToken(),
        ]);
    }

    public function loadDeltaEvents(): void
    {
        $calendar = auth()->user()->calendar->first();
        // dd($calendar);
        $calendarSyncToken = $calendar->calendarSyncToken;
        // dd($calendarSyncToken);

        $nextPageToken = null;
        do {
            $response = $this->calendarService->events->listEvents('primary', [
                'pageToken' => $nextPageToken,
                'syncToken' => $calendarSyncToken->sync_token,
                'maxResults' => 500,
                'singleEvents' => true,
            ]);
            foreach ($response->getItems() as $event) {
                $existingEvent = \App\Models\Event::where('event_id', $event->getId())->first();
                if ($event->getStatus() === 'cancelled') {
                    // イベントが削除された場合
                    if ($existingEvent) {
                        $existingEvent->delete();
                    }
                } else {
                    // イベントが追加または更新された場合
                    $data = [
                        'calendar_id' => $calendar->id,
                        'event_id' => $event->getId(),
                        'summary' => $event->getSummary(),
                        'description' => $event->getDescription(),
                        'start_time' => $event->getStart() ? ($event->getStart()->getDateTime() ?? $event->getStart()->getDate()) : null,
                        'end_time' => $event->getEnd() ? ($event->getEnd()->getDateTime() ?? $event->getEnd()->getDate()) : null,
                    ];
                    if ($existingEvent) {
                        $existingEvent->update($data);
                    } else {
                        \App\Models\Event::create($data);
                    }
                }
            }
            $nextPageToken = $response->getNextPageToken();
        } while ($nextPageToken);

        // 新しい sync token を保存
        $calendarSyncToken->sync_token = $response->getNextSyncToken();
        $calendarSyncToken->save();
    }
}
