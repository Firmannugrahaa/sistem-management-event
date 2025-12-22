<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Dashboard (No Layout)</h1>
        
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold">Total Events</h3>
                <p class="text-3xl">{{ $totalEvents }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold">Upcoming Events</h3>
                <p class="text-3xl">{{ $upcomingEventsCount }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold">Total Guests</h3>
                <p class="text-3xl">{{ $totalGuests }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Recent Events</h2>
            @forelse($recentEvents as $event)
                <div class="border-b py-2">
                    <p class="font-semibold">{{ $event->event_name }}</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}</p>
                </div>
            @empty
                <p class="text-gray-500">No events yet</p>
            @endforelse
        </div>

        <p class="mt-4 text-sm text-gray-500">Memory: {{ memory_get_usage(true) / 1024 / 1024 }} MB</p>
    </div>
</body>
</html>
