<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teman Menuju Halal - Wedding Service Partner Resmi dari Instansi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional styles for landing page -->
    <style>
        :root {
            --primary: #F7EDE2; /* Soft Cream */
            --secondary: #1C2440; /* Deep Navy */
            --accent: #FFCDB2; /* Soft Peach */
            --highlight: #D4AF37; /* Gold */
            --text: #1A1A1A;
        }

        body {
            font-family: 'Inter', 'Poppins', sans-serif;
        }

        .font-heading {
            font-family: 'Playfair Display', serif;
        }

        .font-subheading {
            font-family: 'Cormorant Garamond', serif;
        }

        .bg-temanhalal-primary {
            background-color: #F7EDE2;
        }

        .bg-temanhalal-secondary {
            background-color: #1C2440;
        }

        .bg-temanhalal-accent {
            background-color: #FFCDB2;
        }

        .text-temanhalal-primary {
            color: #F7EDE2;
        }

        .text-temanhalal-secondary {
            color: #1C2440;
        }

        .text-temanhalal-accent {
            color: #FFCDB2;
        }

        .text-temanhalal-gold {
            color: #D4AF37;
        }

        .border-temanhalal-gold {
            border-color: #D4AF37;
        }

        .shadow-temanhalal {
            box-shadow: 0 10px 30px -10px rgba(28, 36, 64, 0.2);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #F7EDE2 30%, #FFFFFF 100%);
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(28, 36, 64, 0.25);
        }

        .vendor-card {
            border-radius: 16px;
            overflow: hidden;
        }

        .package-card {
            border-radius: 24px;
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .package-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px -12px rgba(28, 36, 64, 0.3);
        }

        .modal-overlay {
            backdrop-filter: blur(6px);
        }

        .floating-floral {
            position: absolute;
            opacity: 0.1;
            z-index: 0;
        }

        .floating-floral-1 {
            top: 10%;
            left: 5%;
            font-size: 3rem;
            animation: float 8s ease-in-out infinite;
        }

        .floating-floral-2 {
            bottom: 15%;
            right: 5%;
            font-size: 4rem;
            animation: float 10s ease-in-out infinite;
        }

        .floating-floral-3 {
            top: 40%;
            right: 15%;
            font-size: 2.5rem;
            animation: float 9s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        /* Premium spacing and layout */
        section {
            position: relative;
            padding-top: 8rem;
            padding-bottom: 8rem;
        }

        .section-padding {
            padding: 6rem 0;
        }

        /* Elegant border radius */
        .rounded-container {
            border-radius: 24px;
        }

        /* Gold accent elements */
        .gold-accent::before {
            content: '';
            position: absolute;
            height: 4px;
            width: 60px;
            background-color: #D4AF37;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Smooth transitions */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        /* Typography enhancements */
        .text-display {
            font-size: clamp(2.5rem, 5vw, 4rem);
            line-height: 1.1;
        }

        .text-subtitle {
            font-size: clamp(1.25rem, 2.5vw, 1.5rem);
            line-height: 1.6;
        }

        /* Section transition effects */
        section {
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        section.section-fade-out {
            opacity: 0.8;
        }

        section.section-fade-in {
            opacity: 1;
        }

        /* Smooth scrolling behavior */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-temanhalal-primary text-temanhalal-secondary">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-sm transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="bg-temanhalal-secondary text-temanhalal-gold w-10 h-10 rounded-full flex items-center justify-center font-heading text-lg font-bold">
                    TMH
                </div>
                <span class="font-heading text-xl font-bold text-temanhalal-secondary">Teman Menuju Halal</span>
            </div>
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#about" class="text-temanhalal-secondary hover:text-temanhalal-gold transition-colors font-medium">Tentang Kami</a>
                <a href="#vendors" class="text-temanhalal-secondary hover:text-temanhalal-gold transition-colors font-medium">Vendor</a>
                <a href="#portfolio" class="text-temanhalal-secondary hover:text-temanhalal-gold transition-colors font-medium">Portfolio</a>
                <a href="#packages" class="text-temanhalal-secondary hover:text-temanhalal-gold transition-colors font-medium">Paket</a>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="/login" class="text-temanhalal-secondary hover:text-temanhalal-gold transition-colors font-medium">Login</a>
                <button onclick="openRegistrationModal()" class="bg-gradient-to-r from-temanhalal-gold to-amber-600 text-white px-6 py-2 rounded-lg hover:from-amber-600 hover:to-temanhalal-gold transition-all duration-300 font-medium hover:scale-105">Daftar Sekarang</button>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section id="hero" class="relative min-h-screen flex items-center justify-center hero-gradient text-center overflow-hidden" style="background: linear-gradient(135deg, #F7EDE2 30%, #FFFFFF 100%);">
            <!-- Background image with overlay -->
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://placehold.co/1200x800/F7EDE2/1C2440?text=Wedding+Image'); opacity: 0.3;"></div>

            <!-- Floating floral elements -->
            <div class="floating-floral floating-floral-1">🌸</div>
            <div class="floating-floral floating-floral-2">🌸</div>
            <div class="floating-floral floating-floral-3">🌸</div>

            <div class="container mx-auto px-6 z-10">
                <div class="flex flex-col items-center">
                    <!-- Logo TMH and Teman Menuju Halal side-by-side -->
                    <div class="flex items-center justify-center gap-6 mb-8" data-aos="fade-up" data-aos-duration="500">
                        <div class="flex items-center space-x-2">
                            <div class="bg-temanhalal-secondary text-temanhalal-gold w-16 h-16 rounded-full flex items-center justify-center font-heading text-2xl font-bold">
                                TMH
                            </div>
                            <span class="font-heading text-2xl font-bold text-temanhalal-secondary">Teman Menuju Halal</span>
                        </div>
                    </div>

                    <div class="max-w-4xl" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-temanhalal-secondary leading-tight font-heading mb-6">
                            Teman Menuju Halal — Curated Wedding Experience for Your Special Day
                        </h2>
                        <p class="text-lg md:text-xl text-temanhalal-secondary max-w-3xl mx-auto font-subheading mb-10" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                            Platform resmi dari Teman Menuju Halal untuk membantu calon pengantin merencanakan pernikahan secara elegan, terarah, dan penuh kehangatan—dengan vendor pilihan yang telah dikurasi secara profesional.
                        </p>
                        <div class="mb-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                            <p class="text-2xl text-temanhalal-gold font-subheading italic">"Where Your Journey Begins with Harmony."</p>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-center gap-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400">
                            <a href="#vendors" class="bg-gradient-to-r from-temanhalal-secondary to-temanhalal-gold text-white px-10 py-4 rounded-full font-semibold text-lg hover:from-temanhalal-gold hover:to-temanhalal-secondary transition-all duration-300 transform hover:scale-105 shadow-lg shadow-temanhalal-secondary/20">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                    Lihat Vendor Rekanan
                                </span>
                            </a>
                            <button @click="isModalOpen = true" class="bg-temanhalal-secondary text-white px-10 py-4 rounded-full font-semibold text-lg hover:bg-temanhalal-gold hover:text-temanhalal-secondary border-2 border-temanhalal-secondary transition-all duration-300 shadow-lg shadow-temanhalal-secondary/20">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                    Daftar sebagai Pengguna
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gentle fade animation -->
            <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-white to-transparent"></div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="600">
                    <h3 class="text-3xl md:text-4xl font-bold text-temanhalal-secondary mb-4 font-heading">Tentang Teman Menuju Halal</h3>
                    <p class="text-lg text-temanhalal-secondary max-w-2xl mx-auto" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">Teman Menuju Halal adalah penyedia layanan pernikahan terpadu yang hadir untuk menjadi pendamping bagi calon pengantin dalam mempersiapkan hari istimewa mereka.</p>
                </div>

                <div class="max-w-4xl mx-auto text-center" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <p class="text-lg md:text-xl text-temanhalal-secondary font-subheading mb-10 leading-relaxed">
                        Dengan pengalaman dalam merancang dan mengelola berbagai rangkaian pernikahan, kami menghadirkan platform yang menyatukan vendor-vendor rekanan pilihan, layanan profesional, serta paket pernikahan eksklusif.<br><br>

                        Fokus kami adalah kenyamanan Anda—melalui proses yang lebih ringkas, terstruktur, dan personal, serta kualitas layanan yang terjaga karena setiap vendor telah melalui proses seleksi dan kurasi dari tim kami.
                    </p>
                </div>
            </div>
        </section>

        <!-- Vendor Section -->
        <section id="vendors" class="py-20 bg-temanhalal-primary">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="600">
                    <h3 class="text-3xl md:text-4xl font-bold text-temanhalal-secondary mb-4 font-heading">Vendor Rekanan Teman Menuju Halal</h3>
                    <p class="text-lg text-temanhalal-secondary max-w-2xl mx-auto" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">Mitra-mitra terpercaya yang telah dipilih berdasarkan kualitas, profesionalisme, integritas, serta pengalaman mereka dalam industri pernikahan.</p>
                </div>

                <!-- Horizontal slider container -->
                <div class="overflow-x-auto pb-10 -mx-6 px-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <div class="flex space-x-8" id="vendor-slider">
                        @foreach($vendors as $vendor)
                        <div class="flex-shrink-0 w-80 bg-white rounded-2xl shadow-lg overflow-hidden vendor-card card-hover transition-all duration-300 hover:scale-[1.03] cursor-pointer hover:shadow-xl"
                             onclick="showVendorModal('{{ $vendor['name'] }}', '{{ $vendor['category'] }}', '{{ $vendor['image'] }}', '{{ $vendor['rating'] }}', '{{ $vendor['reviews'] }}')"
                             data-aos="fade-up"
                             data-aos-duration="600"
                             data-aos-delay="{{ $loop->index * 150 }}">
                            <div class="relative">
                                <img src="{{ $vendor['image'] }}" alt="{{ $vendor['name'] }}" class="w-full h-64 object-cover">
                                <div class="absolute top-4 right-4 bg-temanhalal-gold text-white text-xs font-bold px-3 py-1 rounded-full">
                                    Verified Partner
                                </div>
                            </div>
                            <div class="p-6">
                                <h4 class="text-xl font-bold text-temanhalal-secondary mb-2">{{ $vendor['name'] }}</h4>
                                <p class="text-temanhalal-secondary mb-4">{{ $vendor['category'] }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="text-temanhalal-gold font-bold mr-1">{{ $vendor['rating'] }}</span>
                                        <svg class="w-4 h-4 text-temanhalal-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"></path></svg>
                                        <span class="text-gray-500 text-sm ml-1">({{ $vendor['reviews'] }})</span>
                                    </div>
                                    <span class="text-xs bg-temanhalal-primary text-temanhalal-secondary px-2 py-1 rounded-full">
                                        {{ $vendor['status'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation buttons -->
                <div class="flex justify-center mt-8 space-x-4">
                    <button id="prev-vendor" class="p-3 rounded-full bg-white shadow-md hover:bg-temanhalal-gold hover:text-white transition-colors">
                        <svg class="w-6 h-6 text-temanhalal-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button id="next-vendor" class="p-3 rounded-full bg-white shadow-md hover:bg-temanhalal-gold hover:text-white transition-colors">
                        <svg class="w-6 h-6 text-temanhalal-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- Portfolio Section -->
        <section id="portfolio" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="600">
                    <h3 class="text-3xl md:text-4xl font-bold text-temanhalal-secondary mb-4 font-heading">Karya & Dokumentasi Acara</h3>
                    <p class="text-lg text-temanhalal-secondary max-w-2xl mx-auto" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">Temukan hasil karya terbaik dari vendor yang telah bekerja sama dengan Teman Menuju Halal. Setiap sentuhan adalah dedikasi dan cerita dari tiap perjalanan menuju hari bahagia.</p>
                </div>

                <!-- Carousel container -->
                <div class="relative" id="portfolio-carousel" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <div class="carousel-wrapper overflow-hidden rounded-3xl">
                        <div class="carousel-track flex transition-transform duration-700 ease-in-out" id="carousel-track">
                            @foreach($events as $event)
                            <div class="carousel-item flex-shrink-0 w-full md:w-11/12 lg:w-3/4 xl:w-1/2 px-4">
                                <div class="relative rounded-3xl overflow-hidden shadow-2xl h-[500px] transform transition-transform duration-500 hover:scale-[1.02]">
                                    <img src="{{ $event['image'] }}" alt="{{ $event['name'] }}" class="w-full h-full object-cover ken-burns-effect">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-8 opacity-80 hover:opacity-100 transition-opacity duration-300">
                                        <div class="text-white">
                                            <h4 class="text-2xl font-bold mb-2">{{ $event['name'] }}</h4>
                                            <p class="text-temanhalal-accent mb-1">{{ $event['vendor'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation controls -->
                    <button id="prev-portfolio" class="absolute top-1/2 left-4 transform -translate-y-1/2 z-10 p-4 rounded-full bg-white/80 shadow-lg hover:bg-temanhalal-gold hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6 text-temanhalal-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <button id="next-portfolio" class="absolute top-1/2 right-4 transform -translate-y-1/2 z-10 p-4 rounded-full bg-white/80 shadow-lg hover:bg-temanhalal-gold hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6 text-temanhalal-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Position indicators -->
                    <div class="flex justify-center mt-8 space-x-2" id="carousel-indicators">
                        @for($i = 0; $i < count($events); $i++)
                        <div class="indicator w-3 h-3 rounded-full bg-gray-300 cursor-pointer transition-colors"></div>
                        @endfor
                    </div>
                </div>
            </div>
        </section>

        <!-- Packages Section -->
        <section id="packages" class="py-20 bg-temanhalal-primary">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="600">
                    <h3 class="text-3xl md:text-4xl font-bold text-temanhalal-secondary mb-4 font-heading">Paket Pernikahan</h3>
                    <p class="text-lg text-temanhalal-secondary max-w-2xl mx-auto" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">Kami menyediakan paket pernikahan yang dirancang untuk berbagai kebutuhan—dari acara intimate sampai perayaan besar dengan detail premium.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-10" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    @foreach($packages as $package)
                    <div class="bg-white rounded-2xl p-8 text-center package-card relative {{ $package['is_featured'] ? 'ring-4 ring-temanhalal-gold shadow-xl' : 'shadow-lg' }} transition-all duration-300 hover:-translate-y-2"
                         data-aos="fade-up"
                         data-aos-duration="600"
                         data-aos-delay="{{ $loop->index * 100 + 300 }}">
                        <!-- Elegant corner decorations -->
                        <div class="absolute top-4 left-4 w-8 h-8 border-l-2 border-t-2 border-temanhalal-gold rounded-tl-lg"></div>
                        <div class="absolute top-4 right-4 w-8 h-8 border-r-2 border-t-2 border-temanhalal-gold rounded-tr-lg"></div>
                        <div class="absolute bottom-4 left-4 w-8 h-8 border-l-2 border-b-2 border-temanhalal-gold rounded-bl-lg"></div>
                        <div class="absolute bottom-4 right-4 w-8 h-8 border-r-2 border-b-2 border-temanhalal-gold rounded-br-lg"></div>

                        @if($package['is_featured'])
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-temanhalal-gold text-white px-8 py-2 rounded-full text-sm font-bold flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            Paket Signature
                        </div>
                        @endif

                        <div class="pt-6 pb-4">
                            <h4 class="text-2xl font-bold text-temanhalal-secondary mb-6 font-heading">{{ $package['name'] }}</h4>

                            <div class="flex justify-center mb-6">
                                <div class="text-temanhalal-gold text-4xl font-bold">Rp {{ number_format($package['price'], 0, ',', '.') }}</div>
                            </div>

                            <ul class="text-temanhalal-secondary space-y-4 mb-10">
                                @foreach($package['features'] as $feature)
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-temanhalal-gold mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </li>
                                @endforeach
                            </ul>

                            <div class="flex items-center justify-center mb-6">
                                <div class="h-px bg-gray-200 flex-grow"></div>
                                <div class="mx-4 text-temanhalal-gold">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="h-px bg-gray-200 flex-grow"></div>
                            </div>

                            <p class="text-sm text-gray-500 italic">Termasuk konsultasi dengan tim kurasi resmi dari Teman Menuju Halal</p>
                        </div>

                        <button class="w-full bg-temanhalal-secondary text-white py-4 rounded-xl font-semibold text-lg hover:bg-temanhalal-gold hover:text-temanhalal-secondary transition-colors shadow-lg shadow-temanhalal-secondary/20">
                            Lihat Detail Paket
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-temanhalal-secondary to-[#2A3458] relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <div class="absolute top-20 left-10 w-64 h-64 bg-temanhalal-gold rounded-full mix-blend-soft-light"></div>
                <div class="absolute bottom-10 right-10 w-48 h-48 bg-temanhalal-accent rounded-full mix-blend-soft-light"></div>
            </div>

            <div class="container mx-auto px-6 text-center relative z-10">
                <div class="max-w-4xl mx-auto">
                    <h3 class="text-3xl md:text-4xl font-bold text-white mb-6 font-heading">Siap Wujudkan Pernikahan Impian Anda?</h3>
                    <p class="text-lg text-temanhalal-accent mb-8">
                        Daftar sekarang dan dapatkan akses ke vendor rekanan resmi berizin dan berkelas dari instansi.
                    </p>
                    <button @click="isModalOpen = true" class="bg-gradient-to-r from-temanhalal-gold to-amber-500 text-temanhalal-secondary px-10 py-4 rounded-full font-semibold text-lg hover:from-amber-500 hover:to-temanhalal-gold transition-all duration-300 transform hover:scale-105 shadow-lg shadow-temanhalal-gold/30">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Daftar Sekarang
                        </span>
                    </button>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-temanhalal-secondary to-[#2A3458] text-temanhalal-primary py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Kolom 1 - Brand Identity -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-temanhalal-gold text-temanhalal-secondary w-10 h-10 rounded-full flex items-center justify-center font-heading text-base font-bold shadow-lg">
                            TMH
                        </div>
                        <span class="font-heading text-lg font-bold text-white">Teman Menuju Halal</span>
                    </div>
                    <p class="text-temanhalal-accent/80 text-sm mb-6">
                        Elevating your wedding journey with curated experiences.
                    </p>
                </div>

                <!-- Kolom 2 - Navigasi Elegan -->
                <div>
                    <h4 class="font-heading font-bold text-lg mb-4 text-white">Navigasi</h4>
                    <ul class="space-y-2">
                        <li><a href="#about" class="text-temanhalal-accent/80 hover:text-temanhalal-gold transition-colors text-sm block">Tentang Kami</a></li>
                        <li><a href="#vendors" class="text-temanhalal-accent/80 hover:text-temanhalal-gold transition-colors text-sm block">Vendor Rekanan</a></li>
                        <li><a href="#portfolio" class="text-temanhalal-accent/80 hover:text-temanhalal-gold transition-colors text-sm block">Portfolio</a></li>
                        <li><a href="#packages" class="text-temanhalal-accent/80 hover:text-temanhalal-gold transition-colors text-sm block">Paket Layanan</a></li>
                        <li><a href="#" class="text-temanhalal-accent/80 hover:text-temanhalal-gold transition-colors text-sm block">Konsultasi</a></li>
                    </ul>
                </div>

                <!-- Kolom 3 - Informasi & Kontak -->
                <div>
                    <h4 class="font-heading font-bold text-lg mb-4 text-white">Kontak & Informasi</h4>
                    <div class="space-y-4">
                        <div>
                            <h5 class="text-sm font-semibold text-temanhalal-gold mb-2">Kantor & Studio:</h5>
                            <p class="text-temanhalal-accent/80 text-sm">Alamat lengkap</p>
                        </div>
                        <div>
                            <h5 class="text-sm font-semibold text-temanhalal-gold mb-2">Email:</h5>
                            <p class="text-temanhalal-accent/80 text-sm">hello@temanmenujuhalal.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-temanhalal-gold/20 text-center text-temanhalal-accent/80 text-sm">
                <p>&copy; 2025 Teman Menuju Halal. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Registration Modal -->
    <div
        id="registration-modal"
        class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 modal-overlay backdrop-blur-sm hidden"
    >
        <div
            class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden"
        >
            <div class="relative z-10 p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-temanhalal-secondary text-left font-heading">Mulai Perjalanan Menuju Halal Anda</h3>
                        <p class="text-gray-500 text-sm mt-1">Bergabung sekarang untuk menjelajahi vendor pilihan, paket eksklusif, dan layanan pernikahan dari Teman Menuju Halal.</p>
                    </div>
                    <button onclick="closeRegistrationModal()" class="text-gray-400 hover:text-temanhalal-secondary transition-colors p-1">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form>
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="text" placeholder="Nama Lengkap" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-temanhalal-gold bg-white text-temanhalal-secondary">
                            <div class="absolute left-3 top-3.5 text-temanhalal-gold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="email" placeholder="Email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-temanhalal-gold bg-white text-temanhalal-secondary">
                            <div class="absolute left-3 top-3.5 text-temanhalal-gold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="text" placeholder="Nomor WhatsApp" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-temanhalal-gold bg-white text-temanhalal-secondary">
                            <div class="absolute left-3 top-3.5 text-temanhalal-gold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="password" placeholder="Password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-temanhalal-gold bg-white text-temanhalal-secondary">
                            <div class="absolute left-3 top-3.5 text-temanhalal-gold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="mt-6 w-full bg-gradient-to-r from-temanhalal-secondary to-temanhalal-gold text-white py-3.5 rounded-xl font-semibold hover:from-temanhalal-gold hover:to-temanhalal-secondary transition-all duration-300 shadow-md shadow-temanhalal-secondary/30">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Buat Akun Saya
                        </span>
                    </button>

                    <div class="mt-6 pt-4 text-center">
                        <p class="text-gray-600 text-sm">
                            Setelah login → tampil onboarding pernikahan
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- AOS Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        // Initialize AOS after DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize AOS with configuration to prevent flickering
            AOS.init({
                duration: 600,
                easing: 'ease-out',
                once: false, // Changed to false so animations can repeat if needed
                offset: 80,
                disable: function() {
                    // Disable AOS on mobile devices if needed
                    return false;
                },
                // Prevent initial flickering
                startEvent: 'DOMContentLoaded',
                initClassName: 'aos-init',
                animatedClassName: 'aos-animate'
            });

            // Custom animations for specific elements if needed
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            // Horizontal vendor slider functionality
            const slider = document.getElementById('vendor-slider');
            const prevBtn = document.getElementById('prev-vendor');
            const nextBtn = document.getElementById('next-vendor');

            if (slider) {
                // Set scroll amount to the width of one card + margin
                const scrollAmount = 320; // width of card (80*4) + space-x-8 (32px)

                if (nextBtn) {
                    nextBtn.addEventListener('click', function() {
                        slider.scrollBy({
                            left: scrollAmount,
                            behavior: 'smooth'
                        });
                    });
                }

                if (prevBtn) {
                    prevBtn.addEventListener('click', function() {
                        slider.scrollBy({
                            left: -scrollAmount,
                            behavior: 'smooth'
                        });
                    });
                }
            }

            // Portfolio carousel functionality
            const portfolioTrack = document.getElementById('carousel-track');
            const prevPortfolioBtn = document.getElementById('prev-portfolio');
            const nextPortfolioBtn = document.getElementById('next-portfolio');
            const indicators = document.querySelectorAll('#carousel-indicators .indicator');
            let portfolioIndex = 0;
            const portfolioItems = document.querySelectorAll('.carousel-item');
            const totalPortfolioItems = portfolioItems.length;

            // Update carousel display
            function updatePortfolioCarousel() {
                portfolioTrack.style.transform = `translateX(-${portfolioIndex * (100/totalPortfolioItems)}%)`;

                // Update indicators
                indicators.forEach((indicator, index) => {
                    if (index === portfolioIndex) {
                        indicator.classList.add('bg-temanhalal-gold');
                        indicator.classList.remove('bg-gray-300');
                    } else {
                        indicator.classList.remove('bg-temanhalal-gold');
                        indicator.classList.add('bg-gray-300');
                    }
                });
            }

            // Next button event
            if (nextPortfolioBtn) {
                nextPortfolioBtn.addEventListener('click', function() {
                    portfolioIndex = (portfolioIndex + 1) % totalPortfolioItems;
                    updatePortfolioCarousel();
                });
            }

            // Previous button event
            if (prevPortfolioBtn) {
                prevPortfolioBtn.addEventListener('click', function() {
                    portfolioIndex = (portfolioIndex - 1 + totalPortfolioItems) % totalPortfolioItems;
                    updatePortfolioCarousel();
                });
            }

            // Indicator click events
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function() {
                    portfolioIndex = index;
                    updatePortfolioCarousel();
                });
            });

            // Initialize carousel
            if (totalPortfolioItems > 0) {
                updatePortfolioCarousel();
            }

            // Ken Burns effect for images
            const kenBurnsImages = document.querySelectorAll('.ken-burns-effect');
            kenBurnsImages.forEach(img => {
                img.style.transition = 'transform 15s ease-in-out';

                // Apply subtle zoom effect
                function applyKenBurns() {
                    img.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        img.style.transform = 'scale(1)';
                    }, 15000);
                }

                // Apply effect periodically
                setInterval(applyKenBurns, 15000);
                applyKenBurns(); // Apply immediately on load
            });

            // Scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Elements to animate on scroll
            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            animatedElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });

            // Pulse animation for CTA buttons
            const ctaButtons = document.querySelectorAll('.animate-pulse');
            ctaButtons.forEach(button => {
                function startPulse() {
                    button.style.transform = 'scale(1)';
                    button.style.transition = 'transform 0.5s ease';

                    setTimeout(() => {
                        button.style.transform = 'scale(1.05)';
                    }, 1000);

                    setTimeout(() => {
                        button.style.transform = 'scale(1)';
                    }, 2000);
                }

                startPulse();
                setInterval(startPulse, 2000);
            });

            // Parallax effect for hero background
            window.addEventListener('scroll', function() {
                const scrollPosition = window.scrollY;
                const heroElement = document.querySelector('#hero');
                if (heroElement) {
                    heroElement.style.backgroundPosition = `center ${scrollPosition * 0.5}px`;
                }
            });

            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        // Add active class for animation effect
                        document.querySelectorAll('a[href^="#"]').forEach(link => {
                            link.classList.remove('text-temanhalal-gold');
                            link.classList.add('text-temanhalal-secondary');
                        });
                        this.classList.remove('text-temanhalal-secondary');
                        this.classList.add('text-temanhalal-gold');

                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Add section transition effects
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('nav a');

            window.addEventListener('scroll', function() {
                let current = '';

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;

                    if (pageYOffset >= (sectionTop - sectionHeight / 3)) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('text-temanhalal-gold');
                    link.classList.add('text-temanhalal-secondary');

                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.remove('text-temanhalal-secondary');
                        link.classList.add('text-temanhalal-gold');
                    }
                });

                // Add subtle effect to current section
                sections.forEach(section => {
                    if (section.getAttribute('id') === current) {
                        section.classList.add('section-fade-in');
                        section.classList.remove('section-fade-out');
                    } else {
                        section.classList.remove('section-fade-in');
                        section.classList.add('section-fade-out');
                    }
                });
            });
        });

        // Function to show vendor modal with details
        function showVendorModal(name, category, image, rating, reviews) {
            document.getElementById('vendor-modal-title').textContent = name;
            document.getElementById('vendor-modal-category').textContent = category;
            document.getElementById('vendor-modal-image').src = image;
            document.getElementById('vendor-modal-rating').textContent = rating;
            document.getElementById('vendor-modal-reviews').textContent = '(' + reviews + ' ulasan)';
            document.getElementById('vendor-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Function to close vendor modal
        function closeVendorModal() {
            document.getElementById('vendor-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the content
        document.getElementById('vendor-modal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeVendorModal();
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 40) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
        });

        // Add smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if(targetId === '#') return;

                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    // Add active class for animation effect
                    document.querySelectorAll('a[href^="#"]').forEach(link => {
                        link.classList.remove('text-temanhalal-gold');
                        link.classList.add('text-temanhalal-secondary');
                    });
                    this.classList.remove('text-temanhalal-secondary');
                    this.classList.add('text-temanhalal-gold');

                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Registration modal functions
        function openRegistrationModal() {
            document.getElementById('registration-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRegistrationModal() {
            document.getElementById('registration-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the content
        document.getElementById('registration-modal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeRegistrationModal();
            }
        });

        // Update the button to use the new function
        document.querySelector('button[onclick]').setAttribute('onclick', 'openRegistrationModal()');
    </script>

    <!-- Vendor Detail Modal -->
    <div id="vendor-modal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 modal-overlay backdrop-blur-sm hidden">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-1 bg-gradient-to-r from-temanhalal-secondary via-temanhalal-gold to-temanhalal-secondary"></div>
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 id="vendor-modal-title" class="text-2xl font-bold text-temanhalal-secondary text-left font-heading"></h3>
                        <p id="vendor-modal-category" class="text-temanhalal-gold font-medium"></p>
                    </div>
                    <button onclick="closeVendorModal()" class="text-gray-400 hover:text-temanhalal-secondary transition-colors p-1">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <img id="vendor-modal-image" src="" alt="" class="w-full h-64 object-cover rounded-2xl">
                    </div>
                    <div>
                        <div class="flex items-center mb-4">
                            <span class="text-temanhalal-gold font-bold text-xl mr-2" id="vendor-modal-rating"></span>
                            <svg class="w-5 h-5 text-temanhalal-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"></path></svg>
                            <span class="text-gray-500 text-sm ml-1" id="vendor-modal-reviews"></span>
                        </div>

                        <h4 class="font-bold text-temanhalal-secondary mb-3 font-heading">Tentang Vendor</h4>
                        <p class="text-temanhalal-secondary mb-4">
                            Ini adalah deskripsi vendor yang akan menampilkan informasi lengkap tentang layanan, pengalaman,
                            dan keunggulan yang ditawarkan oleh vendor ini.
                        </p>

                        <h4 class="font-bold text-temanhalal-secondary mb-3 font-heading">Layanan yang Ditawarkan</h4>
                        <ul class="text-temanhalal-secondary space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-temanhalal-gold mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Pelayanan profesional dengan standar kualitas tinggi</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-temanhalal-gold mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Tim berpengalaman di bidangnya</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-temanhalal-gold mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Kualitas terjamin dan terpercaya</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="font-bold text-temanhalal-secondary mb-4 font-heading">Portfolio Karya</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="aspect-square bg-temanhalal-primary rounded-xl overflow-hidden">
                            <img src="https://placehold.co/300x300/F7EDE2/1C2440?text=Portfolio+1" alt="Portfolio 1" class="w-full h-full object-cover">
                        </div>
                        <div class="aspect-square bg-temanhalal-primary rounded-xl overflow-hidden">
                            <img src="https://placehold.co/300x300/FFCDB2/1C2440?text=Portfolio+2" alt="Portfolio 2" class="w-full h-full object-cover">
                        </div>
                        <div class="aspect-square bg-temanhalal-primary rounded-xl overflow-hidden">
                            <img src="https://placehold.co/300x300/D4AF37/FFFFFF?text=Portfolio+3" alt="Portfolio 3" class="w-full h-full object-cover">
                        </div>
                        <div class="aspect-square bg-temanhalal-primary rounded-xl overflow-hidden">
                            <img src="https://placehold.co/300x300/F7EDE2/1C2440?text=Portfolio+4" alt="Portfolio 4" class="w-full h-full object-cover">
                        </div>
                        <div class="aspect-square bg-temanhalal-primary rounded-xl overflow-hidden">
                            <img src="https://placehold.co/300x300/FFCDB2/1C2440?text=Portfolio+5" alt="Portfolio 5" class="w-full h-full object-cover">
                        </div>
                        <div class="aspect-square bg-temanhalal-primary rounded-xl overflow-hidden">
                            <img src="https://placehold.co/300x300/D4AF37/FFFFFF?text=Portfolio+6" alt="Portfolio 6" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="font-bold text-temanhalal-secondary mb-4 font-heading">Testimoni Klien</h4>
                    <div class="space-y-4">
                        <div class="bg-temanhalal-primary p-4 rounded-xl">
                            <p class="text-temanhalal-secondary italic">"Luar biasa! Vendor ini benar-benar membantu mewujudkan pernikahan impian kami dengan sangat indah dan profesional."</p>
                            <p class="text-temanhalal-gold mt-2 font-medium">- Bapak dan Ibu Santoso</p>
                        </div>
                        <div class="bg-temanhalal-primary p-4 rounded-xl">
                            <p class="text-temanhalal-secondary italic">"Pelayanan sangat memuaskan, detail pekerjaan sangat bagus, dan harga sesuai dengan kualitas yang diberikan."</p>
                            <p class="text-temanhalal-gold mt-2 font-medium">- Bapak dan Ibu Prasetyo</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <button class="bg-gradient-to-r from-temanhalal-secondary to-temanhalal-gold text-white px-8 py-3 rounded-full font-semibold hover:from-temanhalal-gold hover:to-temanhalal-secondary transition-all duration-300">
                        Hubungi Vendor
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>