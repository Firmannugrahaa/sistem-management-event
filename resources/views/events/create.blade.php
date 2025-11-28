{{-- resources/views/events/create.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Buat Event Baru') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          @if ($errors->any())
          <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong>Oops! Ada yang salah:</strong>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form id="create-event-form" action="{{ route('events.store') }}" method="POST">
            @csrf
            
            @if(isset($clientRequest))
                <input type="hidden" name="client_request_id" value="{{ $clientRequest->id }}">
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Creating event from lead: <strong>{{ $clientRequest->client_name }} - {{ $clientRequest->event_type }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-4">
              <x-input-label for="event_name" :value="__('Nama Event')" />
              <x-text-input id="event_name" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="event_name" :value="old('event_name', isset($clientRequest) ? $clientRequest->event_type . ' - ' . $clientRequest->client_name : '')" required autofocus />
            </div>

            {{-- VENUE SELECTION WITH TWO OPTIONS --}}
            <div class="mb-4">
              <x-input-label for="venue_type" :value="__('Tipe Venue')" />
              <select name="venue_type" id="venue_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" onchange="toggleVenueFields()">
                <option value="none" {{ old('venue_type') == 'none' ? 'selected' : '' }}>-- Tidak ada venue --</option>
                <option value="standard" {{ old('venue_type') == 'standard' ? 'selected' : '' }}>Venue Standar</option>
                <option value="vendor" {{ old('venue_type') == 'vendor' ? 'selected' : '' }}>Vendor Venue</option>
              </select>
            </div>

            {{-- STANDARD VENUE FIELD --}}
            <div id="standard-venue-field" class="mb-4" style="{{ old('venue_type') == 'standard' ? '' : 'display: none;' }}">
              <x-input-label for="venue_id" :value="__('Pilih Venue Standar')" />
              <select name="venue_id" id="venue_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">-- Pilih venue standar --</option>
                @foreach ($venues as $venue)
                <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                  {{ $venue->name }} (Kapasitas: {{ $venue->capacity }} orang, Harga: Rp {{ number_format($venue->price, 0, ',', '.') }})
                </option>
                @endforeach
              </select>
            </div>

            {{-- VENDOR VENUE FIELD --}}
            <div id="vendor-venue-field" class="mb-4" style="{{ old('venue_type') == 'vendor' ? '' : 'display: none;' }}">
              <x-input-label for="vendor_venue_id" :value="__('Pilih Vendor Venue')" />
              <select name="vendor_venue_id" id="vendor_venue_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">-- Pilih vendor yang menyediakan venue --</option>
                @foreach ($vendorVenues as $vendor)
                <option value="{{ $vendor->id }}" {{ old('vendor_venue_id') == $vendor->id ? 'selected' : '' }}>
                  {{ $vendor->user->name ?? $vendor->contact_person }} ({{ $vendor->phone_number }})
                </option>
                @endforeach
              </select>
            </div>

            {{-- VENDOR VENUE DETAILS FIELD (shown when vendor venue is selected) --}}
            <div id="vendor-venue-details" class="mb-4" style="{{ old('venue_type') == 'vendor' ? '' : 'display: none;' }}">
              <x-input-label for="vendor_venue_name" :value="__('Nama Venue Vendor')" />
              <x-text-input id="vendor_venue_name" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="vendor_venue_name" :value="old('vendor_venue_name')" placeholder="Masukkan nama venue dari vendor" />

              <x-input-label for="vendor_venue_price" class="mt-4" :value="__('Harga Venue Vendor')" />
              <x-text-input id="vendor_venue_price" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="number" name="vendor_venue_price" :value="old('vendor_venue_price')" placeholder="Masukkan harga venue dari vendor" />
            </div>

            <script>
              function toggleVenueFields() {
                const venueType = document.getElementById('venue_type').value;
                const standardVenueField = document.getElementById('standard-venue-field');
                const vendorVenueField = document.getElementById('vendor-venue-field');
                const vendorVenueDetails = document.getElementById('vendor-venue-details');

                // Hide all fields initially
                if(standardVenueField) standardVenueField.style.display = 'none';
                if(vendorVenueField) vendorVenueField.style.display = 'none';
                if(vendorVenueDetails) vendorVenueDetails.style.display = 'none';

                // Show appropriate field based on selection
                if(venueType === 'standard' && standardVenueField) {
                  standardVenueField.style.display = 'block';
                } else if(venueType === 'vendor' && vendorVenueField) {
                  vendorVenueField.style.display = 'block';
                  if(vendorVenueDetails) vendorVenueDetails.style.display = 'block';
                }
              }

              // Handle vendor selection to automatically populate venue details
              function handleVendorVenueSelection() {
                const vendorSelect = document.getElementById('vendor_venue_id');
                if (!vendorSelect) return;

                vendorSelect.addEventListener('change', function() {
                  const selectedVendorId = this.value;
                  if (!selectedVendorId) {
                    document.getElementById('vendor_venue_name').value = '';
                    document.getElementById('vendor_venue_price').value = '';
                    return;
                  }

                  // Find the vendor in the dropdown options data
                  const selectedOption = this.options[this.selectedIndex];
                  if (selectedOption) {
                    // In a real implementation, we would fetch service details from the backend
                    // For now, we'll simulate getting the first available service of the vendor
                    fetch(`/api/vendor/${selectedVendorId}/venue-service`)
                      .then(response => response.json())
                      .then(data => {
                        if (data.service) {
                          document.getElementById('vendor_venue_name').value = data.service.name;
                          document.getElementById('vendor_venue_price').value = data.service.price;
                        }
                      })
                      .catch(error => {
                        console.log('Vendor service not found, using vendor contact person as name');
                        // Fallback: use vendor name as venue name
                        document.getElementById('vendor_venue_name').value = selectedOption.text.split('(')[0].trim();
                        document.getElementById('vendor_venue_price').value = '';
                      });
                  }
                });
              }

              // Initialize on page load
              document.addEventListener('DOMContentLoaded', function() {
                toggleVenueFields();
                handleVendorVenueSelection();
              });
            </script>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <x-input-label for="start_time" :value="__('Waktu Mulai')" />
                <x-text-input id="start_time" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="datetime-local" name="start_time" :value="old('start_time', isset($clientRequest) ? $clientRequest->event_date->format('Y-m-d\TH:i') : '')" required onchange="updateEndTime()" />
              </div>
              <div>
                <x-input-label for="end_time" :value="__('Waktu Selesai')" />
                <x-text-input id="end_time" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="datetime-local" name="end_time" :value="old('end_time')" required />
              </div>
            </div>

            <script>
              function updateEndTime() {
                const startTime = document.getElementById('start_time').value;
                const endTimeField = document.getElementById('end_time');
                
                if (startTime && !endTimeField.value) {
                  // Auto-fill end_time = start_time + 3 hours
                  const start = new Date(startTime);
                  start.setHours(start.getHours() + 3);
                  
                  // Format to datetime-local input format (YYYY-MM-DDTHH:mm)
                  const year = start.getFullYear();
                  const month = String(start.getMonth() + 1).padStart(2, '0');
                  const day = String(start.getDate()).padStart(2, '0');
                  const hours = String(start.getHours()).padStart(2, '0');
                  const minutes = String(start.getMinutes()).padStart(2, '0');
                  
                  endTimeField.value = `${year}-${month}-${day}T${hours}:${minutes}`;
                }
              }
              
              // Also auto-fill on page load if start_time has value
              document.addEventListener('DOMContentLoaded', function() {
                updateEndTime();
              });
            </script>

            <div class="mb-4">
              <x-input-label for="description" :value="__('Deskripsi Event')" />
              <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', isset($clientRequest) ? "Event Type: " . $clientRequest->event_type . "\nBudget: " . number_format($clientRequest->budget) . "\nNotes: " . $clientRequest->message : '') }}</textarea>
            </div>

            {{-- CLIENT INFORMATION SECTION --}}
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">Informasi Klien</h3>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <x-input-label for="client_name" :value="__('Nama Klien')" />
                  <x-text-input id="client_name" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="client_name" :value="old('client_name', isset($clientRequest) ? $clientRequest->client_name : '')" placeholder="Masukkan nama klien" />
                </div>
                <div>
                  <x-input-label for="client_phone" :value="__('Nomor Telepon')" />
                  <x-text-input id="client_phone" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="client_phone" :value="old('client_phone', isset($clientRequest) ? $clientRequest->client_phone : '')" placeholder="Contoh: +6281234567890" />
                </div>
              </div>
              
              <div class="mb-4">
                <x-input-label for="client_email" :value="__('Email')" />
                <x-text-input id="client_email" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="email" name="client_email" :value="old('client_email', isset($clientRequest) ? $clientRequest->client_email : '')" placeholder="email@klien.com" />
              </div>
              
              <div class="mb-4">
                <x-input-label for="client_address" :value="__('Alamat')" />
                <textarea id="client_address" name="client_address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Alamat lengkap klien">{{ old('client_address') }}</textarea>
              </div>
            </div>

            <x-primary-button type="submit" id="submit-btn">
              Simpan Event
            </x-primary-button>
          </form>

        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('create-event-form');
      const submitBtn = document.getElementById('submit-btn');
      
      if (form && submitBtn) {
        submitBtn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          Swal.fire({
            title: 'Konfirmasi Pembuatan Event',
            text: 'Apakah Anda yakin ingin membuat event baru ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Buat Event',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#012A4A',
            cancelButtonColor: '#6B7280'
          }).then((result) => {
            if (result.isConfirmed) {
              // Show loading
              Swal.fire({
                title: 'Menyimpan Event...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                  Swal.showLoading();
                }
              });
              
              // Force submit
              form.submit();
            }
          });
        });
      }
    });
  </script>
  @endpush
</x-app-layout>