<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
            {{ __('Tambah Portfolio Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('vendor.portfolios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Basic Info -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Informasi Project</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Judul Project <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Kategori / Album</label>
                            <input type="text" name="category" value="{{ old('category') }}" placeholder="Contoh: Wedding, Pre-wedding"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Tanggal Project</label>
                            <input type="date" name="project_date" value="{{ old('project_date') }}"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Nama Klien (Opsional)</label>
                            <input type="text" name="client_name" value="{{ old('client_name') }}"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Lokasi</label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Deskripsi</label>
                            <textarea name="description" rows="4"
                                      class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Upload Foto</h3>
                    
                    <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition cursor-pointer relative">
                        <input type="file" name="images[]" id="file-input" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        
                        <div class="space-y-2">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium text-[#012A4A]">Klik untuk upload</span> atau drag and drop
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                        </div>
                    </div>

                    <!-- Preview Area -->
                    <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6"></div>
                </div>

                <!-- Publish Settings -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-[#1A1A1A]">Status Publikasi</h3>
                            <p class="text-sm text-gray-500">Pilih apakah project ini akan langsung ditampilkan atau disimpan sebagai draft.</p>
                        </div>
                        <select name="status" class="px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            <option value="published">Publish</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('vendor.portfolios.index') }}" class="px-6 py-3 bg-gray-100 text-[#1A1A1A] rounded-lg font-medium hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition">
                        Simpan Project
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const fileInput = document.getElementById('file-input');
        const previewContainer = document.getElementById('preview-container');
        const dropZone = document.getElementById('drop-zone');

        // Drag & Drop effects
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-[#27AE60]', 'bg-green-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-[#27AE60]', 'bg-green-50');
        }

        // Handle file selection
        fileInput.addEventListener('change', handleFiles);
        dropZone.addEventListener('drop', handleDrop);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFiles();
        }

        function handleFiles() {
            previewContainer.innerHTML = ''; // Clear previous previews
            const files = [...fileInput.files];
            
            files.forEach(previewFile);
        }

        function previewFile(file) {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function() {
                const div = document.createElement('div');
                div.className = 'relative aspect-square rounded-lg overflow-hidden bg-gray-100 border border-gray-200';
                div.innerHTML = `
                    <img src="${reader.result}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition flex items-center justify-center">
                        <span class="text-white text-xs">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                    </div>
                `;
                previewContainer.appendChild(div);
            }
        }
    </script>
    @endpush
</x-app-layout>
