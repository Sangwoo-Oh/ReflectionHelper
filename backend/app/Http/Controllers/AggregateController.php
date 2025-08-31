<?php

namespace App\Http\Controllers;

use App\CalendarServiceInterface;
use DateTime;
use Illuminate\Http\Request;

class AggregateController extends Controller
{
    public function aggregateEvents(Request $request, CalendarServiceInterface $calendarServiceInterface)
    {
        $keyword = $request->input('keyword') ?? "";
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startDate = isset($startDate) ? new DateTime($startDate) : null;
        $endDate = isset($endDate) ? new DateTime($endDate) : null;

        // dd($keyword, $startDate, $endDate);

        // ここでイベントの集計処理を行う
        $events = $calendarServiceInterface->listEvents($keyword, $startDate, $endDate);

        // dd($events);

        return redirect()->route('dashboard')->withInput()->with('events', $events); // 集計結果保持
    }
}
