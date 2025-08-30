<h2>Your Calendars:</h2>
<ul>
    @foreach ($calendarList->getItems() as $calendar)
        <li>{{ $calendar->getSummary() }}</li>
    @endforeach
</ul>


<form action="{{ route('analyze') }}" method="POST">
    @csrf
    <input type="text" name="query">
    <button type="submit">検索</button>
</form>
