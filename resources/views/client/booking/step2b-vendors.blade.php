@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="gallerySelector()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white font-bold">✓</div>
                    <div class="w-20 h-1 bg-green-500 mx-2"></div>
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-bold">2</div>
                    <div class="w-20 h-1 bg-gray-300 mx-2"></div>
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-500 font-bold">3</div>
                    <div class="w-20 h-1 bg-gray-300 mx-2"></div>
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-500 font-bold">4</div>
                </div>
            </div>
            <div class="text-center mt-3">
                <p class="text-sm text-gray-600">Step 2 of 4: <strong>Pilih Produk & Layanan</strong></p>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pilih Produk & Layanan</h1>
            <p class="text-gray-600 mt-2">Klik foto untuk melihat detail dan pilih yang sesuai dengan kebutuhan Anda</p>
        </div>

        <form method="POST" action="{{ route('client.booking.vendors.select') }}" id="vendor-form">
            @csrf
            
            <!-- Categories -->
            @forelse($catalogItems as $category => $items)
                @php
                    $isSingleSelect = in_array($category, $singleSelectCategories);
                @endphp
                
                <div class="mb-10">
                    <!-- Category Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $category }}</h2>
                            @if($isSingleSelect)
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Pilih 1 saja</span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Bisa pilih banyak</span>
                            @endif
                        </div>
                        <span class="text-sm text-gray-500">{{ $items->count() }} pilihan</span>
                    </div>
                    
                    <!-- Horizontal Scroll Gallery -->
                    <div class="relative">
                        <div class="flex gap-4 overflow-x-auto pb-4 scroll-smooth hide-scrollbar" id="gallery-{{ Str::slug($category) }}">
                            @foreach($items as $item)
                                <div class="flex-shrink-0 w-64 relative group cursor-pointer"
                                     @click="toggleItem({{ $item['id'] }}, '{{ $category }}', {{ $isSingleSelect ? 'true' : 'false' }})">
                                    
                                    <!-- Hidden Input -->
                                    <input type="checkbox" 
                                           name="items[]" 
                                           value="{{ $item['id'] }}" 
                                           class="hidden"
                                           :checked="selectedItems.includes({{ $item['id'] }})">
                                    
                                    <!-- Image Card -->
                                    <div class="relative h-48 rounded-xl overflow-hidden border-2 transition-all duration-300"
                                         :class="selectedItems.includes({{ $item['id'] }}) ? 'border-blue-600 ring-4 ring-blue-200' : 'border-transparent'">
                                        
                                        @if($item['image'])
                                            <img src="{{ $item['image'] }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="w-full h-full object-cover transition duration-300 group-hover:scale-110">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Selected Badge -->
                                        <div class="absolute top-3 right-3 transition-all duration-200"
                                             :class="selectedItems.includes({{ $item['id'] }}) ? 'opacity-100 scale-100' : 'opacity-0 scale-75'">
                                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Hover Overlay with Info -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                                            <h3 class="text-white font-bold text-lg mb-1">{{ $item['name'] }}</h3>
                                            <p class="text-white/80 text-sm mb-2 line-clamp-2">{{ $item['description'] ?? 'Produk berkualitas' }}</p>
                                            <p class="text-white font-bold">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                            <p class="text-white/60 text-xs mt-1">{{ $item['vendor_name'] }}</p>
                                            
                                            <!-- Select Button -->
                                            <button type="button" 
                                                    class="mt-3 w-full py-2 rounded-lg font-medium transition text-sm"
                                                    :class="selectedItems.includes({{ $item['id'] }}) ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-white hover:bg-blue-50 text-blue-600'">
                                                <span x-text="selectedItems.includes({{ $item['id'] }}) ? 'Batalkan ✕' : 'Pilih ✓'"></span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Label below image -->
                                    <div class="mt-2 text-center">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $item['name'] }}</p>
                                        <p class="text-xs text-gray-500">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Scroll Arrows (optional for desktop) -->
                        @if($items->count() > 4)
                            <button type="button" 
                                    onclick="document.getElementById('gallery-{{ Str::slug($category) }}').scrollBy({left: -300, behavior: 'smooth'})"
                                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white shadow-lg rounded-full flex items-center justify-center hover:bg-gray-100 transition hidden md:flex z-10">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button type="button"
                                    onclick="document.getElementById('gallery-{{ Str::slug($category) }}').scrollBy({left: 300, behavior: 'smooth'})"
                                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white shadow-lg rounded-full flex items-center justify-center hover:bg-gray-100 transition hidden md:flex z-10">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Produk Tersedia</h3>
                    <p class="text-gray-500">Silakan hubungi admin untuk informasi lebih lanjut.</p>
                </div>
            @endforelse

            @error('items')
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ $message }}
                </div>
            @enderror

            <!-- Selection Summary & Action Buttons -->
            <div class="mt-8 bg-white border border-gray-200 p-6 rounded-xl shadow-sm">
                <!-- Selected Items Summary -->
                <div class="mb-4" x-show="selectedItems.length > 0">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Pilihan Anda:</h3>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="itemId in selectedItems" :key="itemId">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                <span x-text="getItemName(itemId)"></span>
                                <button type="button" @click.stop="removeItem(itemId)" class="hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <a href="{{ route('client.booking.start') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                        ← Kembali
                    </a>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600">
                            <span class="font-bold text-blue-600" x-text="selectedItems.length"></span> item dipilih
                        </span>
                        <button type="submit" 
                                class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="selectedItems.length === 0">
                            Lanjutkan →
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function gallerySelector() {
    // Data items from server
    const itemsData = @json($catalogItems->flatten()->keyBy('id'));
    
    return {
        selectedItems: [],
        categorySelections: {}, // Track selections per category for single-select
        
        toggleItem(itemId, category, isSingleSelect) {
            const index = this.selectedItems.indexOf(itemId);
            
            if (index > -1) {
                // Deselect
                this.selectedItems.splice(index, 1);
                if (isSingleSelect) {
                    delete this.categorySelections[category];
                }
            } else {
                // Select
                if (isSingleSelect) {
                    // Remove previous selection in this category
                    const prevSelection = this.categorySelections[category];
                    if (prevSelection) {
                        const prevIndex = this.selectedItems.indexOf(prevSelection);
                        if (prevIndex > -1) {
                            this.selectedItems.splice(prevIndex, 1);
                        }
                    }
                    this.categorySelections[category] = itemId;
                }
                this.selectedItems.push(itemId);
            }
        },
        
        removeItem(itemId) {
            const index = this.selectedItems.indexOf(itemId);
            if (index > -1) {
                this.selectedItems.splice(index, 1);
            }
            // Also remove from category selections
            for (const cat in this.categorySelections) {
                if (this.categorySelections[cat] === itemId) {
                    delete this.categorySelections[cat];
                }
            }
        },
        
        getItemName(itemId) {
            return itemsData[itemId]?.name || 'Item ' + itemId;
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
@endsection
