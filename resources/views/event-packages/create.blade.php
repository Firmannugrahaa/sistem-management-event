<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
                {{ __('Buat Event Package Baru') }}
            </h2>
            <a href="{{ route('event-packages.index') }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <form action="{{ route('event-packages.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                    {{-- Nama Package --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Package <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent"
                               placeholder="Contoh: Paket Nikah Hemat 1" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent"
                                  placeholder="Deskripsikan paket event ini..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga Paket --}}
                    <div>
                        <label for="package_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Paket <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" name="package_price" id="package_price" value="{{ old('package_price') }}"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent"
                                   placeholder="0" min="0" required>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Harga bundle setelah diskon/markup</p>
                        @error('package_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Thumbnail --}}
                    <div>
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Thumbnail Package</label>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#012A4A] file:text-white hover:file:bg-[#013d70]">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WEBP. Max: 2MB.</p>
                        @error('thumbnail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pilih Produk dari Vendor --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Produk/Layanan <span class="text-red-500">*</span></label>
                        <p class="text-sm text-gray-500 mb-3">Pilih produk/layanan dari berbagai vendor untuk dimasukkan ke paket ini</p>
                        
                        <div id="items-container" class="space-y-3">
                            {{-- Initial item row --}}
                            <div class="item-row flex gap-3">
                                <select name="items[0][vendor_product_id]" 
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent" required>
                                    <option value="">-- Pilih Produk/Layanan --</option>
                                    @foreach($vendorProducts as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} ({{ $product->vendor->brand_name ?? 'N/A' }}) - Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="number" name="items[0][quantity]" 
                                       class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent"
                                       placeholder="Qty" min="1" value="1" required>
                                <button type="button" class="remove-item px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition" disabled>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="button" id="add-item" class="mt-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            + Tambah Produk
                        </button>

                        @error('items')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status Active --}}
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               class="w-4 h-4 text-[#012A4A] border-gray-300 rounded focus:ring-[#012A4A]"
                               {{ old('is_active') ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Aktifkan paket ini</label>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('event-packages.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-[#012A4A] text-white rounded-lg hover:bg-[#013d70] transition">
                            Simpan Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let itemIndex = 1;

        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'item-row flex gap-3';
            newRow.innerHTML = `
                <select name="items[${itemIndex}][vendor_product_id]" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent" required>
                    <option value="">-- Pilih Produk/Layanan --</option>
                    @foreach($vendorProducts as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} ({{ $product->vendor->brand_name ?? 'N/A' }}) - Rp {{ number_format($product->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                <input type="number" name="items[${itemIndex}][quantity]" 
                       class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent"
                       placeholder="Qty" min="1" value="1" required>
                <button type="button" class="remove-item px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            container.appendChild(newRow);
            itemIndex++;
            updateRemoveButtons();
        });

        document.getElementById('items-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                e.target.closest('.item-row').remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.item-row');
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector('.remove-item');
                removeBtn.disabled = rows.length === 1;
            });
        }
    </script>
    @endpush
</x-app-layout>
