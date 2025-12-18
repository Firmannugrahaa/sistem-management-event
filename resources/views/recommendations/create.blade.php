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
    <div class="item-row border border-gray-200 rounded-lg p-4 bg-gray-50 relative group transition hover:border-blue-300">
        <button type="button" onclick="removeItem(this)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- First Row: Category, Type -->
            <div class="md:col-span-4">
                <label class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                <select name="items[INDEX][category]" class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white" required>
                    <option value="">Select Category</option>
                    @foreach(['Venue', 'Catering', 'Decoration', 'MUA', 'Attire', 'Documentation', 'Entertainment', 'WO/Organizer', 'Souvenir', 'Invitation', 'Other'] as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-4">
                 <label class="block text-xs font-medium text-gray-500 mb-1">Recommendation Type</label>
                 <select name="items[INDEX][recommendation_type]" class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                     <option value="primary">⭐ Utama (Recommended)</option>
                     <option value="alternative">🔄 Alternatif</option>
                     <option value="upgrade">💎 Upgrade Option</option>
                 </select>
            </div>

             <div class="md:col-span-4">
                <label class="block text-xs font-medium text-gray-500 mb-1">Est. Price (Rp)</label>
                <input type="number" name="items[INDEX][estimated_price]" 
                       class="price-input w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="0" oninput="calculateTotal()">
            </div>

            <!-- Second Row: Vendor, Service Name -->
            <div class="md:col-span-6">
                <label class="block text-xs font-medium text-gray-500 mb-1">Vendor</label>
                
                <!-- Internal Vendor Select -->
                <div class="internal-vendor-wrapper">
                    <select name="items[INDEX][vendor_id]" class="vendor-select w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="handleVendorChange(this)">
                        <option value="">Select Registered Vendor</option>
                        <option value="external" class="font-semibold text-blue-600">+ Add External Vendor</option>
                        <optgroup label="Registered Vendors">
                            @foreach($vendors as $category => $list)
                                @foreach($list as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $category }})</option>
                                @endforeach
                            @endforeach
                        </optgroup>
                    </select>
                    
                    <!-- Product/Package Quick Select -->
                    <div class="product-select-wrapper hidden mt-2">
                        <select class="product-select w-full rounded-md border-gray-200 text-xs text-gray-600 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" onchange="autoFillProduct(this)">
                            <option value="">-- Auto-fill from Package/Product --</option>
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1 ml-1">* Select to auto-fill details below</p>
                    </div>
                </div>

                <!-- External Vendor Input (Hidden by default) -->
                <div class="external-vendor-input hidden mt-2">
                    <div class="flex">
                        <input type="text" name="items[INDEX][external_vendor_name]" 
                               class="w-full rounded-l-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter vendor name manually">
                         <button type="button" onclick="cancelExternal(this)" class="px-3 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md text-xs text-gray-600 hover:bg-gray-200">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            <div class="md:col-span-6">
                <label class="block text-xs font-medium text-gray-500 mb-1">Package / Service Details</label>
                <input type="text" name="items[INDEX][service_name]" 
                       class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g. Platinum Package 300 Pax">
            </div>

            <!-- Third Row: Reason/Notes -->
            <div class="md:col-span-12">
                <label class="block text-xs font-medium text-gray-500 mb-1">Reason for Recommendation</label>
                <textarea name="items[INDEX][notes]" rows="2" 
                       class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Why do you recommend this vendor? e.g. 'Best value for money', 'Matches your color theme', etc."></textarea>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    let itemIndex = 0;

    function addItem() {
        const container = document.getElementById('items-container');
        const template = document.getElementById('item-template');
        const clone = template.content.cloneNode(true);
        
        // Replace placeholder INDEX with unique index
        const html = clone.querySelector('.item-row').outerHTML.replace(/INDEX/g, itemIndex);
        
        // Create a temporary div to hold the HTML string and convert it to DOM node
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        container.appendChild(tempDiv.firstElementChild);
        itemIndex++;
    }

    function removeItem(btn) {
        btn.closest('.item-row').remove();
        calculateTotal();
    }

    function handleVendorChange(select) {
        const wrapper = select.closest('.md\\:col-span-6');
        const externalInput = wrapper.querySelector('.external-vendor-input');
        const internalWrapper = wrapper.querySelector('.internal-vendor-wrapper');
        const productWrapper = wrapper.querySelector('.product-select-wrapper');
        const productSelect = wrapper.querySelector('.product-select');
        
        if (select.value === 'external') {
            internalWrapper.classList.add('hidden');
            if(productWrapper) productWrapper.classList.add('hidden');
            externalInput.classList.remove('hidden');
            select.value = ''; // Reset select
            
            // Enable external input
            externalInput.querySelector('input').disabled = false;
        } else if (select.value) {
            // Load products via AJAX
            loadVendorProducts(select.value, productSelect, productWrapper);
        } else {
            if(productWrapper) productWrapper.classList.add('hidden');
        }
    }

    function loadVendorProducts(vendorId, selectElement, wrapperElement) {
        if(!selectElement || !wrapperElement) return;

        // Clear previous options
        selectElement.innerHTML = '<option value="">Loading packages...</option>';
        wrapperElement.classList.remove('hidden');
        selectElement.disabled = true;

        fetch(`/vendors/${vendorId}/offerings`)
            .then(response => {
                if(!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                selectElement.innerHTML = '<option value="">-- Auto-fill from Package/Product --</option>';
                
                if (data.length === 0) {
                     const option = document.createElement('option');
                     option.textContent = 'No packages found';
                     option.disabled = true;
                     selectElement.appendChild(option);
                } else {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name + ' - Rp ' + new Intl.NumberFormat('id-ID').format(item.price);
                        
                        // Store data attributes
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
                selectElement.innerHTML = '<option value="">Failed to load packages</option>';
            });
    }

    function autoFillProduct(select) {
        const option = select.options[select.selectedIndex];
        if (!option.value) return;

        const row = select.closest('.item-row');
        const nameInput = row.querySelector('input[name*="[service_name]"]');
        const priceInput = row.querySelector('.price-input');
        const notesInput = row.querySelector('textarea[name*="[notes]"]');

        if (nameInput) nameInput.value = option.dataset.name || '';
        
        if (priceInput) {
             priceInput.value = option.dataset.price || '';
             // Trigger change for total calculation
             calculateTotal(); 
        }
        
        if (notesInput && !notesInput.value) { // Only fill notes if empty
            notesInput.value = option.dataset.desc || '';
        }
    }

    function cancelExternal(btn) {
        const wrapper = btn.closest('.md\\:col-span-6');
        const externalInput = wrapper.querySelector('.external-vendor-input');
        const internalWrapper = wrapper.querySelector('.internal-vendor-wrapper');
        
        externalInput.classList.add('hidden');
        internalWrapper.classList.remove('hidden');
        
        // Disable external input so it doesn't submit
        externalInput.querySelector('input').value = '';
    }

    function calculateTotal() {
        const inputs = document.querySelectorAll('.price-input');
        let total = 0;
        
        inputs.forEach(input => {
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
