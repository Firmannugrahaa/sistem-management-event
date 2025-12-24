<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Font Awesome for icons in modal -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <!-- Navigation -->
    <nav class="bg-white shadow-md py-4 px-6 sticky top-0 z-50 border-b border-gray-100">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="/" class="flex items-center gap-3">
                    @if(isset($companySettings) && !empty($companySettings->company_logo_path))
                        <img src="{{ asset($companySettings->company_logo_path) }}" alt="{{ $companySettings->company_name ?? 'Logo' }}" class="w-12 h-12 object-contain">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-[#9CAF88] to-[#8a9e7a] rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">RS</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-xl font-bold text-[#8B8680]">{{ $companySettings->company_name ?? 'TemanMenujuHalal' }}</span>
                        <p class="text-xs text-gray-500">Professional Event Organizer</p>
                    </div>
                </a>
            </div>
            <div class="hidden md:flex space-x-8">
                <a href="#about" class="text-gray-600 hover:text-[#9CAF88] font-medium transition">Tentang</a>
                <a href="#portfolio" class="text-gray-600 hover:text-[#9CAF88] font-medium transition">Portfolio</a>
                <a href="#gallery" class="text-gray-600 hover:text-[#9CAF88] font-medium transition">Galeri</a>
                <a href="#venues" class="text-gray-600 hover:text-[#9CAF88] font-medium transition">Venue</a>
                <a href="#vendors" class="text-gray-600 hover:text-[#9CAF88] font-medium transition">Vendor</a>
                <a href="#faq" class="text-gray-600 hover:text-[#9CAF88] font-medium transition">FAQ</a>
                <a href="#contact" class="text-gray-600 hover:text-[#9CAF88] font-medium transition">Kontak</a>
            </div>
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        @if(auth()->user()->hasRole('User'))
                            <a href="{{ route('client.dashboard') }}" class="text-sm text-gray-700 font-medium hover:text-[#9CAF88] transition">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   class="text-sm text-gray-700 font-medium hover:text-[#9CAF88] transition"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </a>
                            </form>
                        @else
                            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 font-medium hover:text-[#9CAF88] transition">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   class="text-sm text-gray-700 font-medium hover:text-[#9CAF88] transition"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </a>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 font-medium hover:text-[#9CAF88] transition">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 bg-[#9CAF88] text-white px-5 py-2 rounded-lg font-medium hover:bg-[#8a9e7a] transition shadow-md">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#8B8680] text-white py-12 mt-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        @if(isset($companySettings) && !empty($companySettings->company_logo_path))
                            <img src="{{ asset($companySettings->company_logo_path) }}" alt="{{ $companySettings->company_name ?? 'Logo' }}" class="w-12 h-12 object-contain bg-white rounded-lg p-1">
                        @else
                            <div class="w-12 h-12 bg-[#9CAF88] rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-xl">RS</span>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold">{{ $companySettings->company_name ?? 'TemanMenujuHalal' }}</h3>
                            <p class="text-sm text-gray-300">Professional Event Organizer</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Mewujudkan acara impian Anda dengan layanan profesional dan terpercaya. Spesialis dalam lamaran, pernikahan, ulang tahun, dan acara korporat.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-white/10 hover:bg-[#9CAF88] rounded-full flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 hover:bg-[#9CAF88] rounded-full flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#about" class="text-gray-300 hover:text-[#9CAF88] transition">Tentang Kami</a></li>
                        <li><a href="#portfolio" class="text-gray-300 hover:text-[#9CAF88] transition">Portfolio</a></li>
                        <li><a href="#gallery" class="text-gray-300 hover:text-[#9CAF88] transition">Galeri</a></li>
                        <li><a href="#venues" class="text-gray-300 hover:text-[#9CAF88] transition">Venue</a></li>
                        <li><a href="#vendors" class="text-gray-300 hover:text-[#9CAF88] transition">Vendor</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak Kami</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $companySettings->company_email ?? 'info@temanmenujuhalal.com' }}
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $companySettings->company_phone ?? '+62 812-3456-7890' }}
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            @if(!empty($companySettings->company_address))
                                {{ $companySettings->company_address }}
                            @else
                                Jl. Melati Indah No. 45<br>Jakarta Selatan 12150
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-white/20 text-center text-gray-300 text-sm">
                &copy; {{ date('Y') }} {{ $companySettings->company_name ?? 'TemanMenujuHalal' }}. All rights reserved. | Designed with <span class="text-[#F4A6A0]">♥</span> for memorable events.
            </div>
        </div>
    </footer>
    <x-loading />
    
    @stack('scripts')
</body>
</html>