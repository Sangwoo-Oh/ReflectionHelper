@extends('layouts')

@section('title', '集計・分析')

@section('content')
    @auth
        <h2>集計・分析</h2>
        @if ($errors->any())
            @component('components.alert', ['type' => 'danger'])
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endcomponent
        @endif
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('aggregate') }}" method="POST" class="mb-0">
                    @csrf
                    <div class="mb-3">
                        <label for="freeword" class="form-label">フリーワード</label>
                        <input type="text" class="form-control" id="freeword" name="freeword"
                            value="{{ old('freeword', $freeword ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="keyword" class="form-label">キーワード</label>
                        <select class="form-select" name="keyword">
                            <option value="">選択してください</option>
                            @foreach ($keywords as $keyword)
                                <option value="{{ $keyword->id }}"
                                    {{ old('keyword', $selected_keyword ?? '') == $keyword->id ? 'selected' : '' }}>
                                    {{ $keyword->keyword }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="excluded_string" class="form-label">検索結果に含めない文字列</label>
                        <input type="text" class="form-control" id="excluded_string" name="excluded_string"
                            value="{{ old('excluded_string', $excluded_string ?? '') }}">
                        <div id="excludedStringHelpBlock" class="form-text">
                            検索結果に含めたくない文字列を入力してください。複数ある場合は半角/全角カンマあるいは半角/全角スペースで区切ってください。<br>例）会議,打ち合わせ、研修　セミナー
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="date_range" class="form-label">期間選択（開始日 - 終了日）<span class="text-danger">*</span></label>
                        <div class="react-root" data-components='Datepicker' data-props='@json(['start_date' => $start_date ?? null, 'end_date' => $end_date ?? null])'></div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">集計</button>
                    </div>
                </form>
            </div>
        </div>
        @if (isset($events))
            <div class="card-group mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">合計時間</h5>
                        <p class="card-text"><span class="display-1">{{ $summary['sumDuration'] }} </span>時間</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">平均時間</h5>
                        <p class="card-text"><span class="display-1">{{ $summary['averageDuration'] }} </span>時間</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">活動日数</h5>
                        <p class="card-text"><span class="display-1">{{ $summary['countDays'] }} </span>日</p>
                    </div>
                </div>
            </div>
            @php
                $csrfToken = csrf_token();
                $props = [
                    'events' => $events,
                    'freeword' => $freeword,
                    'keyword' => $selected_keyword,
                    'excluded_string' => $excluded_string,
                    'csrfToken' => $csrfToken,
                ];
            @endphp


            <div class="react-root" data-components="Table" data-props='@json($props)'></div>
        @endif
    @else
        <h1>カレンダー集計・分析アプリ「シューカレ」</h1>
        <p>ログインしてイベントを確認してください。</p>
    @endauth

@endsection
