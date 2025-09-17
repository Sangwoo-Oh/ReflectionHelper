<?php

namespace App\Providers;

use App\CalendarServiceInterface;
use App\Services\GoogleCalendarService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CalendarServiceInterface::class,
            function () {
                // 認証済みユーザーを取得
                $user = auth()->user();

                // ログイン状態でなければnullを返す
                if (!$user) {
                    return null;
                }

                // コンストラクタにユーザーを渡してインスタンス生成
                return new GoogleCalendarService($user);
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
