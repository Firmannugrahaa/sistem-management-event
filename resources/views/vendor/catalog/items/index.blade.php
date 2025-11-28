<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
                {{ __('Katalog Produk / Inventaris') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('vendor.catalog.categories.index') }}" 
                   class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                    Kelola Kategori
                </a>
                <a href="{{ route('vendor.catalog.items.create') }}" 
                   class="px-4 py-2 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Produk
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($items->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($items as $item)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden group hover:shadow-md transition">
                            <div class="relative aspect-square bg-gray-100">
                                @if($item->coverImage)
                                    <img src="{{ asset('storage/' . $item->coverImage->image_path) }}" 
                                         alt="{{ $item->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="absolute top-2 right-2">
                                    @if($item->status === 'available')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">Available</span>
                                    @elseif($item->status === 'booked')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">Booked</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">Not Available</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <div class="text-xs text-gray-500 mb-1">{{ $item->category->name ?? 'Uncategorized' }}</div>
                                <h3 class="font-semibold text-gray-900 mb-1 truncate">{{ $item->name }}</h3>
                                <div class="text-[#27AE60] font-bold mb-3">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                
                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <a href="{{ route('vendor.catalog.items.edit', $item->id) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                    <form action="{{ route('vendor.catalog.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $items->links() }}
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-2xl shadow-sm">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Katalog Kosong</h3>
                    <p class="text-gray-500 mb-6">Mulai tambahkan produk atau inventaris Anda.</p>
                    <a href="{{ route('vendor.catalog.items.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition">
                        Tambah Produk Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
