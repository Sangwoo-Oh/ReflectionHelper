@extends('layouts')

@section('content')
    @auth
        <h2>カレンダー集計:</h2>
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
        <ul>
            @foreach (session('events') as $event)
                <li>
                    <strong>{{ $event->getSummary() }}</strong>
                    <br>
                    <em>{{ $event->getStart()->dateTime }} - {{ $event->getEnd()->dateTime }}</em>
                    <br>
                </li>
            @endforeach
        </ul>
        @endif
    @else
        <h1>カレンダー集計・分析アプリ「シューカレ」</h1>
        <p>ログインしてイベントを確認してください。</p>
    @endauth

@endsection