{{-- Step 5: Data Diri --}}
<div class="mb-24">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="font-heading text-2xl font-bold text-[#1C2440] mb-2">Data Diri</h2>
            <p class="text-gray-600">Lengkapi informasi Anda untuk menyelesaikan booking</p>
        </div>

        {{-- Login Required Notice --}}
        <div x-show="!isLoggedIn" class="max-w-xl mx-auto mb-8">
            <div class="p-6 bg-gradient-to-r from-[#F7EDE2] to-[#FFF9F0] rounded-xl border-2 border-[#D4AF37]/30">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-[#D4AF37]/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-[#1C2440] mb-1">Login Diperlukan</h4>
                        <p class="text-sm text-gray-600 mb-4">Silakan login atau daftar untuk melanjutkan booking Anda. Data booking akan tersimpan.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" 
                               class="px-5 py-2 bg-[#9CAF88] text-white rounded-lg font-medium hover:bg-[#7A9A6B] transition text-sm">
                                Login
                            </a>
                            <a href="{{ route('register') }}?redirect={{ urlencode(request()->fullUrl()) }}" 
                               class="px-5 py-2 border-2 border-[#9CAF88] text-[#9CAF88] rounded-lg font-medium hover:bg-[#9CAF88] hover:text-white transition text-sm">
                                Daftar Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Fields --}}
        <div class="max-w-xl mx-auto space-y-6" :class="!isLoggedIn ? 'opacity-50 pointer-events-none' : ''">
            {{-- Basic Info --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-[#1C2440] mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" x-model="clientName"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#9CAF88] focus:ring-2 focus:ring-[#9CAF88]/20 transition outline-none"
                           placeholder="Masukkan nama lengkap Anda">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#1C2440] mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" x-model="clientEmail"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#9CAF88] focus:ring-2 focus:ring-[#9CAF88]/20 transition outline-none"
                           placeholder="email@example.com">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#1C2440] mb-2">
                        WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" x-model="clientPhone"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#9CAF88] focus:ring-2 focus:ring-[#9CAF88]/20 transition outline-none"
                           placeholder="08xxxxxxxxxx">
                </div>
            </div>

            {{-- Wedding-specific Fields --}}
            <div x-show="eventType === 'Wedding'" class="pt-6 border-t border-gray-100">
                <h4 class="font-semibold text-[#1C2440] mb-4 flex items-center gap-2">
                    💍 Informasi Calon Pengantin
                </h4>

                <div class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#1C2440] mb-2">
                                Nama Calon Pengantin Pria
                                <span x-show="!fillCoupleLater" class="text-red-500">*</span>
                            </label>
                            <input type="text" x-model="groomName" :disabled="fillCoupleLater"
                                   :class="fillCoupleLater ? 'bg-gray-100' : ''"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#9CAF88] focus:ring-2 focus:ring-[#9CAF88]/20 transition outline-none"
                                   placeholder="Nama lengkap">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#1C2440] mb-2">
                                Nama Calon Pengantin Wanita
                                <span x-show="!fillCoupleLater" class="text-red-500">*</span>
                            </label>
                            <input type="text" x-model="brideName" :disabled="fillCoupleLater"
                                   :class="fillCoupleLater ? 'bg-gray-100' : ''"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#9CAF88] focus:ring-2 focus:ring-[#9CAF88]/20 transition outline-none"
                                   placeholder="Nama lengkap">
                        </div>
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" x-model="fillCoupleLater"
                               class="w-5 h-5 rounded border-gray-300 text-[#9CAF88] focus:ring-[#9CAF88]">
                        <span class="text-sm text-gray-600">Isi nama pasangan nanti di dashboard</span>
                    </label>
                </div>
            </div>

            {{-- Privacy Notice --}}
            <div class="p-4 bg-gray-50 rounded-xl text-sm text-gray-600">
                <p>Dengan mengirim booking ini, Anda menyetujui bahwa data Anda akan digunakan untuk keperluan pemrosesan booking dan komunikasi terkait event Anda.</p>
            </div>
        </div>
    </div>
</div>
