@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="packageModal()">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Indicator (sama dengan Step 1) -->
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
                <p class="text-sm text-gray-600">Step 2 of 4: <strong>Pilih Paket Event</strong></p>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pilih Paket Event</h1>
            <p class="text-gray-600 mt-2">Klik card untuk melihat detail lengkap dan pilih paket yang sesuai</p>
        </div>

        <!-- Package Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @forelse($packages as $package)
                <div class="bg-white rounded-xl border-2 border-gray-200 hover:border-blue-500 hover:shadow-lg transition-all cursor-pointer overflow-hidden group"
                     @click="openModal({{ $package->id }})">
                    
                    <!-- Image -->
                    <div class="relative h-48 bg-gray-100">
                        @if($package->image_url)
                            <img src="{{ asset('storage/' . $package->image_url) }}" 
                                 alt="{{ $package->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <span class="text-6xl">🎉</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-3 right-3 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                            {{ $package->items->count() }} Items
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition">{{ $package->name }}</h3>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $package->description ?? 'Paket lengkap untuk event Anda' }}</p>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                            <span class="text-sm text-blue-600 font-medium group-hover:underline">Lihat Detail →</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden data for modal -->
                <div x-ref="package_{{ $package->id }}" class="hidden">
                    <div data-id="{{ $package->id }}" 
                         data-name="{{ $package->name }}" 
                         data-price="{{ $package->price }}"
                         data-description="{{ $package->description }}"
                         data-image="{{ $package->image_url ? asset('storage/' . $package->image_url) : '' }}"
                         data-items='@json($package->items->map(fn($i) => ["name" => $i->item_name, "qty" => $i->quantity]))'>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-yellow-50 border border-yellow-200 rounded-xl p-12 text-center">
                    <svg class="w-16 h-16 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-yellow-900 mb-2">Belum Ada Paket Tersedia</h3>
                    <p class="text-yellow-700 mb-4">Silakan pilih mode Custom untuk memilih vendor secara manual.</p>
                    <a href="{{ route('client.booking.start') }}" class="inline-block px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-semibold">
                        Kembali ke Pilih Mode
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Back Button -->
        <div class="flex justify-between items-center">
            <a href="{{ route('client.booking.start') }}" class="text-gray-600 hover:text-gray-900 font-medium">← Kembali</a>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" 
         x-cloak
         @keydown.escape.window="showModal = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showModal = false"></div>
        
        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div @click.away="showModal = false" 
                 class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                <!-- Close Button -->
                <button @click="showModal = false" class="absolute top-4 right-4 z-10 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-100 transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Image -->
                <div class="h-56 bg-gradient-to-br from-blue-500 to-purple-600 relative">
                    <img x-show="selectedPackage.image" :src="selectedPackage.image" :alt="selectedPackage.name" class="w-full h-full object-cover"
                         @error="$el.style.display='none'">
                    <div x-show="!selectedPackage.image" class="flex items-center justify-center h-full">
                        <span class="text-8xl">🎉</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8 max-h-[calc(90vh-14rem)] overflow-y-auto">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2" x-text="selectedPackage.name"></h2>
                    <p class="text-3xl font-bold text-blue-600 mb-6">Rp <span x-text="Number(selectedPackage.price).toLocaleString('id-ID')"></span></p>

                    <!-- Description -->
                    <div class="mb-6" x-show="selectedPackage.description">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Deskripsi</h3>
                        <p class="text-gray-700" x-text="selectedPackage.description"></p>
                    </div>

                    <!-- Include Items -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Termasuk dalam Paket</h3>
                        <div class="grid grid-cols-1 gap-2">
                            <template x-for="item in selectedPackage.items" :key="item.name">
                                <div class="flex items-start bg-gray-50 p-3 rounded-lg">
                                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="text-gray-900 font-medium" x-text="item.name"></span>
                                        <span class="text-gray-600 text-sm ml-1">(<span x-text="item.qty"></span>x)</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Select Button -->
                    <form method="POST" action="{{ route('client.booking.package.select') }}">
                        @csrf
                        <input type="hidden" name="package_id" :value="selectedPackage.id">
                        <button type="submit" class="w-full px-6 py-4 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg">
                            Pilih Paket Ini
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function packageModal() {
    return {
        showModal: false,
        selectedPackage: {
            id: null,
            name: '',
            price: 0,
            description: '',
            image: '',
            items: []
        },
        
        openModal(packageId) {
            const packageData = this.$refs['package_' + packageId].querySelector('[data-id]');
            
            this.selectedPackage = {
                id: packageData.dataset.id,
                name: packageData.dataset.name,
                price: packageData.dataset.price,
                description: packageData.dataset.description,
                image: packageData.dataset.image,
                items: JSON.parse(packageData.dataset.items)
            };
            
            this.showModal = true;
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
</style>
@endsection
