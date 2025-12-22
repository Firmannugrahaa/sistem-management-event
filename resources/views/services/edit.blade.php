<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Layanan</h2>
        <p class="text-gray-600 dark:text-gray-400">Edit detail layanan yang tersedia untuk vendor</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('services.update', $service->id) }}">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Detail Layanan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nama Layanan *
                    </label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name', $service->name) }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Nama dari layanan yang akan ditawarkan</p>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kategori
                    </label>
                    <input type="text" name="category" id="category"
                           value="{{ old('category', $service->category) }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kategori dari layanan ini</p>
                </div>
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Deskripsi Layanan
                </label>
                <textarea name="description" id="description"
                          rows="3"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('description', $service->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Deskripsi detail dari layanan ini</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Harga (Rp)
                    </label>
                    <input type="number" name="price" id="price"
                           value="{{ old('price', $service->price) }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                           min="0">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Harga default dari layanan ini</p>
                </div>

                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Durasi (jam)
                    </label>
                    <input type="number" name="duration" id="duration"
                           value="{{ old('duration', $service->duration) }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                           min="0">
                    @error('duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Durasi layanan dalam jam</p>
                </div>
            </div>

            <div class="mt-6 flex items-center">
                <input type="checkbox" name="is_available" id="is_available"
                       value="1"
                       {{ old('is_available', $service->is_available) ? 'checked' : '' }}
                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                <label for="is_available" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                    Layanan Tersedia
                </label>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('services.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</x-app-layout>