<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Tambah Vendor Baru') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          @if ($errors->any())
          <div class="mb-4 bg-red-100 ...">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form action="{{ route('vendors.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="mb-4">
                <x-input-label for="name" :value="__('Nama Vendor')" />
                <x-text-input id="name" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="name" :value="old('name')" required autofocus />
              </div>
              <div class="mb-4">
                <x-input-label for="category" :value="__('Kategori (Cth: Katering, Dekorasi)')" />
                <x-text-input id="category" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="category" :value="old('category')" required />
              </div>
              <div class="mb-4">
                <x-input-label for="contact_person" :value="__('Nama Kontak Person')" />
                <x-text-input id="contact_person" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="contact_person" :value="old('contact_person')" required />
              </div>
              <div class="mb-4">
                <x-input-label for="phone_number" :value="__('No. Telepon / WA')" />
                <x-text-input id="phone_number" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="phone_number" :value="old('phone_number')" required />
              </div>
            </div>

            <div class="mb-4">
              <x-input-label for="address" :value="__('Alamat')" />
              <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address') }}</textarea>
            </div>

            <x-primary-button type="submit">
              Simpan Vendor
            </x-primary-button>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>