<x-guest-layout>
    <div class="min-h-screen flex">
        <!-- Left Side - Visual & Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#9CAF88] overflow-hidden">
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
                        <span class="text-2xl font-bold tracking-wide">PT. Lorem Ipsum</span>
                    </div>
                </div>

                <div class="space-y-6">
                    <h1 class="text-5xl font-bold leading-tight">
                        Wujudkan Momen <br>
                        <span class="text-[#F4A6A0]">Istimewa</span> Anda
                    </h1>
                    <p class="text-lg text-gray-100 max-w-md leading-relaxed">
                        Bergabunglah bersama kami untuk merencanakan dan mengelola event impian dengan mudah dan profesional.
                    </p>
                </div>

                <div class="flex items-center gap-4 text-sm text-gray-200">
                    <span>© {{ date('Y') }} PT. Lorem Ipsum</span>
                    <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                    <span>All Rights Reserved</span>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 sm:p-12 lg:p-24">
            <div class="w-full max-w-md space-y-8">
                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="lg:hidden text-center mb-8">
                    <h2 class="text-2xl font-bold text-[#8B8680]">PT. Renjana Sanubari</h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Selamat Datang Kembali
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Silakan masuk ke akun Anda untuk melanjutkan
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf

                    <!-- Email or Username -->
                    <div class="space-y-2">
                        <x-input-label for="identity" :value="__('Email atau Username')" class="text-gray-700 font-medium" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <x-text-input id="identity"
                                        class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-xl focus:ring-[#9CAF88] focus:border-[#9CAF88] transition-colors"
                                        type="text"
                                        name="identity"
                                        :value="old('identity')"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        placeholder="Masukkan email atau username" />
                        </div>
                        <x-input-error :messages="$errors->get('identity')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#9CAF88] hover:text-[#8a9e7a] transition-colors">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <x-text-input id="password"
                                        class="block w-full pl-10 pr-10 py-3 border-gray-300 rounded-xl focus:ring-[#9CAF88] focus:border-[#9CAF88] transition-colors"
                                        x-bind:type="show ? 'text' : 'password'"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Masukkan password" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 .847 0 1.67.127 2.454.364m-6.024 4.458A3 3 0 119.976 12c0 .303.044.594.126.875m-2.992 2.992a10.023 10.023 0 01-4.24-4.24M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="h-4 w-4 text-[#9CAF88] focus:ring-[#9CAF88] border-gray-300 rounded transition" name="remember">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-600">
                            {{ __('Ingat Saya') }}
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#9CAF88] hover:bg-[#8a9e7a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9CAF88] transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                        {{ __('Masuk Sekarang') }}
                    </button>

                    <!-- Register Link -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="font-semibold text-[#9CAF88] hover:text-[#8a9e7a] transition-colors">
                                Daftar disini
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
