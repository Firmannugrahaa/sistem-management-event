<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
            Event Ditugaskan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Section -->
            <div class="mb-6 bg-white rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">🔍 Filter Event</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status-filter" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#27AE60] focus:ring-[#27AE60]">
                            <option value="">Semua Status</option>
                            <option value="upcoming">Akan Datang</option>
                            <option value="ongoing">Sedang Berlangsung</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" id="date-filter" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#27AE60] focus:ring-[#27AE60]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Event</label>
                        <input type="text" id="search-input" placeholder="Cari nama event..." class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#27AE60] focus:ring-[#27AE60]">
                    </div>
                </div>
            </div>

            <!-- Events List -->
            @if($events->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Event
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Klien
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($events as $event)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-[#1A1A1A]">{{ $event->event_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $event->venue ? $event->venue->name : 'No venue' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#1A1A1A]">{{ $event->client_name ?: 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $event->client_phone ?: 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#1A1A1A]">{{ \Carbon\Carbon::parse($event->start_time)->format('d M Y H:i') }}</div>
                                        <div class="text-sm text-gray-500">s/d {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y H:i') }}</div>
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
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                              {{ $status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 
                                                 ($status === 'ongoing' ? 'bg-green-100 text-green-700' : 
                                                 ($status === 'completed' ? 'bg-gray-100 text-gray-700' : 
                                                 'bg-red-100 text-red-700')) }}">
                                            {{ $status === 'upcoming' ? 'Akan Datang' : 
                                               ($status === 'ongoing' ? 'Sedang Berlangsung' : 
                                               ($status === 'completed' ? 'Selesai' : 'Dibatalkan')) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('events.show', $event->id) }}" 
                                           class="text-[#012A4A] hover:text-[#27AE60] font-medium">
                                            Lihat Detail →
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
                <div class="bg-white rounded-2xl p-12 text-center shadow-sm">
                    <div class="text-6xl mb-4">📅</div>
                    <h3 class="text-lg font-medium text-[#1A1A1A] mb-2">Belum ada event</h3>
                    <p class="text-gray-500">
                        Anda belum ditugaskan ke event apapun saat ini.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>