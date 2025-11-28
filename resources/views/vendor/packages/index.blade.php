<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
                {{ __('Paket Layanan') }}
            </h2>
            <a href="{{ route('vendor.packages.create') }}" class="px-4 py-2 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Paket
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($packages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($packages as $package)
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition group">
                            <!-- Thumbnail -->
                            <div class="relative aspect-video bg-gradient-to-br from-[#012A4A] to-[#013d70] overflow-hidden">
                                @if($package->thumbnail_path)
                                    <img src="{{ asset('storage/' . $package->thumbnail_path) }}" 
                                         alt="{{ $package->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white">
                                        <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Visibility Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($package->is_visible)
                                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">Tampil</span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-500 text-white text-xs font-bold rounded-full">Tersembunyi</span>
                                    @endif
                                </div>

                                <!-- Savings Badge -->
                                @if($package->savings > 0)
                                    <div class="absolute top-3 left-3">
                                        <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full">
                                            Hemat {{ $package->savings_percentage }}%
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2">{{ $package->name }}</h3>
                                
                                @if($package->description)
                                    <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $package->description }}</p>
                                @endif

                                <!-- Services Count -->
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span>{{ $package->services->count() }} Layanan</span>
                                </div>

                                <!-- Price -->
                                <div class="mb-4">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-2xl font-bold text-[#27AE60]">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                    </div>
                                    @if($package->individual_price > $package->price)
                                        <div class="text-xs text-gray-500 line-through">
                                            Rp {{ number_format($package->individual_price, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2 pt-4 border-t border-gray-100">
                                    <a href="{{ route('vendor.packages.edit', $package) }}" 
                                       class="flex-1 text-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition text-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('vendor.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Hapus paket ini?');" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-4 py-2 bg-red-50 text-red-600 rounded-lg font-medium hover:bg-red-100 transition text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $packages->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl shadow-sm">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Paket</h3>
                    <p class="text-gray-500 mb-6">Buat paket layanan untuk menarik lebih banyak customer.</p>
                    <a href="{{ route('vendor.packages.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Paket Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
