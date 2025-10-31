<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Detail Event: {{ $event->event_name }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Info Event</h3>

            {{-- TOMBOL BARU UNTUK INVOICE --}}
            <div class="flex space-x-2">
              {{-- Tombol 1: Generate/Update Invoice --}}
              <form action="{{ route('events.generateInvoice', $event) }}" method="POST">
                @csrf
                <x-secondary-button type="submit">
                  Kalkulasi Ulang Invoice
                </x-secondary-button>
              </form>

              {{-- Tombol 2: Lihat Invoice (jika sudah ada) --}}
              @if ($event->invoice)
              <x-primary-button tag="a" :href="route('invoice.show', $event->invoice)">
                Lihat Invoice
              </x-primary-button>
              @endif
            </div>
          </div>
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <h3 class="text-lg font-bold">Info Event</h3>
            <p>Venue: {{ $event->venue->name ?? 'N/A' }}</p>
            <p>Kapan: {{ $event->start_time }}</p>
          </div>
        </div>

        {{-- DAFTAR TAMU --}}
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-bold">Daftar Tamu</h3>
              <x-primary-button tag="a" :href="route('events.guests.create', $event)">
                + Tambah Tamu
              </x-primary-button>
              <x-secondary-button tag="a" :href="route('events.guests.import.form', $event)" class="ml-2">
                Import Excel
              </x-secondary-button>
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead>
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Whatsapp</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($event->guests as $guest)
                <tr>
                  <td class="px-6 py-4">{{ $guest->name }}</td>
                  <td class="px-6 py-4">{{ $guest->whatsapp_number }}</td>
                  <td class="px-6 py-4">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $guest->status == 'Attended' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                      {{ $guest->status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    {{-- Link Tiket --}}
                    @if ($guest->ticket)
                    {{-- Jika tiket ADA, tampilkan tombol --}}
                    <x-secondary-button tag="a" :href="route('tickets.show', $guest->ticket->ticket_code)" target="_blank">
                      Tiket
                    </x-secondary-button>
                    @else
                    {{-- Jika tiket TIDAK ADA, tampilkan tombol non-aktif --}}
                    <x-secondary-button disabled title="Tiket gagal dibuat untuk tamu ini">
                      Tiket Error
                    </x-secondary-button>
                    @endif
                    {{-- Edit Guest --}}
                    <x-secondary-button tag="a" :href="route('events.guests.edit', [$event, $guest])" class="ml-2">
                      Edit
                    </x-secondary-button>
                    {{-- Hapus Guest --}}
                    <form action="{{ route('events.guests.destroy', [$event, $guest]) }}" method="POST" class="inline-block ml-2">
                      @csrf
                      @method('DELETE')
                      <x-danger-button type="submit" onclick="return confirm('Yakin hapus tamu ini?')">
                        Hapus
                      </x-danger-button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="px-6 py-4 text-center">Belum ada tamu.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        {{-- DAFTAR VENDOR YANG DITUGASKAN --}}
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <h3 class="text-lg font-bold mb-4">Vendor Ditugaskan</h3>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead>
                <tr>
                  <th class="px-6 py-3 text-left ...">Nama Vendor</th>
                  <th class="px-6 py-3 text-left ...">Kategori</th>
                  <th class="px-6 py-3 text-left ...">Harga Sepakat</th>
                  <th class="px-6 py-3 text-left ...">Status</th>
                  <th class="px-6 py-3 text-right ...">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($event->vendors as $vendor)
                <tr>
                  <td class="px-6 py-4">{{ $vendor->name }}</td>
                  <td class="px-6 py-4">{{ $vendor->category }}</td>
                  {{-- Ambil data pivot --}}
                  <td class="px-6 py-4">Rp {{ number_format($vendor->pivot->agreed_price, 0, ',', '.') }}</td>
                  <td class="px-6 py-4">{{ $vendor->pivot->status }}</td>
                  <td class="px-6 py-4 text-right">
                    <form action="{{ route('events.detachVendor', [$event, $vendor]) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <x-danger-button type="submit" onclick="return confirm('Yakin lepas vendor ini?')">
                        Lepas
                      </x-danger-button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="px-6 py-4 text-center">Belum ada vendor ditugaskan.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        {{-- FORM TAMBAH VENDOR --}}
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <h3 class="text-lg font-bold mb-4">Tugaskan Vendor Baru</h3>

            @if ($errors->any())
            {{-- Tampilkan error validasi form ini --}}
            @endif

            <form action="{{ route('events.assignVendor', $event) }}" method="POST">
              @csrf
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Dropdown Vendor --}}
                <div>
                  <x-input-label for="vendor_id" :value="__('Pilih Vendor (Rekanan)')" />
                  <select name="vendor_id" id="vendor_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="">-- Pilih Vendor --</option>
                    @foreach ($all_vendors as $vendor)
                    <option value="{{ $vendor->id }}">
                      {{ $vendor->name }} ({{ $vendor->category }})
                    </option>
                    @endforeach
                  </select>
                </div>
                {{-- Harga --}}
                <div>
                  <x-input-label for="agreed_price" :value="__('Harga Kesepakatan (Rp)')" />
                  <x-text-input id="agreed_price" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="number" name="agreed_price" :value="old('agreed_price')" />
                </div>
              </div>
              {{-- Kontrak --}}
              <div class="mt-4">
                <x-input-label for="contract_details" :value="__('Detail Kontrak/Catatan')" />
                <textarea id="contract_details" name="contract_details" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('contract_details') }}</textarea>
              </div>

              <x-primary-button type="submit" class="mt-4">
                Tugaskan Vendor
              </x-primary-button>
            </form>
          </div>
        </div>
      </div>
    </div>
</x-app-layout>