<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profil Vendor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Vendor Profile Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row">
                        <!-- Vendor Photo -->
                        <div class="md:w-1/3 mb-6 md:mb-0 md:pr-6">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-64 flex items-center justify-center text-gray-500">
                                Foto Vendor
                            </div>
                        </div>
                        
                        <!-- Vendor Info -->
                        <div class="md:w-2/3">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $vendor->user ? $vendor->user->name : $vendor->contact_person }}</h1>
                            
                            <!-- Rating Section -->
                            <div class="flex items-center mb-4">
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($averageRating))
                                            <svg class="w-5 h-5 text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                            </svg>
                                        @elseif ($i - 0.5 <= $averageRating)
                                            <svg class="w-5 h-5 text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                            </svg>
                                            <svg class="w-5 h-5 text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 22 20">
                                                <path stroke="currentColor" stroke-width="1.5" d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-gray-600 dark:text-gray-300">
                                    {{ number_format($averageRating, 1) }} dari 5.0 
                                    ({{ $totalReviews }} ulasan)
                                </span>
                            </div>
                            
                            <!-- Vendor Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Detail Vendor</h3>
                                    <div class="space-y-2">
                                        <p><strong>Kategori:</strong> {{ $vendor->category }}</p>
                                        <p><strong>Nama Kontak:</strong> {{ $vendor->contact_person }}</p>
                                        <p><strong>No. Telepon:</strong> {{ $vendor->phone_number }}</p>
                                        <p><strong>Alamat:</strong> {{ $vendor->address ?: '-' }}</p>
                                        <p><strong>Jenis Layanan:</strong> {{ $vendor->serviceType ? $vendor->serviceType->name : '-' }}</p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Deskripsi Vendor</h3>
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ $vendor->serviceType && $vendor->serviceType->description ? $vendor->serviceType->description : 'Deskripsi layanan vendor akan muncul di sini. Vendor ini menyediakan layanan berkualitas tinggi sesuai kebutuhan acara Anda.' }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex space-x-4">
                                <x-primary-button tag="a" :href="route('vendors.edit', $vendor->id)">
                                    Edit Profil
                                </x-primary-button>
                                <x-secondary-button tag="a" :href="route('vendors.index')">
                                    Kembali ke Daftar
                                </x-secondary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Services Available Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Detail Layanan Tersedia</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Layanan Dasar</h3>
                            <ul class="list-disc pl-5 space-y-1 text-gray-700 dark:text-gray-300">
                                <li>Penyediaan peralatan dasar</li>
                                <li>Pendampingan teknis</li>
                                <li>Setup awal</li>
                                <li>Dukungan selama acara</li>
                            </ul>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Layanan Premium</h3>
                            <ul class="list-disc pl-5 space-y-1 text-gray-700 dark:text-gray-300">
                                <li>Konsultasi pra-acara</li>
                                <li>Personalisasi layanan</li>
                                <li>Laporan pasca-acara</li>
                                <li>Layanan pelanggan 24/7</li>
                            </ul>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Layanan Tambahan</h3>
                            <ul class="list-disc pl-5 space-y-1 text-gray-700 dark:text-gray-300">
                                <li>Fotografi dokumentasi</li>
                                <li>Videografi</li>
                                <li>Layanan catering</li>
                                <li>Dekorasi khusus</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reviews Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Penilaian dan Ulasan</h2>
                    
                    <div class="space-y-6">
                        @foreach($vendorRatings as $review)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $review['user'] }}</h4>
                                    <div class="flex items-center mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review['rating'])
                                                <svg class="w-4 h-4 text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.533 0 0 0 .387-1.575Z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($review['date'])->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 text-gray-700 dark:text-gray-300">{{ $review['comment'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>