<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
                {{ __('Edit Paket Layanan') }}
            </h2>
            <a href="{{ route('vendor.packages.index') }}" class="text-sm text-gray-600 hover:text-[#012A4A]">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="packageFormManager()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('vendor.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- Section A: Informasi Paket --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-[#012A4A] text-white rounded-lg flex items-center justify-center text-sm font-bold">A</span>
                        Informasi Paket
                    </h3>
                    
                    <div class="space-y-5">
                        <!-- Package Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Paket <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $package->name) }}" required
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
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">{{ old('description', $package->description) }}</textarea>
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Paket <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" name="price" value="{{ old('price', $package->price) }}" required min="0" step="0.01"
                                       placeholder="0"
                                       class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent @error('price') border-red-500 @enderror">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">💡 Tip: Buat harga paket lebih murah dari total harga individual untuk menarik customer</p>
                        </div>

                        <!-- Visibility Toggle -->
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" name="is_visible" id="is_visible" value="1" 
                                   {{ old('is_visible', $package->is_visible) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#27AE60] focus:ring-[#27AE60]">
                            <label for="is_visible" class="text-sm font-medium text-gray-700 cursor-pointer">
                                Tampilkan paket ini di website (Aktif)
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Section B: Layanan dalam Paket --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-[#012A4A] text-white rounded-lg flex items-center justify-center text-sm font-bold">B</span>
                        Layanan dalam Paket
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">Pilih layanan jasa yang termasuk dalam paket ini (opsional jika hanya menjual produk).</p>
                    
                    @php
                        $selectedServices = old('services', $package->services->pluck('id')->toArray());
                    @endphp
                    
                    @if($services->count() > 0)
                        <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto space-y-2">
                            @foreach($services as $service)
                                <label class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                    <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                           {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}
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

                {{-- Section C: Produk dalam Paket --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-[#27AE60] text-white rounded-lg flex items-center justify-center text-sm font-bold">C</span>
                        Produk dalam Paket
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Tambahkan produk yang termasuk dalam paket ini. Produk ini akan otomatis masuk ke event client jika paket dipilih.
                    </p>
                    
                    @if($catalogItems->count() > 0)
                        {{-- Vendor Category Notice --}}
                        @if(str_contains($vendorCategory, 'catering'))
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-700">
                                    <strong>⚠️ Catering:</strong> Tentukan jumlah porsi agar client memahami kapasitas layanan Anda.
                                </p>
                            </div>
                        @endif

                        {{-- Products Table --}}
                        <div class="space-y-3" id="items-container">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex flex-col md:flex-row gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    {{-- Product Select --}}
                                    <div class="flex-1 min-w-0">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Produk</label>
                                        <select :name="'items[' + index + '][catalog_item_id]'" x-model="item.catalog_item_id"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                            <option value="">Pilih Produk...</option>
                                            @foreach($catalogItems as $catalogItem)
                                                <option value="{{ $catalogItem->id }}">
                                                    {{ $catalogItem->name }} - Rp {{ number_format($catalogItem->price, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    {{-- Quantity --}}
                                    <div class="w-24" x-show="!isVenueCategory">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">
                                            Qty 
                                            <span x-show="isCateringCategory" class="text-red-500">*</span>
                                        </label>
                                        <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity"
                                               min="1" placeholder="1"
                                               :required="isCateringCategory"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                    </div>
                                    
                                    {{-- Unit --}}
                                    <div class="w-32">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Satuan</label>
                                        <select :name="'items[' + index + '][unit]'" x-model="item.unit"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                            <option value="">Pilih...</option>
                                            <template x-if="isCateringCategory">
                                                <template>
                                                    <option value="pax">pax</option>
                                                    <option value="box">box</option>
                                                    <option value="porsi">porsi</option>
                                                    <option value="meja">meja</option>
                                                </template>
                                            </template>
                                            <template x-if="isDecorationCategory">
                                                <template>
                                                    <option value="set">set</option>
                                                    <option value="pcs">pcs</option>
                                                    <option value="unit">unit</option>
                                                </template>
                                            </template>
                                            <template x-if="isMuaCategory">
                                                <template>
                                                    <option value="orang">orang</option>
                                                    <option value="sesi">sesi</option>
                                                </template>
                                            </template>
                                            <template x-if="isVenueCategory">
                                                <template>
                                                    <option value="jam">jam</option>
                                                    <option value="hari">hari</option>
                                                </template>
                                            </template>
                                            <template x-if="isOtherCategory">
                                                <template>
                                                    <option value="pcs">pcs</option>
                                                    <option value="unit">unit</option>
                                                    <option value="set">set</option>
                                                    <option value="orang">orang</option>
                                                    <option value="pax">pax</option>
                                                </template>
                                            </template>
                                        </select>
                                    </div>
                                    
                                    {{-- Notes --}}
                                    <div class="flex-1 min-w-0">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Keterangan</label>
                                        <input type="text" :name="'items[' + index + '][notes]'" x-model="item.notes"
                                               placeholder="Opsional..."
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                                    </div>
                                    
                                    {{-- Remove Button --}}
                                    <div class="flex items-end">
                                        <button type="button" @click="removeItem(index)"
                                                class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <button type="button" @click="addItem()"
                                class="mt-4 px-4 py-2 bg-[#27AE60] text-white rounded-lg hover:bg-[#219653] transition text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Produk
                        </button>
                        
                        @error('items')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    @else
                        <div class="border border-gray-300 rounded-lg p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p class="text-gray-500 mb-3">Belum ada produk di katalog Anda.</p>
                            <a href="{{ route('vendor.catalog.items.create') }}" class="text-[#012A4A] hover:underline font-medium">
                                Tambah Produk ke Katalog →
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Section D: Benefits --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-gray-500 text-white rounded-lg flex items-center justify-center text-sm font-bold">D</span>
                        Benefit / Kelebihan Paket
                    </h3>
                    
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

                {{-- Section E: Thumbnail --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-gray-500 text-white rounded-lg flex items-center justify-center text-sm font-bold">E</span>
                        Foto Thumbnail
                    </h3>
                    
                    @if($package->thumbnail_path)
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 mb-2">Thumbnail saat ini:</p>
                            <img src="{{ Storage::url($package->thumbnail_path) }}" alt="Current thumbnail" class="w-32 h-32 object-cover rounded-lg border">
                        </div>
                    @endif
                    
                    <input type="file" name="thumbnail" accept="image/*" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
                </div>

                {{-- Submit Button --}}
                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition">
                        Update Paket
                    </button>
                    <a href="{{ route('vendor.packages.index') }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function packageFormManager() {
            const vendorCategory = '{{ $vendorCategory }}';
            
            // Prepare existing items
            const existingItems = @json($package->items->map(function($item) {
                return [
                    'catalog_item_id' => (string) $item->id,
                    'quantity' => $item->pivot->quantity ?? 1,
                    'unit' => $item->pivot->unit ?? '',
                    'notes' => $item->pivot->notes ?? ''
                ];
            }));
            
            // Prepare existing benefits
            const existingBenefits = @json($package->benefits ?? []);
            
            return {
                vendorCategory: vendorCategory,
                
                // Category checks
                get isCateringCategory() {
                    return this.vendorCategory.includes('catering');
                },
                get isDecorationCategory() {
                    return this.vendorCategory.includes('dekor') || this.vendorCategory.includes('decoration');
                },
                get isMuaCategory() {
                    return this.vendorCategory.includes('mua') || this.vendorCategory.includes('makeup') || this.vendorCategory.includes('talent');
                },
                get isVenueCategory() {
                    return this.vendorCategory.includes('venue');
                },
                get isOtherCategory() {
                    return !this.isCateringCategory && !this.isDecorationCategory && !this.isMuaCategory && !this.isVenueCategory;
                },
                
                // Items (products) - start with existing or empty
                items: existingItems.length > 0 ? existingItems : [{ catalog_item_id: '', quantity: 1, unit: '', notes: '' }],
                addItem() {
                    this.items.push({ catalog_item_id: '', quantity: 1, unit: '', notes: '' });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    } else {
                        this.items[0] = { catalog_item_id: '', quantity: 1, unit: '', notes: '' };
                    }
                },
                
                // Benefits - start with existing or empty
                benefits: existingBenefits.length > 0 
                    ? existingBenefits.map(b => ({ value: b })) 
                    : [{ value: '' }],
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
