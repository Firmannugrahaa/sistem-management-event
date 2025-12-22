{{-- Step 4: Ringkasan --}}
<div class="mb-24">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="font-heading text-2xl font-bold text-[#1C2440] mb-2">Ringkasan Booking</h2>
            <p class="text-gray-600">Periksa kembali data Anda sebelum melanjutkan</p>
        </div>

        <div class="max-w-2xl mx-auto space-y-6">
            {{-- Section 1: Gambaran Acara --}}
            <div class="p-5 bg-gray-50 rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-[#1C2440] flex items-center gap-2">
                        <span class="w-6 h-6 bg-[#9CAF88] text-white rounded-full text-xs flex items-center justify-center">1</span>
                        Gambaran Acara
                    </h3>
                    <button @click="goToStep(1)" class="text-sm text-[#9CAF88] hover:underline">Edit</button>
                </div>
                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Jenis Acara:</span>
                        <span class="ml-2 font-medium text-[#1C2440]" x-text="eventType || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500">Tanggal:</span>
                        <span class="ml-2 font-medium text-[#1C2440]" x-text="eventDate ? new Date(eventDate).toLocaleDateString('id-ID', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}) : '-'"></span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-gray-500">Lokasi:</span>
                        <span class="ml-2 font-medium text-[#1C2440]" x-text="eventLocation || '-'"></span>
                    </div>
                    <div x-show="eventNotes" class="sm:col-span-2">
                        <span class="text-gray-500">Catatan:</span>
                        <span class="ml-2 text-[#1C2440]" x-text="eventNotes"></span>
                    </div>
                </div>
            </div>

            {{-- Section 2: Metode Booking --}}
            <div class="p-5 bg-gray-50 rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-[#1C2440] flex items-center gap-2">
                        <span class="w-6 h-6 bg-[#9CAF88] text-white rounded-full text-xs flex items-center justify-center">2</span>
                        Metode Booking
                    </h3>
                    <button @click="goToStep(2)" class="text-sm text-[#9CAF88] hover:underline">Edit</button>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-2xl" x-text="bookingMethod === 'package' ? '📦' : '🎨'"></span>
                    <span class="font-medium text-[#1C2440]" x-text="bookingMethod === 'package' ? 'Gunakan Paket' : 'Susun Sendiri'"></span>
                </div>
            </div>

            {{-- Section 3: Layanan Dipilih --}}
            <div class="p-5 bg-gray-50 rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-[#1C2440] flex items-center gap-2">
                        <span class="w-6 h-6 bg-[#9CAF88] text-white rounded-full text-xs flex items-center justify-center">3</span>
                        Layanan Dipilih
                    </h3>
                    <button @click="goToStep(3)" class="text-sm text-[#9CAF88] hover:underline">Edit</button>
                </div>

                {{-- If Package --}}
                <div x-show="bookingMethod === 'package' && selectedPackage">
                    <div class="flex items-start gap-4 p-4 bg-white rounded-lg border border-gray-200">
                        <div class="w-16 h-16 bg-[#F7EDE2] rounded-lg flex items-center justify-center text-2xl">📦</div>
                        <div class="flex-1">
                            <div class="font-bold text-[#1C2440]" x-text="selectedPackage?.name"></div>
                            <div class="text-sm text-gray-500 mt-1">Paket lengkap dengan vendor terkurasi</div>
                            <div class="text-lg font-bold text-[#D4AF37] mt-2" x-text="formatPrice(selectedPackage?.final_price)"></div>
                        </div>
                    </div>
                </div>

                {{-- If Custom --}}
                <div x-show="bookingMethod === 'custom'" class="space-y-3">
                    <template x-for="(selection, typeId) in serviceSelections" :key="typeId">
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                            <div>
                                <div class="text-sm text-gray-500" x-text="selection.category_name"></div>
                                <div class="font-medium text-[#1C2440]" x-text="selection.item_name"></div>
                                <div class="text-xs text-gray-400" x-text="selection.vendor_name"></div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-[#D4AF37]" x-text="formatPrice(selection.subtotal)"></div>
                            </div>
                        </div>
                    </template>

                    {{-- Non-Partner Vendors --}}
                    <template x-for="(vendor, idx) in nonPartnerVendors" :key="idx">
                        <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg border border-amber-200">
                            <div>
                                <div class="text-sm text-amber-600">Vendor Non-Rekanan</div>
                                <div class="font-medium text-[#1C2440]" x-text="vendor.vendor_name"></div>
                                <div class="text-xs text-gray-400" x-text="vendor.category"></div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-amber-600" x-text="formatPrice(vendor.charge || 600000)"></div>
                                <div class="text-xs text-gray-400">Biaya tambahan</div>
                            </div>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <div x-show="Object.keys(serviceSelections).length === 0 && nonPartnerVendors.length === 0" 
                         class="text-center py-6 text-gray-400">
                        <p>Belum ada layanan dipilih</p>
                        <button @click="goToStep(3)" class="mt-2 text-[#9CAF88] font-medium hover:underline">
                            Pilih layanan →
                        </button>
                    </div>
                </div>
            </div>

            {{-- Total Estimasi --}}
            <div class="p-5 bg-gradient-to-r from-[#1C2440] to-[#2A3458] rounded-xl text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-300">Estimasi Total</div>
                        <div class="text-2xl font-bold" x-text="formatPrice(calculateTotal())"></div>
                    </div>
                    <div class="text-right text-sm text-gray-300">
                        <div>Harga belum termasuk</div>
                        <div>biaya tambahan lainnya</div>
                    </div>
                </div>
            </div>

            {{-- Info --}}
            <div class="p-4 bg-[#F7EDE2] rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-[#D4AF37] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-[#1C2440]">
                    Setelah booking dibuat, tim kami akan menghubungi Anda untuk konfirmasi dan memberikan rekomendasi tambahan jika diperlukan.
                </p>
            </div>
        </div>
    </div>
</div>
