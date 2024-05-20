<!DOCTYPE html>
<html>
<head>
    <title>Create Message</title>
</head>
<body>
    <h1>Create Message</h1>
    <form action="{{ route('tweets.store') }}" method="POST">
        @csrf
        <label for="content">Content:</label>
        <textarea name="content" id="content" required></textarea>
        <button type="submit">Create</button>
    </form>
    <a href="{{ route('tweets.index') }}">Back to Messages</a>
</body>
</html>
