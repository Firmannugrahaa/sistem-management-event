<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Event Planning</h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Plan Your Event</h1>
            <p class="text-gray-600">Select your preferred venue and vendors for your special event</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Venue Selection -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Select Venue</h2>
                
                <div class="space-y-4 mb-6">
                    @forelse($venues as $venue)
                    <div class="border rounded-lg p-4 hover:border-blue-500 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg text-gray-800">{{ $venue->name }}</h3>
                                <p class="text-gray-600 text-sm">{{ $venue->address }}</p>
                                <p class="text-blue-600 font-semibold mt-1">Rp {{ number_format($venue->price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <button 
                                    class="select-venue-btn px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                    data-venue-id="{{ $venue->id }}"
                                    data-venue-name="{{ $venue->name }}"
                                >
                                    Select
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-600">No venues available at the moment.</p>
                    @endforelse
                </div>
                
                <div id="selected-venue" class="mt-4 p-4 bg-blue-50 rounded-lg hidden">
                    <h3 class="font-bold text-gray-800">Selected Venue:</h3>
                    <p id="selected-venue-name" class="text-gray-700"></p>
                    <input type="hidden" id="selected-venue-id" name="selected_venue_id">
                    <button id="remove-venue" class="mt-2 text-red-600 hover:text-red-800 text-sm">Remove Selection</button>
                </div>
            </div>

            <!-- Vendor Selection -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Select Vendors</h2>
                
                <div class="space-y-4 mb-6">
                    @forelse($vendors as $vendor)
                    <div class="border rounded-lg p-4 hover:border-purple-500 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg text-gray-800">{{ $vendor->user ? $vendor->user->name : $vendor->contact_person }}</h3>
                                <p class="text-gray-600 text-sm">
                                    Contact: {{ $vendor->phone_number ?? $vendor->user?->phone ?? 'N/A' }}
                                </p>
                                <p class="text-gray-600 text-sm mt-1">Service Type: {{ $vendor->serviceType ? $vendor->serviceType->name : 'Not specified' }}</p>
                            </div>
                            <div>
                                <button
                                    class="select-vendor-btn px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"
                                    data-vendor-id="{{ $vendor->id }}"
                                    data-vendor-name="{{ $vendor->user ? $vendor->user->name : $vendor->contact_person }}"
                                >
                                    Select
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-600">No vendors available at the moment.</p>
                    @endforelse
                </div>
                
                <div id="selected-vendors" class="mt-4">
                    <h3 class="font-bold text-gray-800 mb-2">Selected Vendors:</h3>
                    <div id="selected-vendors-list" class="space-y-2"></div>
                    <button id="review-order" class="mt-4 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors hidden">
                        Review Your Order
                    </button>
                </div>
            </div>
        </div>

        <!-- Progress Navigation -->
        <div class="mt-8 flex justify-end">
            <a 
                href="{{ route('client.order.review') }}" 
                id="continue-to-review"
                class="continue-btn px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                disabled
            >
                Continue to Review Order
            </a>
        </div>
    </div>

    <!-- Hidden form to submit selections -->
    <form id="selections-form" method="POST" action="{{ route('client.order.store.selections') }}" style="display: none;">
        @csrf
        <input type="hidden" name="selected_venue_id" id="form-selected-venue-id" value="">
        <div id="form-selected-vendor-ids"></div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedVenue = null;
            let selectedVendors = [];

            // Venue selection
            document.querySelectorAll('.select-venue-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const venueId = this.getAttribute('data-venue-id');
                    const venueName = this.getAttribute('data-venue-name');

                    // Remove previous selection
                    document.querySelectorAll('.select-venue-btn').forEach(btn => {
                        btn.textContent = 'Select';
                        btn.classList.remove('bg-blue-700');
                        btn.classList.add('bg-blue-600');
                    });

                    // Update button state
                    this.textContent = 'Selected';
                    this.classList.remove('bg-blue-600');
                    this.classList.add('bg-blue-700');

                    // Update selected venue
                    selectedVenue = { id: venueId, name: venueName };

                    // Show selected venue
                    document.getElementById('selected-venue').classList.remove('hidden');
                    document.getElementById('selected-venue-name').textContent = venueName;
                    document.getElementById('selected-venue-id').value = venueId;
                    document.getElementById('form-selected-venue-id').value = venueId;
                });
            });

            // Remove venue selection
            document.getElementById('remove-venue').addEventListener('click', function() {
                selectedVenue = null;

                // Reset button state
                document.querySelectorAll('.select-venue-btn').forEach(btn => {
                    btn.textContent = 'Select';
                    btn.classList.remove('bg-blue-700');
                    btn.classList.add('bg-blue-600');
                });

                // Hide selected venue
                document.getElementById('selected-venue').classList.add('hidden');
                document.getElementById('selected-venue-name').textContent = '';
                document.getElementById('selected-venue-id').value = '';
                document.getElementById('form-selected-venue-id').value = '';
            });

            // Vendor selection
            document.querySelectorAll('.select-vendor-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const vendorId = this.getAttribute('data-vendor-id');
                    const vendorName = this.getAttribute('data-vendor-name');

                    // Check if vendor is already selected
                    const existingIndex = selectedVendors.findIndex(v => v.id === vendorId);

                    if (existingIndex > -1) {
                        // Remove vendor
                        selectedVendors.splice(existingIndex, 1);
                        this.textContent = 'Select';
                        this.classList.remove('bg-purple-700');
                        this.classList.add('bg-purple-600');

                        // Remove from display
                        const vendorItem = document.querySelector(`[data-display-vendor-id="${vendorId}"]`);
                        if (vendorItem) vendorItem.remove();

                        // Remove from form
                        const formVendorInput = document.querySelector(`[name="selected_vendor_ids[]"][value="${vendorId}"]`);
                        if (formVendorInput) formVendorInput.remove();
                    } else {
                        // Add vendor
                        selectedVendors.push({ id: vendorId, name: vendorName });
                        this.textContent = 'Selected';
                        this.classList.remove('bg-purple-600');
                        this.classList.add('bg-purple-700');

                        // Add to display
                        const vendorDiv = document.createElement('div');
                        vendorDiv.className = 'flex justify-between items-center bg-gray-50 p-2 rounded';
                        vendorDiv.setAttribute('data-display-vendor-id', vendorId);
                        vendorDiv.innerHTML = `
                            <span>${vendorName}</span>
                            <button type="button" class="remove-vendor-btn text-red-600 hover:text-red-800" data-vendor-id="${vendorId}">Remove</button>
                        `;
                        document.getElementById('selected-vendors-list').appendChild(vendorDiv);

                        // Add to form
                        const formVendorInput = document.createElement('input');
                        formVendorInput.type = 'hidden';
                        formVendorInput.name = 'selected_vendor_ids[]';
                        formVendorInput.value = vendorId;
                        document.getElementById('form-selected-vendor-ids').appendChild(formVendorInput);
                    }

                    // Update continue button state
                    updateContinueButton();
                });
            });

            // Remove vendor from display list
            document.getElementById('selected-vendors-list').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-vendor-btn')) {
                    const vendorId = e.target.getAttribute('data-vendor-id');

                    // Find and remove vendor from selected vendors
                    const index = selectedVendors.findIndex(v => v.id === vendorId);
                    if (index > -1) {
                        selectedVendors.splice(index, 1);

                        // Update vendor button to unselected state
                        const vendorButton = document.querySelector(`.select-vendor-btn[data-vendor-id="${vendorId}"]`);
                        if (vendorButton) {
                            vendorButton.textContent = 'Select';
                            vendorButton.classList.remove('bg-purple-700');
                            vendorButton.classList.add('bg-purple-600');
                        }

                        // Remove vendor from display
                        e.target.closest('[data-display-vendor-id]').remove();

                        // Remove from form
                        const formVendorInput = document.querySelector(`[name="selected_vendor_ids[]"][value="${vendorId}"]`);
                        if (formVendorInput) formVendorInput.remove();

                        // Update continue button state
                        updateContinueButton();
                    }
                }
            });

            // Update continue button based on selections
            function updateContinueButton() {
                const continueBtn = document.getElementById('continue-to-review');

                if (selectedVenue && selectedVendors.length > 0) {
                    continueBtn.disabled = false;
                    continueBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
                } else {
                    continueBtn.disabled = true;
                    continueBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
                }
            }

            // Submit form when clicking continue
            document.getElementById('continue-to-review').addEventListener('click', function(e) {
                e.preventDefault();

                if (selectedVenue && selectedVendors.length > 0) {
                    // Submit the form
                    document.getElementById('selections-form').submit();
                } else {
                    alert('Please select at least one venue and one vendor before proceeding.');
                }
            });
        });
    </script>
</x-app-layout>