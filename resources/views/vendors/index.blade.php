<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Manajemen Vendor') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          <x-primary-button tag="a" :href="route('vendors.create')">
            Tambah Vendor Baru
          </x-primary-button>

          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-6">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left ...">Kontak Person</th>
                <th class="px-6 py-3 text-left ...">Kategori</th>
                <th class="px-6 py-3 text-left ...">Alamat</th>
                <th class="px-6 py-3 text-left ...">No. Telepon</th>
                <th class="px-6 py-3 text-right ...">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              @forelse ($vendors as $vendor)
              <tr>
                <td class="px-6 py-4">{{ $vendor->contact_person }}</td>
                <td class="px-6 py-4">{{ $vendor->category }}</td>
                <td class="px-6 py-4">{{ $vendor->address ?: '-' }}</td>
                <td class="px-6 py-4">{{ $vendor->phone_number }}</td>
                <td class="px-6 py-4 text-right">
                  <!-- Edit button (now a direct link) -->
                  <a href="{{ route('vendors.show', $vendor->id) }}"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    Lihat
                  </a>
                  <a href="{{ route('vendors.edit', $vendor->id) }}"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    Edit
                  </a>

                  {{-- Tombol Hapus dengan konfirmasi modal global --}}
                  <button
                    type="button"
                    @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', {
                        detail: {
                            formId: 'delete-form-vendor-{{ $vendor->id }}',
                            title: 'Konfirmasi Hapus Vendor',
                            message: 'Apakah Anda yakin ingin menghapus vendor {{ $vendor->name }}? Tindakan ini tidak dapat dibatalkan.'
                        }
                    }))"
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 inline-block ml-2">
                    Delete
                  </button>

                  {{-- Form tersembunyi untuk aksi hapus --}}
                  <form id="delete-form-vendor-{{ $vendor->id }}" action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="px-6 py-4 text-center">Belum ada data vendor rekanan.</td>
              </tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>