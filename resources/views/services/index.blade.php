<x-app-layout>
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Layanan</h2>
        <a href="{{ route('services.create') }}"
           class="inline-flex items-center px-4 py-2 bg-primary hover:bg-blue-700 text-white rounded-md transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Layanan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-3">Filter Layanan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                <select id="category-filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Semua Kategori</option>
                    @foreach($serviceTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select id="status-filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Semua Status</option>
                    <option value="1">Tersedia</option>
                    <option value="0">Tidak Tersedia</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Layanan</label>
                <input type="text" id="search-input" placeholder="Cari layanan..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>
        </div>
    </div>

    <!-- Services List -->
    @if($services->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $service)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $service->name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                              {{ $service->is_available ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                            {{ $service->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                        </span>
                    </div>

                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">
                        {{ $service->description }}
                    </p>

                    <div class="mb-4">
                        <p class="text-lg font-bold text-primary">
                            Rp {{ number_format($service->price, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Durasi: {{ $service->duration }} jam
                        </p>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                            {{ $service->category ?: 'Umum' }}
                        </span>

                        <div class="flex space-x-2">
                            <a href="{{ route('services.edit', $service->id) }}"
                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Vendor Count -->
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Digunakan oleh {{ $service->vendors->count() }} vendor
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $services->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada layanan</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Belum ada layanan yang ditambahkan. Klik tombol di atas untuk menambahkan layanan baru.
            </p>
        </div>
    @endif
</x-app-layout>