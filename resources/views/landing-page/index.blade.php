@extends('layouts.landing')

@section('content')
    {{-- Hero Section --}}
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1707982335467-c35cd7072dc0?q=80&w=1169&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                 alt="Event Background" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
        </div>

        {{-- Hero Content --}}
        <div class="container mx-auto px-6 relative z-10 text-white">
            <div class="max-w-3xl">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                    Wujudkan Momen Istimewa Bersama <span class="text-[#9CAF88]">{{ $companySetting->company_name ?? 'TemanMenujuHalal' }}</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-200">
                    Event Organizer profesional untuk lamaran, pernikahan, ulang tahun, dan acara korporat yang berkesan
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('public.booking.form') }}" class="bg-[#9CAF88] text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-[#8a9e7a] transition shadow-lg text-center">
                        Book Now
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="bg-white text-[#8B8680] px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition shadow-lg text-center">
                        Hubungi Kami via WhatsApp
                    </a>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    {{-- Tentang Kami --}}
    <section id="about" class="py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-[#8B8680] mb-6">Tentang Kami</h2>
                    <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                        <strong class="text-[#9CAF88]">{{ $companySetting->company_name ?? 'TemanMenujuHalal' }}</strong> adalah partner terpercaya Anda dalam mewujudkan setiap momen istimewa. Dengan pengalaman bertahun-tahun dalam industri event organizer, kami berkomitmen memberikan layanan terbaik untuk lamaran, pernikahan, ulang tahun, hingga acara korporat.
                    </p>
                    <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                        Tim profesional kami memahami bahwa setiap acara memiliki cerita unik. Kami hadir untuk mendengarkan visi Anda dan mewujudkannya dengan detail sempurna, kreativitas tinggi, dan dedikasi penuh.
                    </p>
                    <div class="bg-[#9CAF88]/10 border-l-4 border-[#9CAF88] p-6 rounded">
                        <h3 class="font-bold text-xl text-[#8B8680] mb-3">Visi Kami</h3>
                        <p class="text-gray-700">
                            Menjadi event organizer pilihan utama yang dipercaya untuk menciptakan pengalaman tak terlupakan dalam setiap momen berharga kehidupan.
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&q=80" 
                         alt="Wedding Event" 
                         class="rounded-2xl shadow-lg h-64 w-full object-cover">
                    <img src="https://images.unsplash.com/photo-1544155892-b2b6c64204fc?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8YmlydGhkYXklMjBwYXJ0eXxlbnwwfHwwfHx8MA%3D%3D" 
                         alt="Birthday Party" 
                         class="rounded-2xl shadow-lg h-64 w-full object-cover mt-8">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80" 
                         alt="Corporate Event" 
                         class="rounded-2xl shadow-lg h-64 w-full object-cover -mt-8">
                    <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=600&q=80" 
                         alt="Engagement" 
                         class="rounded-2xl shadow-lg h-64 w-full object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- Event Slider --}}
    <section id="portfolio" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-[#8B8680] mb-4">Event Terbaru Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Lihat hasil karya terbaik kami dalam berbagai jenis acara</p>
            </div>

            <div x-data="{
                currentSlide: 0,
                slides: [
                    { img: 'https://plus.unsplash.com/premium_photo-1663088413939-b0caff27cea4?q=80&w=1180&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', title: 'Pernikahan Elegant Garden', desc: 'Pernikahan outdoor dengan tema garden party yang menawan' },
                    { img: 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1200&q=80', title: 'Wedding Reception Grand Ballroom', desc: 'Resepsi pernikahan mewah di ballroom hotel bintang 5' },
                    { img: 'https://images.unsplash.com/photo-1620019891836-553486eacfdd?q=80&w=1521&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', title: 'Birthday Party Kids', desc: 'Pesta ulang tahun anak dengan tema princess yang memukau' },
                    { img: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80', title: 'Corporate Gathering', desc: 'Acara gathering perusahaan dengan 500+ peserta' },
                    { img: 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=1200&q=80', title: 'Romantic Engagement', desc: 'Acara lamaran romantis di rooftop dengan pemandangan kota' }
                ],
                autoplay: null,
                init() {
                    this.autoplay = setInterval(() => { this.next() }, 5000);
                },
                next() {
                    this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                },
                prev() {
                    this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
                }
            }" class="relative group">
                {{-- Slider Container --}}
                <div class="relative h-96 md:h-[600px] rounded-3xl overflow-hidden shadow-2xl">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="currentSlide === index" 
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="absolute inset-0">
                            <img :src="slide.img" :alt="slide.title" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12 text-white">
                                <h3 class="text-3xl md:text-4xl font-bold mb-3" x-text="slide.title"></h3>
                                <p class="text-lg md:text-xl text-gray-200" x-text="slide.desc"></p>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Navigation Arrows --}}
                <button @click="prev" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#8B8680] rounded-full p-3 shadow-lg transition opacity-0 group-hover:opacity-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button @click="next" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#8B8680] rounded-full p-3 shadow-lg transition opacity-0 group-hover:opacity-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                {{-- Dots Indicator --}}
                <div class="flex justify-center gap-2 mt-6">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="currentSlide = index" 
                                :class="currentSlide === index ? 'bg-[#9CAF88] w-8' : 'bg-gray-300 w-3'" 
                                class="h-3 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>
        </div>
    </section>

    {{-- Gallery Masonry --}}
    {{-- Company Portfolios Section --}}
    @if($portfolios->count() > 0)
    <section id="portfolios" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#27AE60] font-semibold tracking-wider uppercase text-sm">Portfolio Kami</span>
                <h2 class="text-4xl md:text-5xl font-bold text-[#1A1A1A] mt-2 mb-4">Project Terbaru</h2>
                <div class="w-24 h-1 bg-[#27AE60] mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($portfolios as $portfolio)
                <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                    <div class="relative h-72 overflow-hidden">
                        @if($portfolio->coverImage)
                            <img src="{{ asset('storage/' . $portfolio->coverImage) }}" 
                                 alt="{{ $portfolio->title }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-[#27AE60] bg-green-50 px-3 py-1 rounded-full">
                                {{ $portfolio->category ?? 'Event' }}
                            </span>
                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $portfolio->location ?? 'Indonesia' }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-[#1A1A1A] mb-2 group-hover:text-[#27AE60] transition-colors">
                            {{ $portfolio->title }}
                        </h3>
                        <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                            {{ Str::limit($portfolio->description, 100) }}
                        </p>
                        @if($portfolio->client)
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                            <span>Klien: <span class="font-medium text-gray-900">{{ $portfolio->client }}</span></span>
                            <span>{{ $portfolio->project_date ? $portfolio->project_date->format('M Y') : '' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section id="gallery" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-[#8B8680] mb-4">Galeri Foto</h2>
                <p class="text-lg text-gray-600">Dokumentasi momen-momen berharga dari berbagai acara</p>
            </div>

            @if(isset($galleryItems) && $galleryItems->count() > 0)
                {{-- Filter Kategori --}}
                <div x-data="{ activeFilter: 'all' }" class="mb-8">
                    <div class="flex flex-wrap justify-center gap-3">
                        <button @click="activeFilter = 'all'" 
                                :class="activeFilter === 'all' ? 'bg-[#9CAF88] text-white' : 'bg-white text-[#8B8680]'" 
                                class="px-6 py-2 rounded-full font-semibold transition shadow-md hover:shadow-lg">
                            Semua
                        </button>
                        <button @click="activeFilter = 'wedding'" 
                                :class="activeFilter === 'wedding' ? 'bg-[#9CAF88] text-white' : 'bg-white text-[#8B8680]'" 
                                class="px-6 py-2 rounded-full font-semibold transition shadow-md hover:shadow-lg">
                            Pernikahan
                        </button>
                        <button @click="activeFilter = 'birthday'" 
                                :class="activeFilter === 'birthday' ? 'bg-[#9CAF88] text-white' : 'bg-white text-[#8B8680]'" 
                                class="px-6 py-2 rounded-full font-semibold transition shadow-md hover:shadow-lg">
                            Ulang Tahun
                        </button>
                        <button @click="activeFilter = 'corporate'" 
                                :class="activeFilter === 'corporate' ? 'bg-[#9CAF88] text-white' : 'bg-white text-[#8B8680]'" 
                                class="px-6 py-2 rounded-full font-semibold transition shadow-md hover:shadow-lg">
                            Korporat
                        </button>
                        <button @click="activeFilter = 'engagement'" 
                                :class="activeFilter === 'engagement' ? 'bg-[#9CAF88] text-white' : 'bg-white text-[#8B8680]'" 
                                class="px-6 py-2 rounded-full font-semibold transition shadow-md hover:shadow-lg">
                            Lamaran
                        </button>
                    </div>

                    {{-- Masonry Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-8">
                        @foreach($galleryItems as $index => $item)
                        <div x-show="activeFilter === 'all' || activeFilter === '{{ $item->category }}'" 
                             x-transition
                             class="group relative overflow-hidden rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 cursor-pointer {{ $index % 3 === 0 ? 'row-span-2' : '' }}">
                            <img src="{{ asset('storage/' . $item->image_path) }}" 
                                 alt="{{ $item->title ?? $item->category_label }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                <div class="text-white">
                                    @if($item->title)
                                        <h4 class="font-semibold text-sm mb-1">{{ $item->title }}</h4>
                                    @endif
                                    <span class="text-xs capitalize">{{ $item->category_label }}</span>
                                    @if($item->is_featured)
                                        <span class="ml-2 text-yellow-400">⭐</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Fallback: Hardcoded Gallery when no items in database --}}
                <div x-data="{ activeFilter: 'all' }" class="mb-8">
                    <div class="flex flex-wrap justify-center gap-3">
                        <button @click="activeFilter = 'all'" 
                                :class="activeFilter === 'all' ? 'bg-[#9CAF88] text-white' : 'bg-white text-[#8B8680]'" 
                                class="px-6 py-2 rounded-full font-semibold transition shadow-md hover:shadow-lg">
                            Semua
                        </button>
                        <button @click="activeFilter = 'wedding'" 
                                :class="activeFilter === 'wedding' ? 'bg-[#9CAF88] text-white' : 'bg-white text-[#8B8680]'" 
                                class="px-6 py-2 rounded-full font-semibold transition shadow-md hover:shadow-lg">
                            Pernikahan
                        </button>
                        <button @click="activeFilter = 'birthday'" 
                                :class="activeFilter === 'birthday' ? 'bg-[#9CAF88] text-white' : 'bg-white text-[#8B8680]'" 
                                class="px-6 py-2 rounded-full font-semibold transition shadow-md hover:shadow-lg">
                            Ulang Tahun
                        </button>
                        <button @click="activeFilter = 'corporate'" 
                                :class="activeFilter === 'corporate' ? 'bg-[#9CAF88] text-white' : 'bg-white text-[#8B8680]'" 
                                class="px-6 py-2 rounded-full font-semibold transition shadow-md hover:shadow-lg">
                            Korporat
                        </button>
                    </div>

                    {{-- Masonry Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-8">
                        @php
                        $galleryFallback = [
                            ['img' => 'https://images.unsplash.com/photo-1550784718-990c6de52adf?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjZ8fHdlZGRpbmd8ZW58MHx8MHx8fDA%3D', 'category' => 'wedding', 'tall' => true, 'title' => 'Elegant Wedding'],
                            ['img' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=400', 'category' => 'wedding', 'tall' => false, 'title' => 'Reception Hall'],
                            ['img' => 'https://images.unsplash.com/photo-1544155891-969f15a055d3?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjZ8fGJpcnRoZGF5JTIwcGFydHl8ZW58MHx8MHx8fDA%3D', 'category' => 'birthday', 'tall' => false, 'title' => 'Birthday Celebration'],
                            ['img' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400', 'category' => 'corporate', 'tall' => true, 'title' => 'Corporate Event'],
                            ['img' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=400', 'category' => 'wedding', 'tall' => false, 'title' => 'Romantic Wedding'],
                            ['img' => 'https://images.unsplash.com/photo-1478146896981-b80fe463b330?w=400', 'category' => 'corporate', 'tall' => true, 'title' => 'Business Conference'],
                            ['img' => 'https://images.unsplash.com/photo-1505236858219-8359eb29e329?w=400', 'category' => 'birthday', 'tall' => false, 'title' => 'Kids Party'],
                            ['img' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=400', 'category' => 'corporate', 'tall' => false, 'title' => 'Team Building'],
                        ];
                        @endphp

                        @foreach($galleryFallback as $item)
                        <div x-show="activeFilter === 'all' || activeFilter === '{{ $item['category'] }}'" 
                             x-transition
                             class="group relative overflow-hidden rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 cursor-pointer {{ $item['tall'] ? 'row-span-2' : '' }}">
                            <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                <span class="text-white font-semibold capitalize">{{ ucfirst($item['category']) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Our Venue --}}

    {{-- Event Packages - Decoy Effect Pricing Strategy --}}
    @if(isset($eventPackages) && $eventPackages->count() > 0)
    <section id="packages" class="py-20 bg-gradient-to-br from-[#9CAF88]/5 via-white to-[#F4A6A0]/5">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-[#8B8680] mb-4">Paket Event Spesial</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Pilih paket yang sesuai dengan kebutuhan acara Anda. Hemat hingga 30% dengan paket lengkap!</p>
            </div>

            {{-- Adaptive Grid: Shows ALL active packages --}}
            @php
                $packageCount = $eventPackages->count();
                // Determine grid columns based on package count
                $gridCols = match(true) {
                    $packageCount <= 2 => 'md:grid-cols-2',
                    $packageCount == 3 => 'md:grid-cols-3',
                    default => 'md:grid-cols-2 lg:grid-cols-4',
                };
            @endphp
            
            <div class="grid grid-cols-1 {{ $gridCols }} gap-6 max-w-7xl mx-auto">
                @foreach($eventPackages->sortBy('final_price') as $package)
                    @php
                        $isFeatured = $package->is_featured ?? false;
                    @endphp
                    
                    <div class="relative {{ $isFeatured ? 'md:scale-105 z-10' : '' }}">
                        {{-- Featured Badge --}}
                        @if($isFeatured)
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-20">
                            <span class="relative inline-flex items-center gap-1.5 bg-gradient-to-r from-[#FF6B6B] via-[#F4A6A0] to-[#FF8E53] text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-lg">
                                <span class="absolute inset-0 rounded-full bg-gradient-to-r from-[#FF6B6B] to-[#FF8E53] blur-sm opacity-60 animate-pulse"></span>
                                <span class="relative flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    PILIHAN TERFAVORIT
                                </span>
                            </span>
                        </div>
                        @endif
                        
                        <div class="{{ $isFeatured ? 'bg-gradient-to-br from-[#9CAF88] to-[#8a9e7a] text-white shadow-2xl ring-4 ring-[#9CAF88]/30' : 'bg-white shadow-lg hover:shadow-xl' }} rounded-2xl overflow-hidden transition-all duration-300 h-full flex flex-col {{ $isFeatured ? 'pt-4' : '' }}">
                            {{-- Package Image/Header --}}
                            @if($package->thumbnail_path || $package->image_url)
                            <div class="h-32 overflow-hidden">
                                <img src="{{ $package->thumbnail_path ? asset('storage/' . $package->thumbnail_path) : $package->image_url }}" 
                                     alt="{{ $package->name }}" class="w-full h-full object-cover">
                            </div>
                            @endif
                            
                            <div class="p-6 flex-1 flex flex-col">
                                {{-- Package Name --}}
                                <h3 class="text-xl font-bold {{ $isFeatured ? 'text-white' : 'text-gray-800' }} mb-2">{{ $package->name }}</h3>
                                
                                {{-- Price --}}
                                <div class="mb-4">
                                    @if($package->discount_percentage > 0)
                                        <span class="text-sm {{ $isFeatured ? 'text-white/70' : 'text-gray-400' }} line-through block">
                                            Rp {{ number_format($package->base_price, 0, ',', '.') }}
                                        </span>
                                        <span class="inline-block {{ $isFeatured ? 'bg-white/20' : 'bg-red-100 text-red-600' }} px-2 py-0.5 rounded text-xs font-bold mb-1">
                                            Hemat {{ $package->discount_percentage }}%
                                        </span>
                                    @endif
                                    <span class="text-2xl font-bold {{ $isFeatured ? 'text-white' : 'text-[#9CAF88]' }} block">
                                        Rp {{ number_format($package->final_price, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                {{-- Items List --}}
                                <ul class="space-y-2 mb-6 flex-1">
                                    @if($package->items && $package->items->count() > 0)
                                        @foreach($package->items->take(5) as $item)
                                        <li class="flex items-start gap-2 text-sm">
                                            <svg class="w-4 h-4 {{ $isFeatured ? 'text-white' : 'text-[#9CAF88]' }} flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="{{ $isFeatured ? 'text-white/90' : 'text-gray-600' }}">{{ $item->item_name ?? $item->vendorCatalogItem->name ?? $item->vendorPackage->name ?? 'Item' }}</span>
                                        </li>
                                        @endforeach
                                        @if($package->items->count() > 5)
                                        <li class="text-xs {{ $isFeatured ? 'text-white/70' : 'text-gray-400' }} italic">
                                            + {{ $package->items->count() - 5 }} item lainnya
                                        </li>
                                        @endif
                                    @else
                                        <li class="text-sm {{ $isFeatured ? 'text-white/70' : 'text-gray-500' }} italic">Lihat detail untuk info lengkap</li>
                                    @endif
                                </ul>
                                
                                {{-- CTA Button --}}
                                <button onclick="selectPackage({{ $package->id }}, '{{ addslashes($package->name) }}', {{ $package->final_price }}, '{{ $package->slug }}')"
                                   class="block w-full text-center {{ $isFeatured ? 'bg-white text-[#9CAF88] hover:bg-gray-100' : 'bg-[#9CAF88] text-white hover:bg-[#8a9e7a]' }} px-4 py-3 rounded-xl font-semibold transition cursor-pointer">
                                    Pilih Paket Ini →
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Trust Indicators --}}
            <div class="mt-16 text-center">
                <div class="inline-flex items-center gap-6 flex-wrap justify-center">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#9CAF88]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-600 font-medium">Garansi Kepuasan 100%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#9CAF88]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                        </svg>
                        <span class="text-gray-600 font-medium">Dipercaya 500+ Klien</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-600 font-medium">Konsultasi Gratis</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Vendor Rekanan --}}
    <section id="vendors" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-[#8B8680] mb-4">Vendor Rekanan Terpercaya</h2>
                <p class="text-lg text-gray-600">Partner profesional untuk melengkapi acara Anda</p>
            </div>

            @if(isset($vendors) && $vendors->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($vendors as $vendor)
                    <div class="group relative">
                        {{-- Modern Card with Glassmorphism --}}
                        <div class="relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                            {{-- Logo/Header Section with Gradient --}}
                            <div class="relative h-52 bg-gradient-to-br from-[#9CAF88] via-[#8a9e7a] to-[#7a8e6a] overflow-hidden">
                                {{-- Decorative Pattern --}}
                                <div class="absolute inset-0 opacity-10">
                                    <div class="absolute top-0 right-0 w-40 h-40 bg-white rounded-full -mr-20 -mt-20"></div>
                                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white rounded-full -ml-16 -mb-16"></div>
                                </div>
                                
                                @if($vendor->logo_path)
                                    <div class="relative h-full flex items-center justify-center p-8 backdrop-blur-sm bg-white/5">
                                        <img src="{{ asset('storage/' . $vendor->logo_path) }}" 
                                             alt="{{ $vendor->display_name }}"
                                             class="max-h-full max-w-full object-contain drop-shadow-2xl group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                @else
                                    <div class="h-full flex items-center justify-center">
                                        <div class="w-28 h-28 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border-4 border-white/30 group-hover:scale-110 transition-transform duration-500">
                                            <span class="text-6xl font-bold text-white drop-shadow-lg">{{ substr($vendor->display_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                @endif
                                
                                {{-- Category Badge - Floating --}}
                                <div class="absolute top-4 right-4 z-10">
                                    <span class="inline-block bg-[#F4A6A0] text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-xl backdrop-blur-sm">
                                        {{ $vendor->display_category }}
                                    </span>
                                </div>
                                
                                {{-- Verified Badge --}}
                                <div class="absolute bottom-4 left-4 z-10">
                                    <div class="flex items-center gap-1 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-lg">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-700">Verified</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Content Section --}}
                            <div class="p-6 space-y-4">
                                {{-- Vendor Name --}}
                                <div>
                                    <h3 class="text-xl font-bold text-[#8B8680] mb-1 line-clamp-1 group-hover:text-[#9CAF88] transition-colors">
                                        {{ $vendor->display_name }}
                                    </h3>
                                    
                                    {{-- Location --}}
                                    @if($vendor->location)
                                        <div class="flex items-center gap-1.5 text-sm text-gray-500">
                                            <svg class="w-4 h-4 flex-shrink-0 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="line-clamp-1">{{ $vendor->location }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Description --}}
                                @if($vendor->description)
                                    <p class="text-sm text-gray-600 leading-relaxed line-clamp-3 min-h-[60px]">
                                        {{ $vendor->description }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-400 italic min-h-[60px]">
                                        Vendor profesional untuk kebutuhan acara Anda
                                    </p>
                                @endif

                                {{-- Divider --}}
                                <div class="border-t border-gray-100"></div>

                                {{-- Stats Row --}}
                                <div class="flex items-center justify-between text-sm">
                                    {{-- Rating --}}
                                    <div class="flex items-center gap-1">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= ($vendor->average_rating ?? 4.5) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="font-semibold text-gray-700">{{ number_format($vendor->average_rating ?? 4.5, 1) }}</span>
                                    </div>
                                    
                                    {{-- Reviews Count --}}
                                    <span class="text-gray-500">({{ $vendor->total_reviews ?? rand(10, 50) }} ulasan)</span>
                                </div>

                                {{-- Social Media Icons --}}
                                @if($vendor->instagram || $vendor->tiktok || $vendor->whatsapp)
                                    <div class="flex items-center gap-2 pt-2">
                                        @if($vendor->instagram)
                                            <a href="https://www.instagram.com/{{ $vendor->instagram }}" 
                                               target="_blank"
                                               class="w-9 h-9 rounded-xl bg-gradient-to-tr from-purple-600 via-pink-600 to-orange-500 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                </svg>
                                            </a>
                                        @endif

                                        @if($vendor->tiktok)
                                            <a href="https://www.tiktok.com/@{{ $vendor->tiktok }}" 
                                               target="_blank"
                                               class="w-9 h-9 rounded-xl bg-black flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
                                                </svg>
                                            </a>
                                        @endif

                                        @if($vendor->whatsapp)
                                            <a href="https://wa.me/{{ $vendor->whatsapp }}" 
                                               target="_blank"
                                               class="w-9 h-9 rounded-xl bg-green-500 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                {{-- Action Button --}}
                                <a href="{{ route('vendor.profile.show', $vendor->id) }}" 
                                   class="block w-full text-center bg-gradient-to-r from-[#9CAF88] to-[#8a9e7a] text-white px-4 py-3.5 rounded-xl font-bold hover:shadow-lg hover:scale-[1.02] transition-all duration-300 mt-4">
                                    <span class="flex items-center justify-center gap-2">
                                        <span>Lihat Profil</span>
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- View All Button --}}
                @if($vendors->count() >= 8)
                    <div class="text-center mt-12">
                        <a href="#" class="inline-block bg-white border-2 border-[#9CAF88] text-[#9CAF88] px-8 py-3 rounded-lg font-semibold hover:bg-[#9CAF88] hover:text-white transition shadow-md">
                            Lihat Semua Vendor
                        </a>
                    </div>
                @endif
            @else
                {{-- Fallback when no vendors --}}
                <div class="text-center py-16">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Vendor Terdaftar</h3>
                    <p class="text-gray-500">Vendor rekanan kami akan segera hadir</p>
                </div>
            @endif
        </div>
    </section>

    {{-- FAQ Section --}}
    <section id="faq" class="py-20 bg-white">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-[#8B8680] mb-4">Frequently Asked Questions</h2>
                <p class="text-lg text-gray-600">Pertanyaan yang sering diajukan seputar layanan kami</p>
            </div>

            <div x-data="{ openFaq: null }" class="space-y-4">
                @php
                $faqs = [
                    [
                        'q' => 'Berapa lama waktu yang dibutuhkan untuk persiapan event?',
                        'a' => 'Waktu persiapan bervariasi tergantung skala acara. Untuk acara kecil minimal 2 minggu, acara menengah 1-2 bulan, dan acara besar seperti pernikahan minimal 3-6 bulan untuk hasil optimal.'
                    ],
                    [
                        'q' => 'Apakah bisa request vendor tertentu?',
                        'a' => 'Tentu saja! Kami sangat terbuka dengan preferensi Anda. Anda bisa memilih dari daftar vendor rekanan kami atau mengusulkan vendor pilihan Anda sendiri.'
                    ],
                    [
                        'q' => 'Bagaimana sistem pembayaran yang berlaku?',
                        'a' => 'Sistem pembayaran kami fleksibel dengan DP 30% di awal, pelunasan 50% H-14 sebelum acara, dan sisanya 20% setelah acara selesai. Kami juga menerima cicilan untuk paket tertentu.'
                    ],
                    [
                        'q' => 'Apakah ada garansi jika terjadi kendala?',
                        'a' => 'Ya, kami memiliki backup plan untuk setiap elemen acara. Tim standby kami siap menangani kendala teknis, dan kami bekerja sama dengan asuransi event untuk acara skala besar.'
                    ],
                    [
                        'q' => 'Wilayah mana saja yang dilayani?',
                        'a' => 'Kami melayani seluruh wilayah Jabodetabek dan sekitarnya. Untuk acara di luar kota, silakan hubungi kami untuk konsultasi lebih lanjut.'
                    ]
                ];
                @endphp

                @foreach($faqs as $index => $faq)
                <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                    <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}" 
                            class="w-full px-6 py-5 text-left flex justify-between items-center hover:bg-gray-100 transition">
                        <span class="font-semibold text-lg text-[#8B8680]">{{ $faq['q'] }}</span>
                        <svg :class="openFaq === {{ $index }} ? 'rotate-180' : ''" 
                             class="w-6 h-6 text-[#9CAF88] transition-transform duration-300" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === {{ $index }}" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="px-6 pb-5">
                        <p class="text-gray-700 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section id="contact" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-[#8B8680] mb-4">Hubungi Kami</h2>
                <p class="text-lg text-gray-600">Siap membantu mewujudkan acara impian Anda</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                {{-- Contact Form --}}
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-[#8B8680] mb-6">Kirim Pesan</h3>
                    <form class="space-y-5">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                            <input type="text" placeholder="Nama Anda" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9CAF88] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" placeholder="email@example.com" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9CAF88] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Pesan</label>
                            <textarea rows="5" placeholder="Ceritakan tentang acara Anda..." 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9CAF88] focus:border-transparent transition"></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full bg-[#9CAF88] text-white py-4 rounded-lg font-bold text-lg hover:bg-[#8a9e7a] transition shadow-lg">
                            Kirim Pesan
                        </button>
                    </form>

                    {{-- WhatsApp Button --}}
                    <div class="mt-6">
                        <a href="https://wa.me/6281234567890" target="_blank" 
                           class="flex items-center justify-center gap-3 w-full bg-green-500 text-white py-4 rounded-lg font-bold text-lg hover:bg-green-600 transition shadow-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Chat via WhatsApp
                        </a>
                    </div>
                </div>

                {{-- Google Maps & Info --}}
                <div class="space-y-6">
                    {{-- Info Cards --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-[#8B8680] mb-4">Informasi Kontak</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#9CAF88]/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Telepon</p>
                                    <p class="text-gray-600">+62 812-3456-7890</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#9CAF88]/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Email</p>
                                    <p class="text-gray-600">info@loremipsum.com</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#9CAF88]/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Alamat Kantor</p>
                                    <p class="text-gray-600">Jl. Melati Indah No. 45, Jakarta Selatan 12150</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Google Maps --}}
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden h-80">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.2087536911584!2d106.82493931476889!3d-6.238188395497124!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-r from-[#9CAF88] to-[#8a9e7a] text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <img src="https://images.unsplash.com/photo-1519167758481-83f29da8c2bc?auto=format&fit=crop&w=1200&q=60" 
                 alt="Background" 
                 class="w-full h-full object-cover">
        </div>
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Siap Mulai Merencanakan Acara Impian?</h2>
            <p class="text-xl mb-10 max-w-2xl mx-auto">
                Hubungi PT. Lorem Ipsum sekarang dan biarkan kami membantu mewujudkan momen spesial Anda
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('public.booking.form') }}" class="bg-white text-[#9CAF88] px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-xl">
                    Booking Sekarang
                </a>
                <a href="tel:+6281234567890" class="bg-[#F4A6A0] text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-[#e69589] transition shadow-xl">
                    Hubungi: +62 812-3456-7890
                </a>
            </div>
        </div>
    </section>

@push('scripts')
<script>
// Package Selection Soft Confirmation Flow
function selectPackage(packageId, packageName, packagePrice, packageSlug) {
    // Save package to localStorage for persistence
    const selectedPackage = {
        id: packageId,
        name: packageName,
        final_price: packagePrice,
        slug: packageSlug,
        selected_at: new Date().toISOString()
    };
    localStorage.setItem('preSelectedPackage', JSON.stringify(selectedPackage));
    
    // Also pre-set booking form data with package mode
    const bookingFormData = {
        currentStep: 1,
        bookingMethod: 'package',
        selectedPackage: selectedPackage,
        eventType: '',
        eventDate: '',
        eventLocation: '',
        eventNotes: '',
        serviceSelections: {},
        nonPartnerVendors: []
    };
    localStorage.setItem('bookingFormData', JSON.stringify(bookingFormData));
    
    // Format price
    const formattedPrice = new Intl.NumberFormat('id-ID').format(packagePrice);
    
    // Show soft confirmation toast (non-intrusive)
    Swal.fire({
        toast: true,
        position: 'center',
        icon: 'success',
        title: `<strong>Paket ${packageName} dipilih!</strong>`,
        html: `
            <div class="text-sm text-gray-600 mt-1">
                Harga: <strong class="text-[#9CAF88]">Rp ${formattedPrice}</strong>
            </div>
            <p class="text-xs text-gray-500 mt-2">Anda bisa melanjutkan booking atau melihat detail paket</p>
        `,
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: '🚀 Lanjutkan Booking',
        cancelButtonText: '📋 Lihat Detail',
        confirmButtonColor: '#9CAF88',
        cancelButtonColor: '#8B8680',
        timer: 10000,
        timerProgressBar: true,
        customClass: {
            popup: 'rounded-2xl shadow-2xl',
            title: 'text-left text-base font-semibold text-gray-800',
            htmlContainer: 'text-left',
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Continue to booking form
            window.location.href = '{{ route("public.booking.form") }}';
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // View package details - use correct route /packages/{slug}
            window.location.href = '/packages/' + packageSlug;
        }
        // If timer expires or closed, do nothing - user stays on page
    });
}
</script>
@endpush
@endsection