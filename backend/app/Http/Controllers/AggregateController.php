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
        foreach ($queries as $query) {
            $events = array_merge($events, $calendarServiceInterface->listEvents($query, $startDate, $endDate));
        }

        $events = array_unique($events, SORT_REGULAR);
        // dd($events);
        $summary = [];
        $sumDuration = 0;
        foreach ($events as $event) {
            if (isset($event['start']) && isset($event['end'])) {
                $start = new DateTime($event['start']->dateTime);
                $end = new DateTime($event['end']->dateTime);
                $duration = $end->getTimestamp() - $start->getTimestamp();
                $sumDuration += $duration;
            }
        }

        $sumDuration /= 60 * 60; // 秒を時間に変換
        $sumDuration = round($sumDuration, 2);
        $summary['sumDuration'] = $sumDuration;

        $countDays = [];
        foreach ($events as $event) {
            if (isset($event['start'])) {
                $start = new DateTime($event['start']->dateTime);
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
