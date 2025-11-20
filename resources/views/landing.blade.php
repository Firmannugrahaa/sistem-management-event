<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teman Menuju Halal - Wedding Organizer</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Swiper.js for carousel -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

    <style>
        :root {
            --primary-bg: #F6F2EE;
            --accent-gold: #A27B5C;
            --text-dark: #2C2C2C;
            --white: #FFFFFF;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-dark);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Playfair Display', serif;
        }

        .hero-section {
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .slideshow-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .slide.active {
            opacity: 1;
        }

        .slide-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.3);
            color: white;
            z-index: 10;
        }

        .vendor-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .vendor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .package-card {
            transition: all 0.3s ease;
        }

        .package-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-overlay {
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: var(--white);
            border-radius: 10px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .ken-burns {
            animation: kenburns 15s infinite;
        }

        @keyframes kenburns {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.1);
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans" x-data="appState()" @alpine:init="initApp()">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white bg-opacity-90 backdrop-blur-sm z-50 shadow-sm">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-xl md:text-2xl font-bold" style="color: #A27B5C;">Teman Menuju Halal</h1>
            </div>

            <div class="hidden md:flex space-x-8">
                <a href="#home" class="font-medium hover:text-[#A27B5C] transition-colors">Home</a>
                <a href="#vendor" class="font-medium hover:text-[#A27B5C] transition-colors">Vendor</a>
                <a href="#paket" class="font-medium hover:text-[#A27B5C] transition-colors">Paket</a>
            </div>

            <div class="flex items-center space-x-4">
                <button @click="showRegistrationModal = true" class="px-4 py-2 bg-[#A27B5C] text-white rounded-md hover:bg-opacity-90 transition-colors">Daftar</button>
                <button class="px-4 py-2 border border-[#A27B5C] text-[#A27B5C] rounded-md hover:bg-[#A27B5C] hover:text-white transition-colors">Login</button>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Slideshow -->
    <section id="home" class="hero-section">
        <div class="slideshow-container">
            <template x-for="(image, index) in images" :key="index">
                <div
                    class="slide ken-burns"
                    :class="{'active': currentIndex === index}"
                    :style="`background-image: url('${image}')`">
                </div>
            </template>

            <div class="slide-content">
                <h1 class="text-4xl md:text-6xl font-bold mb-4" data-aos="fade-down" data-aos-duration="1000">Teman Menuju Halal</h1>
                <p class="text-xl md:text-2xl mb-8 max-w-2xl" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">Your Wedding Partner to a Blessed Journey</p>
                <div class="flex flex-col sm:flex-row gap-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
                    <button @click="showVendorModal = true" class="px-6 py-3 bg-[#A27B5C] text-white rounded-md hover:bg-opacity-90 transition-colors">Lihat Vendor</button>
                    <button @click="showRegistrationModal = true" class="px-6 py-3 border-2 border-white text-white rounded-md hover:bg-white hover:text-[#2C2C2C] transition-colors">Daftar</button>
                    <button class="px-6 py-3 border-2 border-white text-white rounded-md hover:bg-white hover:text-[#2C2C2C] transition-colors">Login</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Vendor Section -->
    <section id="vendor" class="py-20 bg-[#F6F2EE]">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-16" data-aos="fade-up">Vendor Rekanan Kami</h2>

            <div class="overflow-x-auto pb-8">
                <div class="flex space-x-8" style="min-width: fit-content;">
                    <template x-for="(vendor, index) in vendors" :key="index">
                        <div
                            @click="selectedVendor = vendor; showVendorModal = true;"
                            class="vendor-card flex-shrink-0 w-64 cursor-pointer"
                            data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <img :src="vendor.image" :alt="vendor.name" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="font-bold text-lg mb-1" x-text="vendor.name"></h3>
                                    <p class="text-gray-600 mb-2" x-text="vendor.category"></p>
                                    <div class="flex items-center">
                                        <span class="text-yellow-500 mr-1">★</span>
                                        <span x-text="vendor.rating"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- Wedding Package Section -->
    <section id="paket" class="py-20">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-16" data-aos="fade-up">Paket Pernikahan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <template x-for="(package, index) in packages" :key="index">
                    <div
                        class="package-card bg-white rounded-xl shadow-lg overflow-hidden"
                        data-aos="fade-up"
                        :data-aos-delay="index * 100">
                        <img :src="package.image" :alt="package.name" class="w-full h-56 object-cover">
                        <div class="p-6">
                            <h3 class="font-bold text-xl mb-2" x-text="package.name"></h3>
                            <p class="text-gray-600 mb-4" x-text="package.description"></p>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-[#A27B5C]" x-text="'Rp ' + package.price"></span>
                                <button
                                    @click="selectedPackage = package.name; showRegistrationModal = true;"
                                    class="px-4 py-2 bg-[#A27B5C] text-white rounded-md hover:bg-opacity-90 transition-colors">
                                    Detail Paket
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#2C2C2C] text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4" style="color: #A27B5C;">Teman Menuju Halal</h3>
                    <p class="text-gray-300">Your Wedding Partner to a Blessed Journey</p>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Menu</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-300 hover:text-[#A27B5C] transition-colors">Home</a></li>
                        <li><a href="#vendor" class="text-gray-300 hover:text-[#A27B5C] transition-colors">Vendor</a></li>
                        <li><a href="#paket" class="text-gray-300 hover:text-[#A27B5C] transition-colors">Paket</a></li>
                        <li><a href="#" @click="showRegistrationModal = true" class="text-gray-300 hover:text-[#A27B5C] transition-colors">Daftar</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li>Email: info@temanmenujuhalal.com</li>
                        <li>Telp: 021-1234-5678</li>
                        <li>Alamat: Jl. Wedding No. 123, Jakarta</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-[#A27B5C] transition-colors">Facebook</a>
                        <a href="#" class="text-gray-300 hover:text-[#A27B5C] transition-colors">Instagram</a>
                        <a href="#" class="text-gray-300 hover:text-[#A27B5C] transition-colors">Twitter</a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2023 Teman Menuju Halal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Vendor Detail Modal -->
    <div
        x-show="showVendorModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-overlay"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">
        <div class="modal-content w-full max-w-4xl mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 x-text="selectedVendor.name" class="text-2xl font-bold"></h3>
                    <button @click="showVendorModal = false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <img :src="selectedVendor.image" :alt="selectedVendor.name" class="w-full h-64 object-cover rounded-lg">
                        <div class="mt-4">
                            <h4 class="font-bold text-lg mb-2">Deskripsi</h4>
                            <p x-text="selectedVendor.description" class="text-gray-700"></p>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <h4 class="font-bold text-lg mb-2">Informasi</h4>
                            <p><span class="font-medium">Kategori:</span> <span x-text="selectedVendor.category"></span></p>
                            <p><span class="font-medium">Rating:</span> <span x-text="selectedVendor.rating"></span> ★</p>
                            <p><span class="font-medium">Lokasi:</span> <span x-text="selectedVendor.location"></span></p>
                        </div>

                        <div>
                            <h4 class="font-bold text-lg mb-2">Event yang Pernah Ditangani</h4>
                            <div class="swiper mySwiperVendor">
                                <div class="swiper-wrapper">
                                    <template x-for="(event, index) in selectedVendor.events" :key="index">
                                        <div class="swiper-slide">
                                            <img :src="event.image" :alt="event.name" class="w-full h-32 object-cover rounded">
                                            <p class="mt-2" x-text="event.name"></p>
                                        </div>
                                    </template>
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Modal -->
    <div
        x-show="showRegistrationModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-overlay"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">
        <div class="modal-content w-full max-w-2xl mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-2xl font-bold">Form Pendaftaran</h3>
                    <button @click="showRegistrationModal = false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <form @submit.prevent="submitRegistration">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 mb-2">Nama CPW</label>
                            <input type="text" x-model="formData.namaCPW" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#A27B5C]">
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Nama CPP</label>
                            <input type="text" x-model="formData.namaCPP" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#A27B5C]">
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Email</label>
                            <input type="email" x-model="formData.email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#A27B5C]">
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">No HP</label>
                            <input type="tel" x-model="formData.noHP" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#A27B5C]">
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Tanggal Acara</label>
                            <input type="date" x-model="formData.tanggalAcara" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#A27B5C]">
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Lokasi Acara</label>
                            <input type="text" x-model="formData.lokasiAcara" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#A27B5C]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Pilihan Paket</label>
                            <select x-model="formData.pilihanPaket" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#A27B5C]">
                                <option value="">Pilih Paket</option>
                                <template x-for="(pkg, index) in packages" :key="index">
                                    <option :value="pkg.name" x-text="pkg.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" @click="showRegistrationModal = false" class="px-4 py-2 mr-2 border border-gray-300 rounded-md hover:bg-gray-100">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#A27B5C] text-white rounded-md hover:bg-opacity-90">Daftar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Initialize Scripts -->
    <script>
        function appState() {
            return {
                // Slideshow
                images: [
                    'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=2070&q=80',
                    'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=2070&q=80',
                    'https://images.unsplash.com/photo-1585344804486-bd1b13a18e5d?auto=format&fit=crop&w=2070&q=80',
                    'https://images.unsplash.com/photo-1510089610169-223308d1ab53?auto=format&fit=crop&w=2070&q=80'
                ],
                currentIndex: 0,
                slideshowInterval: null,

                // Modals
                showVendorModal: false,
                showRegistrationModal: false,

                // Data
                selectedVendor: {},
                selectedPackage: '',

                vendors: [{
                        id: 1,
                        name: 'Bridal Elegance',
                        category: 'Make Up & Fashion',
                        rating: '4.8',
                        location: 'Jakarta',
                        image: 'https://images.unsplash.com/photo-1519535240162-cb13e9e8e8e1?auto=format&fit=crop&w=800&q=80',
                        description: 'Specialis rias pengantin tradisional dan modern dengan pengalaman lebih dari 10 tahun.',
                        events: [{
                                name: 'Wedding Anissa & Budi',
                                image: 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80'
                            },
                            {
                                name: 'Wedding Sari & Andi',
                                image: 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80'
                            },
                            {
                                name: 'Wedding Maya & Rian',
                                image: 'https://images.unsplash.com/photo-1585344804486-bd1b13a18e5d?auto=format&fit=crop&w=800&q=80'
                            }
                        ]
                    },
                    {
                        id: 2,
                        name: 'Delicious Moments',
                        category: 'Catering',
                        rating: '4.7',
                        location: 'Bandung',
                        image: 'https://images.unsplash.com/photo-1551218808-94e220e084d2?auto=format&fit=crop&w=800&q=80',
                        description: 'Katering pernikahan dengan hidangan nusantara dan internasional yang lezat.',
                        events: [{
                                name: 'Wedding Nisa & Rizki',
                                image: 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80'
                            },
                            {
                                name: 'Wedding Dina & Reza',
                                image: 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80'
                            }
                        ]
                    },
                    {
                        id: 3,
                        name: 'Sacred Decor',
                        category: 'Dekorasi',
                        rating: '4.9',
                        location: 'Surabaya',
                        image: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                        description: 'Spesialis dekorasi pernikahan dengan sentuhan religius dan estetika tinggi.',
                        events: [{
                                name: 'Wedding Intan & Fajar',
                                image: 'https://images.unsplash.com/photo-1585344804486-bd1b13a18e5d?auto=format&fit=crop&w=800&q=80'
                            },
                            {
                                name: 'Wedding Lila & Yoga',
                                image: 'https://images.unsplash.com/photo-1510089610169-223308d1ab53?auto=format&fit=crop&w=800&q=80'
                            }
                        ]
                    },
                    {
                        id: 4,
                        name: 'Harmony Sounds',
                        category: 'Music & Entertain',
                        rating: '4.6',
                        location: 'Yogyakarta',
                        image: 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80',
                        description: 'Menyediakan hiburan pernikahan dengan musik tradisional dan modern.',
                        events: [{
                                name: 'Wedding Sinta & Agus',
                                image: 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80'
                            },
                            {
                                name: 'Wedding Rina & Joko',
                                image: 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80'
                            }
                        ]
                    }
                ],

                packages: [{
                        id: 1,
                        name: 'Paket Akad',
                        description: 'Paket lengkap untuk akad nikah dengan dekorasi, make up, dan dokumentasi.',
                        price: '7.500.000',
                        image: 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80'
                    },
                    {
                        id: 2,
                        name: 'Paket Resepsi',
                        description: 'Paket untuk resepsi pernikahan dengan catering, dekorasi, dan hiburan.',
                        price: '15.000.000',
                        image: 'https://images.unsplash.com/photo-1585344804486-bd1b13a18e5d?auto=format&fit=crop&w=800&q=80'
                    },
                    {
                        id: 3,
                        name: 'Paket Lengkap',
                        description: 'Paket komplit untuk akad dan resepsi dengan semua kebutuhan pernikahan.',
                        price: '25.000.000',
                        image: 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80'
                    }
                ],

                formData: {
                    namaCPW: '',
                    namaCPP: '',
                    email: '',
                    noHP: '',
                    tanggalAcara: '',
                    lokasiAcara: '',
                    pilihanPaket: ''
                },

                // Methods
                initApp() {
                    // Set default selected vendor
                    this.selectedVendor = this.vendors[0] || {};

                    // Start slideshow
                    this.startSlideshow();

                    // Initialize AOS
                    setTimeout(() => {
                        AOS.init({
                            duration: 1000,
                            once: true
                        });
                    }, 100);

                    // Initialize Swiper when modal is shown
                    setTimeout(() => {
                        this.initSwiper();
                    }, 500);
                },

                startSlideshow() {
                    if (this.slideshowInterval) clearInterval(this.slideshowInterval);
                    this.slideshowInterval = setInterval(() => {
                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                    }, 5000);
                },

                initSwiper() {
                    if (document.querySelector('.mySwiperVendor')) {
                        new Swiper('.mySwiperVendor', {
                            slidesPerView: 1,
                            spaceBetween: 10,
                            loop: true,
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                            autoplay: {
                                delay: 3000,
                                disableOnInteraction: false,
                            }
                        });
                    }
                },

                submitRegistration() {
                    console.log('Form submitted:', this.formData);
                    alert('Pendaftaran berhasil! Kami akan segera menghubungi Anda.');
                    this.showRegistrationModal = false;
                    this.formData = {
                        namaCPW: '',
                        namaCPP: '',
                        email: '',
                        noHP: '',
                        tanggalAcara: '',
                        lokasiAcara: '',
                        pilihanPaket: ''
                    };
                },

                destroy() {
                    if (this.slideshowInterval) clearInterval(this.slideshowInterval);
                }
            }
        }

        // Initialize when Alpine is ready
        document.addEventListener('alpine:init', () => {
            // AOS is already initialized in initApp
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            if (window.__appState && window.__appState.destroy) {
                window.__appState.destroy();
            }
        });
    </script>
</body>

</html>