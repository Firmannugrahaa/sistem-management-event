{{-- resources/views/events/create.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Buat Event Baru') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          @if ($errors->any())
          <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong>Oops! Ada yang salah:</strong>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form action="{{ route('events.store') }}" method="POST">
            @csrf
            <div class="mb-4">
              <x-input-label for="event_name" :value="__('Nama Event')" />
              <x-text-input id="event_name" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="event_name" :value="old('event_name')" required autofocus />
            </div>

            {{-- DROPDOWN VENUE --}}
            <div class="mb-4">
              <x-input-label for="venue_id" :value="__('Pilih Venue')" />
              <select name="venue_id" id="venue_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">-- Tidak ada venue --</option>
                @foreach ($venues as $venue)
                <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                  {{ $venue->name }} (Kapasitas: {{ $venue->capacity }} orang)
                </option>
                @endforeach
              </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <x-input-label for="start_time" :value="__('Waktu Mulai')" />
                <x-text-input id="start_time" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="datetime-local" name="start_time" :value="old('start_time')" required />
              </div>
              <div>
                <x-input-label for="end_time" :value="__('Waktu Selesai')" />
                <x-text-input id="end_time" class="block mt-1 w-full border-blue-300 dark:border-blue-900 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="datetime-local" name="end_time" :value="old('end_time')" required />
              </div>
            </div>

            <div class="mb-4">
              <x-input-label for="description" :value="__('Deskripsi Event')" />
              <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
            </div>

            <x-primary-button type="submit">
              Simpan Event
            </x-primary-button>
          </form>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>