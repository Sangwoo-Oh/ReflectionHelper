@extends('layouts')

@section('content')
    @auth
        <h2>カレンダー集計:</h2>
        <form action="{{ route('aggregate') }}" method="POST">
            @csrf
            <label>フリーワード:</label>
            <input type="text" name="freeword" value="{{ old('freeword') }}">
            <label>キーワード:</label>
            <select name="keyword">
                <option value="">選択してください</option>
                @foreach ($keywords as $keyword)
                    <option value="{{ $keyword->id }}" {{ old('keyword') == $keyword->id ? 'selected' : '' }}>{{ $keyword->keyword }}</option>
                @endforeach
            </select>
            <label>開始日:</label>
            <input type="date" name="start_date" value="{{ old('start_date') }}">
            <label>終了日:</label>
            <input type="date" name="end_date" value="{{ old('end_date') }}">
            <button type="submit">集計</button>
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