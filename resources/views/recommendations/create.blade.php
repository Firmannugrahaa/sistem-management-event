@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('client-requests.show', $clientRequest) }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create Recommendation</h1>
                    <p class="text-sm text-gray-600">For Client: {{ $clientRequest->client_name }} ({{ $clientRequest->event_type }})</p>
                </div>
            </div>
        </div>

        <form action="{{ route('recommendations.store', $clientRequest) }}" method="POST" id="recommendation-form">
            @csrf
            
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recommendation Details</h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" required 
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., Premium Wedding Package - Option A"
                               value="{{ old('title') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description / Opening Note</label>
                        <textarea name="description" rows="3" 
                                  class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Add a personal note or summary of this recommendation...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Items Builder -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Recommendation Items</h2>
                    <button type="button" onclick="addItem()" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition flex items-center text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Item
                    </button>
                </div>

                <div id="items-container" class="space-y-4">
                    <!-- Items will be added here via JS -->
                </div>
                
                <div class="mt-6 flex justify-end border-t pt-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Estimated Budget</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-budget">Rp 0</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('client-requests.show', $clientRequest) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition shadow-sm">
                    Save Draft
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template for Item Row -->
<template id="item-template">
    <div class="item-row border border-gray-200 rounded-lg p-5 bg-gray-50 relative group transition hover:border-blue-300">
        <button type="button" onclick="removeItem(this)" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div class="space-y-4">
            <!-- Row 1: Category, Recommendation Type -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kategori Vendor</label>
                    <select name="items[INDEX][category]" class="category-select w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white" required onchange="handleCategoryChange(this)">
                        <option value="">Pilih Kategori</option>
                        @foreach(['Venue', 'Catering', 'Decoration', 'MUA', 'Attire', 'Documentation', 'Entertainment', 'WO/Organizer', 'Souvenir', 'Invitation', 'Other'] as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tipe Rekomendasi</label>
                    <select name="items[INDEX][recommendation_type]" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="primary">⭐ Utama (Recommended)</option>
                        <option value="alternative">🔄 Alternatif</option>
                        <option value="upgrade">💎 Upgrade Option</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Vendor Selection -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Vendor</label>
                <div class="internal-vendor-wrapper">
                    <select name="items[INDEX][vendor_id]" class="vendor-select w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="handleVendorChange(this)" disabled>
                        <option value="">-- Pilih Kategori dulu --</option>
                    </select>
                </div>
                <!-- External Vendor Input (Hidden by default) -->
                <div class="external-vendor-input hidden mt-2">
                    <div class="flex">
                        <input type="text" name="items[INDEX][external_vendor_name]" 
                               class="external-name-input w-full rounded-l-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Nama vendor eksternal...">
                        <button type="button" onclick="cancelExternal(this)" class="px-4 bg-gray-200 border border-l-0 border-gray-300 rounded-r-lg text-xs text-gray-600 hover:bg-gray-300 font-medium">
                            Batal
                        </button>
                    </div>
                </div>
            </div>

            <!-- Row 3: Package/Service Selection (Unified) -->
            <div class="package-service-section hidden">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Paket / Layanan</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <select class="product-select w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="autoFillProduct(this)">
                            <option value="">Pilih produk/paket vendor</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" name="items[INDEX][service_name]" 
                               class="service-name-input w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Atau tulis manual: Platinum Package 300 Pax">
                    </div>
                </div>
            </div>

            <!-- Row 4: Price, Qty (Conditional) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Harga Estimasi (Rp)</label>
                    <input type="number" name="items[INDEX][estimated_price]" 
                           class="price-input w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="0" oninput="calculateTotal()">
                </div>
                <div class="qty-field hidden">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jumlah / Qty</label>
                    <input type="number" name="items[INDEX][quantity]" 
                           class="qty-input w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="1" min="1" value="1">
                </div>
                <div class="qty-field hidden">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Satuan</label>
                    <input type="text" name="items[INDEX][unit]" 
                           class="unit-input w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="pax / unit / jam">
                </div>
            </div>

            <!-- Row 5: Notes -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Alasan Rekomendasi</label>
                <textarea name="items[INDEX][notes]" rows="2" 
                       class="notes-input w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Kenapa merekomendasikan vendor ini? Contoh: 'Harga terbaik', 'Sesuai tema client'..."></textarea>
            </div>

            <!-- Add-ons Section (for Venue) -->
            <div class="addons-section hidden border-t border-gray-200 pt-4 mt-2">
                <div class="flex justify-between items-center mb-3">
                    <label class="text-xs font-semibold text-gray-600">Add-ons (Opsional)</label>
                    <button type="button" onclick="addAddon(this)" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Add-on
                    </button>
                </div>
                <div class="addons-container space-y-2">
                    <!-- Add-ons will be added here -->
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Template for Add-on Row -->
<template id="addon-template">
    <div class="addon-row flex items-center gap-2 bg-white p-2 rounded-lg border border-gray-200">
        <select name="items[PARENT_INDEX][addons][ADDON_INDEX][type]" class="addon-type flex-shrink-0 w-40 rounded-md border-gray-300 text-xs focus:ring-blue-500 focus:border-blue-500">
            <option value="">Pilih Add-on</option>
            <option value="extra_hour">Extra Hour</option>
            <option value="extra_room">Ruang Tambahan</option>
            <option value="facility">Fasilitas Khusus</option>
            <option value="custom">Lainnya</option>
        </select>
        <input type="text" name="items[PARENT_INDEX][addons][ADDON_INDEX][description]" 
               class="addon-desc flex-1 rounded-md border-gray-300 text-xs focus:ring-blue-500 focus:border-blue-500"
               placeholder="Deskripsi add-on...">
        <input type="number" name="items[PARENT_INDEX][addons][ADDON_INDEX][price]" 
               class="addon-price w-28 rounded-md border-gray-300 text-xs focus:ring-blue-500 focus:border-blue-500"
               placeholder="Harga" oninput="calculateTotal()">
        <button type="button" onclick="removeAddon(this)" class="text-red-400 hover:text-red-600 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</template>

@push('scripts')
<script>
    // Vendor data for category filtering
    const vendorsData = @json($vendorsForJs);
    
    // Categories that require qty
    const qtyRequiredCategories = ['Catering', 'Souvenir', 'Documentation', 'Invitation'];
    // Categories that support add-ons
    const addonSupportCategories = ['Venue'];
    
    let itemIndex = 0;
    let addonIndexes = {}; // Track addon indexes per item

    function addItem() {
        const container = document.getElementById('items-container');
        const template = document.getElementById('item-template');
        const clone = template.content.cloneNode(true);
        
        // Replace placeholder INDEX with unique index
        const html = clone.querySelector('.item-row').outerHTML.replace(/INDEX/g, itemIndex);
        
        // Create a temporary div and add to container
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        const newRow = tempDiv.firstElementChild;
        newRow.dataset.itemIndex = itemIndex;
        
        container.appendChild(newRow);
        addonIndexes[itemIndex] = 0;
        itemIndex++;
    }

    function removeItem(btn) {
        btn.closest('.item-row').remove();
        calculateTotal();
    }

    function handleCategoryChange(categorySelect) {
        const row = categorySelect.closest('.item-row');
        const vendorSelect = row.querySelector('.vendor-select');
        const qtyFields = row.querySelectorAll('.qty-field');
        const addonsSection = row.querySelector('.addons-section');
        const packageSection = row.querySelector('.package-service-section');
        const selectedCategory = categorySelect.value;
        
        // Reset vendor and hide sections
        vendorSelect.innerHTML = '<option value="">-- Pilih Kategori dulu --</option>';
        vendorSelect.disabled = true;
        packageSection.classList.add('hidden');
        
        if (!selectedCategory) {
            qtyFields.forEach(f => f.classList.add('hidden'));
            addonsSection.classList.add('hidden');
            return;
        }
        
        // Enable vendor select
        vendorSelect.disabled = false;
        vendorSelect.innerHTML = '<option value="">Pilih Vendor</option>';
        vendorSelect.innerHTML += '<option value="external" class="font-semibold text-blue-600">+ Tambah Vendor Eksternal</option>';
        
        // Filter vendors by category
        const filteredVendors = vendorsData.filter(v => {
            return v.category.toLowerCase() === selectedCategory.toLowerCase() ||
                   (selectedCategory === 'Other' && !['venue', 'catering', 'decoration', 'mua', 'attire', 'documentation', 'entertainment', 'wo/organizer', 'souvenir', 'invitation'].includes(v.category.toLowerCase()));
        });
        
        if (filteredVendors.length > 0) {
            const optgroup = document.createElement('optgroup');
            optgroup.label = 'Vendor ' + selectedCategory;
            
            filteredVendors.forEach(vendor => {
                const option = document.createElement('option');
                option.value = vendor.id;
                option.textContent = vendor.name;
                optgroup.appendChild(option);
            });
            
            vendorSelect.appendChild(optgroup);
        }
        
        // Toggle qty fields based on category
        if (qtyRequiredCategories.includes(selectedCategory)) {
            qtyFields.forEach(f => f.classList.remove('hidden'));
        } else {
            qtyFields.forEach(f => f.classList.add('hidden'));
        }
        
        // Toggle addons section based on category
        if (addonSupportCategories.includes(selectedCategory)) {
            addonsSection.classList.remove('hidden');
        } else {
            addonsSection.classList.add('hidden');
        }
    }

    function handleVendorChange(select) {
        const row = select.closest('.item-row');
        const externalInput = row.querySelector('.external-vendor-input');
        const internalWrapper = row.querySelector('.internal-vendor-wrapper');
        const packageSection = row.querySelector('.package-service-section');
        const productSelect = row.querySelector('.product-select');
        
        if (select.value === 'external') {
            internalWrapper.classList.add('hidden');
            packageSection.classList.add('hidden');
            externalInput.classList.remove('hidden');
            select.value = '';
        } else if (select.value) {
            // Show package section and load products
            packageSection.classList.remove('hidden');
            loadVendorProducts(select.value, productSelect);
        } else {
            packageSection.classList.add('hidden');
        }
    }

    function loadVendorProducts(vendorId, selectElement) {
        if(!selectElement) return;

        selectElement.innerHTML = '<option value="">Loading...</option>';
        selectElement.disabled = true;

        fetch(`/vendors/${vendorId}/offerings`)
            .then(response => {
                if(!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                selectElement.innerHTML = '<option value="">Pilih produk/paket vendor</option>';
                
                if (data.length === 0) {
                    const option = document.createElement('option');
                    option.textContent = 'Tidak ada produk tersedia';
                    option.disabled = true;
                    selectElement.appendChild(option);
                } else {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name + ' - Rp ' + new Intl.NumberFormat('id-ID').format(item.price);
                        option.dataset.name = item.name;
                        option.dataset.price = item.price;
                        option.dataset.desc = item.description || '';
                        selectElement.appendChild(option);
                    });
                }
                selectElement.disabled = false;
            })
            .catch(error => {
                console.error('Error loading products:', error);
                selectElement.innerHTML = '<option value="">Gagal memuat produk</option>';
            });
    }

    function autoFillProduct(select) {
        const option = select.options[select.selectedIndex];
        if (!option.value) return;

        const row = select.closest('.item-row');
        const nameInput = row.querySelector('.service-name-input');
        const priceInput = row.querySelector('.price-input');
        const notesInput = row.querySelector('.notes-input');

        if (nameInput) nameInput.value = option.dataset.name || '';
        
        if (priceInput) {
            priceInput.value = option.dataset.price || '';
            calculateTotal(); 
        }
        
        if (notesInput && !notesInput.value) {
            notesInput.value = option.dataset.desc || '';
        }
    }

    function cancelExternal(btn) {
        const row = btn.closest('.item-row');
        const externalInput = row.querySelector('.external-vendor-input');
        const internalWrapper = row.querySelector('.internal-vendor-wrapper');
        
        externalInput.classList.add('hidden');
        internalWrapper.classList.remove('hidden');
        externalInput.querySelector('.external-name-input').value = '';
    }

    function addAddon(btn) {
        const row = btn.closest('.item-row');
        const itemIdx = row.dataset.itemIndex;
        const container = row.querySelector('.addons-container');
        const template = document.getElementById('addon-template');
        
        const addonIdx = addonIndexes[itemIdx] || 0;
        const html = template.content.cloneNode(true).querySelector('.addon-row').outerHTML
            .replace(/PARENT_INDEX/g, itemIdx)
            .replace(/ADDON_INDEX/g, addonIdx);
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        container.appendChild(tempDiv.firstElementChild);
        
        addonIndexes[itemIdx] = addonIdx + 1;
    }

    function removeAddon(btn) {
        btn.closest('.addon-row').remove();
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        
        // Sum item prices
        document.querySelectorAll('.price-input').forEach(input => {
            total += Number(input.value) || 0;
        });
        
        // Sum addon prices
        document.querySelectorAll('.addon-price').forEach(input => {
            total += Number(input.value) || 0;
        });
        
        document.getElementById('total-budget').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    // Add first item by default
    document.addEventListener('DOMContentLoaded', () => {
        addItem();
    });
</script>
@endpush
@endsection
