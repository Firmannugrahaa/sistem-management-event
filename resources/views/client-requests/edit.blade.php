@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Edit Client Request</h2>
                <p class="mt-1 text-sm text-gray-600">Update informasi request #{{ $clientRequest->id }}</p>
            </div>

            <form action="{{ route('client-requests.update', $clientRequest) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Client Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Client</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Client *</label>
                                <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $clientRequest->client_name) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_name') border-red-500 @enderror">
                                @error('client_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="client_email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" name="client_email" id="client_email" value="{{ old('client_email', $clientRequest->client_email) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_email') border-red-500 @enderror">
                                @error('client_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon *</label>
                                <input type="tel" name="client_phone" id="client_phone" value="{{ old('client_phone', $clientRequest->client_phone) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_phone') border-red-500 @enderror">
                                @error('client_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Event Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Event</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="event_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Event *</label>
                                <select name="event_type" id="event_type" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('event_type') border-red-500 @enderror">
                                    <option value="">Pilih Tipe Event</option>
                                    <option value="Wedding" {{ old('event_type', $clientRequest->event_type) == 'Wedding' ? 'selected' : '' }}>Wedding</option>
                                    <option value="Birthday" {{ old('event_type', $clientRequest->event_type) == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                                    <option value="Corporate" {{ old('event_type', $clientRequest->event_type) == 'Corporate' ? 'selected' : '' }}>Corporate Event</option>
                                    <option value="Conference" {{ old('event_type', $clientRequest->event_type) == 'Conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="Engagement" {{ old('event_type', $clientRequest->event_type) == 'Engagement' ? 'selected' : '' }}>Engagement</option>
                                    <option value="Other" {{ old('event_type', $clientRequest->event_type) == 'Other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('event_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Event *</label>
                                <input type="date" name="event_date" id="event_date" value="{{ old('event_date', $clientRequest->event_date->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('event_date') border-red-500 @enderror">
                                @error('event_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-2 text-gray-500">Rp</span>
                                    <input type="number" name="budget" id="budget" value="{{ old('budget', $clientRequest->budget) }}" min="0" step="0.01"
                                        class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('budget') border-red-500 @enderror">
                                </div>
                                @error('budget')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                                <select name="vendor_id" id="vendor_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vendor_id') border-red-500 @enderror">
                                    <option value="">Pilih Vendor (Opsional)</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id', $clientRequest->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }} - {{ $vendor->category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan / Keterangan</label>
                            <textarea name="message" id="message" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror">{{ old('message', $clientRequest->message) }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status & Assignment (Admin/Owner only) -->
                    @if(auth()->user()->hasAnyRole(['SuperUser', 'Owner', 'Admin']))
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status & Assignment</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" id="status" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                    <option value="pending" {{ old('status', $clientRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="on_process" {{ old('status', $clientRequest->status) == 'on_process' ? 'selected' : '' }}>On Process</option>
                                    <option value="done" {{ old('status', $clientRequest->status) == 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                                <select name="assigned_to" id="assigned_to"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('assigned_to') border-red-500 @enderror">
                                    <option value="">Belum di-assign</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}" {{ old('assigned_to', $clientRequest->assigned_to) == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Internal</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                placeholder="Catatan untuk tim internal...">{{ old('notes', $clientRequest->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @else
                    <!-- Staff can only change status -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                <option value="pending" {{ old('status', $clientRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="on_process" {{ old('status', $clientRequest->status) == 'on_process' ? 'selected' : '' }}>On Process</option>
                                <option value="done" {{ old('status', $clientRequest->status) == 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hidden fields to preserve values -->
                        <input type="hidden" name="assigned_to" value="{{ $clientRequest->assigned_to }}">
                        <input type="hidden" name="notes" value="{{ $clientRequest->notes }}">
                    </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('client-requests.show', $clientRequest) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
