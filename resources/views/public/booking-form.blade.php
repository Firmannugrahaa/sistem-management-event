<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Event - {{ config('app.name') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="py-12" x-data="bookingWizard()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Book Your Event</h1>
                @if(isset($package))
                    <p class="text-lg text-blue-600 font-semibold">Booking Paket: {{ $package->name }}</p>
                    <p class="text-sm text-gray-600 mt-1">Ikuti langkah-langkah di bawah untuk menyelesaikan booking</p>
                @else
                    <p class="text-lg text-gray-600">Ikuti langkah-langkah di bawah untuk memulai perencanaan event Anda</p>
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
                </div>
            @endif

            <!-- Main Card -->
<<<<<<< HEAD
            <div class="bg-white rounded-2xl shadow-xl p-8" x-data="bookingWizard()">
                <!-- Progress Step Indicators -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <!-- Step 1: Form Details -->
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all"
                                 :class="currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'">
                                1
                            </div>
                            <p class="text-xs mt-2 font-medium transition-all"
                               :class="currentStep >= 1 ? 'text-blue-600' : 'text-gray-500'">
                                Form Details
                            </p>
                        </div>
                        <div class="flex-1 h-1 mx-2 transition-all"
                             :class="currentStep >= 2 ? 'bg-blue-600' : 'bg-gray-200'"></div>
                        
                        <!-- Step 2: Review Booking -->
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all"
                                 :class="currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'">
                                2
                            </div>
                            <p class="text-xs mt-2 font-medium transition-all"
                               :class="currentStep >= 2 ? 'text-blue-600' : 'text-gray-500'">
                                Review Booking
                            </p>
                        </div>
                        <div class="flex-1 h-1 mx-2 transition-all"
                             :class="currentStep >= 3 ? 'bg-blue-600' : 'bg-gray-200'"></div>
                        
                        <!-- Step 3: Submit -->
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all"
                                 :class="currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'">
                                3
                            </div>
                            <p class="text-xs mt-2 font-medium transition-all"
                               :class="currentStep >= 3 ? 'text-blue-600' : 'text-gray-500'">
                                Submit
                            </p>
                        </div>
                        <div class="flex-1 h-1 mx-2 transition-all"
                             :class="currentStep >= 4 ? 'bg-blue-600' : 'bg-gray-200'"></div>
                        
                        <!-- Step 4: Confirmed -->
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all"
                                 :class="currentStep >= 4 ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-500'">
                                <span x-show="currentStep < 4">4</span>
                                <svg x-show="currentStep >= 4" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-xs mt-2 font-medium transition-all"
                               :class="currentStep >= 4 ? 'text-green-600' : 'text-gray-500'">
                                Confirmed
                            </p>
                        </div>
=======
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Stepper Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <template x-for="(step, index) in steps" :key="index">
                            <div class="flex items-center" :class="{ 'flex-1': index < steps.length - 1 }">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-300"
                                         :class="{
                                             'bg-blue-600 text-white': currentStep > index + 1,
                                             'bg-blue-600 text-white ring-4 ring-blue-200': currentStep === index + 1,
                                             'bg-gray-200 text-gray-500': currentStep < index + 1
                                         }">
                                        <template x-if="currentStep > index + 1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </template>
                                        <span x-show="currentStep <= index + 1" x-text="index + 1"></span>
                                    </div>
                                    <p class="text-xs mt-2 font-medium transition-colors duration-300 text-center"
                                       :class="{
                                           'text-blue-600': currentStep >= index + 1,
                                           'text-gray-500': currentStep < index + 1
                                       }"
                                       x-text="step.name"></p>
                                </div>
                                <div x-show="index < steps.length - 1" 
                                     class="flex-1 h-1 mx-4 rounded transition-colors duration-300"
                                     :class="{
                                         'bg-blue-600': currentStep > index + 1,
                                         'bg-gray-200': currentStep <= index + 1
                                     }"></div>
                            </div>
                        </template>
>>>>>>> fix-from-e378004
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

