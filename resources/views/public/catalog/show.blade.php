@extends('layouts.landing')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('landing.page') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail Venue</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 lg:gap-8">
                <!-- Image Gallery -->
                <div class="p-6 lg:p-8">
                    @if($item->images->count() > 0)
                        <div class="mb-4 rounded-xl overflow-hidden aspect-video relative group">
                            <img id="mainImage" src="{{ asset('storage/' . $item->images->first()->image_path) }}" 
                                 alt="{{ $item->name }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                        </div>
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($item->images as $image)
                                <div class="cursor-pointer rounded-lg overflow-hidden aspect-square border-2 border-transparent hover:border-primary transition"
                                     onclick="changeImage('{{ asset('storage/' . $image->image_path) }}')">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl overflow-hidden aspect-video bg-gray-200 flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="p-6 lg:p-8 lg:pl-0 flex flex-col">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $item->status === 'available' ? 'bg-green-100 text-green-800' : 
                                   ($item->status === 'booked' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $item->status === 'available' ? 'Tersedia' : 
                                   ($item->status === 'booked' ? 'Dipesan' : 'Tidak Tersedia') }}
                            </span>
                            @if($item->category)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $item->category->name }}
                                </span>
                            @endif
                        </div>

                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $item->name }}</h1>
                        
                        <div class="flex items-center gap-2 mb-6">
                            <span class="text-gray-500 text-sm">Oleh:</span>
                            <a href="{{ route('vendor.profile.show', $item->vendor->id) }}" class="text-primary font-medium hover:underline">
                                {{ $item->vendor->brand_name ?? $item->vendor->user->name }}
                            </a>
                        </div>

                        <div class="text-3xl font-bold text-primary mb-6">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </div>

                        <div class="prose prose-sm text-gray-600 mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h3>
                            <p class="whitespace-pre-line">{{ $item->description ?? 'Tidak ada deskripsi.' }}</p>
                        </div>

                        @if($item->attributes && count($item->attributes) > 0)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Spesifikasi</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($item->attributes as $key => $value)
                                        <div class="flex justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-gray-600 font-medium">{{ $key }}</span>
                                            <span class="text-gray-900">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('public.booking.form') }}" class="flex-1 bg-primary text-white text-center px-8 py-4 rounded-xl font-bold text-lg hover:bg-blue-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Booking Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
    }
</script>
@endpush
@endsection
