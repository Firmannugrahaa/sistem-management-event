{{-- resources/views/venues/index.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Manajemen Venue') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          <x-primary-button tag="a" :href="route('venues.create')">
            Tambah Venue Baru
          </x-primary-button>

          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-6">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kapasitas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              @forelse ($venues as $venue)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $venue->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $venue->capacity }} orang</td>
                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($venue->price, 0, ',', '.') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <x-secondary-button tag="a" :href="route('venues.edit', $venue->id)">
                    Edit
                  </x-secondary-button>
                  <form action="{{ route('venues.destroy', $venue->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="submit" onclick="return confirm('Yakin ingin menghapus venue ini?')">
                      Delete
                    </x-danger-button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center">Belum ada data venue.</td>
              </tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>