<<<<<<< HEAD
                <form action="{{ route('public.booking.store') }}" method="POST" class="space-y-6" id="booking-form">
                    @csrf
                    
                    {{-- Hidden field for package --}}
                    @if($package)
                        <input type="hidden" name="event_package_id" value="{{ $package->id }}">
                    @endif

                    <!-- STEP 1: FORM DETAILS -->
                    <div x-show="currentStep === 1" 
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="space-y-6">

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
                                <input type="text" name="client_name" id="client_name" x-model="formData.client_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_name') border-red-500 @enderror"
                                    placeholder="Nama Lengkap Anda">
                                @error('client_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="client_email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="client_email" id="client_email" x-model="formData.client_email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_email') border-red-500 @enderror"
                                    placeholder="email@example.com">
                                @error('client_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp *</label>
                                <input type="tel" name="client_phone" id="client_phone" x-model="formData.client_phone" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_phone') border-red-500 @enderror"
                                    placeholder="Contoh: 081234567890">
                                @error('client_phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
=======
                <!-- STEP 1: Form Details -->
                <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <form @submit.prevent="validateStep1()">
                        @csrf
                        @if(isset($package))
                            <input type="hidden" name="package_id" x-model="formData.package_id">
                        @endif

                        <!-- Personal Information Section -->
                        <div class="border-b border-gray-200 pb-6 mb-6">
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
                                        x-model="formData.client_name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': errors.client_name }"
                                        placeholder="Masukkan nama lengkap Anda"
                                        @if(isset($user)) readonly @endif>
                                    <p x-show="errors.client_name" x-text="errors.client_name" class="mt-2 text-sm text-red-600"></p>
                                </div>

                                <div>
                                    <label for="client_email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                    <input type="email" name="client_email" id="client_email" 
                                        x-model="formData.client_email"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': errors.client_email }"
                                        placeholder="email@example.com"
                                        @if(isset($user)) readonly @endif>
                                    <p x-show="errors.client_email" x-text="errors.client_email" class="mt-2 text-sm text-red-600"></p>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-2">No. WhatsApp/Telepon *</label>
                                    <input type="tel" name="client_phone" id="client_phone" 
                                        x-model="formData.client_phone"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': errors.client_phone }"
                                        placeholder="08xxxxxxxxxx">
                                    <p x-show="errors.client_phone" x-text="errors.client_phone" class="mt-2 text-sm text-red-600"></p>
                                </div>
>>>>>>> fix-from-e378004
                            </div>
                        </div>

                        <!-- Event Information Section -->
                        <div class="border-b border-gray-200 pb-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Detail Event
                            </h2>

<<<<<<< HEAD
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="event_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Event *</label>
                                <select name="event_type" id="event_type" required x-model="formData.event_type"
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
                                <input type="date" name="event_date" id="event_date" 
                                    x-model="formData.event_date" required 
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('event_date') border-red-500 @enderror">
                                @error('event_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Wedding Couple Information (Only shown if Wedding) --}}
                            <div x-show="formData.event_type === 'Wedding'" x-transition 
                                 class="md:col-span-2 bg-pink-50 rounded-xl p-6 border border-pink-100">
                                <h3 class="text-lg font-medium text-pink-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    Data Pasangan Pengantin
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mempelai Pria</label>
                                        <input type="text" name="groom_name" x-model="formData.groom_name"
                                            :required="formData.event_type === 'Wedding' && !formData.fill_couple_later"
                                            :disabled="formData.fill_couple_later"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent placeholder-gray-400"
                                            placeholder="Nama Mempelai Pria">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mempelai Wanita</label>
                                        <input type="text" name="bride_name" x-model="formData.bride_name"
                                            :required="formData.event_type === 'Wedding' && !formData.fill_couple_later"
                                            :disabled="formData.fill_couple_later"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent placeholder-gray-400"
                                            placeholder="Nama Mempelai Wanita">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="inline-flex items-center">
                                            <input type="hidden" name="fill_couple_later" :value="formData.fill_couple_later ? 1 : 0">
                                            <input type="checkbox" x-model="formData.fill_couple_later" class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Saya belum memiliki data lengkap, isi nanti melalui dashboard</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Budget (Opsional)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-gray-500 font-medium">Rp</span>
                                    <input type="number" name="budget" id="budget" 
                                        x-model="formData.budget" min="0" step="100000"
                                        class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('budget') border-red-500 @enderror"
                                        placeholder="5000000">
=======
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="event_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Event *</label>
                                    <select name="event_type" id="event_type" x-model="formData.event_type"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': errors.event_type }">
                                        <option value="">Pilih Tipe Event</option>
                                        <option value="Wedding">Pernikahan</option>
                                        <option value="Prewedding">Prewedding</option>
                                        <option value="Birthday">Ulang Tahun</option>
                                        <option value="Corporate">Corporate Event</option>
                                        <option value="Conference">Conference/Seminar</option>
                                        <option value="Engagement">Tunangan</option>
                                        <option value="Other">Lainnya</option>
                                    </select>
                                    <p x-show="errors.event_type" x-text="errors.event_type" class="mt-2 text-sm text-red-600"></p>
>>>>>>> fix-from-e378004
                                </div>

                                <div>
                                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Event *</label>
                                    <input type="date" name="event_date" id="event_date" 
                                        x-model="formData.event_date"
                                        :min="minDate"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        :class="{ 'border-red-500': errors.event_date }">
                                    <p class="mt-1 text-xs text-gray-500">Pilih tanggal minimal H+1 dari hari ini</p>
                                    <p x-show="errors.event_date" x-text="errors.event_date" class="mt-2 text-sm text-red-600"></p>
                                </div>

                                <div>
                                    <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Budget (Opsional)</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-3.5 text-gray-500 font-medium">Rp</span>
                                        <input type="number" name="budget" id="budget" x-model="formData.budget" min="0" step="100000"
                                            class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="5000000">
                                    </div>
                                </div>

                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan/Catatan</label>
                                    <textarea name="message" id="message" rows="3" x-model="formData.message"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Detail kebutuhan event Anda..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Wedding Couple Section (Show only for Wedding events) -->
                        <div x-show="formData.event_type === 'Wedding' || formData.event_type === 'Engagement'" 
                             x-transition
                             class="border-b border-gray-200 pb-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Data Calon Pengantin
                            </h2>

                            <div class="bg-pink-50 rounded-lg p-4 mb-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" x-model="formData.fill_couple_later" 
                                           class="w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500">
                                    <span class="text-sm text-gray-700">
                                        <strong>Isi Nanti</strong> - Saya akan melengkapi data pasangan dari dashboard sebelum hari-H
                                    </span>
                                </label>
                            </div>

                            <div x-show="!formData.fill_couple_later" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="cpp_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Calon Pengantin Pria</label>
                                    <input type="text" name="cpp_name" id="cpp_name" x-model="formData.cpp_name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                        placeholder="Nama lengkap CPP">
                                </div>
                                <div>
                                    <label for="cpw_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Calon Pengantin Wanita</label>
                                    <input type="text" name="cpw_name" id="cpw_name" x-model="formData.cpw_name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                        placeholder="Nama lengkap CPW">
                                </div>
                            </div>
                        </div>

                        <!-- Vendor Selection (if applicable) -->
                        @if(isset($serviceTypes) && $serviceTypes->count() > 0)
                        <div class="border-b border-gray-200 pb-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Pilih Vendor Per Kategori
                            </h2>
                            <p class="text-sm text-gray-600 mb-4">
                                @if(isset($package))
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">Mode Paket</span>
                                    Vendor sudah dipilih sesuai paket. Anda bisa mengubahnya jika diperlukan.
                                @else
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Mode Custom</span>
                                    Pilih vendor sesuai kebutuhan Anda untuk setiap kategori.
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 mb-4">Pilihan vendor akan ditampilkan di halaman Review Booking.</p>
                        </div>
                        @endif
                    </form>
                </div>

                <!-- STEP 2: Review Booking -->
                <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Ringkasan Booking</h3>
                                <button @click="currentStep = 1" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    Edit
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Nama</p>
                                    <p class="font-medium" x-text="formData.client_name"></p>
                                </div>
                                <div class="bg-white p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Email</p>
                                    <p class="font-medium" x-text="formData.client_email"></p>
                                </div>
                                <div class="bg-white p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">No. Telepon</p>
                                    <p class="font-medium" x-text="formData.client_phone"></p>
                                </div>
                                <div class="bg-white p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Tipe Event</p>
                                    <p class="font-medium" x-text="formData.event_type"></p>
                                </div>
                                <div class="bg-white p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Tanggal Event</p>
                                    <p class="font-medium" x-text="formatDate(formData.event_date)"></p>
                                </div>
                                <div class="bg-white p-4 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Budget</p>
                                    <p class="font-medium" x-text="formData.budget ? 'Rp ' + Number(formData.budget).toLocaleString('id-ID') : 'Tidak ditentukan'"></p>
                                </div>
                            </div>

<<<<<<< HEAD
                            <div class="md:col-span-2">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan / Detail Kebutuhan</label>
                                <textarea name="message" id="message" rows="4"
                                    x-model="formData.message"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror"
                                    placeholder="Ceritakan detail kebutuhan event Anda...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div> <!-- END STEP 1 -->

                <!-- STEP 2: REVIEW BOOKING -->
                <div x-show="currentStep === 2" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="space-y-6">
                    
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Detail Booking</h3>
                        
                        <div class="space-y-4">
                            <!-- Personal Info Review -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Informasi Pribadi</h4>
                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <span class="block text-xs text-gray-400">Nama Lengkap</span>
                                        <span class="font-medium text-gray-900" x-text="formData.client_name"></span>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-gray-400">Email</span>
                                        <span class="font-medium text-gray-900" x-text="formData.client_email"></span>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-gray-400">WhatsApp</span>
                                        <span class="font-medium text-gray-900" x-text="formData.client_phone"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- Event Info Review -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Detail Event</h4>
                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <span class="block text-xs text-gray-400">Tipe Event</span>
                                        <span class="font-medium text-gray-900" x-text="formData.event_type"></span>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-gray-400">Tanggal Event</span>
                                        <span class="font-medium text-gray-900" x-text="formData.event_date"></span>
                                    </div>
                                    <div x-show="formData.budget">
                                        <span class="block text-xs text-gray-400">Budget</span>
                                        <span class="font-medium text-gray-900">Rp <span x-text="formData.budget"></span></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Wedding Couple Review (Conditional) -->
                            <div x-show="formData.event_type === 'Wedding'" class="bg-pink-50 p-4 rounded-lg mt-4">
                                <h4 class="text-sm font-medium text-pink-800 uppercase tracking-wider mb-2">Pasangan Pengantin</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <template x-if="!formData.fill_couple_later">
                                        <div class="contents">
                                            <div>
                                                <span class="block text-xs text-pink-400">Mempelai Pria</span>
                                                <span class="font-medium text-pink-900" x-text="formData.groom_name"></span>
                                            </div>
                                            <div>
                                                <span class="block text-xs text-pink-400">Mempelai Wanita</span>
                                                <span class="font-medium text-pink-900" x-text="formData.bride_name"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="formData.fill_couple_later">
                                        <div class="col-span-2 text-sm text-pink-700 italic">
                                            Data pasangan akan dilengkapi nanti via dashboard
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- Message Review -->
                            <div x-show="formData.message">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pesan / Catatan</h4>
                                <p class="mt-1 text-gray-700 bg-white p-3 rounded border border-gray-100" x-text="formData.message"></p>
                            </div>
                        </div>
                    </div>
                </div> <!-- END STEP 2 -->

                <!-- STEP 3: SUBMIT CONFIRMATION -->
                <div x-show="currentStep === 3" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="text-center py-8">
                    
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-20"></span>
                        <svg class="w-10 h-10 text-blue-600 relative" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Siap untuk Submit?</h3>
                    <p class="text-gray-600 max-w-md mx-auto mb-8">
                        Pastikan semua data sudah benar. Setelah submit, tim kami akan segera memproses permintaan Anda.
                    </p>
                </div> <!-- END STEP 3 -->

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <!-- Back Button -->
                    <button type="button" 
                            x-show="currentStep > 1"
                            @click="prevStep()"
                            class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </button>
                    
                    <!-- Back to Home (Show only on Step 1) -->
                    <a href="{{ route('landing.page') }}" 
                       x-show="currentStep === 1"
                       class="text-gray-600 hover:text-gray-900 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Home
                    </a>

                    <!-- Next Button -->
                    <button type="button" 
                            x-show="currentStep < 3"
                            @click="nextStep()"
                            class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition transform hover:scale-105 flex items-center shadow-lg shadow-blue-200">
                        Review Booking
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Submit Button -->
                    <button type="button" 
                            x-show="currentStep === 3"
                            @click="submitForm()"
                            class="px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition transform hover:scale-105 flex items-center shadow-lg shadow-green-200">
                        Submit Booking Request
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                </div>
            </form>
            </div>
=======
                            <!-- Wedding Couple Info -->
                            <div x-show="formData.event_type === 'Wedding' || formData.event_type === 'Engagement'" class="mt-4">
                                <div class="bg-pink-50 p-4 rounded-lg">
                                    <p class="text-xs text-pink-600 font-medium mb-2">Data Calon Pengantin</p>
                                    <template x-if="formData.fill_couple_later">
                                        <p class="text-sm text-gray-600 italic">Akan diisi nanti dari dashboard</p>
                                    </template>
                                    <template x-if="!formData.fill_couple_later">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500">CPP</p>
                                                <p class="font-medium" x-text="formData.cpp_name || '-'"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">CPW</p>
                                                <p class="font-medium" x-text="formData.cpw_name || '-'"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Message -->
                            <div x-show="formData.message" class="mt-4 bg-white p-4 rounded-lg">
                                <p class="text-xs text-gray-500 mb-1">Pesan/Catatan</p>
                                <p class="text-sm text-gray-700" x-text="formData.message"></p>
                            </div>
                        </div>

                        @if(isset($package))
                        <div class="bg-blue-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Paket yang Dipilih</h3>
                            <div class="flex items-start gap-4">
                                @if($package->thumbnail_path)
                                    <img src="{{ asset('storage/' . $package->thumbnail_path) }}" class="w-20 h-20 object-cover rounded-lg">
                                @endif
                                <div>
                                    <p class="font-bold text-gray-900">{{ $package->name }}</p>
                                    <p class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($package->final_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- STEP 3: Submit (Loading) -->
                <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="text-center py-12">
                        <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent mb-6"></div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Memproses Booking Anda...</h3>
                        <p class="text-gray-600">Mohon tunggu sebentar, jangan tutup halaman ini.</p>
                    </div>
                </div>

                <!-- STEP 4: Confirmed -->
                <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="text-center py-8">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Booking Berhasil!</h3>
                        <p class="text-gray-600 mb-6">Terima kasih, booking Anda telah kami terima.</p>
                        
                        <div class="bg-gray-50 rounded-xl p-6 max-w-md mx-auto mb-8">
                            <p class="text-sm text-gray-500 mb-2">Nomor Booking Anda</p>
                            <p class="text-2xl font-bold text-blue-600" x-text="bookingNumber"></p>
                            <p class="text-xs text-gray-500 mt-4">Simpan nomor ini untuk referensi. Tim kami akan segera menghubungi Anda.</p>
                        </div>
>>>>>>> fix-from-e378004

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('client.dashboard') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                                Lihat Booking Saya
                            </a>
                            <a href="{{ route('landing.page') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition">
                                Kembali ke Home
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div x-show="currentStep < 3" class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
                    <button x-show="currentStep > 1" @click="prevStep()" type="button"
                        class="px-6 py-3 text-gray-600 hover:text-gray-900 font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </button>
                    <a x-show="currentStep === 1" href="{{ route('landing.page') }}" class="px-6 py-3 text-gray-600 hover:text-gray-900 font-medium">
                        ← Kembali ke Home
                    </a>
                    
                    <button x-show="currentStep === 1" @click="validateStep1()" type="button"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition flex items-center">
                        Lanjut ke Review
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    
                    <button x-show="currentStep === 2" @click="submitBooking()" type="button"
                        class="px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Submit Booking
                    </button>
                </div>
            </div>
        </div>
    </div>

<<<<<<< HEAD
    {{-- Alpine.js Booking Wizard Component --}}
=======
>>>>>>> fix-from-e378004
    <script>
        function bookingWizard() {
            return {
                currentStep: 1,
<<<<<<< HEAD
                formData: {
                    // Personal & Event Info
                    client_name: "{{ old('client_name', optional($user)->name ?? '') }}",
                    client_email: "{{ old('client_email', optional($user)->email ?? '') }}",
                    client_phone: "{{ old('client_phone', optional($user)->phone ?? optional($user)->phone_number ?? '') }}",
                    event_type: "{{ old('event_type', optional($package)->event_type ?? '') }}",
                    event_date: "{{ old('event_date') }}",
                    budget: "{{ old('budget') }}",
                    message: "{{ old('message') }}",
                    
                    // Wedding couple fields
                    groom_name: "{{ old('groom_name') }}",
                    bride_name: "{{ old('bride_name') }}",
                    fill_couple_later: {{ old('fill_couple_later') ? 'true' : 'false' }},
                },
                
                nextStep() {
                    if (this.validateCurrentStep()) {
                        this.currentStep++;
                        this.scrollToTop();
                    }
                },
                
                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                        this.scrollToTop();
                    }
                },
                
                goToStep(step) {
                    // Only allow going back or to next step if current is valid
                    if (step < this.currentStep) {
                        this.currentStep = step;
                        this.scrollToTop();
                    }
                },
                
                validateCurrentStep() {
                    if (this.currentStep === 1) {
                        // Validate required fields in step 1
                        const form = document.querySelector('form');
                        // Select inputs that are visible and required
                        const visibleRequiredFields = Array.from(form.querySelectorAll('[required]')).filter(field => {
                            return field.offsetParent !== null; // Check visibility
                        });
                        
                        let isValid = true;
                        
                        visibleRequiredFields.forEach(field => {
                            if (!field.value) {
                                isValid = false;
                                field.classList.add('border-red-500');
                                // Add shake animation or highlight
                            } else {
                                field.classList.remove('border-red-500');
                            }
                        });
                        
                        // Validate Email format
                        if (this.formData.client_email && !this.isValidEmail(this.formData.client_email)) {
                            isValid = false;
                            alert('Format email tidak valid');
                        }
                        
                        if (!isValid) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Mohon Lengkapi Data',
                                text: 'Beberapa field wajib belum diisi',
                                confirmButtonColor: '#012A4A'
                            });
                        }
                        
                        return isValid;
                    }
                    return true;
=======
                bookingNumber: '',
                minDate: new Date(Date.now() + 86400000).toISOString().split('T')[0], // Tomorrow
                steps: [
                    { name: 'Form Details' },
                    { name: 'Review Booking' },
                    { name: 'Submit' },
                    { name: 'Confirmed' }
                ],
                formData: {
                    package_id: '{{ $package->id ?? '' }}',
                    client_name: '{{ $user->name ?? old('client_name', '') }}',
                    client_email: '{{ $user->email ?? old('client_email', '') }}',
                    client_phone: '{{ $user->phone_number ?? old('client_phone', '') }}',
                    event_type: '{{ (isset($package) && $package->event_type) ? $package->event_type : old('event_type', '') }}',
                    event_date: '{{ old('event_date', '') }}',
                    budget: '{{ old('budget', '') }}',
                    message: '{{ old('message', '') }}',
                    cpp_name: '',
                    cpw_name: '',
                    fill_couple_later: false
                },
                errors: {},
                
                validateStep1() {
                    this.errors = {};
                    let isValid = true;

                    if (!this.formData.client_name.trim()) {
                        this.errors.client_name = 'Nama lengkap wajib diisi';
                        isValid = false;
                    }
                    if (!this.formData.client_email.trim()) {
                        this.errors.client_email = 'Email wajib diisi';
                        isValid = false;
                    } else if (!this.isValidEmail(this.formData.client_email)) {
                        this.errors.client_email = 'Format email tidak valid';
                        isValid = false;
                    }
                    if (!this.formData.client_phone.trim()) {
                        this.errors.client_phone = 'No. telepon wajib diisi';
                        isValid = false;
                    }
                    if (!this.formData.event_type) {
                        this.errors.event_type = 'Tipe event wajib dipilih';
                        isValid = false;
                    }
                    if (!this.formData.event_date) {
                        this.errors.event_date = 'Tanggal event wajib diisi';
                        isValid = false;
                    } else if (new Date(this.formData.event_date) <= new Date()) {
                        this.errors.event_date = 'Tanggal harus lebih dari hari ini';
                        isValid = false;
                    }

                    if (isValid) {
                        this.currentStep = 2;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                async submitBooking() {
                    this.currentStep = 3;
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    try {
                        const response = await fetch('{{ route('public.booking.store.ajax') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.bookingNumber = data.booking_number;
                            this.currentStep = 4;
                        } else {
                            this.currentStep = 2;
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: data.message || 'Terjadi kesalahan. Silakan coba lagi.',
                            });
                        }
                    } catch (error) {
                        this.currentStep = 2;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                        });
                    }
>>>>>>> fix-from-e378004
                },

                isValidEmail(email) {
                    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                },
