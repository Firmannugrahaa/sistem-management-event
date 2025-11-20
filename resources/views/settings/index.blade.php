<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Perusahaan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Informasi Perusahaan</h3>
                    
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
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
                    
                    <form method="POST" action="{{ route('superuser.settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Logo Upload Section -->
                        <div class="mb-6">
                            <h4 class="text-md font-semibold mb-3">Logo Perusahaan</h4>

                            <div class="flex flex-col md:flex-row items-start gap-6">
                                <div class="flex-shrink-0">
                                    @if($settings->company_logo_path)
                                        <img src="{{ asset('storage/' . $settings->company_logo_path) }}"
                                             alt="Logo Perusahaan"
                                             class="w-24 h-24 object-contain rounded-lg border border-gray-300">
                                    @else
                                        <div class="w-24 h-24 bg-gray-200 rounded-lg border border-gray-300 flex items-center justify-center">
                                            <span class="text-gray-500 text-sm">No Logo</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <x-input-label for="company_logo_path" :value="__('Pilih Logo')" />
                                    <input type="file"
                                           id="company_logo_path"
                                           name="company_logo_path"
                                           class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/svg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPEG, PNG, JPG, GIF, SVG. Maksimal ukuran: 2MB</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="company_name" :value="__('Nama Perusahaan')" />
                                <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name', $settings->company_name)" required />
                            </div>

                            <div>
                                <x-input-label for="company_phone" :value="__('Nomor Telepon')" />
                                <x-text-input id="company_phone" class="block mt-1 w-full" type="text" name="company_phone" :value="old('company_phone', $settings->company_phone)" required />
                            </div>

                            <div>
                                <x-input-label for="company_email" :value="__('Email Perusahaan')" />
                                <x-text-input id="company_email" class="block mt-1 w-full" type="email" name="company_email" :value="old('company_email', $settings->company_email)" required />
                            </div>

                            <div>
                                <x-input-label for="tax_number" :value="__('Nomor NPWP')" />
                                <x-text-input id="tax_number" class="block mt-1 w-full" type="text" name="tax_number" :value="old('tax_number', $settings->tax_number)" />
                            </div>
                        </div>
                        
                        <!-- Address Options -->
                        <div class="mb-6">
                            <h4 class="text-md font-semibold mb-4">Alamat Perusahaan</h4>

                            <!-- Manual Address Input -->
                            <div class="mb-6">
                                <x-input-label for="company_address" :value="__('Alamat Lengkap (Opsional)')" />
                                <textarea id="company_address" name="company_address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('company_address', $settings->company_address) }}</textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Isi secara manual jika ingin menggunakan alamat lengkap yang berbeda dari struktur alamat di bawah</p>
                            </div>

                            <!-- Structured Address Input -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <h5 class="text-sm font-medium mb-3">Struktur Alamat (akan digunakan jika alamat lengkap tidak diisi)</h5>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="province_id" :value="__('Provinsi')" />
                                        <select id="province_id" name="province_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">-- Pilih Provinsi --</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province->id }}" {{ old('province_id', $settings->province_id) == $province->id ? 'selected' : '' }}>
                                                    {{ $province->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="city_id" :value="__('Kota/Kabupaten')" />
                                        <select id="city_id" name="city_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" {{ $settings->province_id ? '' : 'disabled' }}>
                                            <option value="">-- Pilih Kota/Kabupaten --</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id', $settings->city_id) == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="district_id" :value="__('Kecamatan')" />
                                        <select id="district_id" name="district_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" {{ $settings->city_id ? '' : 'disabled' }}>
                                            <option value="">-- Pilih Kecamatan --</option>
                                            @foreach($districts as $district)
                                                <option value="{{ $district->id }}" {{ old('district_id', $settings->district_id) == $district->id ? 'selected' : '' }}>
                                                    {{ $district->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="village_id" :value="__('Desa/Kelurahan')" />
                                        <select id="village_id" name="village_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" {{ $settings->district_id ? '' : 'disabled' }}>
                                            <option value="">-- Pilih Desa/Kelurahan --</option>
                                            @foreach($villages as $village)
                                                <option value="{{ $village->id }}" {{ old('village_id', $settings->village_id) == $village->id ? 'selected' : '' }}>
                                                    {{ $village->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="postal_code" :value="__('Kode Pos')" />
                                        <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code', $settings->postal_code)"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)" />
                                    </div>

                                    <div>
                                        <x-input-label for="street_name" :value="__('Nama Jalan')" />
                                        <x-text-input id="street_name" class="block mt-1 w-full" type="text" name="street_name" :value="old('street_name', $settings->street_name)" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="building" :value="__('Gedung')" />
                                        <x-text-input id="building" class="block mt-1 w-full" type="text" name="building" :value="old('building', $settings->building)" />
                                    </div>
                                    <div>
                                        <x-input-label for="company_number" :value="__('No Perusahaan')" />
                                        <x-text-input id="company_number" class="block mt-1 w-full" type="text" name="company_number" :value="old('company_number', $settings->company_number)" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="address_details" :value="__('Detail Lainnya')" />
                                    <textarea id="address_details" name="address_details" rows="2" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address_details', $settings->address_details) }}</textarea>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Contoh: Gedung A, Lantai 3, Unit 5, atau informasi tambahan alamat</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h4 class="text-md font-semibold mb-3">Informasi Rekening Bank</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="bank_account_name" :value="__('Atas Nama')" />
                                    <x-text-input id="bank_account_name" class="block mt-1 w-full" type="text" name="bank_account_name" :value="old('bank_account_name', $settings->bank_account_name)" />
                                </div>
                                
                                <div>
                                    <x-input-label for="bank_account_number" :value="__('Nomor Rekening')" />
                                    <x-text-input id="bank_account_number" class="block mt-1 w-full" type="text" name="bank_account_number" :value="old('bank_account_number', $settings->bank_account_number)" />
                                </div>
                                
                                <div>
                                    <x-input-label for="bank_name" :value="__('Nama Bank')" />
                                    <x-text-input id="bank_name" class="block mt-1 w-full" type="text" name="bank_name" :value="old('bank_name', $settings->bank_name)" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <x-primary-button type="submit">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_id');
            const citySelect = document.getElementById('city_id');
            const districtSelect = document.getElementById('district_id');
            const villageSelect = document.getElementById('village_id');
            const postalCodeInput = document.getElementById('postal_code');
            let allDistricts = []; // Store all districts for the selected city
            let allVillages = []; // Store all villages for the selected district

            // Get saved values from PHP
            const savedProvinceId = '{{ old('province_id', $settings->province_id ?? '') }}';
            const savedCityId = '{{ old('city_id', $settings->city_id ?? '') }}';
            const savedDistrictId = '{{ old('district_id', $settings->district_id ?? '') }}';
            const savedVillageId = '{{ old('village_id', $settings->village_id ?? '') }}';
            const savedPostalCode = '{{ old('postal_code', $settings->postal_code ?? '') }}';

            // Function to fetch provinces
            function loadProvinces() {
                // Show loading state
                provinceSelect.innerHTML = '<option value="">Memuat provinsi...</option>';
                provinceSelect.disabled = true;

                fetch('/api/provinces')
                    .then(response => response.json())
                    .then(data => {
                        // Clear existing options except the first one
                        provinceSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';

                        if (data && Array.isArray(data) && data.length > 0) {
                            data.forEach(province => {
                                const option = document.createElement('option');
                                option.value = province.id;
                                option.textContent = province.name;

                                if (savedProvinceId && savedProvinceId == province.id) {
                                    option.selected = true;
                                }

                                provinceSelect.appendChild(option);
                            });

                            provinceSelect.disabled = false;

                            // Initialize dependent dropdowns if values exist
                            if (savedProvinceId) {
                                loadCitiesForProvince(savedProvinceId, true);
                            }
                        } else {
                            // If no data returned, still enable the dropdown so user can see it's empty
                            provinceSelect.innerHTML = '<option value="">-- Tidak ada provinsi ditemukan --</option>';
                            provinceSelect.disabled = false;
                            console.warn('No provinces found');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching provinces:', error);
                        // Show error message in dropdown
                        provinceSelect.innerHTML = '<option value="">-- Gagal memuat provinsi --</option>';
                        provinceSelect.disabled = false; // Ensure dropdown is enabled even if API fails
                    });
            }

            // Function to load cities for a specific province
            function loadCitiesForProvince(provinceId, initializing = false) {
                if (provinceId) {
                    // Show loading state
                    citySelect.innerHTML = '<option value="">Memuat kota/kabupaten...</option>';
                    citySelect.disabled = true;

                    fetch(`/api/cities/${provinceId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Clear city options except the first one
                            citySelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';

                            if (data && Array.isArray(data) && data.length > 0) {
                                data.forEach(city => {
                                    const option = document.createElement('option');
                                    option.value = city.id;
                                    option.textContent = city.name;

                                    if (savedCityId && savedCityId == city.id && initializing) {
                                        option.selected = true;
                                    }

                                    citySelect.appendChild(option);
                                });

                                citySelect.disabled = false;

                                // Initialize dependent dropdown if initializing and value exists
                                if (initializing && savedCityId) {
                                    loadDistrictsForCity(savedCityId, true);
                                }
                            } else {
                                // If no data returned, still enable the dropdown so user can see it's empty
                                citySelect.innerHTML = '<option value="">-- Tidak ada kota/kabupaten ditemukan --</option>';
                                citySelect.disabled = true;
                                console.warn('No cities found for province ID:', provinceId);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching cities:', error);
                            // Show error message in dropdown
                            citySelect.innerHTML = '<option value="">-- Gagal memuat kota/kabupaten --</option>';
                            citySelect.disabled = false; // Still enable so user can see the error
                        });
                } else {
                    citySelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                    districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    villageSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                    citySelect.disabled = true;
                    districtSelect.disabled = true;
                    villageSelect.disabled = true;
                    postalCodeInput.disabled = true;
                }
            }

            // Function to load districts for a specific city
            function loadDistrictsForCity(cityId, initializing = false) {
                if (cityId) {
                    // Show loading state
                    districtSelect.innerHTML = '<option value="">Memuat kecamatan...</option>';
                    districtSelect.disabled = true;

                    // Clear district options except the first one
                    allDistricts = [];

                    fetch(`/api/districts/${cityId}`)
                        .then(response => response.json())
                        .then(data => {
                            allDistricts = data; // Store all districts data

                            if (data && Array.isArray(data) && data.length > 0) {
                                data.forEach(district => {
                                    const option = document.createElement('option');
                                    option.value = district.id;
                                    option.textContent = district.name;

                                    if (savedDistrictId && savedDistrictId == district.id && initializing) {
                                        option.selected = true;
                                    }

                                    districtSelect.appendChild(option);
                                });

                                districtSelect.disabled = false;

                                // Initialize village dropdown if initializing and value exists
                                if (initializing && savedDistrictId) {
                                    loadVillagesForDistrict(savedDistrictId, true);
                                } else if (!initializing) {
                                    // Clear dependent dropdowns when parent selection changes
                                    districtSelect.selectedIndex = 0; // Reset to default option
                                    villageSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                                    villageSelect.disabled = true;
                                    villageSelect.selectedIndex = 0; // Reset to default option
                                    postalCodeInput.value = '';
                                }
                            } else {
                                // If no data returned, still enable the dropdown so user can see it's empty
                                districtSelect.innerHTML = '<option value="">-- Tidak ada kecamatan ditemukan --</option>';
                                districtSelect.disabled = true;
                                console.warn('No districts found for city ID:', cityId);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching districts:', error);
                            // Show error message in dropdown
                            districtSelect.innerHTML = '<option value="">-- Gagal memuat kecamatan --</option>';
                            districtSelect.disabled = false; // Still enable so user can see the error
                        });
                } else {
                    districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    villageSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                    allDistricts = [];
                    allVillages = [];
                    districtSelect.disabled = true;
                    villageSelect.disabled = true;
                    postalCodeInput.disabled = true;
                }
            }

            // Function to load villages for a specific district
            function loadVillagesForDistrict(districtId, initializing = false) {
                if (districtId) {
                    // Show loading state
                    villageSelect.innerHTML = '<option value="">Memuat desa/kelurahan...</option>';
                    villageSelect.disabled = true;

                    // Clear village options except the first one
                    allVillages = [];

                    // Use direct fetch to app's local database
                    fetch(`/api/villages/${districtId}`)
                        .then(response => response.json())
                        .then(data => {
                            allVillages = data; // Store all villages data

                            if (data && Array.isArray(data) && data.length > 0) {
                                data.forEach(village => {
                                    const option = document.createElement('option');
                                    option.value = village.id;
                                    option.textContent = village.name;

                                    if (savedVillageId && savedVillageId == village.id && initializing) {
                                        option.selected = true;

                                        // Auto-populate postal code if available and initializing
                                        if (initializing && village.postal_code) {
                                            postalCodeInput.value = savedPostalCode || village.postal_code;
                                            postalCodeInput.disabled = false;
                                        }
                                    }

                                    villageSelect.appendChild(option);
                                });

                                villageSelect.disabled = false;

                                // If initializing and we have a saved village but no saved postal code,
                                // try to populate postal code from the selected village
                                if (initializing && savedVillageId && !savedPostalCode) {
                                    const selectedVillage = Array.isArray(data) ? data.find(v => v.id == savedVillageId) : undefined;
                                    if (selectedVillage && selectedVillage.postal_code) {
                                        postalCodeInput.value = selectedVillage.postal_code;
                                    }
                                }
                            } else {
                                // If no data returned, still enable the dropdown so user can see it's empty
                                villageSelect.innerHTML = '<option value="">-- Tidak ada desa/kelurahan ditemukan --</option>';
                                villageSelect.disabled = true;
                                console.warn('No villages found for district ID:', districtId);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching villages:', error);
                            // Show error message in dropdown
                            villageSelect.innerHTML = '<option value="">-- Gagal memuat desa/kelurahan --</option>';
                            villageSelect.disabled = false; // Still enable so user can see the error
                        });
                } else {
                    villageSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                    allVillages = [];
                    villageSelect.disabled = true;
                    postalCodeInput.value = '';
                }
            }

            // Function to fetch cities based on selected province
            provinceSelect.addEventListener('change', function() {
                // When province changes, reset dependent dropdowns
                citySelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                villageSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                citySelect.disabled = true;
                districtSelect.disabled = true;
                villageSelect.disabled = true;
                postalCodeInput.value = '';

                loadCitiesForProvince(this.value);
            });

            // Function to fetch districts based on selected city
            citySelect.addEventListener('change', function() {
                // When city changes, reset dependent dropdowns
                districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                villageSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                districtSelect.disabled = true;
                villageSelect.disabled = true;
                postalCodeInput.value = '';

                loadDistrictsForCity(this.value);
            });

            // Function to fetch villages based on selected district
            districtSelect.addEventListener('change', function() {
                // When district changes, reset dependent fields
                villageSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                villageSelect.disabled = true;
                postalCodeInput.value = '';

                loadVillagesForDistrict(this.value);
            });

            // Function to handle village change and populate postal code
            villageSelect.addEventListener('change', function() {
                const selectedVillageId = this.value;

                if (selectedVillageId && Array.isArray(allVillages) && allVillages.length > 0) {
                    const selectedVillage = allVillages.find(v => v.id == selectedVillageId);
                    if (selectedVillage && selectedVillage.postal_code) {
                        postalCodeInput.value = selectedVillage.postal_code;
                        postalCodeInput.disabled = false;
                    } else {
                        postalCodeInput.value = '';
                        postalCodeInput.disabled = false; // Still allow manual input if no postal code is found
                    }
                } else {
                    postalCodeInput.value = '';
                    postalCodeInput.disabled = false; // Keep enabled for manual input
                }
            });

            // Load provinces on page load
            if (provinceSelect) {
                loadProvinces();
            } else {
                console.error('Province select element not found');
            }
        });
    </script>
</x-app-layout>