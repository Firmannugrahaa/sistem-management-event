@extends('layouts.landing')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="container mx-auto px-6">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('landing.page') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('landing.page') }}#packages" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary md:ml-2">Paket</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $package->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Left Column: Image & Details -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Main Image -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <img src="{{ $package->thumbnail_path ? asset('storage/' . $package->thumbnail_path) : 'https://images.unsplash.com/photo-1519225421980-715cb0202128?auto=format&fit=crop&w=1200&h=600&q=80' }}" 
                             alt="{{ $package->name }}" 
                             class="w-full h-96 object-cover">
                    </div>

                    <!-- Description -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $package->name }}</h1>
                        
                        <div class="flex items-center space-x-4 mb-6">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-clock mr-1"></i> {{ $package->duration ?? 'Flexible Duration' }}
                            </span>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-check-circle mr-1"></i> Available
                            </span>
                        </div>

                        <div class="prose max-w-none text-gray-600">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Deskripsi Paket</h3>
                            <p class="mb-6">{{ $package->description }}</p>

                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Apa yang Anda Dapatkan?</h3>
                            @if($package->features && is_array($package->features))
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($package->features as $feature)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-gray-700">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Pricing & Action -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-8 sticky top-24">
                        <div class="mb-6">
                            <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Harga Mulai Dari</p>
                            @if($package->discount_price)
                                <div class="flex flex-col">
                                    <span class="text-gray-400 line-through text-lg">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                    <span class="text-4xl font-bold text-primary">Rp {{ number_format($package->discount_price, 0, ',', '.') }}</span>
                                    <span class="text-sm text-red-500 font-semibold mt-1">Hemat {{ number_format((($package->price - $package->discount_price) / $package->price) * 100, 0) }}%</span>
                                </div>
                            @else
                                <span class="text-4xl font-bold text-primary">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <hr class="border-gray-100 my-6">

                        <div class="space-y-4">
                            <button onclick="confirmSelection()" class="w-full bg-primary text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-800 transition shadow-lg transform hover:-translate-y-1">
                                Pilih Paket Ini
                            </button>
                            
                            <a href="https://wa.me/{{ \App\Models\CompanySetting::first()->company_phone ?? '' }}?text=Halo, saya tertarik dengan {{ $package->name }}" target="_blank" class="block w-full text-center bg-white border-2 border-green-500 text-green-600 py-3 rounded-xl font-semibold hover:bg-green-50 transition">
                                <i class="fab fa-whatsapp mr-2"></i> Tanya Admin
                            </a>
                        </div>

                        <div class="mt-6 text-center">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-shield-alt mr-1"></i> Transaksi Aman & Terpercaya
                            </p>
                        </div>
                    </div>

                    <!-- Recommendation Section (Conditional) -->
                    @auth
                        @php
                            $userEvents = \App\Models\Event::where('user_id', auth()->id())->whereDoesntHave('vendorItems')->get();
                        @endphp
                        @if($userEvents->count() > 0)
                            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                                <h4 class="font-bold text-yellow-800 mb-2"><i class="fas fa-lightbulb mr-2"></i>Rekomendasi</h4>
                                <p class="text-sm text-yellow-700 mb-4">
                                    Kami melihat Anda memiliki event yang belum memiliki paket. Ingin menerapkan paket ini ke event Anda?
                                </p>
                                <select id="existingEventSelect" class="w-full border-yellow-300 rounded-lg text-sm mb-3 focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="">Pilih Event Anda...</option>
                                    @foreach($userEvents as $event)
                                        <option value="{{ $event->id }}">{{ $event->event_name }} ({{ $event->start_time ? $event->start_time->format('d M Y') : 'TBA' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Related Packages -->
            @if(isset($relatedPackages) && $relatedPackages->count() > 0)
                <div class="mt-20">
                    <h2 class="text-2xl font-bold text-gray-800 mb-8">Paket Lainnya</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach($relatedPackages as $related)
                            <a href="{{ route('event-packages.show', $related->slug) }}" class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ $related->thumbnail_path ? asset('storage/' . $related->thumbnail_path) : 'https://images.unsplash.com/photo-1519225421980-715cb0202128?auto=format&fit=crop&w=600&h=400&q=80' }}" 
                                         alt="{{ $related->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="p-5">
                                    <h3 class="font-bold text-gray-800 mb-2 group-hover:text-primary transition">{{ $related->name }}</h3>
                                    <p class="text-primary font-bold">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function confirmSelection() {
            const existingEventId = document.getElementById('existingEventSelect')?.value;
            
            Swal.fire({
                title: 'Pilih Paket Ini?',
                text: "Anda akan diarahkan ke halaman booking untuk melengkapi detail acara.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#012A4A',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Logic to redirect
                    let url = "{{ route('events.create') }}?package_id={{ $package->id }}";
                    
                    if (existingEventId) {
                        // If user selected an existing event to apply package to
                        // Ideally we would have a route like 'events.apply-package'
                        // For now, let's redirect to event edit or show page with a query param
                        // Or just alert that this feature is coming soon if not implemented
                        url = "{{ url('/events') }}/" + existingEventId + "/apply-package?package_id={{ $package->id }}";
                        // Since apply-package route might not exist, let's fallback to create for now or handle it.
                        // Actually, let's just use the create route for new events as primary flow.
                        // If existing event is selected, we might need a specific controller action.
                        
                        // For this demo, let's stick to creating new booking with package
                        // But if existing event is selected, we'll append it
                         url = "{{ route('events.create') }}?package_id={{ $package->id }}&existing_event_id=" + existingEventId;
                    }

                    window.location.href = url;
                }
            })
        }
    </script>
@endsection
