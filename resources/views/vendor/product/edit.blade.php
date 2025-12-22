<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
            {{ __('Edit Layanan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Detail Layanan</h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Nama Layanan <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Kategori Layanan <span class="text-red-500">*</span></label>
                            <input type="text" name="category" value="{{ old('category', $product->category) }}" required list="categories"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            <datalist id="categories">
                                <option value="Make Up">
                                <option value="Dekorasi">
                                <option value="Catering">
                                <option value="Fotografi">
                                <option value="Videografi">
                                <option value="Entertainment">
                                <option value="Venue">
                                <option value="Attire / Busana">
                                <option value="Organizer (WO/EO)">
                            </datalist>
                            @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Harga Mulai Dari (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0"
                                       class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Estimasi Pengerjaan / Durasi</label>
                                <input type="text" name="duration" value="{{ old('duration', $product->duration) }}"
                                       class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#1A1A1A] mb-2">Deskripsi Layanan</label>
                            <textarea name="description" rows="4"
                                      class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('vendor.products.index') }}" class="px-6 py-3 bg-gray-100 text-[#1A1A1A] rounded-lg font-medium hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
