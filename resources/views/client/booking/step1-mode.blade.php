@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-bold">1</div>
                    <div class="w-20 h-1 bg-gray-300 mx-2"></div>
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-500 font-bold">2</div>
                    <div class="w-20 h-1 bg-gray-300 mx-2"></div>
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-500 font-bold">3</div>
                    <div class="w-20 h-1 bg-gray-300 mx-2"></div>
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-500 font-bold">4</div>
                </div>
            </div>
            <div class="text-center mt-3">
                <p class="text-sm text-gray-600">Step 1 of 4: <strong>Pilih Mode Booking</strong></p>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Buat Booking Baru</h1>
            <p class="text-gray-600 mt-2">Pilih cara booking yang sesuai dengan kebutuhan Anda</p>
        </div>

        <!-- Mode Selection Form -->
        <form method="POST" action="{{ route('client.booking.mode.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Option 1: Package -->
                <label class="cursor-pointer">
                    <input type="radio" name="mode" value="package" class="peer hidden" required>
                    <div class="bg-white rounded-xl border-2 border-gray-200 p-6 hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all h-full flex flex-col">
                        <div class="text-center mb-4">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">🎁 Paket Event</h3>
                        </div>
                        <p class="text-gray-600 text-center mb-4 flex-1">Pilih paket lengkap yang sudah termasuk berbagai vendor dan layanan</p>
                        <div class="flex items-center justify-center gap-2">
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Lebih Cepat</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Hemat Waktu</span>
                        </div>
                    </div>
                </label>

                <!-- Option 2: Custom -->
                <label class="cursor-pointer">
                    <input type="radio" name="mode" value="custom" class="peer hidden" required>
                    <div class="bg-white rounded-xl border-2 border-gray-200 p-6 hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all h-full flex flex-col">
                        <div class="text-center mb-4">
                            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">⚙️ Custom</h3>
                        </div>
                        <p class="text-gray-600 text-center mb-4 flex-1">Pilih vendor satu per satu sesuai kebutuhan dan budget Anda</p>
                        <div class="flex items-center justify-center gap-2">
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">Lebih Fleksibel</span>
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">Kontrol Budget</span>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Validation Error -->
            @error('mode')
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ $message }}
                </div>
            @enderror

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <a href="{{ route('client.dashboard') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                    ← Kembali ke Dashboard
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Lanjutkan →
                </button>
            </div>
        </form>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm text-blue-800">
                        <strong>Tips:</strong> Pilih Paket Event jika Anda ingin proses yang lebih cepat dan praktis. 
                        Pilih Custom jika Anda ingin kontrol penuh atas setiap vendor yang dipilih.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
