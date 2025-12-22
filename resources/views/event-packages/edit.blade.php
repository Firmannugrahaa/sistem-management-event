<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
                {{ __('Edit Event Package: ') . $eventPackage->name }}
            </h2>
            <a href="{{ route('event-packages.index') }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="eventPackageForm({{ Js::from($vendorCatalogItems) }}, {{ Js::from($vendorPackages) }}, {{ Js::from($eventPackage->items) }})">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <form action="{{ route('event-packages.update', $eventPackage->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama Package --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Package <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $eventPackage->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent"
                               placeholder="Contoh: Paket Nikah Hemat 1" required>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent"
                                  placeholder="Deskripsikan paket event ini..." required>{{ old('description', $eventPackage->description) }}</textarea>
                    </div>

                    {{-- Tipe Event --}}
                    <div>
                        <label for="event_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Event <span class="text-red-500">*</span></label>
                        <select name="event_type" id="event_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent">
                            <option value="">Pilih Kategori Event</option>
                            <option value="Wedding" {{ old('event_type', $eventPackage->event_type) == 'Wedding' ? 'selected' : '' }}>Wedding (Pernikahan)</option>
                            <option value="Prewedding" {{ old('event_type', $eventPackage->event_type) == 'Prewedding' ? 'selected' : '' }}>Prewedding</option>
                            <option value="Birthday" {{ old('event_type', $eventPackage->event_type) == 'Birthday' ? 'selected' : '' }}>Birthday (Ulang Tahun)</option>
                            <option value="Corporate" {{ old('event_type', $eventPackage->event_type) == 'Corporate' ? 'selected' : '' }}>Corporate Event</option>
                            <option value="Conference" {{ old('event_type', $eventPackage->event_type) == 'Conference' ? 'selected' : '' }}>Conference/Seminar</option>
                            <option value="Engagement" {{ old('event_type', $eventPackage->event_type) == 'Engagement' ? 'selected' : '' }}>Engagement (Tunangan)</option>
                            <option value="Other" {{ old('event_type', $eventPackage->event_type) == 'Other' ? 'selected' : '' }}>Other (Lainnya)</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Kategori ini akan otomatis terpilih saat client booking dengan paket ini</p>
                    </div>

                    {{-- Harga Paket --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Paket (Base Price) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" name="base_price" id="base_price" value="{{ old('base_price', $eventPackage->base_price) }}"
                                       class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent"
                                       placeholder="0" min="0" required>
                            </div>
                        </div>
                        <div>
                            <label for="pricing_method" class="block text-sm font-medium text-gray-700 mb-2">Metode Harga</label>
                            <select name="pricing_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent">
                                <option value="manual" {{ old('pricing_method', $eventPackage->pricing_method) == 'manual' ? 'selected' : '' }}>Manual (Base Price)</option>
                                <option value="auto" {{ old('pricing_method', $eventPackage->pricing_method) == 'auto' ? 'selected' : '' }}>Otomatis (Sum of Items)</option>
                                <option value="hybrid" {{ old('pricing_method', $eventPackage->pricing_method) == 'hybrid' ? 'selected' : '' }}>Hybrid (Base + Items?)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    <div>
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Thumbnail Package</label>
                        @if ($eventPackage->thumbnail_path)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $eventPackage->thumbnail_path) }}" alt="Current Thumbnail" class="h-20 w-20 object-cover rounded-lg">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#012A4A] focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#012A4A] file:text-white hover:file:bg-[#013d70]">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WEBP. Max: 2MB.</p>
                    </div>

                    {{-- Status Active --}}
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               class="w-4 h-4 text-[#012A4A] border-gray-300 rounded focus:ring-[#012A4A]"
                               {{ old('is_active', $eventPackage->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Aktifkan paket ini</label>
                    </div>

                    {{-- ITEM SELECTION SECTION --}}
                    <div class="border-t pt-6">
                        <label class="block text-lg font-semibold text-gray-800 mb-2">Isi Paket (Items)</label>
                        <p class="text-sm text-gray-500 mb-4">Pilih produk atau paket dari vendor untuk dimasukkan ke dalam paket event ini.</p>

                        {{-- Selected Items Table --}}
                        <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden mb-4">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Qty</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-if="selectedItems.length === 0">
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                                Belum ada item dipilih. Klik tombol dibawah untuk menambahkan.
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(item, index) in selectedItems" :key="index">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span x-text="item.type === 'product' ? 'Produk' : 'Paket Vendor'" 
                                                      :class="item.type === 'product' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'"
                                                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"></span>
                                                <!-- Hidden Inputs -->
                                                <input type="hidden" :name="`items[${index}][type]`" :value="item.type">
                                                <input type="hidden" :name="`items[${index}][id]`" :value="item.id">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.vendor_name"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="item.name"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatRupiah(item.price)"></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1" class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-[#012A4A] focus:border-[#012A4A]">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <button type="button" @click="openModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#012A4A] hover:bg-[#001d33] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#012A4A]">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Item
                        </button>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end gap-3 pt-6 border-t mt-6">
                        <a href="{{ route('event-packages.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-[#012A4A] text-white rounded-lg hover:bg-[#013d70] transition">
                            Update Package
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- SELECTION MODAL --}}
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="closeModal()">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 h-[70vh] flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Pilih Item untuk Paket
                            </h3>
                            <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Tabs & Search --}}
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                            <div class="flex space-x-4 border-b w-full sm:w-auto">
                                <button type="button" @click="activeTab = 'products'" 
                                        :class="activeTab === 'products' ? 'border-[#012A4A] text-[#012A4A]' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Vendor Inventory (Katalog)
                                </button>
                                <button type="button" @click="activeTab = 'packages'" 
                                        :class="activeTab === 'packages' ? 'border-[#012A4A] text-[#012A4A]' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Vendor Packages
                                </button>
                            </div>
                            <div class="w-full sm:w-64">
                                <input type="text" x-model="searchQuery" placeholder="Cari item atau vendor..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#012A4A]">
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 overflow-y-auto border rounded-md p-2">
                            {{-- Products List --}}
                            <div x-show="activeTab === 'products'">
                                <template x-for="(group, vendorName) in groupedCatalogItems" :key="vendorName">
                                    <div class="mb-4">
                                        <h4 class="font-bold text-gray-700 bg-gray-100 px-3 py-1 rounded sticky top-0" x-text="vendorName"></h4>
                                        <div class="mt-2 space-y-2 px-3">
                                            <template x-for="item in group" :key="item.id">
                                                <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded cursor-pointer border hover:border-blue-200">
                                                    <input type="checkbox" :value="item.id" x-model="tempSelectedCatalogItems" class="h-4 w-4 text-[#012A4A] focus:ring-[#012A4A] border-gray-300 rounded">
                                                    <div class="flex-1 flex justify-between">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900" x-text="item.name"></p>
                                                            <p class="text-xs text-gray-500" x-text="item.category ? item.category.name : 'Uncategorized'"></p>
                                                        </div>
                                                        <span class="text-sm font-semibold text-gray-700" x-text="formatRupiah(item.price)"></span>
                                                    </div>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="Object.keys(groupedCatalogItems).length === 0" class="text-center py-10 text-gray-500">
                                    Tidak ada item katalog ditemukan.
                                </div>
                            </div>

                            {{-- Packages List --}}
                            <div x-show="activeTab === 'packages'">
                                <template x-for="(group, vendorName) in groupedPackages" :key="vendorName">
                                    <div class="mb-4">
                                        <h4 class="font-bold text-gray-700 bg-gray-100 px-3 py-1 rounded sticky top-0" x-text="vendorName"></h4>
                                        <div class="mt-2 space-y-2 px-3">
                                            <template x-for="pkg in group" :key="pkg.id">
                                                <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded cursor-pointer border hover:border-purple-200">
                                                    <input type="checkbox" :value="pkg.id" x-model="tempSelectedPackages" class="h-4 w-4 text-[#012A4A] focus:ring-[#012A4A] border-gray-300 rounded">
                                                    <div class="flex-1 flex justify-between">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900" x-text="pkg.name"></p>
                                                            <p class="text-xs text-gray-500" x-text="pkg.description ? pkg.description.substring(0, 50) + '...' : ''"></p>
                                                        </div>
                                                        <span class="text-sm font-semibold text-gray-700" x-text="formatRupiah(pkg.price)"></span>
                                                    </div>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="Object.keys(groupedPackages).length === 0" class="text-center py-10 text-gray-500">
                                    Tidak ada paket vendor ditemukan.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="confirmSelection()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#012A4A] text-base font-medium text-white hover:bg-[#001d33] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#012A4A] sm:ml-3 sm:w-auto sm:text-sm">
                            Tambahkan (<span x-text="tempSelectedCatalogItems.length + tempSelectedPackages.length"></span>)
                        </button>
                        <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function eventPackageForm(catalogItemsData, packagesData, initialItems) {
            return {
                showModal: false,
                activeTab: 'products',
                searchQuery: '',
                
                catalogItems: catalogItemsData,
                packages: packagesData,
                
                selectedItems: [],
                
                // Temporary selections in modal
                tempSelectedCatalogItems: [],
                tempSelectedPackages: [],
                
                init() {
                    if (initialItems && initialItems.length) {
                        this.selectedItems = initialItems.map(item => {
                            let type = 'product';
                            let refId = null;
                            let refName = 'Unknown Item';
                            let refPrice = Number(item.unit_price) || 0;
                            let refVendor = 'Unknown Vendor';

                            if (item.vendor_catalog_item_id) {
                                type = 'product';
                                refId = item.vendor_catalog_item_id;
                                if (item.vendor_catalog_item) {
                                    refName = item.vendor_catalog_item.name;
                                    refVendor = item.vendor_catalog_item.vendor && item.vendor_catalog_item.vendor.brand_name 
                                        ? item.vendor_catalog_item.vendor.brand_name 
                                        : (item.vendor_catalog_item.vendor?.user?.name || 'Vendor #' + (item.vendor_catalog_item.vendor_id || ''));
                                }
                            } else if (item.vendor_package_id) {
                                type = 'package';
                                refId = item.vendor_package_id;
                                if (item.vendor_package) {
                                     refName = item.vendor_package.name;
                                     refVendor = item.vendor_package.vendor && item.vendor_package.vendor.brand_name 
                                        ? item.vendor_package.vendor.brand_name 
                                        : (item.vendor_package.vendor?.user?.name || 'Vendor #' + (item.vendor_package.vendor_id || ''));
                                }
                            }

                            return {
                                type: type,
                                id: refId,
                                name: refName,
                                price: refPrice,
                                vendor_name: refVendor,
                                quantity: item.quantity
                            };
                        });
                    }
                },
                
                openModal() {
                    this.showModal = true;
                    this.tempSelectedCatalogItems = [];
                    this.tempSelectedPackages = [];
                },
                
                closeModal() {
                    this.showModal = false;
                },
                
                confirmSelection() {
                    // Add Catalog Items
                    this.tempSelectedCatalogItems.forEach(id => {
                        const item = this.catalogItems.find(p => p.id == id);
                        if (item) {
                            this.addIfNotExist('product', item);
                        }
                    });
                    
                    // Add Packages
                    this.tempSelectedPackages.forEach(id => {
                        const pkg = this.packages.find(p => p.id == id);
                        if (pkg) {
                            this.addIfNotExist('package', pkg);
                        }
                    });
                    
                    this.closeModal();
                },
                
                addIfNotExist(type, item) {
                    const existing = this.selectedItems.find(i => i.type === type && i.id === item.id);
                    if (existing) {
                        return;
                    }
                    
                    this.selectedItems.push({
                        type: type,
                        id: item.id,
                        name: item.name,
                        price: Number(item.price),
                        vendor_name: item.vendor ? (item.vendor.brand_name || item.vendor.user?.name || 'Vendor #' + item.vendor.id) : 'Unknown',
                        quantity: 1
                    });
                },
                
                removeItem(index) {
                    this.selectedItems.splice(index, 1);
                },
                
                formatRupiah(amount) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
                },
                
                get groupedCatalogItems() {
                    const filtered = this.searchQuery 
                        ? this.catalogItems.filter(p => p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || (p.vendor && (p.vendor.brand_name || '').toLowerCase().includes(this.searchQuery.toLowerCase())))
                        : this.catalogItems;
                        
                    return this.groupBy(filtered, p => p.vendor ? (p.vendor.brand_name || 'Vendor #' + p.vendor.id) : 'No Vendor');
                },
                
                get groupedPackages() {
                    const filtered = this.searchQuery 
                        ? this.packages.filter(p => p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || (p.vendor && (p.vendor.brand_name || '').toLowerCase().includes(this.searchQuery.toLowerCase())))
                        : this.packages;
                        
                    return this.groupBy(filtered, p => p.vendor ? (p.vendor.brand_name || 'Vendor #' + p.vendor.id) : 'No Vendor');
                },
                
                groupBy(array, keyGetter) {
                    const map = {};
                    array.forEach(item => {
                        const key = keyGetter(item);
                        if (!map[key]) {
                            map[key] = [];
                        }
                        map[key].push(item);
                    });
                    return map;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
