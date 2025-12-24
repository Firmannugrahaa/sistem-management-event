<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('client.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-gray-900 leading-tight">
                    {{ $clientRequest->event_type }}
                </h2>
                <div class="flex items-center text-sm text-gray-500 mt-1">
                    <span class="font-mono bg-gray-100 px-2 py-0.5 rounded text-gray-700 mr-2">#{{ str_pad($clientRequest->id, 6, '0', STR_PAD_LEFT) }}</span>
                    <span>{{ $clientRequest->event_date->format('d F Y') }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ $clientRequest->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ editModalOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- TIMELINE STATUS --}}
            @php
                $currentStatus = $clientRequest->effective_status;
                $steps = [
                    ['label' => 'Booking', 'active' => true],
                    ['label' => 'Diproses', 'active' => in_array($currentStatus, ['contacted', 'on_process', 'recommendation_sent', 'ready_to_confirm', 'confirmed', 'approved', 'done'])],
                    ['label' => 'Siap Konfirmasi', 'active' => in_array($currentStatus, ['ready_to_confirm', 'confirmed', 'approved', 'done'])],
                    ['label' => 'Confirmed', 'active' => in_array($currentStatus, ['confirmed', 'done'])],
                    ['label' => 'Event Dibuat', 'active' => $clientRequest->event !== null],
                ];
                
                // Determine highlight color based on cancelled
                $isCancelled = $currentStatus == 'rejected';
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                @if($isCancelled)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <p class="text-red-700 font-bold text-lg">Booking Dibatalkan</p>
                        <p class="text-red-600">Mohon hubungi admin untuk informasi lebih lanjut.</p>
                    </div>
                @else
                    <div class="relative">
                        <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 -translate-y-1/2 rounded-full -z-10"></div>
                        <div class="flex justify-between items-center relative z-10">
                            @foreach($steps as $index => $step)
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold ring-4 ring-white {{ $step['active'] ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-500' }}">
                                        @if($step['active'])
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <span class="mt-2 text-xs font-medium {{ $step['active'] ? 'text-blue-600' : 'text-gray-500' }}">{{ $step['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- LEF SIDE: MAIN DETAILS -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- ACTION BANNER (Dynamic based on status) -->
                    @if($currentStatus == 'new')
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-blue-900">Menunggu Review Admin</h3>
                                <p class="text-sm text-blue-700">Booking Anda telah diterima. Tim kami akan segera menghubungi Anda untuk verifikasi data.</p>
                            </div>
                            <button @click="editModalOpen = true" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm whitespace-nowrap">
                                Edit Data Minor
                            </button>
                        </div>
                    @elseif($currentStatus == 'on_process')
                         <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-blue-900">Sedang Diproses</h3>
                                <p class="text-sm text-blue-700">Kami sedang memproses dan mendiskusikan detail event Anda. Mohon tunggu update selanjutnya.</p>
                            </div>
                        </div>
                    @elseif($currentStatus == 'ready_to_confirm')
                         <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-yellow-900">Siap Dikonfirmasi ✓</h3>
                            <p class="text-sm text-yellow-700 mb-3">Semua data sudah lengkap. Silakan konfirmasi untuk melanjutkan ke event.</p>
                            <div class="bg-white p-3 rounded-lg">
                                <p class="text-xs text-gray-600">Admin akan segera mengkonfirmasi dan membuat event resmi untuk Anda.</p>
                            </div>
                        </div>
                    @elseif($currentStatus == 'confirmed')
                         <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-green-900">Event Telah Dikonfirmasi! 🎉</h3>
                            <p class="text-sm text-green-700">Event Anda telah dikonfirmasi dan siap dijalankan.</p>
                            @if($clientRequest->event)
                                <a href="{{ route('events.show', $clientRequest->event) }}" class="mt-3 inline-block px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                    Lihat Detail Event →
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- SECTION: REKOMENDASI DARI ADMIN (Always visible if exists) -->
                    @if($clientRequest->recommendations->where('status', 'sent')->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900">Rekomendasi dari Admin</h3>
                            <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold animate-pulse">
                                Action Required
                            </span>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-600 mb-4">Admin telah menyiapkan rekomendasi vendor sesuai dengan preferensi Anda.</p>
                            
                            <div class="grid gap-4">
                                @foreach($clientRequest->recommendations->where('status', 'sent') as $rec)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition relative group">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-lg">{{ $rec->title }}</h4>
                                            <p class="text-sm text-gray-500 mb-2">{{ $rec->items->count() }} item(s) • Total Est: <span class="text-gray-900 font-semibold">Rp {{ number_format($rec->total_estimated_budget, 0, ',', '.') }}</span></p>
                                            
                                            <!-- Status Items Summary -->
                                            <div class="flex space-x-2 mt-2">
                                                <span class="text-xs px-2 py-1 bg-gray-100 rounded text-gray-600">Pending: {{ $rec->items->where('status', 'pending')->count() }}</span>
                                                <span class="text-xs px-2 py-1 bg-green-100 rounded text-green-700">Accepted: {{ $rec->items->where('status', 'accepted')->count() }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('client.recommendations.show', $rec) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm">
                                            Lihat & Review
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- A. INFORMASI EVENT & DATA LENGKAP -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900">Informasi Event</h3>
                            <button @click="editModalOpen = true" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Edit Data</button>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs text-gray-500 uppercase tracking-wide font-semibold">Tipe Event</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $clientRequest->event_type }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 uppercase tracking-wide font-semibold">Tanggal & Waktu</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $clientRequest->event_date->format('d F Y') }}</p>
                                </div>
                                
                                @if($clientRequest->event_type == 'Wedding')
                                <div class="col-span-1 md:col-span-2 bg-pink-50 rounded-lg p-4 border border-pink-100">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="text-sm font-bold text-pink-800 uppercase">Couple Details</h4>
                                        @if(!$clientRequest->groom_name || !$clientRequest->bride_name)
                                            <span class="bg-pink-200 text-pink-800 text-xs px-2 py-0.5 rounded-full font-bold">Perlu Dilengkapi</span>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-xs text-pink-600">Groom (Pria)</span>
                                            <p class="font-semibold text-gray-900">{{ $clientRequest->groom_name ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-xs text-pink-600">Bride (Wanita)</span>
                                            <p class="font-semibold text-gray-900">{{ $clientRequest->bride_name ?? '-' }}</p>
                                        </div>
                                    </div>
                                    @if(!$clientRequest->groom_name || !$clientRequest->bride_name)
                                        <div class="mt-3 text-right">
                                            <button @click="editModalOpen = true" class="text-xs bg-pink-600 text-white px-3 py-1.5 rounded hover:bg-pink-700 transition">Lengkapi Sekarang</button>
                                        </div>
                                    @endif
                                </div>
                                @endif

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-xs text-gray-500 uppercase tracking-wide font-semibold">Catatan / Pesan</label>
                                    <div class="mt-1 bg-gray-50 p-3 rounded-lg text-sm text-gray-700">
                                        {{ $clientRequest->message ?: '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- B. VENDOR & PAKET -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Paket & Vendor</h3>
                        </div>
                        <div class="p-6">
                            @php
                                $hasPackage = $clientRequest->eventPackage !== null;
                                $hasDirectVendor = $clientRequest->vendor_id !== null;
                                $isConverted = $clientRequest->event !== null;
                                $hasAcceptedRecommendation = $clientRequest->recommendations->where('status', 'accepted')->first() !== null;
                            @endphp

                            {{-- Case 1: Event already created (Converted) --}}
                            @if($isConverted)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <p class="text-green-800 font-medium flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Booking Anda telah dikonfirmasi dan dikonversi menjadi Event
                                    </p>
                                    <a href="{{ route('events.show', $clientRequest->event) }}" class="mt-3 inline-block text-blue-600 font-medium text-sm hover:text-blue-800">
                                        Lihat Detail Event →
                                    </a>
                                </div>

                            {{-- Case 2: Client chose a package --}}
                            @elseif($hasPackage)
                                <div class="mb-4">
                                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                        <div class="flex items-start">
                                            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="font-bold text-blue-900">{{ $clientRequest->eventPackage->name }}</h4>
                                                <p class="text-sm text-blue-700 mt-1">{{ $clientRequest->eventPackage->description }}</p>
                                                @if($clientRequest->eventPackage->final_price > 0)
                                                    <p class="text-lg font-bold text-blue-900 mt-2">Rp {{ number_format($clientRequest->eventPackage->final_price, 0, ',', '.') }}</p>
                                                @endif
                                            </div>
                                            <span class="ml-3 bg-blue-200 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold">PAKET</span>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Vendor dalam Paket:
                                </h5>

                                @php
                                    // Group vendors by service type
                                    $vendorsByType = [];
                                    foreach ($clientRequest->eventPackage->items as $item) {
                                        $vendor = null;
                                        $serviceTypeName = null;
                                        $itemName = null;

                                        if ($item->vendorCatalogItem && $item->vendorCatalogItem->vendor) {
                                            $vendor = $item->vendorCatalogItem->vendor;
                                            $itemName = $item->vendorCatalogItem->name;
                                        } elseif ($item->vendorPackage && $item->vendorPackage->vendor) {
                                            $vendor = $item->vendorPackage->vendor;
                                            $itemName = $item->vendorPackage->name;
                                        }

                                        if ($vendor && $vendor->serviceType) {
                                            $serviceTypeName = $vendor->serviceType->name;
                                            if (!isset($vendorsByType[$serviceTypeName])) {
                                                $vendorsByType[$serviceTypeName] = [
                                                    'vendor' => $vendor,
                                                    'items' => []
                                                ];
                                            }
                                            $vendorsByType[$serviceTypeName]['items'][] = $itemName;
                                        }
                                    }
                                @endphp

                                <div class="space-y-3">
                                    @forelse($vendorsByType as $serviceType => $data)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="text-xs font-semibold text-gray-500 uppercase">{{ $serviceType }}</span>
                                                        <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-medium">Included</span>
                                                    </div>
                                                    <p class="font-semibold text-gray-900">{{ $data['vendor']->brand_name }}</p>
                                                    @if(count($data['items']) > 0)
                                                        <p class="text-sm text-gray-600 mt-1">
                                                            {{ implode(', ', array_slice($data['items'], 0, 2)) }}
                                                            @if(count($data['items']) > 2)
                                                                <span class="text-blue-600">+{{ count($data['items']) - 2 }} lainnya</span>
                                                            @endif
                                                        </p>
                                                    @endif
                                                </div>
                                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500 italic">Data vendor sedang dipersiapkan...</p>
                                    @endforelse
                                </div>

                                <div class="mt-4 bg-gray-50 rounded-lg p-3 text-xs text-gray-600">
                                    <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Vendor dalam paket ini sudah dikonfirmasi dan siap melayani event Anda. Admin akan menghubungi untuk finalisasi detail.
                                </div>

                            {{-- Case 3: Client chose a vendor directly --}}
                            @elseif($hasDirectVendor)
                                <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                @if($clientRequest->vendor->serviceType)
                                                    <span class="text-xs font-semibold text-gray-500 uppercase">{{ $clientRequest->vendor->serviceType->name }}</span>
                                                @endif
                                                <span class="bg-blue-200 text-blue-800 text-xs px-2 py-0.5 rounded-full font-medium">Vendor Pilihan Anda</span>
                                            </div>
                                            <p class="font-bold text-gray-900">{{ $clientRequest->vendor->brand_name }}</p>
                                            @if($clientRequest->vendor->city)
                                                <p class="text-sm text-gray-600 mt-1">{{ $clientRequest->vendor->city }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 bg-gray-50 rounded-lg p-3 text-xs text-gray-600">
                                    <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Tim kami akan menghubungi vendor pilihan Anda dan kategori vendor lainnya untuk event Anda.
                                </div>

                            {{-- Case 4: Client accepted admin recommendation --}}
                            @elseif($hasAcceptedRecommendation)
                                @php $acceptedRec = $clientRequest->recommendations->where('status', 'accepted')->first(); @endphp
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <p class="text-green-800 font-medium flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Proposal yang Disetujui: {{ $acceptedRec->title }}
                                    </p>
                                    <p class="text-sm text-green-700 mt-2">Admin sedang mempersiapkan detail vendor untuk event Anda.</p>
                                </div>

                            {{-- Case 5: Nothing selected - consultation needed --}}
                            @else
                                <div class="text-center py-12">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Konsultasi Diperlukan</h4>
                                    <p class="text-gray-600 max-w-md mx-auto mb-4">
                                        Tim kami akan menghubungi Anda untuk membahas pilihan vendor terbaik sesuai kebutuhan dan budget event Anda.
                                    </p>
                                    <div class="inline-flex items-center gap-2 text-sm text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Status: Menunggu konsultasi dengan admin
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDE: SIDEBAR -->
                <div class="space-y-6">
                    
                    <!-- ESTIMASI BIAYA (UX Redesign) -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-900 uppercase">Estimasi Biaya</h3>
                        </div>
                        <div class="p-5">
                            @php
                                // Determine what's selected
                                $hasPackage = $clientRequest->eventPackage !== null;
                                $packagePrice = $hasPackage ? ($clientRequest->eventPackage->final_price ?? 0) : 0;
                                
                                // Calculate total from available data
                                $totalEstimasi = $clientRequest->total_price ?? $packagePrice ?? 0;
                                $budgetAwal = $clientRequest->budget ?? 0;
                                $isOverBudget = $totalEstimasi > $budgetAwal && $budgetAwal > 0;
                            @endphp

                            {{-- TOTAL ESTIMASI - Prominent at Top --}}
                            <div class="text-center mb-5 pb-4 border-b border-gray-100">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Total Estimasi</p>
                                @if($totalEstimasi > 0)
                                    <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($totalEstimasi, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-xl font-medium text-gray-400">Belum tersedia</p>
                                    <p class="text-xs text-gray-400 mt-1">Menunggu konfirmasi admin</p>
                                @endif
                            </div>

                            {{-- BREAKDOWN - Conditional based on selection --}}
                            <div class="space-y-3 mb-4">
                                @if($hasPackage)
                                    {{-- Package Selected --}}
                                    <div class="flex items-start justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                                        <div class="flex items-start gap-2">
                                            <span class="text-lg">📦</span>
                                            <div>
                                                <p class="text-xs text-blue-600 font-semibold uppercase">Paket</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $clientRequest->eventPackage->name }}</p>
                                            </div>
                                        </div>
                                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($packagePrice, 0, ',', '.') }}</p>
                                    </div>
                                @endif

                                @if($clientRequest->vendor_id && $clientRequest->vendor)
                                    {{-- Direct Vendor Selected (Susun Sendiri) --}}
                                    <div class="flex items-start justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                                        <div class="flex items-start gap-2">
                                            <span class="text-lg">🏢</span>
                                            <div>
                                                <p class="text-xs text-green-600 font-semibold uppercase">Vendor Utama</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $clientRequest->vendor->brand_name }}</p>
                                                @if($clientRequest->vendor->serviceType)
                                                    <p class="text-xs text-gray-500">{{ $clientRequest->vendor->serviceType->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-400 italic">Harga menyusul</p>
                                    </div>
                                @endif

                                {{-- Accepted Recommendation Items --}}
                                @php
                                    $acceptedRec = $clientRequest->recommendations->where('status', 'accepted')->first();
                                    $acceptedItems = $acceptedRec ? $acceptedRec->items->where('status', 'accepted') : collect([]);
                                @endphp
                                
                                @if($acceptedItems->count() > 0)
                                    <div class="p-3 bg-purple-50 rounded-lg border border-purple-100">
                                        <div class="flex items-start gap-2 mb-2">
                                            <span class="text-lg">✨</span>
                                            <div>
                                                <p class="text-xs text-purple-600 font-semibold uppercase">Rekomendasi Disetujui</p>
                                                <p class="text-xs text-gray-500">{{ $acceptedItems->count() }} item</p>
                                            </div>
                                        </div>
                                        <div class="space-y-1 ml-7">
                                            @foreach($acceptedItems->take(3) as $item)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-700">{{ $item->vendor->brand_name ?? 'Vendor' }}</span>
                                                    <span class="text-gray-900 font-medium">Rp {{ number_format($item->estimated_price ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                            @if($acceptedItems->count() > 3)
                                                <p class="text-xs text-purple-600">+{{ $acceptedItems->count() - 3 }} item lainnya</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Empty State --}}
                                @if(!$hasPackage && !$clientRequest->vendor_id && $acceptedItems->count() === 0)
                                    <div class="text-center py-4">
                                        <p class="text-sm text-gray-500">Belum ada layanan dipilih</p>
                                        <p class="text-xs text-gray-400 mt-1">Admin akan membantu menyusun estimasi</p>
                                    </div>
                                @endif
                            </div>

                            {{-- BUDGET REFERENCE --}}
                            <div class="pt-3 border-t border-gray-100">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Budget Awal Anda</span>
                                    <span class="font-medium {{ $isOverBudget ? 'text-red-600' : 'text-gray-700' }}">
                                        Rp {{ number_format($budgetAwal, 0, ',', '.') }}
                                    </span>
                                </div>
                                @if($isOverBudget && $totalEstimasi > 0)
                                    <div class="mt-2 flex items-center gap-1 text-xs text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <span>Melebihi budget Rp {{ number_format($totalEstimasi - $budgetAwal, 0, ',', '.') }}</span>
                                    </div>
                                @elseif($totalEstimasi > 0 && $budgetAwal > 0)
                                    <div class="mt-2 flex items-center gap-1 text-xs text-green-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>Masih dalam budget</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- UPLOAD DOKUMEN (UI ONLY) -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-900 uppercase">Dokumen</h3>
                        </div>
                        <div class="p-5">
                            <ul class="space-y-3">
                                <li class="flex items-center justify-between text-sm">
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Invoice
                                    </div>
                                    <span class="text-xs text-gray-400">Belum ada</span>
                                </li>
                                <li class="flex items-center justify-between text-sm">
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Upload KTP
                                    </div>
                                    <button class="text-blue-600 hover:underline text-xs">Upload</button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- BANTUAN -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Butuh Bantuan?</h3>
                        <p class="text-xs text-gray-600 mb-4">Hubungi kami jika ada pertanyaan mendesak.</p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="block w-full py-2 bg-green-50 text-green-700 rounded-lg text-center text-sm font-medium hover:bg-green-100 transition flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.6 1.953.229 3.618 1.195 5.244l.872.29-1.298 4.742 4.881-1.281.821.306zm14.087-9.158c-.288-.144-1.706-.842-1.968-.938-.262-.096-.452-.144-.645.144-.193.288-.742.938-.91.1129-.168.191-.31.191-.625.048-.362-.163.662-1.51.57-2.09-.964-.194-.691-.362-1.156-.475-1.587-.144-.545-.048-1.036.044-1.353.196.262.244.595.66.862.464.298 2.204.605 3.179 1.458.073.064.083.187.057.282-.144 1.152-.093 2.067-.024 2.871-.058.675-.383 1.179-.817 1.439z"/></svg>
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Safe Edit Modal (Alpine) -->
        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="editModalOpen = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('client.requests.update', $clientRequest) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Hidden required fields as per controller validation -->
                        <input type="hidden" name="client_name" value="{{ $clientRequest->client_name }}">
                        <input type="hidden" name="client_email" value="{{ $clientRequest->client_email }}">
                        <input type="hidden" name="client_phone" value="{{ $clientRequest->client_phone }}">
                        <input type="hidden" name="event_date" value="{{ $clientRequest->event_date }}">
                        <input type="hidden" name="event_type" value="{{ $clientRequest->event_type }}">
                        <input type="hidden" name="status" value="{{ $clientRequest->status }}">

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Data Event</h3>
                            <div class="mt-4 space-y-4">
                                @if($clientRequest->event_type == 'Wedding')
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Mempelai Pria</label>
                                        <input type="text" name="groom_name" value="{{ $clientRequest->groom_name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Mempelai Wanita</label>
                                        <input type="text" name="bride_name" value="{{ $clientRequest->bride_name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    </div>
                                </div>
                                <div class="bg-yellow-50 p-3 rounded text-xs text-yellow-700">
                                    Informasi ini akan digunakan untuk undangan dan keperluan vendor.
                                </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                                    <textarea name="message" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">{{ $clientRequest->message }}</textarea>
                                </div>
                                
                                <div class="text-xs text-gray-500">
                                    *Untuk perubahan tanggal atau tipe event, silakan hubungi admin secara langsung.
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Perubahan
                            </button>
                            <button @click="editModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
