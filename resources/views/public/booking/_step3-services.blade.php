{{-- Step 3: Pilih Layanan --}}
<div class="mb-24">
    {{-- Package Selection Mode --}}
    <div x-show="bookingMethod === 'package'">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-8">
                <h2 class="font-heading text-2xl font-bold text-[#1C2440] mb-2">Pilih Paket</h2>
                <p class="text-gray-600">Pilih paket yang sesuai dengan kebutuhan acara <span class="font-medium" x-text="eventType"></span> Anda</p>
            </div>

            {{-- Filter by Event Type --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="pkg in packages.filter(p => !eventType || p.event_type === eventType || p.event_type === 'All')" :key="pkg.id">
                    <div @click="selectPackage(pkg)"
                         :class="selectedPackage?.id === pkg.id ? 'ring-2 ring-[#D4AF37] border-[#D4AF37]' : 'border-gray-200 hover:border-[#9CAF88]'"
                         class="package-card bg-white border-2 rounded-2xl overflow-hidden cursor-pointer">
                        
                        {{-- Package Image --}}
                        <div class="h-40 bg-gradient-to-br from-[#F7EDE2] to-[#E8DFD4] flex items-center justify-center">
                            <template x-if="pkg.thumbnail_path || pkg.image_url">
                                <img :src="pkg.thumbnail_path ? '/storage/' + pkg.thumbnail_path : pkg.image_url" 
                                     :alt="pkg.name" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!pkg.thumbnail_path && !pkg.image_url">
                                <div class="text-5xl">📦</div>
                            </template>
                        </div>

                        {{-- Package Info --}}
                        <div class="p-5">
                            {{-- Badge --}}
                            <div class="flex items-center gap-2 mb-2">
                                <span x-show="pkg.is_featured" class="px-2 py-0.5 bg-[#D4AF37] text-white text-xs font-bold rounded-full">
                                    ⭐ Populer
                                </span>
                                <span x-show="pkg.discount_percentage > 0" class="px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">
                                    -<span x-text="pkg.discount_percentage"></span>%
                                </span>
                            </div>

                            <h3 class="font-bold text-lg text-[#1C2440] mb-1" x-text="pkg.name"></h3>
                            
                            {{-- Venue Indicator --}}
                            <div class="flex items-center gap-1 text-xs mb-3">
                                <template x-if="pkg.items?.some(i => i.vendor_catalog_item?.vendor?.category === 'Venue')">
                                    <span class="text-[#9CAF88]">✓ Include Venue</span>
                                </template>
                                <template x-if="!pkg.items?.some(i => i.vendor_catalog_item?.vendor?.category === 'Venue')">
                                    <span class="text-gray-400">Venue tidak termasuk</span>
                                </template>
                            </div>

                            {{-- Features --}}
                            <ul class="text-sm text-gray-600 space-y-1 mb-4">
                                <template x-for="(feature, idx) in (pkg.features || []).slice(0, 3)" :key="idx">
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-[#9CAF88] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span x-text="feature"></span>
                                    </li>
                                </template>
                            </ul>

                            {{-- Price --}}
                            <div class="pt-3 border-t border-gray-100">
                                <div x-show="pkg.discount_percentage > 0" class="text-sm text-gray-400 line-through" x-text="formatPrice(pkg.base_price)"></div>
                                <div class="text-xl font-bold text-[#D4AF37]" x-text="formatPrice(pkg.final_price)"></div>
                            </div>
                        </div>

                        {{-- Selection Indicator --}}
                        <div x-show="selectedPackage?.id === pkg.id" 
                             class="bg-[#D4AF37] text-white py-2 text-center text-sm font-semibold">
                            ✓ Paket Dipilih
                        </div>
                    </div>
                </template>
            </div>

            {{-- Empty State --}}
            <div x-show="packages.filter(p => !eventType || p.event_type === eventType || p.event_type === 'All').length === 0" 
                 class="text-center py-12">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500">Belum ada paket tersedia untuk jenis acara ini.</p>
                <button @click="bookingMethod = 'custom'" class="mt-4 text-[#9CAF88] font-medium hover:underline">
                    Coba susun sendiri →
                </button>
            </div>
        </div>
    </div>

    {{-- Custom Selection Mode --}}
    <div x-show="bookingMethod === 'custom'">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-8">
                <h2 class="font-heading text-2xl font-bold text-[#1C2440] mb-2">Pilih Vendor & Layanan</h2>
                <p class="text-gray-600">Pilih layanan yang Anda butuhkan. Anda bisa melewati kategori yang tidak diperlukan.</p>
            </div>

            {{-- Category Accordions --}}
            <div class="space-y-4" x-data="{ openCategory: null }">
                <template x-for="serviceType in serviceTypes" :key="serviceType.id">
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        {{-- Category Header --}}
                        <button @click="openCategory = openCategory === serviceType.id ? null : serviceType.id"
                                class="w-full px-5 py-4 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">
                                    <template x-if="serviceType.name === 'Venue'">🏛️</template>
                                    <template x-if="serviceType.name === 'Catering'">🍽️</template>
                                    <template x-if="serviceType.name === 'MUA'">💄</template>
                                    <template x-if="serviceType.name === 'Dekorasi'">🎨</template>
                                    <template x-if="serviceType.name === 'Dokumentasi'">📷</template>
                                    <template x-if="serviceType.name === 'Entertainment'">🎵</template>
                                    <template x-if="!['Venue','Catering','MUA','Dekorasi','Dokumentasi','Entertainment'].includes(serviceType.name)">✨</template>
                                </span>
                                <span class="font-semibold text-[#1C2440]" x-text="serviceType.name"></span>
                                <span x-show="serviceSelections[serviceType.id]" class="px-2 py-0.5 bg-[#9CAF88] text-white text-xs rounded-full">
                                    Dipilih
                                </span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="openCategory === serviceType.id ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Category Content --}}
                        <div x-show="openCategory === serviceType.id" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="p-5 border-t border-gray-100">
                            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <template x-for="vendor in serviceType.vendors" :key="vendor.id">
                                    <template x-for="item in vendor.catalog_items || []" :key="item.id">
                                        <div @click="serviceSelections[serviceType.id] = { 
                                                 category_name: serviceType.name,
                                                 vendor_id: vendor.id,
                                                 vendor_name: vendor.brand_name,
                                                 item_id: item.id,
                                                 item_name: item.name,
                                                 price: item.price,
                                                 qty: 1,
                                                 subtotal: item.price
                                             }"
                                             :class="serviceSelections[serviceType.id]?.item_id === item.id ? 'border-[#D4AF37] bg-[#FFFBF5]' : 'border-gray-200 hover:border-[#9CAF88]'"
                                             class="border-2 rounded-xl p-4 cursor-pointer transition">
                                            <div class="text-sm text-gray-500" x-text="vendor.brand_name"></div>
                                            <div class="font-semibold text-[#1C2440]" x-text="item.name"></div>
                                            <div class="text-[#D4AF37] font-bold mt-2" x-text="formatPrice(item.price)"></div>
                                        </div>
                                    </template>
                                </template>
                            </div>

                            {{-- Skip Button --}}
                            <div class="mt-4 flex justify-between items-center">
                                <button @click="delete serviceSelections[serviceType.id]; openCategory = null" 
                                        class="text-sm text-gray-400 hover:text-gray-600">
                                    Lewati kategori ini
                                </button>
                                <button x-show="serviceSelections[serviceType.id]" @click="openCategory = null"
                                        class="text-sm text-[#9CAF88] font-medium">
                                    ✓ Selesai
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Non-Partner Vendor Section --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div x-data="{ showNonPartner: false }">
                    <button @click="showNonPartner = !showNonPartner" 
                            class="flex items-center gap-2 text-[#1C2440] hover:text-[#9CAF88] transition">
                        <svg class="w-5 h-5" :class="showNonPartner ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="font-medium">Gunakan Vendor Sendiri (Non-Rekanan)</span>
                    </button>

                    <div x-show="showNonPartner" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="mt-4 p-4 bg-[#FEF3C7] rounded-xl">
                        <p class="text-sm text-amber-800 mb-4">
                            ⚠️ Biaya tambahan <strong>Rp 600.000</strong> per vendor non-rekanan akan dikenakan.
                        </p>
                        
                        <div class="space-y-3">
                            <input type="text" placeholder="Nama Vendor" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                            <input type="text" placeholder="Kategori (Dekorasi, MUA, dll)" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                            <input type="text" placeholder="Kontak (opsional)" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                            <button class="px-4 py-2 bg-[#1C2440] text-white rounded-lg text-sm font-medium hover:bg-[#2A3458] transition">
                                + Tambah Vendor
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
