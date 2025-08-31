@component('components.header')
@endcomponent
    <h1>Welcome to Reflection Helper</h1>
    @auth
        <h2>Your Events:</h2>
        <ul>
            @foreach ($events as $event)
                <li>{{ $event->getSummary() }}</li>
            @endforeach
        </ul>
    @else
        <p>Please log in to see your events.</p>
    @endauth
@component('components.footer')
@endcomponent
