<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-[#1A1A1A] leading-tight">
                    Dashboard Vendor
                </h2>
                <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ $vendor->brand_name ?? auth()->user()->name }}</p>
            </div>
            @if($vendor->serviceType)
                <span class="px-3 py-1 bg-[#27AE60]/10 text-[#27AE60] text-sm font-medium rounded-full">
                    {{ $vendor->serviceType->name }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Onboarding Section (for new vendors) --}}
            @if($isNewVendor)
            <div class="bg-gradient-to-r from-[#012A4A] to-[#013d70] rounded-2xl p-6 mb-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold">🚀 Lengkapi Profil Bisnis Anda</h3>
                        <p class="text-white/70 text-sm">Selesaikan langkah-langkah berikut agar bisnis Anda siap menerima pesanan</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ round($onboardingProgress) }}%</div>
                        <div class="text-xs text-white/70">Selesai</div>
                    </div>
                </div>
                
                {{-- Progress Bar --}}
                <div class="w-full bg-white/20 rounded-full h-2 mb-4">
                    <div class="bg-[#27AE60] h-2 rounded-full transition-all duration-500" style="width: {{ $onboardingProgress }}%"></div>
                </div>
                
                {{-- Onboarding Steps --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    @foreach($onboardingSteps as $step)
                    <a href="{{ $step['route'] }}" 
                       class="flex flex-col items-center p-3 rounded-xl transition {{ $step['completed'] ? 'bg-white/10' : 'bg-white/5 hover:bg-white/15' }}">
                        <span class="text-2xl mb-1">{{ $step['completed'] ? '✅' : $step['icon'] }}</span>
                        <span class="text-xs text-center {{ $step['completed'] ? 'text-white/50 line-through' : 'text-white' }}">
                            {{ $step['title'] }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                @foreach($stats as $key => $stat)
                <a href="{{ $stat['route'] }}" class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition group">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-3xl">{{ $stat['icon'] }}</span>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-[#27AE60] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-[#1A1A1A]">{{ $stat['total'] }}</div>
                    <div class="text-sm text-gray-500">{{ $stat['label'] }}</div>
                    <div class="mt-2 text-xs">
                        @if($key === 'events')
                            <span class="text-[#27AE60]">{{ $stat['upcoming'] }} upcoming</span>
                        @else
                            <span class="text-[#27AE60]">{{ $stat['active'] }} aktif</span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm mb-6">
                <h3 class="text-lg font-semibold text-[#1A1A1A] mb-4">⚡ Aksi Cepat</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('vendor.products.create') }}" 
                       class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-[#27AE60]/10 hover:border-[#27AE60] border-2 border-transparent transition group">
                        <span class="w-10 h-10 bg-[#27AE60]/10 rounded-lg flex items-center justify-center text-xl group-hover:bg-[#27AE60] group-hover:text-white transition">
                            ➕
                        </span>
                        <div>
                            <div class="font-medium text-[#1A1A1A]">Tambah Layanan</div>
                            <div class="text-xs text-gray-500">Buat layanan baru</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('vendor.catalog.items.create') }}" 
                       class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-[#27AE60]/10 hover:border-[#27AE60] border-2 border-transparent transition group">
                        <span class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-xl group-hover:bg-blue-500 group-hover:text-white transition">
                            📦
                        </span>
                        <div>
                            <div class="font-medium text-[#1A1A1A]">Tambah Produk</div>
                            <div class="text-xs text-gray-500">Ke katalog Anda</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('vendor.packages.create') }}" 
                       class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-[#27AE60]/10 hover:border-[#27AE60] border-2 border-transparent transition group">
                        <span class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-xl group-hover:bg-purple-500 group-hover:text-white transition">
                            🎁
                        </span>
                        <div>
                            <div class="font-medium text-[#1A1A1A]">Buat Paket</div>
                            <div class="text-xs text-gray-500">Bundel layanan</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('vendor.portfolios.create') }}" 
                       class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-[#27AE60]/10 hover:border-[#27AE60] border-2 border-transparent transition group">
                        <span class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-xl group-hover:bg-amber-500 group-hover:text-white transition">
                            🖼️
                        </span>
                        <div>
                            <div class="font-medium text-[#1A1A1A]">Upload Portfolio</div>
                            <div class="text-xs text-gray-500">Tampilkan karya</div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Packages Section --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-[#1A1A1A]">📦 Paket Layanan Anda</h3>
                        <a href="{{ route('vendor.packages.index') }}" class="text-sm text-[#27AE60] hover:underline">Lihat Semua →</a>
                    </div>
                    
                    @if($packages->count() > 0)
                    <div class="space-y-3">
                        @foreach($packages as $package)
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-[#27AE60] transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-[#1A1A1A]">{{ $package->name }}</h4>
                                        @if($package->is_visible)
                                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Aktif</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">Nonaktif</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 mb-2">{{ Str::limit($package->description, 60) }}</p>
                                    
                                    {{-- Package Contents --}}
                                    <div class="flex flex-wrap gap-2 text-xs">
                                        @if($package->services->count() > 0)
                                            <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded">
                                                🛠️ {{ $package->services->count() }} Layanan
                                            </span>
                                        @endif
                                        @if($package->items->count() > 0)
                                            <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded">
                                                📦 {{ $package->items->count() }} Produk
                                            </span>
                                        @endif
                                        @if($isCatering && $package->items->count() > 0)
                                            @php
                                                $totalPax = $package->items->sum(fn($item) => $item->pivot->quantity ?? 0);
                                            @endphp
                                            @if($totalPax > 0)
                                            <span class="px-2 py-1 bg-amber-50 text-amber-600 rounded">
                                                🍽️ {{ $totalPax }} pax
                                            </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <div class="text-lg font-bold text-[#27AE60]">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                    <a href="{{ route('vendor.packages.edit', $package) }}" class="text-xs text-gray-500 hover:text-[#012A4A]">Edit →</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="text-4xl mb-3">📦</div>
                        <p class="text-gray-500 mb-4">Belum ada paket layanan.</p>
                        <a href="{{ route('vendor.packages.create') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-[#012A4A] text-white rounded-lg hover:bg-[#013d70] transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Buat Paket Pertama
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Right Sidebar --}}
                <div class="space-y-6">
                    {{-- Revenue Card --}}
                    <div class="bg-gradient-to-br from-[#27AE60] to-[#219653] rounded-2xl p-5 text-white">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl">💰</span>
                            <span class="text-sm opacity-80">Total Pendapatan</span>
                        </div>
                        <div class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        <div class="text-xs opacity-70 mt-1">Dari {{ $completedEvents }} event selesai</div>
                    </div>

                    {{-- Upcoming Events --}}
                    <div class="bg-white rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-[#1A1A1A]">📅 Event Mendatang</h3>
                            <a href="{{ route('vendor.events.index') }}" class="text-xs text-[#27AE60] hover:underline">Lihat →</a>
                        </div>
                        
                        @if($upcomingEvents->count() > 0)
                        <div class="space-y-3">
                            @foreach($upcomingEvents->take(3) as $event)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 bg-[#012A4A] rounded-lg flex flex-col items-center justify-center text-white text-xs">
                                    <span class="font-bold">{{ $event->start_time->format('d') }}</span>
                                    <span class="text-[10px] opacity-70">{{ $event->start_time->format('M') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm text-[#1A1A1A] truncate">{{ $event->event_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $event->venue->name ?? 'TBD' }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-6">
                            <div class="text-3xl mb-2">📭</div>
                            <p class="text-sm text-gray-500">Belum ada event yang ditugaskan</p>
                        </div>
                        @endif
                    </div>

                    {{-- Recent Activity --}}
                    <div class="bg-white rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-[#1A1A1A]">
                                🔔 Notifikasi
                                @if($unreadNotificationsCount > 0)
                                    <span class="ml-1 px-2 py-0.5 bg-red-500 text-white text-xs rounded-full">{{ $unreadNotificationsCount }}</span>
                                @endif
                            </h3>
                        </div>
                        
                        @if($recentNotifications->count() > 0)
                        <div class="space-y-2">
                            @foreach($recentNotifications as $notification)
                            <div class="p-3 rounded-lg {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50' }}">
                                <div class="text-sm {{ $notification->read_at ? 'text-gray-600' : 'text-[#1A1A1A] font-medium' }}">
                                    {{ $notification->data['message'] ?? 'Notifikasi baru' }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-6">
                            <div class="text-3xl mb-2">✨</div>
                            <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
