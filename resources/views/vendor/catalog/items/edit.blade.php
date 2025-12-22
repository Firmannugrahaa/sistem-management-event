<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('vendor.catalog.items.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Informasi Produk</h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Nama Produk <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $item->name) }}" required
                                       class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Kategori</label>
                                <select name="category_id" class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                    <option value="available" {{ old('status', $item->status) == 'available' ? 'selected' : '' }}>Available (Tersedia)</option>
                                    <option value="booked" {{ old('status', $item->status) == 'booked' ? 'selected' : '' }}>Booked (Dipesan)</option>
                                    <option value="not_available" {{ old('status', $item->status) == 'not_available' ? 'selected' : '' }}>Not Available (Tidak Tersedia)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="price" value="{{ old('price', $item->price) }}" required min="0"
                                       class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Deskripsi</label>
                            <textarea name="description" rows="3"
                                      class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">{{ old('description', $item->description) }}</textarea>
                        </div>

                        <!-- Existing Images -->
                        @if($item->images->count() > 0)
                            <div>
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Foto Saat Ini</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($item->images as $image)
                                        <div class="relative group aspect-square rounded-lg overflow-hidden">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                                <button type="button" onclick="deleteImage({{ $image->id }})" class="text-white bg-red-600 p-2 rounded-full hover:bg-red-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Images -->
                        <div>
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Tambah Foto Baru</label>
                            <input type="file" name="images[]" multiple accept="image/*"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Dynamic Attributes -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-[#1A1A1A]">Atribut Tambahan</h3>
                        <button type="button" onclick="addAttribute()" class="text-sm text-[#012A4A] font-medium hover:underline">
                            + Tambah Atribut
                        </button>
                    </div>
                    
                    <div id="attributes-container" class="space-y-3">
                        @if($item->attributes)
                            @foreach($item->attributes as $key => $value)
                                <div class="flex gap-3 items-start">
                                    <div class="flex-1">
                                        <input type="text" name="attributes_keys[]" value="{{ $key }}" placeholder="Nama Atribut" 
                                               class="w-full px-3 py-2 border border-[#E0E0E0] rounded-lg text-sm focus:ring-1 focus:ring-[#27AE60]">
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" name="attributes_values[]" value="{{ $value }}" placeholder="Nilai" 
                                               class="w-full px-3 py-2 border border-[#E0E0E0] rounded-lg text-sm focus:ring-1 focus:ring-[#27AE60]">
                                    </div>
                                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Setelah textarea Deskripsi (atau di akhir Basic Info) --}}
<div class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
    <div class="flex items-center gap-3">
        <input type="checkbox"
               name="show_stock"
               id="show_stock"
               value="1"
               {{ old('show_stock', $item->show_stock) ? 'checked' : '' }}
               class="rounded border-[#E0E0E0] text-[#27AE60] focus:ring-[#27AE60] h-5 w-5">
        <label for="show_stock" class="text-sm font-medium text-[#1A1A1A]">
            Tampilkan jumlah stok katalog di profil publik
        </label>
    </div>
    <p class="text-xs text-gray-500 ml-8">
        Jika dicentang, pengunjung dapat melihat stok produk.
    </p>

    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
        <input type="checkbox"
               name="show_on_landing"
               id="show_on_landing"
               value="1"
               {{ old('show_on_landing', $item->show_on_landing) ? 'checked' : '' }}
               class="rounded border-[#E0E0E0] text-[#27AE60] focus:ring-[#27AE60] h-5 w-5">
        <label for="show_on_landing" class="text-sm font-medium text-[#1A1A1A]">
            Tampilkan di Landing Page (Section Venue Tersedia)
        </label>
    </div>
    <p class="text-xs text-gray-500 ml-8">
        Khusus untuk vendor Venue. Produk akan muncul di halaman depan website.
    </p>
</div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('vendor.catalog.items.index') }}" class="px-6 py-3 bg-gray-100 text-[#1A1A1A] rounded-lg font-medium hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden form for image deletion -->
    <form id="delete-image-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
    <script>
        function addAttribute() {
            const container = document.getElementById('attributes-container');
            const div = document.createElement('div');
            div.className = 'flex gap-3 items-start';
            div.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="attributes_keys[]" placeholder="Nama Atribut" 
                           class="w-full px-3 py-2 border border-[#E0E0E0] rounded-lg text-sm focus:ring-1 focus:ring-[#27AE60]">
                </div>
                <div class="flex-1">
                    <input type="text" name="attributes_values[]" placeholder="Nilai" 
                           class="w-full px-3 py-2 border border-[#E0E0E0] rounded-lg text-sm focus:ring-1 focus:ring-[#27AE60]">
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            container.appendChild(div);
        }

        function deleteImage(id) {
            if(confirm('Hapus foto ini?')) {
                const form = document.getElementById('delete-image-form');
                form.action = `/vendor/catalog/items/images/${id}`;
                form.submit();
            }
        }
    </script>
    @endpush
</x-app-layout>
