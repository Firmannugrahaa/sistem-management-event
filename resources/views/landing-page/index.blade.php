@extends('layouts.landing')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-primary to-blue-900 text-white py-24 overflow-hidden">
        <!-- Carousel Background -->
        <div x-data="{
            currentIndex: 0,
            images: [
                {
                    url: 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1200&h=600&q=80',
                    alt: 'Wedding Event'
                },
                {
                    url: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&h=600&q=80',
                    alt: 'Corporate Event'
                },
                {
                    url: 'https://images.unsplash.com/photo-1557893316-479e1c9dae12?auto=format&fit=crop&w=1200&h=600&q=80',
                    alt: 'Conference Event'
                },
                {
                    url: 'https://images.unsplash.com/photo-1560818135-64a643f1b2a7?auto=format&fit=crop&w=1200&h=600&q=80',
                    alt: 'Music Festival'
                }
            ],
            autoSlide: true,
            slideInterval: null
        }"
        x-init="
            // Auto slide every 5 seconds
            slideInterval = setInterval(() => {
                currentIndex = (currentIndex + 1) % images.length;
            }, 5000);
        "
        class="absolute inset-0 z-0">

            <!-- Background Images -->
            <template x-for="(image, index) in images" :key="index">
                <div class="absolute inset-0 transition-opacity duration-1500 ease-in-out"
                     :class="{ 'opacity-100 z-10': index === currentIndex, 'opacity-0 z-0': index !== currentIndex }">
                    <img :src="image.url"
                         :alt="image.alt"
                         class="w-full h-full object-cover mix-blend-overlay">
                </div>
            </template>
        </div>

        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/70 to-blue-900/5 z-10"></div>

        <!-- Text Container with Enhanced Readability -->
        <div class="container mx-auto px-6 relative z-20">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-12 md:mb-0">
                    @if(auth()->check() && auth()->user()->hasRole('Client'))
                        <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight drop-shadow-lg">Selamat Datang, {{ auth()->user()->name }}!</h1>
                        <p class="text-xl mb-8 max-w-lg drop-shadow-md">Mulai rencanakan acara impian Anda dengan layanan lengkap, vendor terpercaya, dan kemudahan dalam satu platform</p>
                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('events.create') }}" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg text-center">Buat Event Baru</a>
                            <a href="{{ route('client.dashboard') }}" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-center">Lihat Dashboard</a>
                        </div>
                    @else
                        <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight drop-shadow-lg">Wujudkan Acara Impian Anda dengan Profesional</h1>
                        <p class="text-xl mb-8 max-w-lg drop-shadow-md">Layanan lengkap untuk semua kebutuhan event Anda dengan vendor terpercaya dan layanan berkualitas</p>
                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('public.booking.form') }}" class="bg-gradient-to-r from-green-400 to-green-600 text-white px-8 py-4 rounded-lg font-bold hover:from-green-500 hover:to-green-700 transition shadow-2xl text-center transform hover:scale-105 duration-200">
                                <svg class="w-5 h-5 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Book Now!
                            </a>
                            <a href="#venues" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg text-center">Jelajahi Venue</a>
                            <a href="#additional-vendors" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-center">Lihat Vendor</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Slider Indicators -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-30 flex space-x-2">
                <template x-for="(image, index) in images" :key="index">
                    <button @click="currentIndex = index"
                        :class="{
                            'w-3 h-3 rounded-full': true,
                            'bg-white': index === currentIndex,
                            'bg-white/40': index !== currentIndex
                        }"
                        :aria-label="`Go to slide ${index + 1}`"
                    ></button>
                </template>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-6">
            <!-- Decorative header image -->
            <div class="text-center mb-10">
                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&h=200&q=80"
                     alt="Event Portfolio"
                     class="mx-auto rounded-xl shadow-md">
            </div>

            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Portfolio Perusahaan</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Beberapa contoh pekerjaan terbaik kami dalam mengelola berbagai jenis event</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($portfolios as $portfolio)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <img src="{{ $portfolio->image ? asset('storage/' . $portfolio->image) : 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&h=400&q=80' }}"
                             alt="{{ $portfolio->title }}"
                             class="w-full h-56 object-cover">
                        <div class="p-7">
                            <div class="flex justify-between items-start mb-3">
                                <span class="inline-block bg-accent-green/10 text-accent-green px-3 py-1 rounded-full text-sm font-medium">{{ $portfolio->category }}</span>
                                <span class="text-sm text-gray-500">{{ $portfolio->project_date ? $portfolio->project_date->format('d M Y') : '' }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $portfolio->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($portfolio->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">{{ $portfolio->client }}</span>
                                <span class="text-sm text-gray-500">{{ $portfolio->location }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="mx-auto w-24 h-24 mb-6 opacity-50">
                            <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=200&h=200&q=80"
                                 alt="No portfolio images"
                                 class="w-full h-full object-cover rounded-full">
                        </div>
                        <p class="text-gray-500 text-lg">Belum ada portfolio tersedia</p>
                    </div>
                @endforelse
            </div>
            
            @if(count($portfolios) > 0)
                <div class="text-center mt-16">
                    <a href="#" class="inline-block bg-primary text-white px-8 py-4 rounded-lg font-medium hover:bg-blue-800 transition shadow-lg">Lihat Semua Portfolio</a>
                </div>
            @endif
        </div>
    </section>

    <!-- Venues Section -->
    <section id="venues" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <!-- Decorative header image -->
            <div class="text-center mb-10">
                <img src="https://images.unsplash.com/photo-1512869251363-8e62799c3f41?auto=format&fit=crop&w=800&h=200&q=80"
                     alt="Event Venues"
                     class="mx-auto rounded-xl shadow-md">
            </div>

            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Venue Tersedia</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Pilihan venue terbaik untuk mewujudkan acara Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($venues as $venue)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <img src="https://images.unsplash.com/photo-1512869251363-8e62799c3f41?auto=format&fit=crop&w=600&h=400&q=80"
                             alt="{{ $venue->name }}"
                             class="w-full h-56 object-cover">
                        <div class="p-7">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $venue->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($venue->address, 100) }}</p>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-gray-700 font-medium">Rp {{ number_format($venue->price, 0, ',', '.') }}</span>
                                <span class="text-sm text-gray-500">{{ $venue->capacity }} orang</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('venues.show', $venue->id) }}" class="inline-block bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="mx-auto w-24 h-24 mb-6 opacity-50">
                            <img src="https://images.unsplash.com/photo-1584285418934-0379b7b8e6d0?auto=format&fit=crop&w=200&h=200&q=80"
                                 alt="No venues available"
                                 class="w-full h-full object-cover rounded-full">
                        </div>
                        <p class="text-gray-500 text-lg">Belum ada venue tersedia</p>
                    </div>
                @endforelse
            </div>

            @if(count($venues) > 0)
                <div class="text-center mt-16">
                    <a href="{{ route('venues.index') }}" class="inline-block bg-primary text-white px-8 py-4 rounded-lg font-medium hover:bg-blue-800 transition shadow-lg">Lihat Semua Venue</a>
                </div>
            @endif
        </div>
    </section>

    <!-- Main Vendors Section -->
    <section id="main-vendors" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-6">
            <!-- Decorative header image -->
            <div class="text-center mb-10">
                <img src="https://images.unsplash.com/photo-1512485694743-9c9538b4e4e7?auto=format&fit=crop&w=800&h=200&q=80"
                     alt="Available Vendors"
                     class="mx-auto rounded-xl shadow-md">
            </div>

            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Vendor Terpercaya Kami</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Partner profesional yang siap membantu mewujudkan acara impian Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($vendors as $vendor)
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
                        <!-- Logo/Image Section -->
                        <div class="relative h-48 bg-gradient-to-br from-[#012A4A] to-[#013d70] overflow-hidden">
                            @if($vendor->logo_path)
                                <img src="{{ asset('storage/' . $vendor->logo_path) }}" 
                                     alt="{{ $vendor->display_name }}"
                                     class="w-full h-full object-contain p-6 bg-white/10 backdrop-blur-sm">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                        <span class="text-4xl font-bold text-white">{{ substr($vendor->display_name, 0, 1) }}</span>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="inline-block bg-[#27AE60] text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    {{ $vendor->display_category }}
                                </span>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="p-5">
                            <!-- Vendor Name -->
                            <h3 class="text-lg font-bold text-[#1A1A1A] mb-2 line-clamp-1">
                                {{ $vendor->display_name }}
                            </h3>

                            <!-- Location -->
                            @if($vendor->location)
                                <div class="flex items-center gap-1 text-sm text-gray-600 mb-3">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="line-clamp-1">{{ $vendor->location }}</span>
                                </div>
                            @endif

                            <!-- Description -->
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                {{ $vendor->description }}
                            </p>

                            <!-- Social Media Icons -->
                            @if($vendor->instagram || $vendor->tiktok || $vendor->whatsapp)
                                <div class="flex items-center gap-2 mb-4">
                                    @if($vendor->instagram)
                                        <a href="https://www.instagram.com/{{ $vendor->instagram }}" 
                                           target="_blank"
                                           class="w-8 h-8 rounded-full bg-gradient-to-tr from-purple-600 via-pink-600 to-orange-500 flex items-center justify-center hover:scale-110 transition">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @php
                                        $tiktokUsername = $vendor->tiktok ?? null;
                                        $tiktokUrl = $tiktokUsername ? 'https://www.tiktok.com/@' . $tiktokUsername : null;
                                    @endphp

                                    @if($tiktokUrl)
                                        <a href="{{ $tiktokUrl }}" 
                                           target="_blank"
                                           class="w-8 h-8 rounded-full bg-black flex items-center justify-center hover:scale-110 transition">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if($vendor->whatsapp)
                                        <a href="https://wa.me/{{ $vendor->whatsapp }}" 
                                           target="_blank"
                                           class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center hover:scale-110 transition">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <!-- Action Button -->
                            <a href="{{ route('vendor.profile.show', $vendor->id) }}" 
                               class="block w-full text-center bg-[#012A4A] text-white px-4 py-2.5 rounded-lg font-medium hover:bg-[#013d70] transition group-hover:shadow-lg">
                                Lihat Profil
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="mx-auto w-24 h-24 mb-6 opacity-50">
                            <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=200&h=200&q=80"
                                 alt="No vendors available"
                                 class="w-full h-full object-cover rounded-full">
                        </div>
                        <p class="text-gray-500 text-lg">Belum ada vendor tersedia</p>
                    </div>
                @endforelse
            </div>

            @if(count($vendors) > 0)
                <div class="text-center mt-16">
                    <a href="#additional-vendors" class="inline-block bg-primary text-white px-8 py-4 rounded-lg font-medium hover:bg-blue-800 transition shadow-lg">Lihat Vendor Lainnya</a>
                </div>
            @endif
        </div>
    </section>

    <!-- Additional Vendors Section -->
    <section id="additional-vendors" class="py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="container mx-auto px-6">
            <!-- Decorative header image -->
            <div class="text-center mb-10">
                <img src="https://images.unsplash.com/photo-1512485694743-9c9538b4e4e7?auto=format&fit=crop&w=800&h=200&q=80"
                     alt="Additional Vendors"
                     class="mx-auto rounded-xl shadow-md">
            </div>

            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Vendor Pilihan Lainnya</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Lebih banyak partner profesional yang siap membantu mewujudkan acara impian Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($additionalVendors as $vendor)
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
                        <!-- Logo/Image Section -->
                        <div class="relative h-48 bg-gradient-to-br from-[#012A4A] to-[#013d70] overflow-hidden">
                            @if($vendor->logo_path)
                                <img src="{{ asset('storage/' . $vendor->logo_path) }}" 
                                     alt="{{ $vendor->display_name }}"
                                     class="w-full h-full object-contain p-6 bg-white/10 backdrop-blur-sm">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                        <span class="text-4xl font-bold text-white">{{ substr($vendor->display_name, 0, 1) }}</span>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="inline-block bg-[#27AE60] text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    {{ $vendor->display_category }}
                                </span>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="p-5">
                            <!-- Vendor Name -->
                            <h3 class="text-lg font-bold text-[#1A1A1A] mb-2 line-clamp-1">
                                {{ $vendor->display_name }}
                            </h3>

                            <!-- Location -->
                            @if($vendor->location)
                                <div class="flex items-center gap-1 text-sm text-gray-600 mb-3">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="line-clamp-1">{{ $vendor->location }}</span>
                                </div>
                            @endif

                            <!-- Description -->
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                {{ $vendor->description }}
                            </p>

                            <!-- Social Media Icons -->
                            @if($vendor->instagram || $vendor->tiktok || $vendor->whatsapp)
                                <div class="flex items-center gap-2 mb-4">
                                    @if($vendor->instagram)
                                        <a href="https://www.instagram.com/{{ $vendor->instagram }}" 
                                           target="_blank"
                                           class="w-8 h-8 rounded-full bg-gradient-to-tr from-purple-600 via-pink-600 to-orange-500 flex items-center justify-center hover:scale-110 transition">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @php
                                        $tiktokUsername = $vendor->tiktok ?? null;
                                        $tiktokUrl = $tiktokUsername ? 'https://www.tiktok.com/@' . $tiktokUsername : null;
                                    @endphp

                                    @if($tiktokUrl)
                                        <a href="{{ $tiktokUrl }}" 
                                           target="_blank"
                                           class="w-8 h-8 rounded-full bg-black flex items-center justify-center hover:scale-110 transition">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if($vendor->whatsapp)
                                        <a href="https://wa.me/{{ $vendor->whatsapp }}" 
                                           target="_blank"
                                           class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center hover:scale-110 transition">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <!-- Action Button -->
                            <a href="{{ route('vendor.profile.show', $vendor->id) }}" 
                               class="block w-full text-center bg-[#012A4A] text-white px-4 py-2.5 rounded-lg font-medium hover:bg-[#013d70] transition group-hover:shadow-lg">
                                Lihat Profil
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="mx-auto w-24 h-24 mb-6 opacity-50">
                            <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=200&h=200&q=80"
                                 alt="No additional vendors available"
                                 class="w-full h-full object-cover rounded-full">
                        </div>
                        <p class="text-gray-500 text-lg">Belum ada vendor tambahan tersedia</p>
                    </div>
                @endforelse
            </div>

            @if(count($additionalVendors) > 0)
                <div class="text-center mt-16">
                    <a href="{{ route('vendors.index') }}" class="inline-block bg-primary text-white px-8 py-4 rounded-lg font-medium hover:bg-blue-800 transition shadow-lg">Lihat Semua Vendor</a>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary to-blue-800 text-white relative overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 opacity-10">
            <img src="https://images.unsplash.com/photo-1529254479751-fbacb4c7d10d?auto=format&fit=crop&w=1200&h=600&q=60"
                 alt="Event Celebration"
                 class="w-full h-full object-cover">
        </div>

        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Siap untuk Event Terbaik Anda?</h2>
            <p class="text-xl mb-10 max-w-2xl mx-auto">{{ $companySetting->company_name ?? 'EventScape' }} siap membantu mewujudkan acara impian Anda</p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                <a href="tel:{{ $companySetting->company_phone ?? '#' }}" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition shadow-lg">Hubungi Kami: {{ $companySetting->company_phone ?? 'Hubungi Kami' }}</a>
                <a href="mailto:{{ $companySetting->company_email ?? '#' }}" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-primary transition">Konsultasi Gratis</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    // Remove modal functionality since we're removing the modal
    // Just keep the carousel functionality for the venue section
});
</script>
@endpush