@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('client.requests.show', $clientRequest) }}" class="text-sm text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Kembali ke Detail Request
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Wedding Planner Checklist</h1>
                    <p class="text-gray-600 mt-1">{{ $clientRequest->event_name }} | Timeline View</p>
                    <p class="text-sm text-gray-500 mt-1">Event Date: <strong>{{ \Carbon\Carbon::parse($eventDate)->format('d M Y') }}</strong> ({{ $daysUntilEvent }} hari lagi)</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600 mb-1">Progress Persiapan</div>
                    <div class="text-3xl font-bold text-green-600">{{ $checklist->progress }}%</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4 w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                <div class="bg-green-500 h-4 transition-all duration-300 rounded-full" style="width: {{ $checklist->progress }}%"></div>
            </div>
            <p class="text-sm text-gray-500 mt-2">
                {{ $checklist->items->where('is_checked', true)->count() }} dari {{ $checklist->items->count() }} tugas selesai
            </p>
        </div>

        <!-- View Tabs -->
        <div class="mb-6 flex gap-3 border-b border-gray-200">
            <a href="{{ route('client.checklist', $clientRequest) }}" 
               class="px-4 py-3 font-medium border-b-2 transition border-transparent text-gray-500 hover:text-gray-700">
                📋 List View
            </a>
            <a href="{{ route('client.checklist.timeline', $clientRequest) }}" 
               class="px-4 py-3 font-medium border-b-2 transition border-blue-600 text-blue-600">
                📅 Timeline View
            </a>
        </div>

        <!-- Rush Booking Alert -->
        @if(in_array($bookingType, ['rush', 'emergency']))
        <div class="mb-6 {{ $bookingType === 'emergency' ? 'bg-red-50 border-l-4 border-red-500' : 'bg-orange-50 border-l-4 border-orange-500' }} p-4 rounded">
            <div class="flex items-start">
                <svg class="w-6 h-6 {{ $bookingType === 'emergency' ? 'text-red-500' : 'text-orange-500' }} mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold {{ $bookingType === 'emergency' ? 'text-red-800' : 'text-orange-800' }}">
                        @if($bookingType === 'emergency')
                            🚨 Emergency Booking Terdeteksi!
                        @else
                            ⚡ Rush Booking Terdeteksi
                        @endif
                    </h3>
                    <p class="text-sm {{ $bookingType === 'emergency' ? 'text-red-700' : 'text-orange-700' }} mt-1">
                        Event hanya <strong>{{ $daysUntilEvent }} hari</strong> lagi! 
                        Kami fokuskan checklist pada <strong>{{ $criticalItemsCount }} item CRITICAL</strong> yang harus diselesaikan segera.
                        @if($bookingType === 'emergency')
                        Hubungi tim kami untuk bantuan prioritas.
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Timeline Groups -->
        @forelse($timelineData as $timeframe)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <!-- Timeframe Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">🕐 {{ $timeframe['label'] }}</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $timeframe['completed'] }}/{{ $timeframe['total'] }} tugas selesai
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600">{{ $timeframe['progress'] }}%</div>
                            <div class="w-24 bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $timeframe['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items List -->
                <div class="divide-y divide-gray-100">
                    @foreach($timeframe['items'] as $item)
                        <div class="px-6 py-4 hover:bg-gray-50 transition {{ $item->is_overdue && !$item->is_checked ? 'bg-red-50' : '' }}">
                            <div class="flex items-start gap-4">
                                <!-- Checkbox -->
                                <form action="{{ route('client.checklist.item.update', $item) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_checked" value="{{ $item->is_checked ? '0' : '1' }}">
                                    <button type="submit" class="w-7 h-7 rounded-lg border-2 flex items-center justify-center transition
                                        {{ $item->is_checked ? 'bg-green-500 border-green-500' : 'bg-white border-gray-300 hover:border-green-500' }}">
                                        @if($item->is_checked)
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                <!-- Item Content -->
                                <div class="flex-1">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <h3 class="text-base font-medium {{ $item->is_checked ? 'text-gray-400 line-through' : 'text-gray-900' }}">
                                                {{ $item->title }}
                                            </h3>
                                            <div class="flex items-center gap-3 mt-2">
                                                <!-- Priority Badge -->
                                                @if($item->priority === 'CRITICAL')
                                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded">🔴 CRITICAL</span>
                                                @elseif($item->priority === 'IMPORTANT')
                                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded">🟠 IMPORTANT</span>
                                                @else
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded">🟡 OPTIONAL</span>
                                                @endif

                                                <!-- Due Date -->
                                                @if($item->due_date)
                                                    <span class="text-sm {{ $item->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                                        📅 Due: {{ \Carbon\Carbon::parse($item->due_date)->format('d M Y') }}
                                                        @if($item->is_overdue && !$item->is_checked)
                                                            <span class="text-xs">(Overdue!)</span>
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    @if($item->notes)
                                        <p class="text-sm text-gray-600 mt-2 bg-gray-50 p-2 rounded">📝 {{ $item->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-12 text-center">
                <svg class="w-16 h-16 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-blue-900 mb-2">Timeline Belum Tersedia</h3>
                <p class="text-blue-700">Tanggal event belum ditentukan atau belum ada item dengan timeline.</p>
            </div>
        @endforelse

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm text-blue-800">
                        <strong>Timeline View</strong> menampilkan tugas berdasarkan jadwal yang disarankan. 
                        Tanggal due date dihitung otomatis berdasarkan tanggal event Anda.
                        @if($bookingType !== 'standard')
                            Timeline telah disesuaikan dengan waktu persiapan Anda yang terbatas.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
