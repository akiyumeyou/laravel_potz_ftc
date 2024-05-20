<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
</head>
<body>
    <h1>Messages</h1>
    <a href="{{ route('tweets.create') }}">Create New Message</a>
    <ul>
        @foreach ($messages as $message)
            <li>{{ $message->user_name }}: {{ $message->content }}</li>
        @endforeach
    </ul>
</body>
</html>
