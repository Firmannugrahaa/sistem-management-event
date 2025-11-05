<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Buat Voucher Baru') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          {{-- Tampilkan error validasi --}}
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

          <form action="{{ route('vouchers.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              {{-- Kode Voucher --}}
              <div>
                <x-input-label for="code" :value="__('Kode Voucher (Unik)')" />
                <x-text-input id="code" class="block mt-1 w-full font-mono uppercase" type="text" name="code" :value="old('code')" required autofocus />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
              </div>

              {{-- Tipe Voucher --}}
              <div>
                <x-input-label for="type" :value="__('Tipe Diskon')" />
                <select name="type" id="type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                  <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed (Potongan Tetap Rp)</option>
                  <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (Potongan %)</option>
                </select>
                <x-input-error :messages="$errors->get('type')" class="mt-2" />
              </div>

              {{-- Nilai/Value --}}
              <div>
                <x-input-label for="value" :value="__('Nilai (Rp atau %)')" />
                <x-text-input id="value" class="block mt-1 w-full" type="number" name="value" :value="old('value')" required placeholder="Cth: 100000 atau 10" />
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Isi 100000 (untuk Rp 100rb) atau 10 (untuk 10%).</p>
                <x-input-error :messages="$errors->get('value')" class="mt-2" />
              </div>

              {{-- Tanggal Kadaluarsa --}}
              <div>
                <x-input-label for="expires_at" :value="__('Tanggal Kadaluarsa (Opsional)')" />
                <x-text-input id="expires_at" class="block mt-1 w-full" type="date" name="expires_at" :value="old('expires_at')" />
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kosongkan jika berlaku selamanya.</p>
                <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
              </div>

              {{-- Batas Penggunaan --}}
              <div>
                <x-input-label for="max_uses" :value="__('Batas Penggunaan (Opsional)')" />
                <x-text-input id="max_uses" class="block mt-1 w-full" type="number" name="max_uses" :value="old('max_uses')" min="1" />
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kosongkan jika tidak ada batas.</p>
                <x-input-error :messages="$errors->get('max_uses')" class="mt-2" />
              </div>
              <div>
                <x-input-label for="status" :value="__('Status Voucher')" />
                <select name="status" id="status" class="block mt-1 w-full border-gray-300 ... rounded-md shadow-sm" required>

                  {{-- Logika untuk 'edit' --}}
                  @if (isset($voucher))
                  <option value="active" {{ old('status', $voucher->status) == 'active' ? 'selected' : '' }}>Aktif (Bisa Dipakai)</option>
                  <option value="inactive" {{ old('status', $voucher->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif (Dibatalkan)</option>
                  {{-- Logika untuk 'create' --}}
                  @else
                  <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif (Bisa Dipakai)</option>
                  <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif (Dibatalkan)</option>
                  @endif

                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
              </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
              <x-primary-button type="submit">
                Simpan Voucher
              </x-primary-button>
              <x-secondary-button tag="a" :href="route('vouchers.index')">
                Batal
              </x-secondary-button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>