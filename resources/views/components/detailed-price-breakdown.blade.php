@props(['vendorSummary'])

@php
    $groupedVendors = collect($vendorSummary['vendors'])->groupBy('category');
    $total = $vendorSummary['total'];
@endphp

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
            <svg class="w-6 h-6 text-[#012A4A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Rincian Biaya Event
        </h3>
    </div>
    
    @if(count($vendorSummary['vendors']) > 0)
        <div class="space-y-6">
            @foreach ($groupedVendors as $category => $vendors)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <h4 class="text-sm font-semibold text-[#012A4A] dark:text-blue-400 mb-3">{{ $category }}</h4>
                    
                    @foreach($vendors as $vendor)
                        <div class="mb-3 last:mb-0">
                            <!-- Vendor Main Service -->
                            <div class="flex justify-between items-start py-2">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $vendor['name'] }}</p>
                                    @php
                                        $sourceIcons = [
                                            'package' => '📦',
                                            'recommendation' => '💡',
                                            'client_choice' => '👤',
                                            'manual' => '✏️'
                                        ];
                                    @endphp
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $sourceIcons[$vendor['source']] ?? '' }} 
                                        {{ ucfirst(str_replace('_', ' ', $vendor['source'])) }}
                                    </p>
                                </div>
                                <p class="font-semibold text-gray-900 dark:text-gray-100 ml-4">
                                    Rp {{ number_format($vendor['base_price'] ?? $vendor['subtotal'], 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <!-- Vendor Add-on Items -->
                            @if(count($vendor['items']) > 0)
                                <div class="ml-6 mt-2 space-y-1">
                                    @foreach($vendor['items'] as $item)
                                        <div class="flex justify-between items-start text-sm text-gray-600 dark:text-gray-400 py-1">
                                            <span class="flex-1">
                                                + {{ $item['name'] }}
                                                @if($item['quantity'] > 1)
                                                    <span class="text-xs">({{ $item['quantity'] }}x)</span>
                                                @endif
                                            </span>
                                            <span class="ml-4">
                                                Rp {{ number_format($item['total_price'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        
        {{-- External Vendors --}}
        @if(count($vendorSummary['external_vendors']) > 0)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h4 class="text-sm font-semibold text-[#012A4A] dark:text-blue-400 mb-3">🏪 Vendor Eksternal</h4>
                <div class="space-y-2">
                    @foreach($vendorSummary['external_vendors'] as $ext)
                        <div class="flex justify-between items-center py-2 text-sm">
                            <div>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $ext['name'] }}</span>
                                <span class="ml-2 text-xs text-gray-500">({{ $ext['category'] }})</span>
                            </div>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($ext['price'], 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        {{-- Non-Partner Charges --}}
        @if(count($vendorSummary['non_partner_charges']) > 0)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h4 class="text-sm font-semibold text-[#012A4A] dark:text-blue-400 mb-3">💳 Biaya Tambahan</h4>
                <div class="space-y-2">
                    @foreach($vendorSummary['non_partner_charges'] as $charge)
                        <div class="flex justify-between items-center py-2 text-sm">
                            <span class="text-gray-700 dark:text-gray-300">{{ $charge['description'] }}</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($charge['amount'], 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Total -->
        <div class="border-t-2 border-gray-300 dark:border-gray-600 pt-4 mt-6">
            <div class="flex justify-between items-center">
                <p class="text-lg font-bold text-gray-900 dark:text-gray-100">TOTAL KESELURUHAN</p>
                <p class="text-3xl font-bold text-[#012A4A] dark:text-blue-400">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </p>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <p class="text-gray-500 dark:text-gray-400">Belum ada vendor/layanan ditugaskan</p>
        </div>
    @endif
</div>
