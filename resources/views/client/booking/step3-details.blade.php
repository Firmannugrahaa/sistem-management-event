@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <p class="text-sm text-gray-600">Step 3 of 4: <strong>Detail Event</strong></p>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Detail Event</h1>
            <p class="text-gray-600 mt-2">Isi informasi event Anda</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('client.booking.details.store') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Event *</label>
                    <input type="text" name="event_name" value="{{ old('event_name', $eventDetails['event_name'] ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Wedding John & Jane">
                    @error('event_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Event *</label>
                    <select name="event_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Tipe Event</option>
                        <option value="Wedding" {{ old('event_type', $eventDetails['event_type'] ?? '') == 'Wedding' ? 'selected' : '' }}>Wedding</option>
                        <option value="Birthday" {{ old('event_type', $eventDetails['event_type'] ?? '') == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                        <option value="Corporate" {{ old('event_type', $eventDetails['event_type'] ?? '') == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                        <option value="Other" {{ old('event_type', $eventDetails['event_type'] ?? '') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('event_type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Event *</label>
                        <input type="date" name="event_date" value="{{ old('event_date', $eventDetails['event_date'] ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        @error('event_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waktu (Opsional)</label>
                        <input type="time" name="event_time" value="{{ old('event_time', $eventDetails['event_time'] ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi *</label>
                    <input type="text" name="location" value="{{ old('location', $eventDetails['location'] ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Jakarta Convention Center">
                    @error('location')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Tamu *</label>
                    <input type="number" name="guest_count" value="{{ old('guest_count', $eventDetails['guest_count'] ?? '') }}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="100">
                    @error('guest_count')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Permintaan khusus atau catatan untuk tim kami...">{{ old('notes', $eventDetails['notes'] ?? '') }}</textarea>
                </div>

                <div class="flex justify-between items-center">
                    <a href="javascript:history.back()" class="text-gray-600 hover:text-gray-900 font-medium">← Kembali</a>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">Lanjut ke Review →</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
