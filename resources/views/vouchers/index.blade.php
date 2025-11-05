{{-- resources/views/vouchers/index.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Manajemen Voucher') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          {{-- Tombol Tambah --}}
          <x-primary-button tag="a" :href="route('vouchers.create')">
            Buat Voucher Baru
          </x-primary-button>

          {{-- Pesan Sukses --}}
          @if (session('success'))
          <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
          </div>
          @endif

          {{-- Tabel Data --}}
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-6">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kadaluarsa</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              @forelse ($vouchers as $voucher)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $voucher->code }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $voucher->type }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @if ($voucher->type == 'percentage')
                  {{ $voucher->value }}%
                  @else
                  Rp {{ number_format($voucher->value, 0, ',', '.') }}
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $voucher->expires_at ?? 'Selamanya' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @if ($voucher->status == 'active')
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Aktif
                  </span>
                  @else
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    Tidak Aktif
                  </span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  {{-- Tombol Edit --}}
                  <x-secondary-button tag="a" :href="route('vouchers.edit', $voucher->id)">
                    Edit
                  </x-secondary-button>
                  {{-- Tombol Hapus --}}
                  <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="submit" onclick="return confirm('Yakin ingin menghapus voucher ini?')">
                      Delete
                    </x-danger-button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">Belum ada data voucher.</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          {{-- Paginasi --}}
          <div class="mt-4">
            {{ $vouchers->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>