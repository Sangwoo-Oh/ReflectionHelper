@component('components.header')
@endcomponent
    <form action="{{ route('aggregate') }}" method="POST">
        @csrf
        <label>キーワード:</label>
        <input type="text" name="keyword" value="{{ old('keyword') }}">
        <label>開始日:</label>
        <input type="date" name="start_date" value="{{ old('start_date') }}">
        <label>終了日:</label>
        <input type="date" name="end_date" value="{{ old('end_date') }}">
        <button type="submit">集計</button>
    </form>

    <h1>Welcome to Reflection Helper</h1>
    @auth
        @if (isset($events))
            <h2>Your Events:</h2>
            <ul>
                @foreach ($events as $event)
                <li>{{ $event->getSummary() }}</li>
            @endforeach
        </ul>
        @endif
    @else
        <p>Please log in to see your events.</p>
    @endauth
@component('components.footer')
@endcomponent
