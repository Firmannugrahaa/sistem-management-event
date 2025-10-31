{{-- resources/views/venues/edit.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Edit Venue: ') . $venue->name }}
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

          <form action="{{ route('venues.update', $venue->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- PENTING: Method untuk update adalah PUT/PATCH --}}

            <div class="mb-4">
              <x-input-label for="name" :value="__('Nama Venue')" />
              <x-text-input id="name" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="name" :value="old('name', $venue->name)" required autofocus />
            </div>

            <div class="mb-4">
              <x-input-label for="address" :value="__('Alamat')" />
              <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address', $venue->address) }}</textarea>
            </div>

            <div class="mb-4">
              <x-input-label for="capacity" :value="__('Kapasitas (orang)')" />
              <x-text-input id="capacity" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="number" name="capacity" :value="old('capacity', $venue->capacity)" required />
            </div>

            <div class="mb-4">
              <x-input-label for="price" :value="__('Harga Sewa (Rp)')" />
              <x-text-input id="price" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="number" name="price" :value="old('price', $venue->price)" required />
            </div>

            <x-primary-button type="submit">
              Update Venue
            </x-primary-button>
          </form>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>