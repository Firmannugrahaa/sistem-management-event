@props(['vendors'])

<!-- Vendor Section -->
<section id="vendors" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-[#012A4A]">Vendor Pilihan Kami</h2>
            <p class="text-lg text-gray-600 mt-2">Kualitas dan kepercayaan adalah prioritas kami.</p>
        </div>
        <div class="swiper-container vendor-slider" data-aos="fade-up" data-aos-delay="200">
            <div class="swiper-wrapper pb-8">
                @foreach($vendors as $vendor)
                <div class="swiper-slide p-2" 
                     data-index="{{ $loop->index }}"
                     x-on:click="selectedVendor = VENDORS[$el.dataset.index]; isVendorModalOpen = true">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-105 hover:shadow-xl transition-all duration-300 group cursor-pointer">
                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $vendor['image'] }}')"></div>
                        <div class="p-5">
                            <h3 class="text-xl font-bold group-hover:text-[#013d70] transition-colors">{{ $vendor['name'] }}</h3>
                            <p class="text-gray-500">{{ $vendor['category'] }}</p>
                            <div class="flex items-center mt-2">
                                <span class="text-yellow-500 font-bold">{{ $vendor['rating'] }}</span>
                                <svg class="w-4 h-4 text-yellow-500 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Add Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>
