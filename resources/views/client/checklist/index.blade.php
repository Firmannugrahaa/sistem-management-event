@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('client.requests.show', $clientRequest) }}" class="text-sm text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Kembali ke Detail Request
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Wedding Planner Checklist</h1>
                    <p class="text-gray-600 mt-1">{{ $clientRequest->event_name }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600 mb-1">Progress Persiapan</div>
                    <div class="text-3xl font-bold text-green-600">{{ $checklist->progress }}%</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4 w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                <div class="bg-green-500 h-4 transition-all duration-300 rounded-full" style="width: {{ $checklist->progress }}%"></div>
            </div>
            <p class="text-sm text-gray-500 mt-2">
                {{ $checklist->items->where('is_checked', true)->count() }} dari {{ $checklist->items->count() }} tugas selesai
            </p>
        </div>

        <!-- View Tabs -->
        <div class="mb-6 flex gap-3 border-b border-gray-200">
            <a href="{{ route('client.checklist', $clientRequest) }}" 
               class="px-4 py-3 font-medium border-b-2 transition {{ !request()->routeIs('*.timeline') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                📋 List View
            </a>
            <a href="{{ route('client.checklist.timeline', $clientRequest) }}" 
               class="px-4 py-3 font-medium border-b-2 transition {{ request()->routeIs('*.timeline') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                📅 Timeline View
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Checklist Items Grouped by Category -->
        @foreach($items as $category => $categoryItems)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $category }}</h2>
                    <p class="text-sm text-gray-500">
                        {{ $categoryItems->where('is_checked', true)->count() }}/{{ $categoryItems->count() }} selesai
                    </p>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($categoryItems as $item)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start gap-4">
                                <!-- Checkbox -->
                                <form action="{{ route('client.checklist.item.update', $item) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_checked" value="{{ $item->is_checked ? '0' : '1' }}">
                                    <button type="submit" class="w-7 h-7 rounded-lg border-2 flex items-center justify-center transition
                                        {{ $item->is_checked ? 'bg-green-500 border-green-500' : 'bg-white border-gray-300 hover:border-green-500' }}">
                                        @if($item->is_checked)
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                <!-- Item Content -->
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-base font-medium {{ $item->is_checked ? 'text-gray-400 line-through' : 'text-gray-900' }}">
                                            {{ $item->title }}
                                        </h3>
                                        @if($item->is_custom)
                                            <form action="{{ route('client.checklist.item.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- Notes Section -->
                                    @if($item->notes)
                                        <p class="text-sm text-gray-600 mt-1">📝 {{ $item->notes }}</p>
                                    @endif

                                    <!-- Edit Notes Form (Collapsible) -->
                                    <details class="mt-2">
                                        <summary class="text-sm text-blue-600 hover:text-blue-800 cursor-pointer">
                                            {{ $item->notes ? 'Edit Catatan' : 'Tambah Catatan' }}
                                        </summary>
                                        <form action="{{ route('client.checklist.item.update', $item) }}" method="POST" class="mt-2">
                                            @csrf
                                            @method('PATCH')
                                            <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tambah catatan...">{{ $item->notes }}</textarea>
                                            <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                                Simpan Catatan
                                            </button>
                                        </form>
                                    </details>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Add Custom Item Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">➕ Tambah Item Checklist Sendiri</h3>
            <form action="{{ route('client.checklist.item.store', $checklist) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Kategori</option>
                            @foreach($items->keys() as $existingCategory)
                                <option value="{{ $existingCategory }}">{{ $existingCategory }}</option>
                            @endforeach
                            <option value="Lainnya">Kategori Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Item</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: Sewa mobil pengantin">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tambahkan catatan atau detail..."></textarea>
                </div>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Tambahkan Item
                </button>
            </form>
        </div>

        <!-- Empty State (if no items) -->
        @if($checklist->items->count() === 0)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-8 text-center">
                <svg class="w-16 h-16 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-blue-900 mb-2">Mulai Rencanakan Pernikahan Anda</h3>
                <p class="text-blue-700">Langkah demi langkah, kami bantu Anda mempersiapkan hari istimewa.</p>
            </div>
        @endif
    </div>
</div>
@endsection
