<?php

use App\CalendarServiceInterface;
use App\Http\Controllers\AggregateController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\SearchKeywordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (?CalendarServiceInterface $calendarServiceInterface) {
    $user = auth()->user();
    if ($user) {
        $keywords = $user->keywords;
        // dd($keywords);
        return view('dashboard', compact('keywords'));
    } else {
        return view('dashboard');
    }
})->name('dashboard');

Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleCallback']);
Route::get('/logout', [GoogleController::class, 'logout'])->name('logout');

Route::post('/aggregate', [AggregateController::class, 'aggregateEvents'])->name('aggregate');

Route::resource('keywords', KeywordController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
Route::resource('search-keywords', SearchKeywordController::class)->only(['store', 'update', 'destroy']);
