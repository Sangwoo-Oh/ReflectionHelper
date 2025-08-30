@component('components.header')
@endcomponent
    <h1>Welcome to Reflection Helper</h1>
    @if(session('google_token'))
        @if(isset($calendarItems))
            <h2>Your Calendar Items:</h2>
            <ul>
                @foreach($calendarItems as $item)
                    <li>{{ $item->getSummary() }}</li>
                @endforeach
            </ul>
        @endif
    @else
        <p>Please log in to view your calendar items.</p>
    @endif
@component('components.footer')
@endcomponent
