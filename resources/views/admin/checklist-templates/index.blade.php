@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Checklist Template Management</h1>
                <p class="text-gray-600 mt-1">Kelola template checklist untuk berbagai tipe event</p>
            </div>
            <a href="{{ route('admin.checklist-templates.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold shadow-sm">
                ➕ Tambah Template Baru
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Template List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($templates as $template)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $template->name }}</h3>
                            <p class="text-sm text-gray-500">Event Type: <span class="font-medium">{{ $template->event_type }}</span></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm">{{ $template->items_count }} items</span>
                        </div>
                        <div class="flex items-center text-gray-500 text-xs mt-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Dibuat: {{ $template->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.checklist-templates.edit', $template) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition text-center">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('admin.checklist-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus template ini? Semua item akan terhapus!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-blue-50 border border-blue-200 rounded-xl p-12 text-center">
                    <svg class="w-16 h-16 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-blue-900 mb-2">Belum Ada Template</h3>
                    <p class="text-blue-700 mb-4">Buat template checklist pertama Anda untuk berbagai tipe event</p>
                    <a href="{{ route('admin.checklist-templates.create') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        ➕ Buat Template Pertama
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
