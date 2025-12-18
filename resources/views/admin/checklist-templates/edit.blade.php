@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.checklist-templates.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Template
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Template: {{ $template->name }}</h1>
                <p class="text-gray-600 mt-1">{{ $template->event_type }} | {{ $template->items->count() }} items</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Template Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Template</h2>
                    
                    <form action="{{ route('admin.checklist-templates.update', $template) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Type</label>
                            <input type="text" name="event_type" value="{{ old('event_type', $template->event_type) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Template</label>
                            <input type="text" name="name" value="{{ old('name', $template->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                            Update Template
                        </button>
                    </form>
                </div>

                <!-- Add Item Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">➕ Tambah Item Baru</h2>
                    
                    <form action="{{ route('admin.checklist-templates.items.store', $template) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <input type="text" name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Administrasi & Legal">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Item</label>
                            <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Menyiapkan KTP CPP & CPW">
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                            Tambah Item
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right: Items List Grouped by Category -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Checklist Items ({{ $template->items->count() }})</h2>
                    </div>

                    @if($itemsByCategory->count() > 0)
                        @foreach($itemsByCategory as $category => $items)
                            <div class="border-b border-gray-200 last:border-b-0">
                                <div class="px-6 py-3 bg-gray-50">
                                    <h3 class="text-md font-semibold text-gray-800">{{ $category }}</h3>
                                    <p class="text-xs text-gray-500">{{ $items->count() }} items</p>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    @foreach($items as $item)
                                        <div class="px-6 py-3 hover:bg-gray-50 flex items-center justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $item->title }}</p>
                                                <p class="text-xs text-gray-500">Order: {{ $item->order }}</p>
                                            </div>
                                            <div class="flex gap-2">
                                                <form action="{{ route('admin.checklist-templates.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200 transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500">Belum ada item. Gunakan form di sebelah kiri untuk menambahkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
