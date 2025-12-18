<!DOCTYPE html>
<html>
<head>
    <title>Test Dashboard</title>
</head>
<body>
    <h1>Minimal Dashboard Test</h1>
    <p>User: {{ auth()->user()->name }}</p>
    <p>Total Events: {{ $totalEvents }}</p>
    <p>Memory: {{ memory_get_usage(true) / 1024 / 1024 }} MB</p>
</body>
</html>
