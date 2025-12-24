@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        {{-- SUCCESS / ERROR MESSAGES --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- HERO SECTION: EVENT READY / COMPLETED --}}
        @if($activeRequest && $activeRequest->detailed_status === 'completed')
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-xl relative overflow-hidden">
             <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-wider">Event Completed</span>
                    <span class="text-indigo-200">|</span>
                    <span class="text-sm font-medium">{{ optional($activeRequest->event->start_time)->format('d M Y') ?? '-' }}</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-4">Terima Kasih, {{ Auth::user()->name }}! 🎉</h1>
                <p class="text-indigo-100 text-lg max-w-2xl">
                    Acara <strong>{{ $activeRequest->event_type }}</strong> Anda telah terlaksana dengan sukses. 
                    Kami sangat senang bisa menjadi bagian dari momen spesial Anda.
                </p>
                <div class="mt-6 flex gap-4">
                    <a href="#rating-section" class="bg-white text-indigo-600 hover:bg-indigo-50 font-bold py-2 px-6 rounded-lg shadow-md transition">
                        Beri Ulasan
                    </a>
                    <a href="{{ route('invoice.show', $activeRequest->event->invoice) }}" class="bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded-lg transition border border-indigo-500">
                        Lihat Invoice
                    </a>
                </div>
            </div>
             {{-- Decorative bg elements --}}
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-purple-500/20 rounded-full blur-2xl"></div>
        </div>

        {{-- RATING SECTION --}}
        @if(isset($vendorsToReview) && count($vendorsToReview) > 0)
        <div id="rating-section" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                <span class="mr-2">⭐</span> Berikan Ulasan Vendor
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6 text-sm">
                Bantu kami meningkatkan kualitas layanan dengan memberikan ulasan untuk vendor yang bertugas.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($vendorsToReview as $vendor)
                <div x-data="{ openRating: false, rating: 5 }" class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $vendor->brand_name ?? $vendor->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $vendor->serviceType->name ?? $vendor->category }}</p>
                        </div>
                        <button @click="openRating = !openRating" class="text-indigo-600 text-sm font-semibold hover:underline">
                            Review
                        </button>
                    </div>

                    <div x-show="openRating" x-collapse class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <form action="{{ route('client.reviews.store', $activeRequest->event) }}" method="POST">
                            @csrf
                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                            
                            {{-- Star Rating --}}
                            <div class="flex flex-col gap-1 mb-3">
                                <label class="text-xs font-semibold text-gray-600">Rating:</label>
                                <div class="flex gap-1">
                                    <template x-for="i in 5">
                                        <button type="button" @click="rating = i" class="focus:outline-none transition transform hover:scale-110">
                                            <svg :class="{'text-yellow-400 fill-current': rating >= i, 'text-gray-300': rating < i}" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                                <input type="hidden" name="rating" x-model="rating">
                            </div>

                            <div class="mb-3">
                                <label class="text-xs font-semibold text-gray-600">Komentar:</label>
                                <textarea name="comment" rows="2" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis pengalaman Anda..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-gray-900 text-white text-sm py-2 rounded-lg hover:bg-black transition">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endif
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

                    <!-- Block: Ringkasan Pesanan (NEW) -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Ringkasan Pesanan
                            </h2>
                        </div>
                        <div class="p-6">
                            @php
                                // Try to load eventPackage if not loaded
                                if ($activeRequest->event_package_id && !$activeRequest->relationLoaded('eventPackage')) {
                                    $activeRequest->load('eventPackage', 'eventPackage.items');
                                }
                                
                                $hasPackage = $activeRequest->event_package_id !== null && $activeRequest->eventPackage !== null;
                                $hasVendor = $activeRequest->vendor_id !== null && !$hasPackage;
                                $packagePrice = $hasPackage ? ($activeRequest->eventPackage->final_price ?? 0) : 0;
                                $totalEstimasi = $activeRequest->total_price ?? $packagePrice;
                            @endphp

                            @if($hasPackage)
                                {{-- Package Selected --}}
                                <div class="flex items-start gap-4 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <span class="text-2xl">📦</span>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-xs text-indigo-600 font-semibold uppercase">Paket Dipilih</span>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $activeRequest->eventPackage->name }}</h3>
                                        <p class="text-xl font-bold text-indigo-600 mt-1">Rp {{ number_format($packagePrice, 0, ',', '.') }}</p>
                                        @if($activeRequest->eventPackage->items && $activeRequest->eventPackage->items->count() > 0)
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $activeRequest->eventPackage->items->count() }} layanan termasuk
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @elseif($hasVendor)
                                {{-- Custom/Susun Sendiri --}}
                                <div class="flex items-start gap-4 p-4 bg-green-50 rounded-lg border border-green-100">
                                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <span class="text-2xl">🏢</span>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-xs text-green-600 font-semibold uppercase">Vendor Pilihan</span>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $activeRequest->vendor->brand_name }}</h3>
                                        @if($activeRequest->vendor->serviceType)
                                            <p class="text-sm text-gray-500">{{ $activeRequest->vendor->serviceType->name }}</p>
                                        @endif
                                        @if($totalEstimasi > 0)
                                            <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($totalEstimasi, 0, ',', '.') }}</p>
                                        @else
                                            <p class="text-sm text-gray-400 mt-1 italic">Harga akan dikonfirmasi admin</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- No selection yet --}}
                                <div class="text-center py-6">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 font-medium">Konsultasi Diperlukan</p>
                                    <p class="text-sm text-gray-500 mt-1">Tim kami akan membantu menyusun paket terbaik</p>
                                </div>
                            @endif

                            {{-- Quick Link to Detail --}}
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <a href="{{ route('client.requests.show', $activeRequest) }}" 
                                   class="flex items-center justify-between text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    <span>Lihat detail lengkap pesanan</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Wedding Planner Checklist Block -->
                    @if($activeRequest->event_type == 'Wedding')
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-sm border border-green-200 p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">📋 Wedding Planner Checklist</h3>
                                    <p class="text-sm text-gray-600">Pantau persiapan pernikahan Anda langkah demi langkah</p>
                                </div>
                            </div>
                            <a href="{{ route('client.checklist', $activeRequest) }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold shadow-sm whitespace-nowrap flex items-center">
                                Lihat Checklist
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endif

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
                                    <div class="space-y-3">
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-500 mb-1">Total Tagihan</p>
                                            <p class="text-3xl font-bold text-gray-900">
                                                <span class="text-lg align-top text-gray-500 font-normal">Rp</span> 
                                                {{ number_format($invoiceSummary['total'], 0, ',', '.') }}
                                            </p>
                                        </div>
                                        
                                        @if(isset($invoiceSummary['discount']) && $invoiceSummary['discount'] > 0)
                                        <div class="flex justify-between text-green-600">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                                Voucher / Diskon
                                            </span>
                                            <span class="font-bold">- Rp {{ number_format($invoiceSummary['discount'], 0, ',', '.') }}</span>
                                        </div>
                                        @endif
                                        
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Sudah Dibayar</span>
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
                                    <a href="{{ route('invoice.show', $invoiceSummary['invoice_id']) }}" 
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
                    @if(in_array($activeRequest->event_type, ['Wedding', 'Engagement']))
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
                            @php
                                // Check both field variations
                                $groomName = $activeRequest->groom_name ?: $activeRequest->cpp_name ?: null;
                                $brideName = $activeRequest->bride_name ?: $activeRequest->cpw_name ?: null;
                            @endphp
                            @if($groomName || $brideName)
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Calon Pengantin Pria</p>
                                        <p class="font-medium">{{ $groomName ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Calon Pengantin Wanita</p>
                                        <p class="font-medium">{{ $brideName ?: '-' }}</p>
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
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/client/recommendation-items/${itemId}/respond`;
            
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
