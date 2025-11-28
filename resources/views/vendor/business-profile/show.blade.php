@extends('layouts.landing')

@section('content')
    {{-- Hero Section dengan Branding PT. Renjana Sanubari --}}
    <section class="relative bg-gradient-to-br from-[#9CAF88] via-[#8a9e7a] to-[#7a8e6a] text-white py-16">
        {{-- Decorative Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-white rounded-full -ml-40 -mb-40"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            {{-- Back Button --}}
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-white/90 hover:text-white mb-8 transition group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>

            {{-- Vendor Header --}}
            <div class="flex flex-col md:flex-row items-start md:items-center gap-8">
                {{-- Logo --}}
                <div class="w-40 h-40 bg-white rounded-3xl shadow-2xl flex-shrink-0 overflow-hidden p-4">
                    @if($vendor->logo_path)
                        <img src="{{ asset('storage/' . $vendor->logo_path) }}" alt="{{ $vendor->brand_name }}" class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#9CAF88] to-[#8a9e7a] text-white text-5xl font-bold rounded-2xl">
                            {{ substr($vendor->brand_name ?? 'V', 0, 1) }}
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-lg">{{ $vendor->brand_name ?? $vendor->user->name }}</h1>
                    <div class="flex flex-wrap gap-3 mb-4">
                        @if($vendor->serviceType)
                            <span class="px-4 py-2 bg-[#F4A6A0] text-white text-sm font-bold rounded-full shadow-lg">{{ $vendor->serviceType->name }}</span>
                        @endif
                        @if($vendor->location)
                            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-full flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $vendor->location }}
                            </span>
                        @endif
                        <div class="flex items-center gap-1 px-3 py-1.5 bg-white/90 backdrop-blur-sm rounded-full">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs font-semibold text-gray-700">Verified Partner</span>
                        </div>
                    </div>
                    @if($vendor->description)
                        <p class="text-white/90 text-lg max-w-3xl leading-relaxed drop-shadow-md">{{ $vendor->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <div class="container mx-auto px-6 py-12 space-y-12">
        
        {{-- Portfolio Gallery dengan Slider --}}
        @php
            $allPortfolioImages = $vendor->publishedPortfolios->flatMap(function($portfolio) {
                return $portfolio->images->map(function($image) use ($portfolio) {
                    return [
                        'image_path' => $image->image_path,
                        'title' => $portfolio->title,
                        'description' => $portfolio->description,
                        'project_date' => $portfolio->project_date
                    ];
                });
            });
        @endphp

        @if($allPortfolioImages->count() > 0)
            <section>
                <div class="text-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-[#8B8680] mb-3">Portfolio Kami</h2>
                    <p class="text-gray-600">{{ $allPortfolioImages->count() }} karya terbaik yang telah kami kerjakan</p>
                </div>

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden p-8">
                    <div class="relative" x-data="{ 
                        current: 0, 
                        total: {{ $allPortfolioImages->count() }},
                        autoplay: null,
                        init() {
                            this.startAutoplay();
                        },
                        startAutoplay() {
                            this.autoplay = setInterval(() => {
                                this.next();
                            }, 5000);
                        },
                        stopAutoplay() {
                            if (this.autoplay) {
                                clearInterval(this.autoplay);
                            }
                        },
                        next() {
                            this.current = this.current < this.total - 1 ? this.current + 1 : 0;
                        },
                        prev() {
                            this.current = this.current > 0 ? this.current - 1 : this.total - 1;
                        }
                    }" @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()">
                        {{-- Main Slider --}}
                        <div class="overflow-hidden rounded-2xl mb-6">
                            <div class="flex transition-transform duration-700 ease-in-out" :style="`transform: translateX(-${current * 100}%)`">
                                @foreach($allPortfolioImages as $index => $item)
                                    <div class="w-full flex-shrink-0">
                                        <div class="aspect-video bg-gray-100 rounded-2xl overflow-hidden relative group">
                                            <img src="{{ asset('storage/' . $item['image_path']) }}" 
                                                 alt="{{ $item['title'] }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent flex items-end p-8">
                                                <div class="text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                                    <h3 class="text-2xl md:text-3xl font-bold mb-2">{{ $item['title'] }}</h3>
                                                    @if($item['description'])
                                                        <p class="text-white/90 line-clamp-2 mb-2">{{ $item['description'] }}</p>
                                                    @endif
                                                    @if($item['project_date'])
                                                        <p class="text-white/70 text-sm flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            {{ \Carbon\Carbon::parse($item['project_date'])->format('F Y') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        {{-- Controls --}}
                        @if($allPortfolioImages->count() > 1)
                            <button @click="prev()" 
                                    class="absolute left-4 top-1/3 -translate-y-1/2 w-14 h-14 bg-white/95 hover:bg-white rounded-full shadow-2xl flex items-center justify-center transition-all z-10 hover:scale-110 group">
                                <svg class="w-6 h-6 text-[#9CAF88] group-hover:text-[#7a8e6a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button @click="next()" 
                                    class="absolute right-4 top-1/3 -translate-y-1/2 w-14 h-14 bg-white/95 hover:bg-white rounded-full shadow-2xl flex items-center justify-center transition-all z-10 hover:scale-110 group">
                                <svg class="w-6 h-6 text-[#9CAF88] group-hover:text-[#7a8e6a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            
                            {{-- Dots Indicator --}}
                            <div class="flex justify-center gap-2 mt-6 flex-wrap">
                                @foreach($allPortfolioImages as $index => $item)
                                    <button @click="current = {{ $index }}" 
                                            :class="current === {{ $index }} ? 'bg-[#9CAF88] w-8' : 'bg-gray-300 w-3'" 
                                            class="h-3 rounded-full transition-all duration-300 hover:bg-[#9CAF88]"></button>
                                @endforeach
                            </div>

                            {{-- Counter --}}
                            <div class="text-center mt-4">
                                <span class="text-sm font-semibold text-gray-600">
                                    <span x-text="current + 1"></span> / <span x-text="total"></span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        {{-- Testimonials / Reviews Section --}}
        @if(isset($testimonials) && $testimonials->count() > 0)
            <section>
                <div class="text-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-[#8B8680] mb-3">Testimoni Klien</h2>
                    <p class="text-gray-600">Apa kata mereka yang telah menggunakan layanan kami</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($testimonials as $testimonial)
                        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 border-l-4 border-[#9CAF88]">
                            {{-- Rating Stars --}}
                            <div class="flex gap-1 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= ($testimonial['rating'] ?? 5) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>

                            {{-- Feedback --}}
                            <p class="text-gray-700 mb-4 leading-relaxed italic">"{{ $testimonial['feedback'] }}"</p>

                            {{-- Client Info --}}
                            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#9CAF88] to-[#8a9e7a] flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($testimonial['client_name'], 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $testimonial['client_name'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $testimonial['event_name'] }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($testimonial['event_date'])->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Operating Hours --}}
        @if($vendor->operating_hours && is_array($vendor->operating_hours))
            <section class="bg-white rounded-3xl shadow-xl p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-[#8B8680] mb-6 text-center">Jam Operasional</h2>
                
                <div class="max-w-2xl mx-auto space-y-3">
                    @php
                        $days = ['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'];
                        $today = strtolower(\Carbon\Carbon::now()->format('l'));
                    @endphp
                    
                    @foreach($days as $dayKey => $dayName)
                        @php
                            $hours = $vendor->operating_hours[$dayKey] ?? null;
                            $isToday = $dayKey === $today;
                            $isClosed = isset($hours['is_closed']) && $hours['is_closed'];
                        @endphp
                        
                        <div class="flex items-center justify-between p-4 rounded-xl {{ $isToday ? 'bg-[#9CAF88]/10 border-2 border-[#9CAF88]' : 'bg-gray-50' }}">
                            <div class="flex items-center gap-3">
                                @if($isToday)
                                    <div class="w-2 h-2 rounded-full bg-[#9CAF88] animate-pulse"></div>
                                @endif
                                <span class="font-semibold text-gray-800 {{ $isToday ? 'text-[#9CAF88]' : '' }}">{{ $dayName }}</span>
                                @if($isToday)
                                    <span class="text-xs bg-[#9CAF88] text-white px-2 py-1 rounded-full">Hari Ini</span>
                                @endif
                            </div>
                            
                            @if($isClosed)
                                <span class="text-red-500 font-medium">Tutup</span>
                            @elseif(isset($hours['open']) && isset($hours['close']))
                                <span class="text-gray-600 font-medium">{{ $hours['open'] }} - {{ $hours['close'] }}</span>
                            @else
                                <span class="text-gray-400">Belum diatur</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Catalog Section --}}
        @if(isset($catalogItems) && $catalogItems->count() > 0)
            <section>
                <div class="text-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-[#8B8680] mb-3">Katalog Produk</h2>
                    <p class="text-gray-600">Produk dan layanan yang tersedia</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($catalogItems as $item)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group">
                            {{-- Image --}}
                            <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                @if($item->coverImage)
                                    <img src="{{ asset('storage/' . $item->coverImage->image_path) }}" 
                                         alt="{{ $item->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                {{-- Status Badge --}}
                                <div class="absolute top-3 right-3">
                                    <span class="px-3 py-1.5 text-xs font-bold rounded-full shadow-lg {{ $item->status === 'available' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                        {{ $item->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                    </span>
                                </div>
                                
                                @if($item->category)
                                    <div class="absolute top-3 left-3">
                                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-white/90 backdrop-blur-sm text-gray-800">
                                            {{ $item->category->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-5">
                                <h4 class="font-bold text-gray-800 text-lg mb-2 line-clamp-1">{{ $item->name }}</h4>
                                
                                @if($item->description)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $item->description }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    @if($item->price)
                                        <p class="text-[#9CAF88] font-bold text-lg">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    @endif
                                    
                                    @if($item->show_stock && $item->quantity !== null)
                                        <p class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            Stok: {{ $item->quantity }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Services & Packages Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Services --}}
            @if($vendor->products && $vendor->products->count() > 0)
                <section class="bg-white rounded-3xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-[#8B8680] mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#9CAF88]/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        Layanan & Harga
                    </h2>
                    <div class="space-y-4">
                        @foreach($vendor->products->take(10) as $index => $product)
                            <div x-data="{ expanded: false }" class="overflow-hidden">
                                <div @click="expanded = !expanded" 
                                     class="p-5 bg-gradient-to-r from-gray-50 to-white rounded-2xl border border-gray-100 hover:border-[#9CAF88] hover:shadow-md transition cursor-pointer"
                                     :class="{ 'border-[#9CAF88] shadow-md': expanded }">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-2">
                                                <h3 class="font-bold text-gray-800 text-lg flex-1">{{ $product->name }}</h3>
                                                <span class="text-[#9CAF88] font-bold text-lg whitespace-nowrap ml-3">
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <span class="inline-block text-xs text-gray-600 bg-white px-3 py-1 rounded-full">{{ $product->category }}</span>
                                        </div>
                                        
                                        {{-- Expand/Collapse Icon --}}
                                        <div class="ml-4 flex-shrink-0">
                                            <svg class="w-6 h-6 text-[#9CAF88] transition-transform duration-300" 
                                                 :class="{ 'rotate-180': expanded }" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Expandable Content --}}
                                    <div x-show="expanded" 
                                         x-collapse
                                         class="mt-4 pt-4 border-t border-gray-200">
                                        @if($product->description)
                                            <div class="mb-4">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Deskripsi
                                                </h4>
                                                <p class="text-sm text-gray-600 leading-relaxed pl-6">{{ $product->description }}</p>
                                            </div>
                                        @endif
                                        
                                        {{-- Additional Details --}}
                                        <div class="grid grid-cols-2 gap-3 pl-6">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-sm text-gray-600">Kategori: <span class="font-semibold text-gray-800">{{ $product->category }}</span></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-sm text-gray-600">Harga: <span class="font-bold text-[#9CAF88]">Rp {{ number_format($product->price, 0, ',', '.') }}</span></span>
                                            </div>
                                        </div>

                                        {{-- CTA Button --}}
                                        <div class="mt-4 pl-6">
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $vendor->whatsapp ?? $vendor->phone_number) }}?text=Halo, saya tertarik dengan layanan {{ $product->name }}" 
                                               target="_blank"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-[#9CAF88] text-white rounded-xl font-semibold hover:bg-[#8a9e7a] transition">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                                Tanya via WhatsApp
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Packages --}}
            @if($vendor->packages()->where('is_visible', true)->count() > 0)
                <section class="bg-white rounded-3xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-[#8B8680] mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#9CAF88]/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        Paket Layanan
                    </h2>
                    <div class="space-y-6">
                        @foreach($vendor->packages()->where('is_visible', true)->get()->take(5) as $package)
                            <div x-data="{ expanded: false }" class="overflow-hidden">
                                <div class="border-2 rounded-2xl transition-all duration-300"
                                     :class="expanded ? 'border-[#9CAF88] shadow-xl' : 'border-gray-100 hover:border-[#9CAF88] hover:shadow-md'">
                                    
                                    {{-- Thumbnail Image --}}
                                    @if($package->thumbnail_path)
                                        <div class="aspect-video bg-gradient-to-br from-[#9CAF88] to-[#8a9e7a] relative">
                                            <img src="{{ asset('storage/' . $package->thumbnail_path) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                                            @if($package->savings > 0)
                                                <span class="absolute top-4 right-4 px-4 py-2 bg-[#F4A6A0] text-white text-sm font-bold rounded-full shadow-lg animate-pulse">
                                                    Hemat {{ $package->savings_percentage }}%
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    {{-- Package Header (Clickable) --}}
                                    <div @click="expanded = !expanded" class="p-6 cursor-pointer">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h3 class="font-bold text-gray-800 text-xl mb-2">{{ $package->name }}</h3>
                                                <p class="text-sm text-gray-500 mb-3 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                    </svg>
                                                    {{ $package->services->count() }} Layanan Termasuk
                                                </p>
                                                <div class="flex items-baseline gap-3">
                                                    <span class="text-2xl font-bold text-[#9CAF88]">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                                    @if($package->individual_price > $package->price)
                                                        <span class="text-sm text-gray-400 line-through">Rp {{ number_format($package->individual_price, 0, ',', '.') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            {{-- Expand/Collapse Icon --}}
                                            <div class="ml-4 flex-shrink-0">
                                                <svg class="w-6 h-6 text-[#9CAF88] transition-transform duration-300" 
                                                     :class="{ 'rotate-180': expanded }" 
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Expandable Content --}}
                                    <div x-show="expanded" 
                                         x-collapse
                                         class="px-6 pb-6">
                                        <div class="pt-4 border-t border-gray-200">
                                            {{-- Package Description --}}
                                            @if($package->description)
                                                <div class="mb-6">
                                                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Deskripsi Paket
                                                    </h4>
                                                    <p class="text-sm text-gray-600 leading-relaxed pl-6">{{ $package->description }}</p>
                                                </div>
                                            @endif

                                            {{-- Included Services List --}}
                                            @if($package->services && $package->services->count() > 0)
                                                <div class="mb-6">
                                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                        </svg>
                                                        Layanan yang Termasuk
                                                    </h4>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-6">
                                                        @foreach($package->services as $service)
                                                            <div class="flex items-start gap-2">
                                                                <svg class="w-5 h-5 text-[#9CAF88] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <div class="flex-1">
                                                                    <p class="text-sm font-medium text-gray-800">{{ $service->name }}</p>
                                                                    @if($service->category)
                                                                        <p class="text-xs text-gray-500">{{ $service->category }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Savings Highlight --}}
                                            @if($package->savings > 0)
                                                <div class="mb-6 p-4 bg-[#F4A6A0]/10 rounded-xl border-l-4 border-[#F4A6A0]">
                                                    <div class="flex items-center gap-3">
                                                        <svg class="w-6 h-6 text-[#F4A6A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <div>
                                                            <p class="text-sm font-bold text-gray-800">Hemat Rp {{ number_format($package->savings, 0, ',', '.') }}</p>
                                                            <p class="text-xs text-gray-600">Dengan mengambil paket ini ({{ $package->savings_percentage }}% lebih murah)</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- CTA Button --}}
                                            <div class="flex gap-3">
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $vendor->whatsapp ?? $vendor->phone_number) }}?text=Halo, saya tertarik dengan paket {{ $package->name }}" 
                                                   target="_blank"
                                                   class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#9CAF88] text-white rounded-xl font-bold hover:bg-[#8a9e7a] transition shadow-lg hover:shadow-xl">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                    </svg>
                                                    Pesan via WhatsApp
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        {{-- Contact Info --}}
        <section class="bg-gradient-to-br from-[#9CAF88] to-[#8a9e7a] rounded-3xl shadow-xl p-8 md:p-12 text-white">
            <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-bold mb-3">Hubungi Kami</h2>
                <p class="text-white/90">Konsultasikan kebutuhan acara Anda dengan kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl mx-auto">
                @if($vendor->phone_number || $vendor->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $vendor->whatsapp ?? $vendor->phone_number) }}" 
                       target="_blank"
                       class="flex items-center gap-4 p-6 bg-white/10 backdrop-blur-sm rounded-2xl hover:bg-white/20 transition group">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-white/70 text-sm mb-1">WhatsApp</p>
                            <p class="font-bold">{{ $vendor->whatsapp ?? $vendor->phone_number }}</p>
                        </div>
                    </a>
                @endif
                
                @if($vendor->email)
                    <a href="mailto:{{ $vendor->email }}" 
                       class="flex items-center gap-4 p-6 bg-white/10 backdrop-blur-sm rounded-2xl hover:bg-white/20 transition group">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-white/70 text-sm mb-1">Email</p>
                            <p class="font-bold truncate">{{ $vendor->email }}</p>
                        </div>
                    </a>
                @endif
                
                @if($vendor->address)
                    <div class="flex items-center gap-4 p-6 bg-white/10 backdrop-blur-sm rounded-2xl md:col-span-2 lg:col-span-1">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-white/70 text-sm mb-1">Alamat</p>
                            <p class="font-semibold leading-relaxed">{{ $vendor->address }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Social Media --}}
            @if($vendor->instagram || $vendor->tiktok || $vendor->facebook)
                <div class="flex justify-center gap-4 mt-8 pt-8 border-t border-white/20">
                    @if($vendor->instagram)
                        <a href="https://instagram.com/{{ ltrim($vendor->instagram, '@') }}" 
                           target="_blank"
                           class="w-14 h-14 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all hover:scale-110">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/>
                            </svg>
                        </a>
                    @endif
                    @if($vendor->tiktok)
                        <a href="https://tiktok.com/@{{ ltrim($vendor->tiktok, '@') }}" 
                           target="_blank"
                           class="w-14 h-14 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all hover:scale-110">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                            </svg>
                        </a>
                    @endif
                    @if($vendor->facebook)
                        <a href="{{ $vendor->facebook }}" 
                           target="_blank"
                           class="w-14 h-14 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all hover:scale-110">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            @endif
        </section>
    </div>
@endsection
