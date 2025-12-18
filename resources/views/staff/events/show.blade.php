<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $event->event_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- My Role Card --}}
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">My Role in This Event</h3>
                        <p class="text-2xl font-bold mt-2">{{ $crew->role }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-blue-100">Event Date</p>
                        <p class="text-lg font-semibold">{{ $event->start_time->format('d M Y') }}</p>
                        <p class="text-sm">{{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Event Information --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">📋 Event Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Client Name</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $event->client_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Venue</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $event->venue->name ?? 'TBA' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Guests</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $event->guests->count() }} guests</p>
                        </div>
                    </div>
                    @if($event->description)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                            <p class="text-base text-gray-900 dark:text-gray-100 mt-1">{{ $event->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Event Team --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">👥 Event Team</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($event->crews as $member)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $member->user_id == Auth::id() ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-300' : '' }}">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-semibold">
                                            {{ substr($member->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $member->user->name }}
                                            @if($member->user_id == Auth::id())
                                                <span class="text-blue-600">(You)</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $member->role }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Vendors --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">🏢 Vendors</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vendor Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($event->vendors as $vendor)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $vendor->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $vendor->category }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $vendor->phone_number ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                                {{ $vendor->pivot->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">No vendors assigned yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">⚡ Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('staff.events.index') }}" class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Back to My Events</span>
                        </a>
                        
                        <a href="{{ route('staff.events.tasks', $event) }}" class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="text-sm font-medium">My Tasks</span>
                        </a>
                        
                        <button class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition" disabled>
                            <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-400">Team Chat (Coming Soon)</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
