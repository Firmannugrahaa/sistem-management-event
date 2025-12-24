@props(['event'])

@php
    $package = $event->eventPackage;
    $packageItems = $package ? $package->items : collect();
@endphp

@if(!$package)
    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800 p-6">
        <p class="text-yellow-800 dark:text-yellow-200">⚠️ Package data not found for this event.</p>
    </div>
@else

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
            <svg class="w-6 h-6 text-[#012A4A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Paket yang Dipilih
        </h3>
    </div>
    
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-750 rounded-xl p-6 mb-6 border-2 border-[#012A4A]/20">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-12 h-12 bg-[#012A4A] text-white rounded-lg flex items-center justify-center text-xl font-bold">
                    📦
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama Paket</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $package->name }}</h4>
                    @if($package->event_type)
                        <span class="inline-block mt-1 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            {{ ucfirst($package->event_type) }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Harga Paket</p>
                <p class="text-3xl font-bold text-[#012A4A] dark:text-blue-400">
                    Rp {{ number_format($package->final_price, 0, ',', '.') }}
                </p>
                @if($package->discount_percentage > 0)
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                        Hemat {{ $package->discount_percentage }}%
                    </p>
                @endif
            </div>
        </div>
        
        @if($package->description)
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $package->description }}</p>
            </div>
        @endif
    </div>
    
    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Paket ini sudah termasuk:
        </p>
        
        @if($packageItems->count() > 0)
            <ul class="space-y-2">
                @foreach($packageItems as $item)
                    <li class="flex items-start gap-3 text-sm text-gray-700 dark:text-gray-300 py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item->item_name }}
                                        @if($item->quantity > 1)
                                            <span class="text-gray-500 text-xs ml-1">({{ $item->quantity }}x)</span>
                                        @endif
                                    </p>
                                    @if($item->vendorCatalogItem && $item->vendorCatalogItem->vendor)
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs rounded">
                                            {{ $item->vendorCatalogItem->vendor->brand_name ?? $item->vendorCatalogItem->vendor->name }}
                                        </span>
                                    @endif
                                    @if($item->vendorCatalogItem && $item->vendorCatalogItem->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $item->vendorCatalogItem->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-center py-4">Belum ada detail item paket.</p>
        @endif
    </div>
    
    @if($package->features && count($package->features) > 0)
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">🌟 Fitur Tambahan:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                @foreach($package->features as $feature)
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $feature }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endif
