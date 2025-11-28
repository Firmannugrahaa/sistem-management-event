<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
            {{ __('Business Profile') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('vendor.business-profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Informasi Dasar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Brand Name -->
                        <div>
                            <label for="brand_name" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                Nama Vendor / Brand <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="brand_name" 
                                   id="brand_name" 
                                   value="{{ old('brand_name', $vendor->brand_name) }}"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent"
                                   required>
                            @error('brand_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Service Type -->
                        <div>
                            <label for="service_type_id" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                Jenis Vendor <span class="text-red-500">*</span>
                            </label>
                            <select name="service_type_id" 
                                    id="service_type_id"
                                    class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent"
                                    required>
                                <option value="">Pilih Jenis Vendor</option>
                                @foreach($serviceTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('service_type_id', $vendor->service_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Person -->
                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                Nama Kontak Person <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="contact_person" 
                                   id="contact_person" 
                                   value="{{ old('contact_person', $vendor->contact_person) }}"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent"
                                   required>
                            @error('contact_person')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="phone_number" 
                                   id="phone_number" 
                                   value="{{ old('phone_number', $vendor->phone_number) }}"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent"
                                   required>
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $vendor->email) }}"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                WhatsApp
                            </label>
                            <input type="text" 
                                   name="whatsapp" 
                                   id="whatsapp" 
                                   value="{{ old('whatsapp', $vendor->whatsapp) }}"
                                   placeholder="628123456789"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            @error('whatsapp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Logo -->
                    <div class="mt-6">
                        <label for="logo" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                            Logo Vendor
                        </label>
                        @if($vendor->logo_path)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $vendor->logo_path) }}" 
                                     alt="Current Logo" 
                                     class="h-24 w-24 object-contain rounded-lg border border-[#E0E0E0] p-2">
                            </div>
                        @endif
                        <input type="file" 
                               name="logo" 
                               id="logo" 
                               accept="image/*"
                               class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Max: 2MB</p>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                            Deskripsi Singkat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent"
                                  placeholder="Ceritakan tentang bisnis Anda..."
                                  required>{{ old('description', $vendor->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Lokasi</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                Alamat Lengkap
                            </label>
                            <textarea name="address" 
                                      id="address" 
                                      rows="3"
                                      class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">{{ old('address', $vendor->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                Kota/Kabupaten
                            </label>
                            <input type="text" 
                                   name="location" 
                                   id="location" 
                                   value="{{ old('location', $vendor->location) }}"
                                   placeholder="Contoh: Jakarta Selatan"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Media Sosial</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                Instagram
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-[#E0E0E0] bg-gray-50 text-gray-500 text-sm">
                                    @
                                </span>
                                <input type="text" 
                                       name="instagram" 
                                       id="instagram" 
                                       value="{{ old('instagram', $vendor->instagram) }}"
                                       placeholder="username"
                                       class="flex-1 px-4 py-2 border border-[#E0E0E0] rounded-r-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            </div>
                            @error('instagram')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tiktok" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                TikTok
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-[#E0E0E0] bg-gray-50 text-gray-500 text-sm">
                                    @
                                </span>
                                <input type="text" 
                                       name="tiktok" 
                                       id="tiktok" 
                                       value="{{ old('tiktok', $vendor->tiktok) }}"
                                       placeholder="username"
                                       class="flex-1 px-4 py-2 border border-[#E0E0E0] rounded-r-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            </div>
                            @error('tiktok')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="facebook" class="block text-sm font-medium text-[#1A1A1A] mb-2">
                                Facebook
                            </label>
                            <input type="text" 
                                   name="facebook" 
                                   id="facebook" 
                                   value="{{ old('facebook', $vendor->facebook) }}"
                                   placeholder="URL Facebook"
                                   class="w-full px-4 py-2 border border-[#E0E0E0] rounded-lg focus:ring-2 focus:ring-[#27AE60] focus:border-transparent">
                            @error('facebook')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Operating Hours -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Jam Operasional</h3>
                    
                    <div class="space-y-3">
                        @php
                            $days = [
                                'monday' => 'Senin',
                                'tuesday' => 'Selasa',
                                'wednesday' => 'Rabu',
                                'thursday' => 'Kamis',
                                'friday' => 'Jumat',
                                'saturday' => 'Sabtu',
                                'sunday' => 'Minggu',
                            ];
                            $operatingHours = old('operating_hours', $vendor->operating_hours ?? []);
                        @endphp

                        @foreach($days as $key => $label)
                            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                <div class="w-24">
                                    <span class="text-sm font-medium text-[#1A1A1A]">{{ $label }}</span>
                                </div>
                                
                                <div class="flex items-center gap-2 flex-1">
                                    <input type="time" 
                                           name="operating_hours[{{ $key }}][open]" 
                                           value="{{ $operatingHours[$key]['open'] ?? '09:00' }}"
                                           class="px-3 py-2 border border-[#E0E0E0] rounded-lg text-sm focus:ring-2 focus:ring-[#27AE60] focus:border-transparent"
                                           {{ isset($operatingHours[$key]['is_closed']) && $operatingHours[$key]['is_closed'] ? 'disabled' : '' }}>
                                    <span class="text-gray-500">-</span>
                                    <input type="time" 
                                           name="operating_hours[{{ $key }}][close]" 
                                           value="{{ $operatingHours[$key]['close'] ?? '17:00' }}"
                                           class="px-3 py-2 border border-[#E0E0E0] rounded-lg text-sm focus:ring-2 focus:ring-[#27AE60] focus:border-transparent"
                                           {{ isset($operatingHours[$key]['is_closed']) && $operatingHours[$key]['is_closed'] ? 'disabled' : '' }}>
                                </div>

                                <label class="flex items-center gap-2">
                                    <input type="checkbox" 
                                           name="operating_hours[{{ $key }}][is_closed]" 
                                           value="1"
                                           {{ isset($operatingHours[$key]['is_closed']) && $operatingHours[$key]['is_closed'] ? 'checked' : '' }}
                                           class="rounded border-[#E0E0E0] text-[#27AE60] focus:ring-[#27AE60]"
                                           onchange="toggleDayInputs(this, '{{ $key }}')">
                                    <span class="text-sm text-gray-600">Tutup</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Display Settings -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">Pengaturan Tampilan</h3>
                    
                    <div class="flex items-center gap-3">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active" 
                               value="1"
                               {{ old('is_active', $vendor->is_active) ? 'checked' : '' }}
                               class="rounded border-[#E0E0E0] text-[#27AE60] focus:ring-[#27AE60] h-5 w-5">
                        <label for="is_active" class="text-sm font-medium text-[#1A1A1A]">
                            Tampilkan profil saya di website utama
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Jika dinonaktifkan, profil Anda tidak akan muncul di halaman vendor website.
                    </p>

                    <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200">
                        <input type="checkbox" 
                               name="show_stock_on_profile" 
                               id="show_stock_on_profile" 
                               value="1"
                               {{ old('show_stock_on_profile', $vendor->show_stock_on_profile) ? 'checked' : '' }}
                               class="rounded border-[#E0E0E0] text-[#27AE60] focus:ring-[#27AE60] h-5 w-5">
                        <label for="show_stock_on_profile" class="text-sm font-medium text-[#1A1A1A]">
                            Tampilkan jumlah stok katalog di profil publik
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Jika diaktifkan, pengunjung dapat melihat jumlah stok produk katalog Anda.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('dashboard') }}" 
                       class="px-6 py-3 bg-gray-100 text-[#1A1A1A] rounded-lg font-medium hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-[#012A4A] text-white rounded-lg font-medium hover:bg-[#013d70] transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleDayInputs(checkbox, day) {
            const openInput = document.querySelector(`input[name="operating_hours[${day}][open]"]`);
            const closeInput = document.querySelector(`input[name="operating_hours[${day}][close]"]`);
            
            if (checkbox.checked) {
                openInput.disabled = true;
                closeInput.disabled = true;
                openInput.value = '';
                closeInput.value = '';
            } else {
                openInput.disabled = false;
                closeInput.disabled = false;
                openInput.value = '09:00';
                closeInput.value = '17:00';
            }
        }
    </script>
    @endpush
</x-app-layout>
