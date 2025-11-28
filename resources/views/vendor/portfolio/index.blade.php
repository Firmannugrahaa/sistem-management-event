<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
                {{ __('Portfolio / Galeri') }}
            </h2>
            <a href="{{ route('vendor.portfolios.create') }}" 
               class="px-4 py-2 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Project
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

            @if($portfolios->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($portfolios as $portfolio)
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden group hover:shadow-md transition">
                            <!-- Cover Image -->
                            <div class="relative h-48 bg-gray-100">
                                @if($portfolio->coverImage)
                                    <img src="{{ asset('storage/' . $portfolio->coverImage->image_path) }}" 
                                         alt="{{ $portfolio->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $portfolio->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($portfolio->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-[#1A1A1A] mb-1 line-clamp-1">
                                    {{ $portfolio->title }}
                                </h3>
                                <p class="text-sm text-gray-500 mb-4">
                                    {{ $portfolio->category ?? 'Uncategorized' }} • {{ $portfolio->images->count() }} Foto
                                </p>

                                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                    <span class="text-xs text-gray-400">
                                        {{ $portfolio->created_at->format('d M Y') }}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('vendor.portfolios.edit', $portfolio->id) }}" 
                                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('vendor.portfolios.destroy', $portfolio->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus project ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $portfolios->links() }}
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-2xl shadow-sm">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada portfolio</h3>
                    <p class="text-gray-500 mb-6">Mulai tambahkan hasil karya terbaik Anda untuk menarik klien.</p>
                    <a href="{{ route('vendor.portfolios.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition">
                        Tambah Project Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
