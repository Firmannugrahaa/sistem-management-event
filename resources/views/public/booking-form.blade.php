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
                <p class="text-lg text-gray-600">Isi form di bawah untuk memulai perencanaan event Anda</p>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Progress Step Indicators -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">1</div>
                            <p class="text-xs mt-2 font-medium text-blue-600">Form Details</p>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">2</div>
                            <p class="text-xs mt-2 font-medium text-gray-500">Login/Register</p>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">3</div>
                            <p class="text-xs mt-2 font-medium text-gray-500">Verify OTP</p>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">4</div>
                            <p class="text-xs mt-2 font-medium text-gray-500">Confirmed</p>
                        </div>
                    </div>
                </div>

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
                                <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_name') border-red-500 @enderror"
                                    placeholder="Masukkan nama lengkap Anda">
                                @error('client_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="client_email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="client_email" id="client_email" value="{{ old('client_email') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_email') border-red-500 @enderror"
                                    placeholder="email@example.com">
                                @error('client_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-2">No. WhatsApp/Telepon *</label>
                                <input type="tel" name="client_phone" id="client_phone" value="{{ old('client_phone') }}" required
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('event_type') border-red-500 @enderror">
                                    <option value="">Pilih Tipe Event</option>
                                    <option value="Wedding" {{ old('event_type') == 'Wedding' ? 'selected' : '' }}>Pernikahan</option>
                                    <option value="Birthday" {{ old('event_type') == 'Birthday' ? 'selected' : '' }}>Ulang Tahun</option>
                                    <option value="Corporate" {{ old('event_type') == 'Corporate' ? 'selected' : '' }}>Corporate Event</option>
                                    <option value="Conference" {{ old('event_type') == 'Conference' ? 'selected' : '' }}>Conference/Seminar</option>
                                    <option value="Engagement" {{ old('event_type') == 'Engagement' ? 'selected' : '' }}>Tunangan</option>
                                    <option value="Other" {{ old('event_type') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
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

                            <div>
                                <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-2">Vendor Preferensi (Opsional)</label>
                                <select name="vendor_id" id="vendor_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vendor_id') border-red-500 @enderror">
                                    <option value="">Pilih Vendor (Opsional)</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }} - {{ $vendor->category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                            Lanjutkan ke Registrasi
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
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Isi form booking dengan lengkap</li>
                                <li>Daftar atau login untuk melanjutkan</li>
                                <li>Verifikasi nomor telepon dengan kode OTP</li>
                                <li>Booking selesai! Tim kami akan segera menghubungi Anda</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
