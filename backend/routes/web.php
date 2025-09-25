<?php

use App\CalendarServiceInterface;
use App\Http\Controllers\AggregateController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\SearchKeywordController;
use App\Http\Middleware\Authenticate;
use App\Models\Calendar;
use Illuminate\Support\Facades\Route;

Route::get('/', function (?CalendarServiceInterface $calendarServiceInterface) {

    $user = auth()->user();
    if ($user) {
        // ロード処理
        // 1. 全件取得してDBに保存
        //   - 条件：ユーザーIDに紐づくカレンダーが存在しない場合
        // 2. 差分取得してDBに保存
        //   - 条件：ユーザーIDに紐づくカレンダーが存在する && etagの値が異なる場合
        $calendar = Calendar::where('user_id', $user->id)->first();
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

        $keywords = $user->keywords;
        // dd($keywords);
        return view('dashboard', compact('keywords'));
    } else {
        return view('dashboard');
    }
})->name('dashboard');

Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleCallback']);

Route::middleware([Authenticate::class])->group(function () {
    Route::resource('keywords', KeywordController::class)->only(['index', 'show', 'edit', 'store', 'update', 'destroy']);
    Route::resource('search-keywords', SearchKeywordController::class)->only(['store', 'update', 'destroy']);
    Route::post('/aggregate', [AggregateController::class, 'aggregateEvents'])->name('aggregate');
    Route::get('/logout', [GoogleController::class, 'logout'])->name('logout');

    Route::get('/settings', function() {
        return view('settings');
    });
});