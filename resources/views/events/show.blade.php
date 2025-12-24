<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Detail Event: {{ $event->event_name }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            {{-- READ ONLY BANNER --}}
            @if($event->is_locked)
            <div class="bg-green-100 border-b border-green-200 text-green-800 px-6 py-3">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <span class="font-bold">Event Completed (Read-Only)</span>
                        <p class="text-xs">Event ini telah selesai. Data dikunci untuk tujuan arsip dan pelaporan.</p>
                    </div>
                </div>
            </div>
            @endif

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
              
              {{-- Tombol 3: Manage Tasks --}}
              <x-secondary-button tag="a" :href="route('events.tasks.index', $event)" class="bg-blue-50 text-blue-700 hover:bg-blue-100 border-blue-200">
                📋 Manage Tasks
              </x-secondary-button>
            </div>
          </div>
          <div class="p-6 text-gray-900 dark:text-gray-100">
            {{-- EVENT STATUS SECTION --}}
            <div class="mb-6 flex items-center justify-between">
              <div class="flex items-center gap-4">
                <h3 class="text-lg font-bold">Info Event</h3>
                {{-- Status Badge --}}
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $event->status_badge_color }}">
                  {{ $event->computed_status }}
                  @if($event->manual_status_override)
                    <span class="text-xs ml-1" title="Manual Override Active">⚙️</span>
                  @else
                    <span class="text-xs ml-1" title="Auto-Calculated">🤖</span>
                  @endif
                </span>
              </div>

              {{-- Manual Status Override (Admin/Owner only) --}}
              @hasanyrole('SuperUser|Owner|Admin')
              <div class="flex items-center gap-2">
                <form action="{{ route('events.updateStatus', $event) }}" method="POST" class="flex items-center gap-2">
                  @csrf
                  @method('PATCH')
                  <select name="status" 
                          class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg">
                    <option value="">Auto (Smart)</option>
                    <option value="Planning" {{ $event->status == 'Planning' && $event->manual_status_override ? 'selected' : '' }}>Planning</option>
                    <option value="Confirmed" {{ $event->status == 'Confirmed' && $event->manual_status_override ? 'selected' : '' }}>Confirmed</option>
                    <option value="Ongoing" {{ $event->status == 'Ongoing' && $event->manual_status_override ? 'selected' : '' }}>Ongoing</option>
                    <option value="Completed" {{ $event->status == 'Completed' && $event->manual_status_override ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ $event->status == 'Cancelled' && $event->manual_status_override ? 'selected' : '' }}>Cancelled</option>
                  </select>
                  <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Update Status
                  </button>
                </form>
              </div>
              @endhasanyrole
            </div>

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

        {{-- DAFTAR CREW / PANITIA --}}
        {{-- <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-bold">Tim / Crew Event</h3>
              <x-primary-button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'add-crew-modal')"
              >
                + Tambah Crew
              </x-primary-button> 
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead>
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role / Jabatan</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($event->crews as $crew)
                <tr>
                  <td class="px-6 py-4">{{ $crew->user->name }}</td>
                  <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                        {{ $crew->role }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <form action="{{ route('events.crew.destroy', [$event, $crew]) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus crew ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Remove</button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="3" class="px-6 py-4 text-center text-gray-500 text-sm">Belum ada crew ditugaskan.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div> --}}

        {{-- Modal Tambah Crew --}}
        <x-modal name="add-crew-modal" focusable>
            <form method="post" action="{{ route('events.crew.store', $event) }}" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Tambah Crew / Panitia') }}
                </h2>

                <div class="mt-6">
                    <x-input-label for="user_id" value="{{ __('Pilih Staff / User') }}" />
                    <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                        <option value="">-- Pilih User --</option>
                        @foreach($all_users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-6">
                    <x-input-label for="role" value="{{ __('Role / Jabatan (Ex: Project Manager)') }}" />
                    <x-text-input id="role" name="role" type="text" class="mt-1 block w-full" placeholder="Project Manager" required />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button class="ml-3">
                        {{ __('Simpan') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

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

        {{-- PRICING SECTION - CONDITIONAL BASED ON BOOKING METHOD --}}
        @if($event->isPackageBooking())
            {{-- Package Booking: Show only package total and included items (no individual prices) --}}
            <x-package-price-summary :event="$event" />
        @else
            {{-- Custom Booking: Show detailed vendor breakdown with all prices --}}
        {{-- DAFTAR VENDOR YANG DITUGASKAN - ENHANCED --}}
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Vendor Ditugaskan
              </h3>
              <div class="text-right">
                <p class="text-sm text-gray-500">Total Estimasi</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($vendorSummary['total'], 0, ',', '.') }}</p>
              </div>
            </div>

            {{-- Partner Vendors --}}
            @if(count($vendorSummary['vendors']) > 0)
            <div class="space-y-4 mb-6">
              @foreach ($vendorSummary['vendors'] as $vendor)
              <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-blue-300 transition bg-white dark:bg-gray-750" x-data="{ expanded: false }">
                {{-- Vendor Header --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                  <div class="flex items-center gap-3">
                    @if(count($vendor['items']) > 0)
                    <button @click="expanded = !expanded" class="text-gray-400 hover:text-blue-600 transition">
                      <svg x-show="!expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                      <svg x-show="expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                      </svg>
                    </button>
                    @endif
                            <div>
                      <h4 class="font-semibold text-gray-900 dark:text-white">{{ $vendor['name'] }}</h4>
                      <div class="flex items-center gap-2 mt-1">
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded">{{ $vendor['category'] }}</span>
                        @php
                          $sourceColors = [
                            'package' => 'bg-purple-100 text-purple-800',
                            'recommendation' => 'bg-green-100 text-green-800',
                            'client_choice' => 'bg-blue-100 text-blue-800',
                            'manual' => 'bg-gray-100 text-gray-800'
                          ];
                          $sourceLabels = [
                            'package' => '📦 Paket',
                            'recommendation' => '💡 Rekomendasi',
                            'client_choice' => '👤 Pilihan Client',
                            'manual' => '✏️ Manual'
                          ];
                        @endphp
                        <span class="px-2 py-0.5 text-xs font-medium rounded {{ $sourceColors[$vendor['source']] ?? 'bg-gray-100 text-gray-800' }}">
                          {{ $sourceLabels[$vendor['source']] ?? ucfirst($vendor['source']) }}
                        </span>
                        <span class="px-2 py-0.5 text-xs font-medium rounded {{ $vendor['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                          {{ ucfirst($vendor['status']) }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="text-right md:min-w-[140px]">
                    <p class="text-lg font-bold text-gray-900 dark:text-white">Rp {{ number_format($vendor['subtotal'], 0, ',', '.') }}</p>
                    @if(count($vendor['items']) > 0)
                    <p class="text-xs text-gray-500">{{ count($vendor['items']) }} item/add-on</p>
                    @endif
                  </div>
                </div>

                {{-- Expandable Items/Add-ons --}}
                @if(count($vendor['items']) > 0)
                <div x-show="expanded" x-collapse class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                  <p class="text-xs font-semibold text-gray-500 mb-2">📋 Detail Layanan / Add-ons:</p>
                  <div class="bg-gray-50 dark:bg-gray-800 rounded-lg overflow-hidden">
                    <table class="min-w-full text-sm">
                      <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                          <th class="text-left py-2 px-3 font-medium text-gray-600 dark:text-gray-400">Item</th>
                          <th class="text-center py-2 px-3 font-medium text-gray-600 dark:text-gray-400">Qty</th>
                          <th class="text-right py-2 px-3 font-medium text-gray-600 dark:text-gray-400">Harga</th>
                          <th class="text-right py-2 px-3 font-medium text-gray-600 dark:text-gray-400">Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($vendor['items'] as $item)
                        <tr class="border-b border-gray-100 dark:border-gray-700 last:border-0">
                          <td class="py-2 px-3">
                            <span class="font-medium">{{ $item['name'] }}</span>
                            @if($item['description'])
                            <p class="text-xs text-gray-500 mt-0.5">{{ $item['description'] }}</p>
                            @endif
                          </td>
                          <td class="py-2 px-3 text-center">{{ $item['quantity'] }}</td>
                          <td class="py-2 px-3 text-right">Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                          <td class="py-2 px-3 text-right font-medium">Rp {{ number_format($item['total_price'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                @endif
              </div>
              @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-8">Belum ada vendor ditugaskan.</p>
            @endif

            {{-- External Vendors --}}
            @if(count($vendorSummary['external_vendors']) > 0)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
              <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">🏪 Vendor Eksternal</h4>
              <div class="space-y-2">
                @foreach($vendorSummary['external_vendors'] as $ext)
                <div class="flex items-center justify-between p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                  <div>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $ext['name'] }}</span>
                    <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-medium rounded">{{ $ext['category'] }}</span>
                    @if($ext['notes'])
                    <p class="text-xs text-gray-500 mt-1">{{ $ext['notes'] }}</p>
                    @endif
                  </div>
                  <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($ext['price'], 0, ',', '.') }}</span>
                </div>
                @endforeach
              </div>
            </div>
            @endif

            {{-- Non-Partner Charges --}}
            @if(count($vendorSummary['non_partner_charges']) > 0)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
              <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">💳 Biaya Tambahan</h4>
              <div class="space-y-2">
                @foreach($vendorSummary['non_partner_charges'] as $charge)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                  <span class="text-gray-700 dark:text-gray-300">{{ $charge['description'] }}</span>
                  <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($charge['amount'], 0, ',', '.') }}</span>
                </div>
                @endforeach
              </div>
            </div>
            @endif

            {{-- Summary Footer --}}
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
              <div class="flex justify-end">
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 min-w-[200px]">
                  <p class="text-sm text-gray-600 dark:text-gray-400">Total Semua Vendor</p>
                  <p class="text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($vendorSummary['total'], 0, ',', '.') }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endif {{-- End conditional pricing display --}}

        {{-- TIM / CREW EVENT SECTION --}}
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <h3 class="text-lg font-bold mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              Tim / Crew Event
            </h3>

            @if($event->crews->count() > 0)
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($event->crews as $crew)
                  <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                    <div class="flex items-start gap-3">
                      <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                      </div>
                      <div class="flex-1">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $crew->user->name }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $crew->role }}</p>
                        @if($crew->user->email)
                          <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $crew->user->email }}</p>
                        @endif
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Belum ada crew ditugaskan.</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Staff akan otomatis ditambahkan saat event dibuat dari Client Request yang sudah di-assign.</p>
              </div>
            @endif
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