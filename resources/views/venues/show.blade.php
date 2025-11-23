{{-- resources/views/venues/show.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Detail Venue: ') . $venue->name }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
        
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="mb-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ $venue->name }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-2"><strong>Alamat:</strong> {{ $venue->address }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-2"><strong>Kapasitas:</strong> {{ $venue->capacity }} orang</p>
              <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Harga:</strong> Rp {{ number_format($venue->price, 0, ',', '.') }}</p>
            </div>
            
            <div class="flex items-center justify-center">
              <img src="https://images.unsplash.com/photo-1512869251363-8e62799c3f41?auto=format&fit=crop&w=600&h=400&q=80"
                   alt="{{ $venue->name }}"
                   class="w-full h-64 object-cover rounded-lg shadow-md">
            </div>
          </div>

          <div class="mt-6 flex space-x-4">
            <a href="{{ route('client.landing') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
              Kembali
            </a>
            
            <a href="{{ route('client.order.review') }}" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
              Booking Venue Ini
            </a>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</x-app-layout>