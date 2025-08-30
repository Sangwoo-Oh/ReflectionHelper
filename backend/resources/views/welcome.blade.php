@component('components.header')
@endcomponent
    <h1>Welcome to Reflection Helper</h1>
    @auth
        <h2>Your Calendars:</h2>
        <ul>
            @foreach ($calendarList->getItems() as $calendar)
                <li>{{ $calendar->getSummary() }}</li>
            @endforeach
        </ul>
    @else
        <p>Please log in to see your calendars.</p>
    @endauth
@component('components.footer')
@endcomponent
