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
        $freeword = $request->input('freeword') ?? "";
        $keyword = $request->input('keyword') ?? "";
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

        return redirect()->route('dashboard')->withInput()->with('events', $events); // 集計結果保持
    }
}
