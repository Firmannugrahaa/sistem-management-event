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
</head>

<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center">
            @php
                $companySetting = \App\Models\CompanySetting::first();
            @endphp
            @if($companySetting && $companySetting->company_logo_path)
                <a href="/" class="flex items-center">
                    <img src="{{ asset('storage/' . $companySetting->company_logo_path) }}"
                         alt="{{ $companySetting->company_name ?? 'EventScape' }}"
                         class="h-8 w-auto object-contain mr-2">
                    <span class="text-xl font-bold text-primary">{{ $companySetting->company_name ?? 'EventScape' }}</span>
                </a>
            @else
                <a href="/" class="text-xl font-bold text-primary">{{ $companySetting->company_name ?? 'EventScape' }}</a>
            @endif
        </div>
        <div class="hidden md:flex space-x-8">
            <a href="#portfolio" class="text-gray-600 hover:text-primary font-medium transition">Portfolio</a>
            <a href="#vendors" class="text-gray-600 hover:text-primary font-medium transition">Vendors</a>
            <a href="#services" class="text-gray-600 hover:text-primary font-medium transition">Services</a>
        </div>
        <div class="flex items-center space-x-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 font-medium hover:text-primary transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 font-medium hover:text-primary transition">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 bg-primary text-white px-5 py-2 rounded-lg font-medium hover:bg-blue-800 transition shadow-md">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 py-12 mt-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold text-primary mb-4">{{ $companySetting->company_name ?? 'EventScape' }}</h3>
                    <p class="text-gray-600">Mewujudkan acara impian Anda dengan layanan profesional dan terpercaya.</p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#portfolio" class="text-gray-600 hover:text-primary transition">Portfolio</a></li>
                        <li><a href="#vendors" class="text-gray-600 hover:text-primary transition">Vendor</a></li>
                        <li><a href="#services" class="text-gray-600 hover:text-primary transition">Layanan</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-primary transition">Tentang Kami</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-600">
                        <li>Email: {{ $companySetting->company_email ?? 'info@eventscape.com' }}</li>
                        <li>Telepon: {{ $companySetting->company_phone ?? '(021) 12345678' }}</li>
                        <li>Alamat: {{ $companySetting->company_address ?? 'Jl. Event No. 123, Jakarta' }}</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} {{ $companySetting->company_name ?? 'EventScape' }}. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>