<<<<<<< HEAD
                
                scrollToTop() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                
                submitForm() {
                    document.querySelector('form').submit();
=======

                formatDate(dateString) {
                    if (!dateString) return '-';
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    return new Date(dateString).toLocaleDateString('id-ID', options);
>>>>>>> fix-from-e378004
                }
            }
        }
    </script>

    {{-- SweetAlert for Duplicate Booking Check --}}
    @if(session('booking_check'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('booking_check') === 'same_package')
                    Swal.fire({
                        title: 'Paket Sudah Dipesan',
                        html: 'Anda sudah memiliki booking untuk paket <strong>{{ session('package_name') }}</strong>.<br><br>Apakah Anda ingin membuat booking baru untuk tanggal berbeda?',
                        icon: 'question',
                        showCancelButton: true,
                        showDenyButton: true,
                        confirmButtonText: 'Lanjutkan Booking Baru',
                        denyButtonText: 'Lihat Booking Saya',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#3085d6',
                        denyButtonColor: '#27ae60',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
<<<<<<< HEAD
                        if (result.isConfirmed) {
                            // Continue with booking - do nothing, stay on form
                        } else if (result.isDenied) {
                            // Redirect to bookings list
                            window.location.href = "{{ route('client.dashboard') }}";
                        } else {
                            // Cancel - go back to package detail
                            window.history.back();
=======
                        if (result.isDenied) {
                            window.location.href = "{{ route('client.requests.index') }}";
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = "{{ route('landing.page') }}";
>>>>>>> fix-from-e378004
                        }
                    });
                @elseif(session('booking_check') === 'other_package')
                    Swal.fire({
                        title: 'Anda Sudah Memiliki Booking',
                        html: 'Anda sudah memiliki booking aktif untuk event lain.<br><br>Apakah Anda yakin ingin menambah booking baru untuk paket <strong>{{ session('package_name') }}</strong>?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = "{{ route('landing.page') }}";
                        }
                    });
                @endif
            });
        </script>
    @endif
</body>
</html>
