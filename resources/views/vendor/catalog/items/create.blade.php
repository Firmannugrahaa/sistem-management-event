<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
            {{ __('Tambah Produk ke Katalog') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('vendor.catalog.items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Informasi Produk</h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Nama Produk <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Kategori</label>
                                <select name="category_id" class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">
                                    Belum ada kategori? <a href="{{ route('vendor.catalog.categories.index') }}" class="text-blue-600 hover:underline">Buat baru</a>
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available (Tersedia)</option>
                                    <option value="booked" {{ old('status') == 'booked' ? 'selected' : '' }}>Booked (Dipesan)</option>
                                    <option value="not_available" {{ old('status') == 'not_available' ? 'selected' : '' }}>Not Available (Tidak Tersedia)</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="price" value="{{ old('price', 0) }}" required min="0"
                                       class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Deskripsi</label>
                            <textarea name="description" rows="4" 
                                      class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent"
                                      placeholder="Jelaskan detail produk Anda...">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Images Upload Section -->
                <div class="bg-white rounded-2xl shadow-sm p-6" x-data="imageUpload()">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Foto Produk</h3>
                    <p class="text-sm text-gray-500 mb-4">Upload foto produk Anda (Max 5 foto, masing-masing max 5MB)</p>
                    
                    <!-- Upload Area -->
                    <div class="border-2 border-dashed border-[#E0E0E0] rounded-lg p-8 text-center hoverBorder-[#27AE60] transition-colors cursor-pointer"
                         @click="$refs.fileInput.click()"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleDrop($event)"
                         :class="{ 'border-[#27AE60] bg-green-50': isDragging }">
                        
                        <input type="file" 
                               name="images[]" 
                               multiple 
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               x-ref="fileInput"
                               @change="handleFiles($event.target.files)"
                               class="hidden">
                        
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        
                        <p class="mt-4 text-sm text-gray-600">
                            <span class="font-semibold text-[#012A4A]">Klik untuk upload</span> atau drag & drop
                        </p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG, WEBP up to 5MB</p>
                    </div>

                    <!-- Image Previews -->
                    <div x-show="previews.length > 0" class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative group">
                                <img :src="preview" :alt="'Preview ' + (index + 1)" 
                                     class="w-full h-32 object-cover rounded-lg border-2 border-[#E0E0E0]">
                                <button type="button" 
                                        @click="removeImage(index)"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                <div class="absolute bottom-2 left-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                                    <span x-text="'Foto ' + (index + 1)"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    @error('images.*') 
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Dynamic Attributes -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-[#1A1A1A]">Atribut Tambahan (Opsional)</h3>
                        <button type="button" onclick="addAttribute()" class="text-sm text-[#012A4A] font-medium hover:underline">
                            + Tambah Atribut
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Tambahkan detail spesifik seperti Warna, Ukuran, Bahan, Menu, dll.</p>
                    
                    <div id="attributes-container" class="space-y-3">
                        <!-- Attributes will be added here -->
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <input type="checkbox"
                               name="show_stock"
                               id="show_stock"
                               value="1"
                               {{ old('show_stock') ? 'checked' : '' }}
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
                               {{ old('show_on_landing') ? 'checked' : '' }}
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
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image Upload Handler
        function imageUpload() {
            return {
                previews: [],
                files: [],
                isDragging: false,
                
                handleFiles(newFiles) {
                    const maxFiles = 5;
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    
                    Array.from(newFiles).forEach(file => {
                        if (this.files.length >= maxFiles) {
                            alert(`Maksimal ${maxFiles} foto`);
                            return;
                        }
                        
                        if (file.size > maxSize) {
                            alert(`File ${file.name} terlalu besar (max 5MB)`);
                            return;
                        }
                        
                        if (!file.type.match('image.*')) {
                            alert(`File ${file.name} bukan gambar`);
                            return;
                        }
                        
                        this.files.push(file);
                        
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previews.push(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    });
                    
                    // Update file input
                    this.updateFileInput();
                },
                
                handleDrop(e) {
                    this.isDragging = false;
                    this.handleFiles(e.dataTransfer.files);
                },
                
                removeImage(index) {
                    this.previews.splice(index, 1);
                    this.files.splice(index, 1);
                    this.updateFileInput();
                },
                
                updateFileInput() {
                    const dataTransfer = new DataTransfer();
                    this.files.forEach(file => dataTransfer.items.add(file));
                    this.$refs.fileInput.files = dataTransfer.files;
                }
            }
        }
        
        // Attribute Handler
        function addAttribute() {
            const container = document.getElementById('attributes-container');
            const div = document.createElement('div');
            div.className = 'flex gap-3 items-start';
            div.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="attributes_keys[]" placeholder="Nama Atribut (Contoh: Warna)" 
                           class="w-full px-3 py-2 border border-[#E0E0E0] rounded-lg text-sm focus:ring-1 focus:ring-[#27AE60]">
                </div>
                <div class="flex-1">
                    <input type="text" name="attributes_values[]" placeholder="Nilai (Contoh: Merah Maroon)" 
                           class="w-full px-3 py-2 border border-[#E0E0E0] rounded-lg text-sm focus:ring-1 focus:ring-[#27AE60]">
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            container.appendChild(div);
        }
    </script>
    @endpush
</x-app-layout>
