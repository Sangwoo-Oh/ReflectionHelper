<?php

namespace App\Http\Controllers;

use App\CalendarServiceInterface;
use App\Models\SearchKeyword;
use DateTime;
use Illuminate\Http\Request;

class AggregateController extends Controller
{
    public function aggregateEvents(Request $request, CalendarServiceInterface $calendarServiceInterface)
    {
        // dd($request->input('reaggregate'));
        $is_reaggregate = !is_null($request->input('reaggregate'));

        $user = auth()->user();
        $calendar = $user->calendar;
        if (!$calendar) {
            // カレンダーが存在しない場合、全件取得
            $calendarServiceInterface->loadAllEvents();
        } else {
            // etagが異なる場合、差分取得
            $listEventsEtag = $calendarServiceInterface->getListEventsEtag();
            if ($calendar->list_events_etag !== $listEventsEtag) {
                $calendarServiceInterface->loadDeltaEvents();
            }
        }

        $freeword = $request->input('freeword') ?? '';
        $keyword = $request->input('keyword') ?? '';
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startDate = isset($startDate) ? new DateTime($startDate) : null;
        $endDate = isset($endDate) ? new DateTime($endDate) : null;

        // dd($freeword, $startDate, $endDate);

        $queries = [];
        if (!empty($freeword)) {
            $queries[] = $freeword;
        }
        if (!empty($keyword)) {
            $searchKeywords = SearchKeyword::where('keyword_id', $keyword);
            $queries = array_merge($searchKeywords->pluck('search_keyword')->toArray(), $queries);
        }

        $events = [];
        $eventsQuery = auth()->user()->calendar->events();

        if ($is_reaggregate) {
            // 再集計の場合
            $event_ids = $request->input('event_ids') ?? [];
            $eventsQuery->whereIn('id', $event_ids);
        } else {
            $eventsQuery->where(function ($q) use ($queries) {
                foreach ($queries as $query) {
                    $q->orWhere('summary', 'like', '%' . $query . '%');
                }
            });
        }

        if ($startDate) {
            $eventsQuery->where('start_time', '>=', $startDate->format('Y-m-d') . 'T00:00:00Z');
        }
        if ($endDate) {
            $eventsQuery->where('end_time', '<=', $endDate->format('Y-m-d') . 'T23:59:59Z');
        }

        $events = $eventsQuery->get();

        $summary = [];
        $sumDuration = 0;
        foreach ($events as $event) {
            if (isset($event['start_time']) && isset($event['end_time'])) {
                $start = new DateTime($event['start_time']);
                $end = new DateTime($event['end_time']);
                $duration = $end->getTimestamp() - $start->getTimestamp();
                $sumDuration += $duration;
            }
        }

        foreach ($events as $key => $event) {
            $events[$key]['duration_h'] = isset($event['start_time']) && isset($event['end_time']) ? (new DateTime($event['end_time']))->getTimestamp() - (new DateTime($event['start_time']))->getTimestamp() : 0;
            $events[$key]['duration_h'] = round($events[$key]['duration_h'] / 3600, 2);
        }

        $sumDuration /= 60 * 60; // 秒を時間に変換
        $sumDuration = round($sumDuration, 2);
        $summary['sumDuration'] = $sumDuration;

        $countDays = [];
        foreach ($events as $event) {
            if (isset($event['start_time'])) {
                $start = new DateTime($event['start_time']);
                $day = $start->format('Y-m-d');
                $countDays[$day] = true;
            }
        }
        $summary['countDays'] = count($countDays);

        $averageDuration = $sumDuration > 0 && count($countDays) > 0 ? $sumDuration / count($countDays) : 0;
        $averageDuration = round($averageDuration, 2);
        $summary['averageDuration'] = $averageDuration;

        // dd($events);

        return redirect()->route('dashboard')->withInput()->with('events', $events)->with('summary', $summary); // 集計結果保持
    }
}
