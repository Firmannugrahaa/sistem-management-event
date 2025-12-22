@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center space-x-4">
            <a href="{{ route('client.requests.show', $recommendation->clientRequest) }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $recommendation->title }}</h1>
                <div class="flex items-center mt-1 space-x-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $recommendation->status_badge_color }}">
                        {{ ucfirst($recommendation->status) }}
                    </span>
                    <span class="text-sm text-gray-500">Received on {{ $recommendation->sent_at ? $recommendation->sent_at->format('d M Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                @if($recommendation->description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Note from Organizer</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $recommendation->description }}</p>
                </div>
                @endif

                <!-- Items List (Grouped by Category) -->
                @php
                    $groupedItems = $recommendation->items->groupBy('category');
                @endphp

                <div class="space-y-8">
                    @foreach($groupedItems as $category => $items)
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <span class="w-1 h-6 bg-blue-600 rounded-full mr-3"></span>
                            {{ $category }}
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($items as $item)
                            <div class="bg-white rounded-xl shadow-sm border {{ $item->status === 'accepted' ? 'border-green-500 ring-1 ring-green-500' : ($item->status === 'rejected' ? 'border-red-200 bg-red-50' : 'border-gray-200') }} p-5 relative transition hover:shadow-md flex flex-col h-full">
                                
                                <!-- Badges -->
                                <div class="absolute top-4 right-4 flex space-x-2">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $item->recommendation_type_badge_color }}">
                                        {{ $item->recommendation_type }}
                                    </span>
                                    @if($item->status !== 'pending')
                                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $item->status_badge_color }}">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-4 pr-16">
                                    <h3 class="font-bold text-lg text-gray-900">{{ $item->vendor_name }}</h3>
                                    @if($item->service_name)
                                        <p class="text-sm text-blue-600 font-medium">{{ $item->service_name }}</p>
                                    @endif
                                    <p class="text-lg font-bold text-gray-900 mt-2">
                                        Rp {{ number_format($item->estimated_price, 0, ',', '.') }}
                                    </p>
                                </div>

                                @if($item->notes)
                                <div class="bg-blue-50 p-3 rounded-lg mb-4 text-xs text-blue-800">
                                    <span class="font-bold">Admin Note:</span> {{ $item->notes }}
                                </div>
                                @endif

                                <div class="mt-auto pt-4 border-t border-gray-100">
                                    @if($item->status === 'pending')
                                        <div class="flex space-x-2">
                                            <button onclick="acceptItem({{ $item->id }})" class="flex-1 bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                                                Setujui
                                            </button>
                                            <button onclick="rejectItem({{ $item->id }})" class="flex-1 bg-white border border-red-200 text-red-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-50 transition">
                                                Tolak
                                            </button>
                                        </div>
                                    @elseif($item->status === 'accepted')
                                        <div class="text-center py-2 bg-green-50 rounded-lg text-green-700 text-sm font-medium">
                                            ✓ Disetujui
                                        </div>
                                    @elseif($item->status === 'rejected')
                                        <div class="text-center py-2 bg-red-50 rounded-lg text-red-700 text-sm font-medium">
                                            ✕ Ditolak
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 bg-gray-50 rounded-xl p-6 border border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-gray-900">Total Estimated Budget</h3>
                        <p class="text-sm text-gray-500">Based on all recommendations</p>
                    </div>
                    <span class="text-2xl font-bold text-blue-600">
                        Rp {{ number_format($recommendation->total_estimated_budget, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Action Sidebar (Summary) -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Rekomendasi</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Total Items</span>
                            <span class="font-medium">{{ $recommendation->items->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Pending</span>
                            <span class="font-medium text-orange-600">{{ $recommendation->items->where('status', 'pending')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Accepted</span>
                            <span class="font-medium text-green-600">{{ $recommendation->items->where('status', 'accepted')->count() }}</span>
                        </div>
                         <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Rejected</span>
                            <span class="font-medium text-red-600">{{ $recommendation->items->where('status', 'rejected')->count() }}</span>
                        </div>
                    </div>
                     <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-xs text-gray-500 text-center">
                             Silakan setujui atau tolak item per kategori. Item yang disetujui akan otomatis masuk ke draft event Anda.
                        </p>
                    </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function acceptItem(itemId) {
        if(!confirm('Apakah Anda yakin ingin menyetujui rekomendasi vendor ini?')) return;

        fetch(`/portal/recommendations/items/${itemId}/accept`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if(data.success) {
                // Show success feedback
                alert('Berhasil menyetujui rekomendasi!');
                window.location.reload();
            } else {
                alert('Terjadi kesalahan saat memproses permintaan via server.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghubungi server. Silakan coba lagi.');
        });
    }

    function rejectItem(itemId) {
        const reason = prompt('Mohon berikan alasan penolakan (opsional):');
        if(reason === null) return; // User cancelled

        fetch(`/portal/recommendations/items/${itemId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
             if(data.success) {
                window.location.reload();
            } else {
                 alert('Terjadi kesalahan saat memproses permintaan via server.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghubungi server. Silakan coba lagi.');
        });
    }
</script>
@endpush
@endsection
