@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My Dashboard</h1>
            <p class="text-sm text-gray-600">Welcome back, {{ Auth::user()->name }}</p>
        </div>

        @if($activeRequest)
            <!-- Booking Number Badge -->
            @if($activeRequest->booking_number)
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                    {{ $activeRequest->booking_number }}
                </span>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Block 1: Status Acara -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status Acara
                            </h2>
                        </div>
                        <div class="p-6">
                            <!-- Event Info -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-lg font-semibold text-gray-900">{{ $activeRequest->event_type }}</p>
                                    <p class="text-sm text-gray-500">
                                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $activeRequest->event_date->format('d F Y') }}
                                    </p>
                                </div>
                                <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $activeRequest->status_badge_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $activeRequest->detailed_status)) }}
                                </span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Progress</span>
                                    <span class="text-sm font-medium text-blue-600">{{ round($progressData['percentage']) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-500" 
                                         style="width: {{ $progressData['percentage'] }}%"></div>
                                </div>
                            </div>

                            <!-- Step Indicators -->
                            <div class="flex items-center justify-between">
                                @foreach($progressData['steps'] as $stepNum => $step)
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold
                                        {{ $progressData['currentStep'] >= $stepNum ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                                        @if($progressData['currentStep'] > $stepNum)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            {{ $stepNum }}
                                        @endif
                                    </div>
                                    <span class="text-xs mt-1 text-center {{ $progressData['currentStep'] >= $stepNum ? 'text-blue-600 font-medium' : 'text-gray-500' }}">
                                        {{ $step['name'] }}
                                    </span>
                                </div>
                                @if($stepNum < count($progressData['steps']))
                                <div class="flex-1 h-0.5 mx-2 {{ $progressData['currentStep'] > $stepNum ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Block 2: Rekomendasi dari Admin -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-orange-50 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                Rekomendasi dari Admin
                            </h2>
                            @if($newRecommendationsCount > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500 text-white animate-pulse">
                                {{ $newRecommendationsCount }} Baru
                            </span>
                            @endif
                        </div>
                        <div class="p-6">
                            @if($pendingItems->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($pendingItems as $item)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition" 
                                         x-data="{ showRejectModal: false }">
                                        <!-- Category Badge -->
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                                {{ $item->category }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $item->recommendation->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        <!-- Vendor Info -->
                                        <h4 class="font-semibold text-gray-900 mb-1">
                                            {{ $item->vendor ? $item->vendor->brand_name : $item->external_vendor_name }}
                                        </h4>
                                        @if($item->notes)
                                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($item->notes, 80) }}</p>
                                        @endif
                                        
                                        <!-- Price -->
                                        <p class="text-lg font-bold text-green-600 mb-4">
                                            Rp {{ number_format($item->estimated_price, 0, ',', '.') }}
                                        </p>

                                        <!-- Action Buttons -->
                                        <div class="flex gap-2">
                                            <button onclick="approveItem({{ $item->id }}, '{{ $item->category }}', {{ $item->estimated_price }})"
                                                class="flex-1 px-3 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Setujui
                                            </button>
                                            <button @click="showRejectModal = true"
                                                class="flex-1 px-3 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded-lg hover:bg-red-200 transition flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tolak
                                            </button>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div x-show="showRejectModal" x-cloak 
                                             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                             @click.self="showRejectModal = false">
                                            <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl">
                                                <h3 class="text-lg font-bold text-gray-900 mb-4">Tolak Rekomendasi</h3>
                                                <form action="{{ route('client.recommendation-items.respond', $item) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan (opsional)</label>
                                                        <textarea name="feedback" rows="3" 
                                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500"
                                                            placeholder="Berikan alasan penolakan..."></textarea>
                                                    </div>
                                                    <div class="flex gap-3">
                                                        <button type="button" @click="showRejectModal = false"
                                                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                                            Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                                            Konfirmasi Tolak
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada rekomendasi dari admin.</p>
                                    <p class="text-sm text-gray-400 mt-1">Kami akan segera mengirimkan proposal untuk acara Anda.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Approved Items Summary -->
                    @php
                        $approvedItems = collect();
                        if($activeRequest && $activeRequest->recommendations) {
                            foreach($activeRequest->recommendations as $rec) {
                                $approvedItems = $approvedItems->merge($rec->items->where('client_response', 'approved'));
                            }
                        }
                    @endphp
                    @if($approvedItems->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Vendor yang Disetujui
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach($approvedItems as $item)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div>
                                        <span class="text-xs text-green-600 font-medium">{{ $item->category }}</span>
                                        <p class="font-semibold text-gray-900">{{ $item->vendor ? $item->vendor->brand_name : $item->external_vendor_name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-green-600">Rp {{ number_format($item->estimated_price, 0, ',', '.') }}</p>
                                        <span class="text-xs text-green-500">Disetujui</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                <span class="font-semibold text-gray-700">Total</span>
                                <span class="text-xl font-bold text-green-600">Rp {{ number_format($approvedItems->sum('estimated_price'), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Right Column (1/3) -->
                <div class="space-y-6">
                    
                    <!-- Block 4: Ringkasan Biaya & Invoice -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Ringkasan Biaya
                            </h2>
                        </div>
                        <div class="p-6">
                            @if($invoiceSummary)
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Total Tagihan</p>
                                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($invoiceSummary['total'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Sudah Dibayar</span>
                                        <span class="font-medium text-green-600">Rp {{ number_format($invoiceSummary['paid'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Sisa Tagihan</span>
                                        <span class="font-medium text-red-600">Rp {{ number_format($invoiceSummary['remaining'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="pt-3 border-t">
                                        <span class="px-3 py-1 text-sm font-medium rounded-full
                                            {{ $invoiceSummary['status'] === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $invoiceSummary['status'] }}
                                        </span>
                                    </div>
                                    <a href="{{ route('invoices.show', $invoiceSummary['invoice_id']) }}" 
                                       class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                        Lihat Invoice
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm">Invoice belum tersedia.</p>
                                    <p class="text-xs text-gray-400 mt-1">Invoice akan dibuat setelah event dikonfirmasi.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900">Aksi Cepat</h2>
                        </div>
                        <div class="p-4 space-y-2">
                            <a href="{{ route('client.requests.show', $activeRequest) }}" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Lihat Detail Booking
                            </a>
                            @if($activeRequest->event)
                            <a href="{{ route('events.show', $activeRequest->event) }}" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Lihat Event
                            </a>
                            @endif
                            <a href="{{ route('public.booking.form') }}" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Booking Baru
                            </a>
                        </div>
                    </div>

                    <!-- Wedding Couple Info (if applicable) -->
                    @if(($activeRequest->event_type === 'Wedding' || $activeRequest->event_type === 'Engagement'))
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-pink-50 to-rose-50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Data Pasangan
                            </h2>
                        </div>
                        <div class="p-6">
                            @if($activeRequest->cpp_name || $activeRequest->cpw_name)
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Calon Pengantin Pria</p>
                                        <p class="font-medium">{{ $activeRequest->cpp_name ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Calon Pengantin Wanita</p>
                                        <p class="font-medium">{{ $activeRequest->cpw_name ?: '-' }}</p>
                                    </div>
                                </div>
                            @elseif($activeRequest->fill_couple_later)
                                <div class="text-center py-4">
                                    <p class="text-sm text-yellow-600">Data pasangan belum diisi.</p>
                                    <button class="mt-2 text-sm text-blue-600 hover:underline">Isi Sekarang</button>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 text-center py-4">Belum ada data pasangan.</p>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- All Requests History -->
            @if($allRequests->count() > 1)
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Riwayat Booking</h2>
                </div>
                <ul class="divide-y divide-gray-200">
                    @foreach($allRequests->skip(1)->take(5) as $request)
                    <li class="hover:bg-gray-50 transition">
                        <a href="{{ route('client.requests.show', $request) }}" class="block px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $request->event_type }}</p>
                                    <p class="text-sm text-gray-500">{{ $request->event_date->format('d M Y') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $request->status_badge_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->detailed_status)) }}
                                </span>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

        @else
            <!-- No Active Request -->
            <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-200">
                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada booking</h3>
                <p class="mt-2 text-sm text-gray-500">Mulai perencanaan event impian Anda sekarang!</p>
                <div class="mt-6">
                    <a href="{{ route('public.booking.form') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Booking Sekarang
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function approveItem(itemId, category, price) {
    Swal.fire({
        title: 'Setujui Rekomendasi?',
        html: `Apakah Anda menyetujui <strong>${category}</strong> dengan harga <strong>Rp ${price.toLocaleString('id-ID')}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/portal/recommendation-items/${itemId}/respond`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const action = document.createElement('input');
            action.type = 'hidden';
            action.name = 'action';
            action.value = 'approve';
            form.appendChild(action);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
