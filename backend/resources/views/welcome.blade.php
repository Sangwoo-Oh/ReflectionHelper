<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome to Laravel</h1>
    <a href="{{ url('/google/redirect') }}">
        Google Calendar Login
    </a>
    @if(isset($calendarItems))
        <h2>Your Calendar Items:</h2>
        <ul>
            @foreach($calendarItems as $item)
                <li>{{ $item->getSummary() }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>
