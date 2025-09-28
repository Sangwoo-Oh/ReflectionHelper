<html>
    <head>
        <title>@yield('title')@if (!request()->is('/')) - @endifシューカレ</title>

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head> 
    <body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">シューカレ</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">集計・分析</a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/keywords') }}">キーワード管理</a>
                    </li>
                    @endauth
                    <li class="nav-item">
                        @auth
                            <li>
                                <a class="nav-link" href="{{ url('/settings') }}">設定</a>
                            </li>
                            <a class="nav-link" aria-current="page" href="{{ url('/logout') }}">ログアウト</a>
                        @else
                            <a class="nav-link" aria-current="page" href="{{ url('/google/redirect') }}">ログイン</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">