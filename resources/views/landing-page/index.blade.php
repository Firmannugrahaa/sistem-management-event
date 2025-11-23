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

            <!-- Carousel Container -->
            <div class="relative" x-data="{
                    currentIndex: 0,
                    venues: @php echo json_encode($venues->map(function($venue) {
                        // Prepare venue data with all necessary information
                        $venueData = [
                            'id' => $venue->id,
                            'name' => $venue->name,
                            'address' => $venue->address,
                            'price' => $venue->price,
                            'capacity' => $venue->capacity,
                        ];
                        return $venueData;
                    })->toArray(), JSON_HEX_TAG | JSON_HEX_AMP) @endphp,
                    itemsPerPage: 4
                }"
                 x-init="
                    function updateItemsPerPage() {
                        if (window.innerWidth >= 1024) itemsPerPage = 4;
                        else if (window.innerWidth >= 768) itemsPerPage = 3;
                        else if (window.innerWidth >= 640) itemsPerPage = 2;
                        else itemsPerPage = 1;
                    }
                    updateItemsPerPage();
                    window.addEventListener('resize', updateItemsPerPage);
                    // Ensure initial state is valid
                    if (venues.length === 0) {
                        itemsPerPage = 1;
                    }
                 ">
                <!-- Venue Cards Carousel -->
                <div class="overflow-hidden" style="min-height: 350px;">
                    <template x-if="venues.length > 0">
                        <div class="flex transition-transform duration-500 ease-in-out" :style="`transform: translateX(-${currentIndex * (100 / itemsPerPage)}%);`">
                            <template x-for="(venue, index) in venues" :key="index">
                                <div class="flex-shrink-0 px-3" :style="`width: ${100 / itemsPerPage}%`">
                                    <div class="bg-gray-50 rounded-2xl p-6 text-center hover:shadow-xl transition-all duration-300 border border-gray-100 h-full">
                                        <div class="w-20 h-20 mx-auto mb-5 rounded-full overflow-hidden">
                                            <img src="https://images.unsplash.com/photo-1512869251363-8e62799c3f41?auto=format&fit=crop&w=200&h=200&q=80"
                                                 :alt="venue.name || 'Venue'"
                                                 class="w-full h-full object-cover"
                                                 onerror="this.src='https://ui-avatars.com/api/?name=' + (venue.name || 'Venue') + '&background=0D8ABC&color=fff'; this.onerror=null;">
                                        </div>

                                        <h3 class="text-lg font-bold text-gray-800 mb-2 truncate" x-text="venue.name || 'Venue'">Venue</h3>
                                        <p class="text-sm text-gray-600 mb-2 truncate" x-text="venue.address || 'Address'"></p>
                                        <p class="text-sm text-primary font-medium mb-2" x-text="'Rp ' + venue.price?.toLocaleString() || 'Price'"></p>
                                        <p class="text-xs text-gray-500 mb-3" x-text="'Capacity: ' + venue.capacity + ' people'"></p>
                                        <div class="flex justify-center mt-2">
                                            <a :href="'/venues/' + venue.id" class="text-sm text-primary font-medium hover:text-blue-800 transition whitespace-nowrap">Lihat Detail →</a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="venues.length === 0">
                        <div class="text-center py-16">
                            <div class="mx-auto w-24 h-24 mb-6 opacity-50">
                                <img src="https://images.unsplash.com/photo-1584285418934-0379b7b8e6d0?auto=format&fit=crop&w=200&h=200&q=80"
                                     alt="No venues available"
                                     class="w-full h-full object-cover rounded-full">
                            </div>
                            <p class="text-gray-500 text-lg">Belum ada venue tersedia</p>
                        </div>
                    </template>
                </div>

                <!-- Navigation Arrows -->
                <template x-if="venues.length > 0">
                <div>
                    <button @click="if(currentIndex > 0) currentIndex--"
                        :disabled="currentIndex === 0"
                        class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-6 bg-white rounded-full p-3 shadow-lg border border-gray-200 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed z-10">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <button @click="if(currentIndex < Math.ceil(venues.length / itemsPerPage) - 1) currentIndex++"
                        :disabled="currentIndex >= Math.ceil(venues.length / itemsPerPage) - 1"
                        class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-6 bg-white rounded-full p-3 shadow-lg border border-gray-200 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed z-10">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                </template>

                <!-- Pagination Indicators -->
                <template x-if="venues.length > 0">
                <div class="flex justify-center mt-10 space-x-2">
                    <template x-for="i in Math.ceil(venues.length / itemsPerPage)" :key="i">
                        <button
                            @click="currentIndex = i - 1"
                            :class="{
                                'w-3 h-3 rounded-full': true,
                                'bg-primary': currentIndex === i - 1,
                                'bg-gray-300': currentIndex !== i - 1
                            }"
                            :aria-label="`Go to page ${i}`"
                        ></button>
                    </template>
                </div>
                </template>
            </div>

            @if(count($venues) > 0)
                <div class="text-center mt-16">
                    <a href="{{ route('venues.index') }}" class="inline-block bg-primary text-white px-8 py-4 rounded-lg font-medium hover:bg-blue-800 transition shadow-lg">Lihat Semua Venue</a>
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
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Vendor Tersedia Lainnya</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Lebih banyak partner terpercaya kami yang siap membantu mewujudkan acara Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($additionalVendors as $vendor)
                    <div class="bg-gray-50 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="p-7">
                            <div class="flex justify-between items-start mb-4">
                                <span class="inline-block bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">{{ $vendor->display_category }}</span>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                    <span class="ml-1 text-sm text-gray-600">{{ number_format($vendor->average_rating, 1) }}</span>
                                </div>
                            </div>
                            <div class="flex items-center mb-4">
                                <div class="w-16 h-16 rounded-full overflow-hidden mr-4">
                                    <img src="{{ $vendor->user && $vendor->user->profile_photo_path ? asset('storage/' . $vendor->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($vendor->display_name) . '&background=0D8ABC&color=fff' }}"
                                         alt="{{ $vendor->display_name }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent('{{ $vendor->display_name }}') + '&background=0D8ABC&color=fff'; this.onerror=null;">
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">{{ $vendor->display_name }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $vendor->phone_number }}</p>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-6">{{ $vendor->contact_person ? 'Contact Person: ' . $vendor->contact_person : 'Vendor Profesional' }}</p>
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-sm text-gray-500">Reviews: {{ $vendor->total_reviews }}</span>
                            </div>
                            <div class="w-full">
                                <a href="/vendors/{{ $vendor->id }}" class="w-full block text-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-800 transition">Lihat Profil</a>
                            </div>
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