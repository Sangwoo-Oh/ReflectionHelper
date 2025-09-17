@extends('layouts')

@section('content')
    @auth
        <h2>集計・分析</h2>
        <form action="{{ route('aggregate') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="freeword" class="form-label">フリーワード</label>
                <input type="text" class="form-control" id="freeword" name="freeword" value="{{ old('freeword') }}">
            </div>
            <div class="mb-3">
                <label for="keyword" class="form-label">キーワード</label>
                <select class="form-select" name="keyword">
                    <option value="">選択してください</option>
                    @foreach ($keywords as $keyword)
                    <option value="{{ $keyword->id }}" {{ old('keyword') == $keyword->id ? 'selected' : '' }}>{{ $keyword->keyword }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">開始日</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">終了日</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
            </div>
            <button type="submit" class="btn btn-primary">集計</button>
        </form>
        @if (session('events'))

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">日付</th>
                    <th scope="col">スケジュール名</th>
                    <th scope="col">開始時間</th>
                    <th scope="col">終了時間</th>
                    <th scope="col">合計時間</th>
                </tr>
            </thead>
            <tbody>
                @foreach (session('events') as $event)
                <tr>
                    <td>{{ $event->getStart()->dateTime }}</td>
                    <td>{{ $event->getSummary() }}</td>
                    <td>{{ $event->getStart()->dateTime }}</td>
                    <td>{{ $event->getEnd()->dateTime }}</td>
                    <td>15時間</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    @else
        <h1>カレンダー集計・分析アプリ「シューカレ」</h1>
        <p>ログインしてイベントを確認してください。</p>
    @endauth

@endsection