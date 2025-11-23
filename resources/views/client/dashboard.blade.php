<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Client Dashboard</h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600">Manage your events and invitations</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-700">Total Events</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $eventCount }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-700">Running Events</h3>
                <p class="text-3xl font-bold text-green-600">{{ $runningEvents->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-700">Completed Events</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $completedEvents->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-700">Pending Payment</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingInvoices->count() }}</p>
            </div>
        </div>

        <!-- Event Status Tabs -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="#running" class="whitespace-nowrap border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 active">
                        Running ({{ $runningEvents->count() }})
                    </a>
                    <a href="#pending" class="whitespace-nowrap border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Pending ({{ $pendingEvents->count() }})
                    </a>
                    <a href="#completed" class="whitespace-nowrap border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Completed ({{ $completedEvents->count() }})
                    </a>
                    <a href="#cancelled" class="whitespace-nowrap border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Cancelled ({{ $cancelledEvents->count() }})
                    </a>
                </nav>
            </div>
        </div>

        <!-- Running Events Section -->
        <div id="running" class="mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Running Events</h2>
                @if($runningEvents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest Count</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($runningEvents as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('d M Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $event->status ?: 'Running' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->guests_count ?: 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <a href="{{ route('events.guests.import.form', $event->id) }}" class="text-green-600 hover:text-green-900">Upload Guests</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">No running events at the moment.</p>
                @endif
            </div>
        </div>

        <!-- Pending Events Section -->
        <div id="pending" class="mb-8 hidden">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Pending Events</h2>
                @if($pendingEvents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest Count</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingEvents as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('d M Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $event->status ?: 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->guests_count ?: 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <a href="{{ route('events.guests.import.form', $event->id) }}" class="text-green-600 hover:text-green-900">Upload Guests</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">No pending events at the moment.</p>
                @endif
            </div>
        </div>

        <!-- Completed Events Section -->
        <div id="completed" class="mb-8 hidden">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Completed Events</h2>
                @if($completedEvents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest Count</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($completedEvents as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('d M Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ $event->status ?: 'Completed' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->guests_count ?: 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">No completed events at the moment.</p>
                @endif
            </div>
        </div>

        <!-- Cancelled Events Section -->
        <div id="cancelled" class="mb-8 hidden">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Cancelled Events</h2>
                @if($cancelledEvents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($cancelledEvents as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('d M Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $event->status ?: 'Cancelled' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">No cancelled events at the moment.</p>
                @endif
            </div>
        </div>


        <!-- Upload Guests Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Upload Guests</h2>
                </div>
                <p class="text-gray-600 mb-6">Upload a list of guests to invite to your events. You can upload in CSV or Excel format.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Upload Form -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Upload Guest List</h3>
                        <form id="guest-upload-form" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="event_id" id="selected-event-id" value="">

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Event</label>
                                <select name="event_id" id="event-selector" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Choose an event...</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}"
                                            {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->name }} ({{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('d M Y') : 'No date' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File</label>
                                <input type="file" name="file" accept=".csv,.xlsx,.xls" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">CSV or Excel file only. Must include columns: name, email, phone</p>
                            </div>

                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Upload Guests
                            </button>
                        </form>
                    </div>

                    <!-- Upload Instructions -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Instructions</h3>
                        <div class="bg-blue-50 p-4 rounded-md">
                            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                                <li>Download the template file below to see the required format</li>
                                <li>Prepare your guest list in CSV or Excel format</li>
                                <li>Include the following columns: <strong>name</strong>, <strong>email</strong>, <strong>phone</strong></li>
                                <li>Make sure to select the appropriate event from the dropdown</li>
                                <li>Click "Upload Guests" to import your guest list</li>
                            </ol>

                            <div class="mt-4">
                                <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Template
                                </a>
                            </div>
                        </div>

                        <div class="mt-4 text-sm text-gray-600">
                            <p><strong>Example format:</strong></p>
                            <pre class="bg-gray-100 p-2 rounded mt-1 text-xs">name,email,phone
John Doe,john@example.com,081234567890
Jane Smith,jane@example.com,082345678901</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple tab switching functionality
            const tabs = document.querySelectorAll('[href^="#"]');
            const sections = document.querySelectorAll('[id^="running"], [id^="pending"], [id^="completed"], [id^="cancelled"]');

            // Hide all sections initially except the first one
            sections.forEach((section, index) => {
                if (index !== 0) {
                    section.classList.add('hidden');
                }
            });

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('border-blue-500', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });

                    // Add active class to clicked tab
                    this.classList.add('border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500', 'active');

                    // Hide all sections
                    sections.forEach(section => {
                        section.classList.add('hidden');
                    });

                    // Show the target section
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.classList.remove('hidden');
                    }
                });
            });
        });
        // Update form action when event is selected
        document.getElementById('event-selector').addEventListener('change', function() {
            const eventId = this.value;
            const form = document.getElementById('guest-upload-form');

            if (eventId) {
                form.action = '/events/' + eventId + '/guests/import';
            } else {
                form.action = '';
            }
        });
    </script>
</x-app-layout>