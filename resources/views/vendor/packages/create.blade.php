<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
                {{ __('Buat Paket Layanan') }}
            </h2>
            <a href="{{ route('vendor.packages.index') }}" class="text-sm text-gray-600 hover:text-[#012A4A]">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('vendor.packages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="bg-white rounded-2xl shadow-sm p-6 space-y-6">
                    <!-- Package Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Paket <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Paket Wedding Premium"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" 
                                  placeholder="Jelaskan tentang paket ini..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">{{ old('description') }}</textarea>
                    </div>

                    <!-- Services Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Layanan <span class="text-red-500">*</span></label>
                        @if($services->count() > 0)
                            <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto space-y-2">
                                @foreach($services as $service)
                                    <label class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                        <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                               {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}
                                               class="mt-1 rounded border-gray-300 text-[#27AE60] focus:ring-[#27AE60]">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900">{{ $service->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $service->category }}</div>
                                            <div class="text-sm font-semibold text-[#27AE60]">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('services')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        @else
                            <div class="border border-gray-300 rounded-lg p-6 text-center">
                                <p class="text-gray-500 mb-3">Anda belum memiliki layanan.</p>
                                <a href="{{ route('vendor.products.create') }}" class="text-[#012A4A] hover:underline font-medium">
                                    Buat Layanan Terlebih Dahulu →
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Paket <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                                   placeholder="0"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent @error('price') border-red-500 @enderror">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">💡 Tip: Buat harga paket lebih murah dari total harga individual untuk menarik customer</p>
                    </div>

                    <!-- Benefits -->
                    <div x-data="benefitsManager()">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Benefit / Kelebihan Paket</label>
                        <div class="space-y-2">
                            <template x-for="(benefit, index) in benefits" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'benefits[' + index + ']'" x-model="benefit.value"
                                           placeholder="Contoh: Free konsultasi wedding planner"
                                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                    <button type="button" @click="removeBenefit(index)"
                                            class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="addBenefit()"
                                class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                            + Tambah Benefit
                        </button>
                    </div>

                    <!-- Thumbnail -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Thumbnail</label>
                        <input type="file" name="thumbnail" accept="image/*" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP. Maksimal 2MB.</p>
                    </div>

                    <!-- Visibility Toggle -->
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                        <input type="checkbox" name="is_visible" id="is_visible" value="1" 
                               {{ old('is_visible', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#27AE60] focus:ring-[#27AE60]">
                        <label for="is_visible" class="text-sm font-medium text-gray-700 cursor-pointer">
                            Tampilkan paket ini di website
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" 
                                class="flex-1 px-6 py-3 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition">
                            Simpan Paket
                        </button>
                        <a href="{{ route('vendor.packages.index') }}" 
                           class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function benefitsManager() {
            return {
                benefits: [
                    { value: '' }
                ],
                addBenefit() {
                    this.benefits.push({ value: '' });
                },
                removeBenefit(index) {
                    if (this.benefits.length > 1) {
                        this.benefits.splice(index, 1);
                    }
                }
            }
        }
    </script>
</x-app-layout>
