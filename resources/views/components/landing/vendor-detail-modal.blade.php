<!-- Vendor Detail Modal -->
<div 
    x-show="isVendorModalOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
>
    <div 
        @click.away="isVendorModalOpen = false"
        x-show="isVendorModalOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="relative bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col"
    >
        <button @click="isVendorModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <template x-if="selectedVendor">
            <div class="overflow-y-auto">
                <!-- Banner -->
                <div class="h-64 bg-cover bg-center" :style="`background-image: url('${selectedVendor.image}')`"></div>
                
                <div class="p-8">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <div>
                            <h2 class="text-3xl font-bold text-[#012A4A]" x-text="selectedVendor.name"></h2>
                            <p class="text-lg text-gray-600" x-text="selectedVendor.category"></p>
                        </div>
                        <div class="flex items-center mt-4 sm:mt-0 bg-yellow-400 text-yellow-900 font-bold px-4 py-2 rounded-lg">
                            <span x-text="selectedVendor.rating"></span>
                            <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"></path></svg>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="prose max-w-none text-gray-700 mb-8">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl. Sed euismod, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl.</p>
                        <p>Integer euismod, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl. Sed euismod, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="w-full bg-[#012A4A] text-white px-8 py-3 rounded-lg font-semibold text-lg hover:bg-[#013d70] transition-colors">
                            Hubungi Vendor
                        </button>
                        <button class="w-full bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-gray-300 transition-colors">
                            Lihat Paket
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
