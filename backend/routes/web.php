<?php

use App\Http\Controllers\AggregateController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\SearchKeywordController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', [AggregateController::class, 'index']);

Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleController::class, 'handleCallback']);

Route::middleware([Authenticate::class])->group(function () {
    Route::resource('keywords', KeywordController::class)->only(['index', 'show', 'edit', 'store', 'update', 'destroy']);
    Route::resource('search-keywords', SearchKeywordController::class)->only(['store', 'update', 'destroy']);
    Route::post('/aggregate', [AggregateController::class, 'aggregateEvents'])->name('aggregate');
    Route::get('/logout', [GoogleController::class, 'logout'])->name('logout');
    Route::delete('/user', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/settings', function() {
        return view('settings');
    });
});

Route::get('/privacy', function() {
    return view('privacy');
})->name('privacy');
Route::get('/terms', function() {
    return view('terms');
})->name('terms');
