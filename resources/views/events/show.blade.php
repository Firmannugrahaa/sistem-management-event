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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <p><strong>Nama Event:</strong> {{ $event->event_name }}</p>
                <p><strong>Venue:</strong> {{ $event->venue->name ?? 'N/A' }}</p>
                <p><strong>Waktu Mulai:</strong> {{ $event->start_time }}</p>
                <p><strong>Waktu Selesai:</strong> {{ $event->end_time }}</p>
              </div>
              <div>
                @if($event->client_name)
                  <p><strong>Nama Klien:</strong> {{ $event->client_name }}</p>
                  @if($event->client_phone)<p><strong>Telepon:</strong> {{ $event->client_phone }}</p>@endif
                  @if($event->client_email)<p><strong>Email:</strong> {{ $event->client_email }}</p>@endif
                  @if($event->client_address)<p><strong>Alamat:</strong> {{ $event->client_address }}</p>@endif
                @else
                  <p><strong>Nama Klien:</strong> Tidak ada informasi klien</p>
                @endif
              </div>
            </div>
            <p><strong>Deskripsi:</strong> {{ $event->description ?? '-' }}</p>
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
                    {{-- Edit button with modal confirmation --}}
                    <button
                      @click="document.dispatchEvent(new CustomEvent('show-alert-edit-guest-{{ $guest->id }}'))"
                      class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 ml-2"
                    >
                      Edit
                    </button>

                    {{-- Modal Konfirmasi Edit Guest --}}
                    <x-alert-modal
                        id="edit-guest-{{ $guest->id }}"
                        title="Edit Guest"
                        message="Apakah anda yakin ingin mengedit tamu {{ $guest->name }}?"
                        type="warning"
                        action="window.location='{{ route('events.guests.edit', [$event, $guest]) }}'"
                        cancel=""
                    />

                    {{-- Hapus Guest --}}
                    <button 
                      @click="document.dispatchEvent(new CustomEvent('show-alert-delete-guest-{{ $guest->id }}'))"
                      class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 inline-block ml-2"
                    >
                      Hapus
                    </button>

                    {{-- Modal Konfirmasi Hapus Guest --}}
                    <x-alert-modal 
                        id="delete-guest-{{ $guest->id }}" 
                        title="Delete Guest" 
                        message="Yakin hapus tamu {{ $guest->name }}? This action cannot be undone." 
                        type="danger"
                        action="document.getElementById('delete-form-guest-{{ $guest->id }}').submit()"
                        cancel=""
                    />

                    {{-- Form tersembunyi untuk aksi hapus guest --}}
                    <form id="delete-form-guest-{{ $guest->id }}" action="{{ route('events.guests.destroy', [$event, $guest]) }}" method="POST" class="hidden">
                      @csrf
                      @method('DELETE')
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
                    {{-- Tombol Kelola Item (New) --}}
                    <x-secondary-button tag="a" :href="route('events.vendor-items.index', [$event, $vendor])" class="mr-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border-indigo-200">
                      Kelola Item
                    </x-secondary-button>

                    {{-- Tombol Edit Vendor --}}
                    <x-secondary-button tag="a" :href="route('vendors.edit', $vendor)" class="mr-2">
                      Edit Vendor
                    </x-secondary-button>

                    {{-- Tombol Lepas Vendor dengan konfirmasi modal --}}
                    <button 
                      @click="document.dispatchEvent(new CustomEvent('show-alert-detach-vendor-{{ $vendor->id }}'))"
                      class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 inline-block"
                    >
                      Lepas
                    </button>

                    {{-- Modal Konfirmasi Lepas Vendor --}}
                    <x-alert-modal 
                        id="detach-vendor-{{ $vendor->id }}" 
                        title="Detach Vendor" 
                        message="Yakin lepas vendor {{ $vendor->name }} dari event ini? This action cannot be undone." 
                        type="danger"
                        action="document.getElementById('detach-form-vendor-{{ $vendor->id }}').submit()"
                        cancel=""
                    />

                    {{-- Form tersembunyi untuk aksi lepas vendor --}}
                    <form id="detach-form-vendor-{{ $vendor->id }}" action="{{ route('events.detach-vendor', [$event, $vendor]) }}" method="POST" class="hidden">
                      @csrf
                      @method('POST')
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