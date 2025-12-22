{{-- Step 2: Cara Booking --}}
<div class="bg-white rounded-2xl shadow-lg p-8 mb-24">
    <div class="text-center mb-8">
        <h2 class="font-heading text-2xl font-bold text-[#1C2440] mb-2">Pilih Cara Booking</h2>
        <p class="text-gray-600">Tentukan bagaimana Anda ingin menyusun layanan untuk acara</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto">
        {{-- Option 1: Gunakan Paket --}}
        <button type="button" @click="bookingMethod = 'package'"
                :class="bookingMethod === 'package' ? 'selected border-[#D4AF37] ring-2 ring-[#D4AF37]/30' : 'border-gray-200 hover:border-[#9CAF88]'"
                class="card-selection p-6 rounded-2xl border-2 text-left transition group">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl"
                     :class="bookingMethod === 'package' ? 'bg-[#D4AF37]/20' : 'bg-gray-100 group-hover:bg-[#9CAF88]/20'">
                    📦
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-[#1C2440] mb-1">Gunakan Paket</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Praktis dan sudah termasuk vendor terkurasi. Pilih paket yang sesuai dengan kebutuhan Anda.
                    </p>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm"
                 :class="bookingMethod === 'package' ? 'text-[#D4AF37]' : 'text-gray-400'">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Rekomendasi untuk pemula</span>
            </div>
        </button>

        {{-- Option 2: Susun Sendiri --}}
        <button type="button" @click="bookingMethod = 'custom'"
                :class="bookingMethod === 'custom' ? 'selected border-[#D4AF37] ring-2 ring-[#D4AF37]/30' : 'border-gray-200 hover:border-[#9CAF88]'"
                class="card-selection p-6 rounded-2xl border-2 text-left transition group">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl"
                     :class="bookingMethod === 'custom' ? 'bg-[#D4AF37]/20' : 'bg-gray-100 group-hover:bg-[#9CAF88]/20'">
                    🎨
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-[#1C2440] mb-1">Susun Sendiri</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pilih vendor dan layanan sesuai kebutuhan. Fleksibilitas penuh untuk menyusun event impian Anda.
                    </p>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm"
                 :class="bookingMethod === 'custom' ? 'text-[#D4AF37]' : 'text-gray-400'">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                <span>Untuk yang tahu apa yang diinginkan</span>
            </div>
        </button>
    </div>

    {{-- Info Box --}}
    <div class="mt-8 max-w-2xl mx-auto">
        <div class="bg-[#F7EDE2] rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-[#D4AF37] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-[#1C2440]">
                Tidak perlu khawatir! Apapun pilihan Anda, tim kami siap membantu memberikan rekomendasi terbaik setelah booking dibuat.
            </p>
        </div>
    </div>
</div>
