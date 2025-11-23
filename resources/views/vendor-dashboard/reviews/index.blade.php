<x-vendor-dashboard-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Ulasan & Rating</h2>
        <p class="text-gray-600 dark:text-gray-400">Penilaian dan komentar dari klien yang menggunakan layanan Anda</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Overall Rating Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow">
            <div class="text-3xl font-bold">4.8</div>
            <div class="text-sm opacity-80">Rating Rata-rata</div>
            <div class="mt-2 text-xs opacity-70">dari 124 ulasan</div>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow">
            <div class="text-3xl font-bold">124</div>
            <div class="text-sm opacity-80">Total Ulasan</div>
            <div class="mt-2 text-xs opacity-70">+12 bulan ini</div>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow">
            <div class="text-3xl font-bold">98%</div>
            <div class="text-sm opacity-80">Kepuasan Klien</div>
            <div class="mt-2 text-xs opacity-70">sangat puas</div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Distribusi Rating</h3>
        <div class="space-y-3">
            @for($i = 5; $i >= 1; $i--)
                <div class="flex items-center">
                    <div class="w-10 text-sm font-medium text-gray-900 dark:text-white">{{ $i }} bintang</div>
                    <div class="flex-1 ml-3">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-yellow-400 h-2.5 rounded-full" style="width: {{ $i == 5 ? '65%' : ($i == 4 ? '20%' : ($i == 3 ? '10%' : ($i == 2 ? '3%' : '2%'))) }}"></div>
                        </div>
                    </div>
                    <div class="w-12 text-right text-sm text-gray-500 dark:text-gray-400">
                        {{ $i == 5 ? '81' : ($i == 4 ? '25' : ($i == 3 ? '12' : ($i == 2 ? '4' : '2'))) }}
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ulasan Terbaru</h3>
            <button class="text-sm text-primary hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                Lihat Semua →
            </button>
        </div>

        <div class="space-y-6">
            @for($i = 0; $i < 3; $i++)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Budi Santoso</h4>
                            <div class="flex items-center mt-1">
                                @for($star = 1; $star <= 5; $star++)
                                    <svg class="w-4 h-4 {{ $star <= ($i == 0 ? 5 : ($i == 1 ? 4 : 5)) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::now()->subDays($i * 3 + 1)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                            Wedding
                        </span>
                    </div>
                    <p class="mt-3 text-gray-700 dark:text-gray-300">
                        {{ $i == 0 ? 'Layanan sangat memuaskan! Vendor profesional, ramah, dan memberikan hasil yang luar biasa untuk pernikahan kami.' : 
                           ($i == 1 ? 'Pelayanan bagus, komunikasi lancar, dan hasil akhir memuaskan. Akan menggunakan lagi di acara mendatang.' : 
                           'Pekerjaan yang luar biasa! Sangat direkomendasikan untuk pasangan yang mencari vendor berkualitas tinggi.') }}
                    </p>
                </div>
            @endfor
        </div>
    </div>
</x-vendor-dashboard-layout>