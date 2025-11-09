<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Revenue Report & Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Revenue Report</h3>
                    <form method="GET" action="{{ route('dashboard') }}" class="mt-4">
                        <div class="flex flex-wrap items-end gap-4">
                            <!-- Filter by Period -->
                            <div>
                                <label for="filter_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Period</label>
                                <select name="filter_period" id="filter_period" class="mt-1 block w-full md:w-auto border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">All Time</option>
                                    <option value="daily" {{ request('filter_period') == 'daily' ? 'selected' : '' }}>Today</option>
                                    <option value="monthly" {{ request('filter_period') == 'monthly' ? 'selected' : '' }}>This Month</option>
                                    <option value="yearly" {{ request('filter_period') == 'yearly' ? 'selected' : '' }}>This Year</option>
                                </select>
                            </div>
                            <!-- Filter by Event -->
                            <div>
                                <label for="filter_event_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event</label>
                                <select name="filter_event_id" id="filter_event_id" class="mt-1 block w-full md:w-auto border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">All Events</option>
                                    @foreach($eventsForFilter as $event)
                                        <option value="{{ $event->id }}" {{ request('filter_event_id') == $event->id ? 'selected' : '' }}>{{ $event->event_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Submit Button -->
                            <x-primary-button type="submit">
                                Filter
                            </x-primary-button>
                        </div>
                    </form>
                </div>
                <div class="p-6 bg-gray-50 dark:bg-gray-700/50">
                    <h4 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Total Revenue (Filtered)</h4>
                    <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Events -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Total Events</h2>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalEvents }}</p>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Upcoming Events</h2>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $upcomingEventsCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Guests -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                            <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Total Guests</h2>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalGuests }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Events -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Events List -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">My Recent Events</h3>
                            <a href="{{ route('events.index') }}" class="text-sm text-blue-500 hover:underline">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Event Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Guests</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Manage</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($recentEvents as $event)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $event->event_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $event->start_time->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $event->guests_count }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Manage</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                You haven't created any events yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                    <div class="space-y-4">
                        <a href="{{ route('events.create') }}" class="w-full flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg text-center transition duration-200">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <span>Create New Event</span>
                        </a>
                        <a href="{{ route('venues.index') }}" class="w-full flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 py-3 px-4 rounded-lg text-center transition duration-200">
                             <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Browse Venues</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
