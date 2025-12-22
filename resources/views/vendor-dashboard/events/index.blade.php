<x-vendor-dashboard-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Event Saya</h2>
        <p class="text-gray-600 dark:text-gray-400">Daftar event yang sedang atau akan saya tangani</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-3">Filter Event</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select id="status-filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Semua Status</option>
                    <option value="upcoming">Akan Datang</option>
                    <option value="ongoing">Sedang Berlangsung</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                <input type="date" id="date-filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Event</label>
                <input type="text" id="search-input" placeholder="Cari nama event..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>
        </div>
    </div>

    <!-- Events List -->
    @if($events->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nama Event
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Klien
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($events as $event)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $event->event_name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $event->venue ? $event->venue->name : 'No venue' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $event->client_name ?: 'N/A' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $event->client_phone ?: 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($event->start_time)->format('d M Y H:i') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">s/d {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $now = now();
                                    $status = 'upcoming';
                                    if ($now->between($event->start_time, $event->end_time)) {
                                        $status = 'ongoing';
                                    } elseif ($now->gt($event->end_time)) {
                                        $status = 'completed';
                                    } elseif ($now->lt($event->start_time)) {
                                        $status = 'upcoming';
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                      {{ $status === 'upcoming' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 
                                         ($status === 'ongoing' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 
                                         ($status === 'completed' ? 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100' : 
                                         'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100')) }}">
                                    {{ $status === 'upcoming' ? 'Akan Datang' : 
                                       ($status === 'ongoing' ? 'Sedang Berlangsung' : 
                                       ($status === 'completed' ? 'Selesai' : 'Dibatalkan')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <a href="{{ route('events.show', $event->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada event</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Anda belum ditugaskan ke event apapun saat ini.
            </p>
        </div>
    @endif
</x-vendor-dashboard-layout>