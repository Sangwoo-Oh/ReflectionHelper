@extends('layouts')

@section('content')
    @auth
        <h2>集計・分析</h2>
        @if($errors->any())
            @component('components.alert', ['type' => 'danger'])
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
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
                        <input type="text" class="form-control" id="freeword" name="freeword" value="{{ old('freeword', $freeword ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="keyword" class="form-label">キーワード</label>
                        <select class="form-select" name="keyword">
                            <option value="">選択してください</option>
                            @foreach ($keywords as $keyword)
                            <option value="{{ $keyword->id }}" {{ (old('keyword', $keyword->id ?? '')) == $keyword->id ? 'selected' : '' }}>{{ $keyword->keyword }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date_range" class="form-label">期間選択（開始日 - 終了日）<span class="text-danger">*</span></label>
                        <div class="react-root" data-components='Datepicker' data-props='@json(["start_date" => $start_date ?? null, "end_date" => $end_date ?? null])'></div>
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
        <form action="{{ route('aggregate') }}" method="POST">
            @csrf
            <input type="hidden" name="reaggregate" value=1>
            <input type="hidden" name="freeword" value="{{ old('freeword') }}">
            <input type="hidden" name="keyword" value="{{ old('keyword') }}">
            <input type="hidden" name="start_date" value="{{ old('start_date') }}">
            <input type="hidden" name="end_date" value="{{ old('end_date') }}">
            <table class="table mb-3">
                <thead>
                    <tr>
                        <th scope="col">集計対象</th>
                        <th scope="col">日付</th>
                        <th scope="col">スケジュール名</th>
                        <th scope="col">開始時間</th>
                        <th scope="col">終了時間</th>
                        <th scope="col">合計時間</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input" checked name="event_ids[]" value="{{ $event['id'] }}">
                        </td>
                        <td>{{ explode('T', $event['start_time'])[0] }}</td>
                        <td>{{ $event['summary'] }}</td>
                        <td>{{ $event['start_time_h'] }}</td>
                        <td>{{ $event['end_time_h'] }}</td>
                        <td>{{ $event['duration_h'] }}時間</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end mb-3">
                <button type="submit" class="btn btn-primary">再集計</button>
            </div>
        </form>
        @endif
        @else
        <h1>カレンダー集計・分析アプリ「シューカレ」</h1>
        <p>ログインしてイベントを確認してください。</p>
        @endauth
        
        @endsection