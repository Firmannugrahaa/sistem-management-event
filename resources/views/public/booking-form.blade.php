<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Event - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Book Your Event</h1>
                @if(isset($package))
                    <p class="text-lg text-blue-600 font-semibold">Booking Paket: {{ $package->name }}</p>
                    <p class="text-sm text-gray-600 mt-1">Isi form di bawah untuk melanjutkan booking paket pilihan Anda</p>
                @else
                    <p class="text-lg text-gray-600">Isi form di bawah untuk memulai perencanaan event Anda</p>
                @endif
            </div>

            @if(isset($package))
                <!-- Package Details Card -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-md p-6 mb-8 border border-blue-100">
                    <div class="flex items-start gap-4">
                        @if($package->thumbnail_path)
                            <img src="{{ asset('storage/' . $package->thumbnail_path) }}" 
                                 alt="{{ $package->name }}" 
                                 class="w-24 h-24 object-cover rounded-lg shadow-sm">
                        @endif
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($package->description, 150) }}</p>
                            <div class="flex items-center gap-4">
                                <div class="bg-white px-4 py-2 rounded-lg shadow-sm">
                                    <p class="text-xs text-gray-500">Harga Paket</p>
                                    <p class="text-lg font-bold text-blue-600">Rp {{ number_format($package->final_price, 0, ',', '.') }}</p>
                                </div>
                                @if($package->items && $package->items->count() > 0)
                                    <div class="bg-white px-4 py-2 rounded-lg shadow-sm">
                                        <p class="text-xs text-gray-500">Termasuk</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $package->items->count() }} Item/Layanan</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($package->items && $package->items->count() > 0)
                        <div class="mt-4 pt-4 border-t border-blue-100">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Yang Anda Dapatkan:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($package->items->take(6) as $item)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ $item->item_name }}</span>
                                        @if($item->quantity > 1)
                                            <span class="text-xs text-gray-400">({{ $item->quantity }}x)</span>
                                        @endif
                                    </div>
                                @endforeach
                                @if($package->items->count() > 6)
                                    <div class="text-sm text-blue-600 font-medium">
                                        +{{ $package->items->count() - 6 }} item lainnya
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                </div>
            @endif

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Progress Step Indicators -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">1</div>
                            <p class="text-xs mt-2 font-medium text-blue-600">
                                @if(isset($user))
                                    Form Details
                                @else
                                    Login/Register
                                @endif
                            </p>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">2</div>
                            <p class="text-xs mt-2 font-medium text-gray-500">
                                @if(isset($user))
                                    Review Booking
                                @else
                                    Form Details
                                @endif
                            </p>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">3</div>
                            <p class="text-xs mt-2 font-medium text-gray-500">Submit</p>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">4</div>
                            <p class="text-xs mt-2 font-medium text-gray-500">Confirmed</p>
                        </div>
                    </div>
                </div>

                @if(!isset($user))
                    {{-- User not logged in - Show warning --}}
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Anda belum login. Silakan <a href="{{ route('login') }}" class="font-semibold underline">login</a> atau 
                                    <a href="{{ route('register') }}" class="font-semibold underline">daftar</a> terlebih dahulu untuk melanjutkan booking.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('public.booking.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Pribadi
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                <input type="text" name="client_name" id="client_name" 
                                    value="{{ old('client_name', $user->name ?? '') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_name') border-red-500 @enderror"
                                    placeholder="Masukkan nama lengkap Anda"
                                    @if(isset($user) && $user) readonly @endif>
                                @if(isset($user) && $user)
                                    <p class="mt-1 text-xs text-gray-500">Data diambil dari akun Anda</p>
                                @endif
                                @error('client_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="client_email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="client_email" id="client_email" 
                                    value="{{ old('client_email', $user->email ?? '') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_email') border-red-500 @enderror"
                                    placeholder="email@example.com"
                                    @if(isset($user) && $user) readonly @endif>
                                @if(isset($user) && $user)
                                    <p class="mt-1 text-xs text-gray-500">Data diambil dari akun Anda</p>
                                @endif
                                @error('client_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-2">No. WhatsApp/Telepon *</label>
                                <input type="tel" name="client_phone" id="client_phone" 
                                    value="{{ old('client_phone', $user->phone_number ?? '') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_phone') border-red-500 @enderror"
                                    placeholder="08xxxxxxxxxx">
                                @error('client_phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Event Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Detail Event
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="event_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Event *</label>
                                <select name="event_type" id="event_type" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('event_type') border-red-500 @enderror"
                                    @if(isset($package) && $package->event_type) readonly @endif>
                                    <option value="">Pilih Tipe Event</option>
                                    <option value="Wedding" {{ (isset($package) && $package->event_type == 'Wedding') || old('event_type') == 'Wedding' ? 'selected' : '' }}>Pernikahan</option>
                                    <option value="Prewedding" {{ (isset($package) && $package->event_type == 'Prewedding') || old('event_type') == 'Prewedding' ? 'selected' : '' }}>Prewedding</option>
                                    <option value="Birthday" {{ (isset($package) && $package->event_type == 'Birthday') || old('event_type') == 'Birthday' ? 'selected' : '' }}>Ulang Tahun</option>
                                    <option value="Corporate" {{ (isset($package) && $package->event_type == 'Corporate') || old('event_type') == 'Corporate' ? 'selected' : '' }}>Corporate Event</option>
                                    <option value="Conference" {{ (isset($package) && $package->event_type == 'Conference') || old('event_type') == 'Conference' ? 'selected' : '' }}>Conference/Seminar</option>
                                    <option value="Engagement" {{ (isset($package) && $package->event_type == 'Engagement') || old('event_type') == 'Engagement' ? 'selected' : '' }}>Tunangan</option>
                                    <option value="Other" {{ (isset($package) && $package->event_type == 'Other') || old('event_type') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @if(isset($package) && $package->event_type)
                                    <p class="mt-1 text-xs text-blue-600">
                                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Tipe event otomatis dipilih sesuai kategori paket
                                    </p>
                                @endif
                                @error('event_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Event *</label>
                                <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" required 
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('event_date') border-red-500 @enderror">
                                @error('event_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Budget (Opsional)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-gray-500 font-medium">Rp</span>
                                    <input type="number" name="budget" id="budget" value="{{ old('budget') }}" min="0" step="100000"
                                        class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('budget') border-red-500 @enderror"
                                        placeholder="5000000">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Estimasi budget untuk event Anda</p>
                                @error('budget')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Pilih Vendor Per Kategori
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    @if(isset($package))
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">Mode Paket</span>
                                        Vendor sudah dipilih sesuai paket. Anda bisa mengubahnya jika diperlukan.
                                    @else
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Mode Custom</span>
                                        Pilih vendor sesuai kebutuhan Anda untuk setiap kategori.
                                    @endif
                                </p>

                                <div x-data="vendorSelection()" class="space-y-4">
                                    @foreach($serviceTypes as $serviceType)
                                        @if($serviceType->vendors && $serviceType->vendors->count() > 0)
                                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    {{ $serviceType->name }} 
                                                    @if(isset($packageVendors[$serviceType->id]))
                                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">Dari Paket</span>
                                                    @endif
                                                </label>
                                                
                                                <select 
                                                    name="vendors[{{ $serviceType->id }}][vendor_id]" 
                                                    x-model="selectedVendors['{{ $serviceType->id }}']"
                                                    @change="handleVendorChange('{{ $serviceType->id }}')"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                    <option value="">Pilih {{ $serviceType->name }} (Opsional)</option>
                                                    @foreach($serviceType->vendors as $vendor)
                                                        <option value="{{ $vendor->id }}" 
                                                            {{ isset($packageVendors[$serviceType->id]) && $packageVendors[$serviceType->id] == $vendor->id ? 'selected' : '' }}>
                                                            {{ $vendor->brand_name ?? $vendor->name }}
                                                        </option>
                                                    @endforeach
                                                    <option value="non-partner">🔹 Vendor Non Rekanan (Vendor Luar)</option>
                                                </select>
                                                
                                                <!-- Non-Partner Vendor Form (shown when 'non-partner' is selected) -->
                                                <div x-show="selectedVendors['{{ $serviceType->id }}'] === 'non-partner'" 
                                                     x-transition
                                                     class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-4 space-y-3">
                                                    
                                                    <div class="flex items-start gap-2 mb-3">
                                                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                        </svg>
                                                        <p class="text-sm text-yellow-800">
                                                            <strong>Catatan:</strong> 
                                                            @php
                                                                $charge = $nonPartnerCharges[$serviceType->name] ?? $nonPartnerCharges['default'];
                                                            @endphp
                                                            @if($charge > 0)
                                                                Akan dikenakan biaya tambahan <strong class="text-yellow-900">Rp {{ number_format($charge, 0, ',', '.') }}</strong> untuk vendor non rekanan.
                                                            @else
                                                                Tidak ada biaya tambahan untuk kategori ini.
                                                            @endif
                                                        </p>
                                                    </div>
                                                    
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Vendor <span class="text-red-500">*</span></label>
                                                        <input type="text" 
                                                            name="vendors[{{ $serviceType->id }}][non_partner_name]"
                                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Contoh: Vendor {{ $serviceType->name }} ABC"
                                                            x-bind:required="selectedVendors['{{ $serviceType->id }}'] === 'non-partner'">
                                                    </div>
                                                    
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Kontak Vendor <span class="text-red-500">*</span></label>
                                                        <input type="text" 
                                                            name="vendors[{{ $serviceType->id }}][non_partner_contact]"
                                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                            placeholder="No. Telepon atau Email"
                                                            x-bind:required="selectedVendors['{{ $serviceType->id }}'] === 'non-partner'">
                                                    </div>
                                                    
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                                                        <textarea 
                                                            name="vendors[{{ $serviceType->id }}][non_partner_notes]"
                                                            rows="2"
                                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Detail atau kebutuhan khusus..."></textarea>
                                                    </div>
                                                    
                                                    <input type="hidden" 
                                                        name="vendors[{{ $serviceType->id }}][non_partner_charge]" 
                                                        value="{{ $charge }}">
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    
                                    <script>
                                        function vendorSelection() {
                                            return {
                                                selectedVendors: {
                                                    @foreach($serviceTypes as $serviceType)
                                                        @if(isset($packageVendors[$serviceType->id]))
                                                            '{{ $serviceType->id }}': '{{ $packageVendors[$serviceType->id] }}',
                                                        @else
                                                            '{{ $serviceType->id }}': '',
                                                        @endif
                                                    @endforeach
                                                },
                                                handleVendorChange(serviceTypeId) {
                                                    // Can add additional logic here if needed
                                                    console.log('Vendor changed for service type:', serviceTypeId);
                                                }
                                            }
                                        }
                                    </script>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan / Detail Kebutuhan</label>
                                <textarea name="message" id="message" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror"
                                    placeholder="Ceritakan detail kebutuhan event Anda...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('landing.page') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                            ← Kembali ke Home
                        </a>
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition transform hover:scale-105">
                            @if(isset($user))
                                Submit Booking Request
                            @else
                                Lanjutkan ke Registrasi
                            @endif
                            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex">
                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-900">Proses Booking</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            @if(isset($user))
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Isi form booking dengan lengkap</li>
                                    <li>Review detail booking Anda</li>
                                    <li>Submit booking request</li>
                                    <li>Tim kami akan segera memproses dan menghubungi Anda</li>
                                </ul>
                            @else
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Login atau daftar akun terlebih dahulu</li>
                                    <li>Isi form booking dengan lengkap</li>
                                    <li>Submit booking request</li>
                                    <li>Tim kami akan segera memproses dan menghubungi Anda</li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert for Duplicate Booking Check --}}
    @if(session('booking_check'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('booking_check') === 'same_package')
                    // User has existing booking for the same package
                    Swal.fire({
                        title: 'Paket Sudah Dipesan',
                        html: "Anda sudah memiliki booking untuk paket <strong>{{ session('package_name') }}</strong>.<br>Apakah ingin membuat booking baru untuk tanggal berbeda?",
                        icon: 'warning',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Lanjutkan Booking Baru',
                        denyButtonText: 'Lihat Booking Saya',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#012A4A',
                        denyButtonColor: '#27AE60',
                        cancelButtonColor: '#d33',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Continue with booking - do nothing, stay on form
                        } else if (result.isDenied) {
                            // Redirect to bookings list
                            window.location.href = "{{ route('client.requests.index') }}";
                        } else {
                            // Cancel - go back to package detail
                            window.history.back();
                        }
                    });
                @elseif(session('booking_check') === 'other_package')
                    // User has existing booking for other package
                    Swal.fire({
                        title: 'Booking Lain Terdeteksi',
                        html: "Anda sudah memiliki booking paket lain.<br>Apakah ingin membuat booking untuk paket <strong>{{ session('package_name') }}</strong> juga?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Lanjutkan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#012A4A',
                        cancelButtonColor: '#d33',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Continue with booking - do nothing, stay on form
                        } else {
                            // Cancel - go back to package detail
                            window.history.back();
                        }
                    });
                @endif
            });
        </script>
    @endif
</body>
</html>
