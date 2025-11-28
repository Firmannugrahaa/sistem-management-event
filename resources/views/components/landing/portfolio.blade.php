@props(['events'])

<!-- Portfolio Section -->
<section id="portfolio" class="py-20">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-[#012A4A]">Galeri Pernikahan Impian</h2>
            <p class="text-lg text-gray-600 mt-2">Lihat bagaimana kami membantu mewujudkan momen tak terlupakan.</p>
        </div>
        <div class="swiper-container portfolio-carousel" data-aos="zoom-in" data-aos-delay="200">
            <div class="swiper-wrapper">
                @foreach($events as $event)
                <div class="swiper-slide">
                    <div class="relative rounded-lg shadow-xl overflow-hidden group">
                        <img src="{{ $event['image'] }}" class="w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end justify-start p-6">
                            <div>
                                <h3 class="text-white text-2xl font-bold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">{{ $event['name'] }}</h3>
                                <p class="text-white/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ $event['location'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
