<x-guest-layout>
    <div class="min-h-screen flex">
        <!-- Left Side - Visual & Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#9CAF88] overflow-hidden">
            <!-- Background Image -->
            <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2070&auto=format&fit=crop" 
                 alt="Wedding Decoration" 
                 class="absolute inset-0 w-full h-full object-cover">
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#9CAF88]/90 to-[#8B8680]/80 mix-blend-multiply"></div>
            
            <!-- Content -->
            <div class="relative z-10 w-full flex flex-col justify-between p-12 text-white">
                <div>
                    <!-- Logo Placeholder -->
                    <div class="flex items-center gap-2 mb-8">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold tracking-wide">{{ $companySettings->company_name ?? 'TemanMenujuHalal' }}</span>
                    </div>
                </div>

                <div class="space-y-6">
                    <h1 class="text-5xl font-bold leading-tight">
                        Bergabung Bersama <br>
                        <span class="text-[#F4A6A0]">Komunitas</span> Kami
                    </h1>
                    <p class="text-lg text-gray-100 max-w-md leading-relaxed">
                        Daftarkan diri Anda sekarang untuk mulai merencanakan event impian atau menawarkan layanan terbaik Anda.
                    </p>
                </div>

                <div class="flex items-center gap-4 text-sm text-gray-200">
                    <span>© {{ date('Y') }} {{ $companySettings->company_name ?? 'TemanMenujuHalal' }}</span>
                    <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                    <span>All Rights Reserved</span>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 sm:p-12 lg:p-24 overflow-y-auto">
            <div class="w-full max-w-md space-y-8">
                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="lg:hidden text-center mb-8">
                    <h2 class="text-2xl font-bold text-[#8B8680]">{{ $companySettings->company_name ?? 'TemanMenujuHalal' }}</h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Buat Akun Baru
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Lengkapi data diri Anda untuk mendaftar
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
                    @csrf
                    
                    @if(isset($returnUrl) || request()->has('return_url') || old('return_url'))
                        <input type="hidden" name="return_url" value="{{ $returnUrl ?? request('return_url') ?? old('return_url') }}">
                    @endif

                    <!-- Name -->
                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700 font-medium" />
                        <x-text-input id="name"
                                    class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-[#9CAF88] focus:border-[#9CAF88] transition-colors"
                                    type="text"
                                    name="name"
                                    :value="old('name')"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Masukkan nama lengkap" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Username -->
                    <div class="space-y-2">
                        <x-input-label for="username" :value="__('Username')" class="text-gray-700 font-medium" />
                        <x-text-input id="username"
                                    class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-[#9CAF88] focus:border-[#9CAF88] transition-colors"
                                    type="text"
                                    name="username"
                                    :value="old('username')"
                                    required
                                    autocomplete="username"
                                    placeholder="Pilih username unik" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                        <x-text-input id="email"
                                    class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-[#9CAF88] focus:border-[#9CAF88] transition-colors"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
                                    autocomplete="username"
                                    placeholder="contoh@email.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                        <x-text-input id="password"
                                    class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-[#9CAF88] focus:border-[#9CAF88] transition-colors"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Minimal 8 karakter" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700 font-medium" />
                        <x-text-input id="password_confirmation"
                                    class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-[#9CAF88] focus:border-[#9CAF88] transition-colors"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Ulangi password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#9CAF88] hover:bg-[#8a9e7a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9CAF88] transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                        {{ __('Daftar Sekarang') }}
                    </button>

                    <!-- Login Link -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="font-semibold text-[#9CAF88] hover:text-[#8a9e7a] transition-colors">
                                Masuk disini
                            </a>
                        </p>
                    </div>
                </form>

                <!-- Back to Home -->
                <div class="pt-6 border-t border-gray-100 text-center">
                    <a href="{{ route('landing.page') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#9CAF88] transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
