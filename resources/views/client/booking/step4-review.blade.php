@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <p class="text-sm text-gray-600">Step 4 of 4: <strong>Review & Submit</strong></p>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Review Booking Anda</h1>
            <p class="text-gray-600 mt-2">Periksa kembali detail booking sebelum submit</p>
        </div>

        <!-- Event Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Detail Event</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nama Event</dt>
                    <dd class="text-base font-semibold text-gray-900">{{ $eventDetails['event_name'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tipe Event</dt>
                    <dd class="text-base font-semibold text-gray-900">{{ $eventDetails['event_type'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu</dt>
                    <dd class="text-base font-semibold text-gray-900">{{ \Carbon\Carbon::parse($eventDetails['event_date'])->format('d M Y') }} {{ $eventDetails['event_time'] ?? '' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                    <dd class="text-base font-semibold text-gray-900">{{ $eventDetails['location'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Jumlah Tamu</dt>
                    <dd class="text-base font-semibold text-gray-900">{{ $eventDetails['guest_count'] }} orang</dd>
                </div>
            </dl>
            @if(!empty($eventDetails['notes']))
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600"><strong>Catatan:</strong> {{ $eventDetails['notes'] }}</p>
                </div>
            @endif
        </div>

        <!-- Package/Vendors Summary -->
        @if($mode === 'package')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Paket Dipilih</h2>
                <div class="flex items-start gap-4">
                    @if($package->image_url)
                        <img src="{{ asset('storage/' . $package->image_url) }}" alt="{{ $package->name }}" class="w-24 h-24 object-cover rounded-lg">
                    @endif
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900">{{ $package->name }}</h3>
                        <p class="text-2xl font-bold text-blue-600 mt-2">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                        <ul class="mt-3 space-y-1">
                            @foreach($package->items as $item)
                                <li class="text-sm text-gray-600">✓ {{ $item->item_name }} ({{ $item->quantity }}x)</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Vendor Dipilih ({{ $selectedVendors->count() }})</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($selectedVendors as $vendor)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <h3 class="font-bold text-gray-900">{{ $vendor->brand_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $vendor->category }}</p>
                            @if($vendor->catalogItems->count() > 0)
                                <p class="text-sm font-semibold text-blue-600 mt-1">Mulai Rp {{ number_format($vendor->catalogItems->min('price'), 0, ',', '.') }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Estimated Total -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Estimasi Total</h2>
                    <p class="text-sm text-gray-600">*Harga dapat berubah setelah konfirmasi vendor</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($estimatedTotal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Submit Form -->
        <form method="POST" action="{{ route('client.booking.submit') }}">
            @csrf
            <div class="flex justify-between items-center">
                <a href="{{ route('client.booking.details') }}" class="text-gray-600 hover:text-gray-900 font-medium">← Edit Detail</a>
                <button type="submit" class="px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition shadow-sm">
                    ✓ Submit Booking
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
