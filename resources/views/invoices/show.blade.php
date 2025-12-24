<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Invoice: {{ $invoice->invoice_number }}
      </h2>

      <div class="flex space-x-2">
        <a href="{{ route('invoices.preview', $invoice) }}" target="_blank"
          class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
          Preview
        </a>
        <a href="{{ route('invoices.export', ['invoice' => $invoice, 'format' => 'pdf']) }}"
          class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          PDF
        </a>
        <a href="{{ route('invoices.export', ['invoice' => $invoice, 'format' => 'xls']) }}"
          class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          XLS
        </a>
        <a href="{{ route('invoices.export', ['invoice' => $invoice, 'format' => 'docx']) }}"
          class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          DOCX
        </a>
      </div>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

      {{-- 1. RINGKASAN INVOICE --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-lg font-bold mb-4">Ringkasan Tagihan</h3>
          @php
              // Calculate correct total based on booking type
              $event = $invoice->event;
              $isPackageBooking = $event->isPackageBooking();
              $calculatedTotal = 0;
              
              if($isPackageBooking) {
                  $package = $event->eventPackage;
                  $calculatedTotal = $package?->final_price ?? 0;
              } else {
                  // Sum vendor costs
                  $calculatedTotal = $event->vendors->sum('pivot.agreed_price');
                  // Add non-partner charges
                  if($event->clientRequest && $event->clientRequest->nonPartnerCharges) {
                      $calculatedTotal += $event->clientRequest->nonPartnerCharges->sum('charge_amount');
                  }
              }
          @endphp
          <div class="grid grid-cols-4 gap-4">
            <div>
              <p class="text-sm text-gray-500">Total Tagihan Awal</p>
              <p class="text-2xl font-bold">Rp {{ number_format($calculatedTotal, 0, ',', '.') }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Diskon (Voucher)</p>
              <p class="text-2xl font-bold text-yellow-500">- Rp {{ number_format($invoice->voucher_discount_amount ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Telah Dibayar</p>
              <p class="text-2xl font-bold text-green-600">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Sisa Tagihan</p>
              <p class="text-2xl font-bold text-red-600">Rp {{ number_format(($calculatedTotal - ($invoice->voucher_discount_amount ?? 0) - $invoice->paid_amount), 0, ',', '.') }}</p>
            </div>
          </div>
          <hr class="my-4 dark:border-gray-700">
          <p><strong>Event:</strong> {{ $invoice->event->event_name }}</p>
          <p><strong>Lokasi:</strong> {{ $invoice->event->venue->name ?? $invoice->event->location }}</p>
          <p><strong>Tanggal:</strong> {{ $invoice->event->date }}</p>
          <p><strong>Status:</strong> <span class="font-bold {{ $invoice->status == 'Paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ $invoice->status }}</span></p>
          <p><strong>Jatuh Tempo:</strong> {{ $invoice->due_date }}</p>
        </div>
      </div>

      {{-- 1b. NON-PARTNER VENDOR CHARGES BREAKDOWN --}}
      @if($invoice->event->nonPartnerCharges && $invoice->event->nonPartnerCharges->count() > 0)
      <div class="bg-yellow-50 dark:bg-yellow-900/20 overflow-hidden shadow-sm sm:rounded-lg border border-yellow-200 dark:border-yellow-800">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-lg font-bold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            Biaya Vendor Non-Rekanan
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Biaya tambahan untuk penggunaan vendor di luar rekanan kami.
          </p>
          <table class="min-w-full divide-y divide-yellow-200 dark:divide-yellow-700">
            <thead>
              <tr class="bg-yellow-100 dark:bg-yellow-800/30">
                <th class="px-4 py-2 text-left text-xs font-medium text-yellow-800 dark:text-yellow-200 uppercase">Kategori</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-yellow-800 dark:text-yellow-200 uppercase">Nama Vendor</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-yellow-800 dark:text-yellow-200 uppercase">Kontak</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-yellow-800 dark:text-yellow-200 uppercase">Biaya Charge</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-yellow-100 dark:divide-yellow-700/50">
              @foreach($invoice->event->nonPartnerCharges as $charge)
              <tr>
                <td class="px-4 py-3 text-sm">{{ $charge->service_type }}</td>
                <td class="px-4 py-3 text-sm font-medium">{{ $charge->vendor_name }}</td>
                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $charge->vendor_contact ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-right font-semibold">Rp {{ number_format($charge->charge_amount, 0, ',', '.') }}</td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr class="bg-yellow-100 dark:bg-yellow-800/30">
                <td colspan="3" class="px-4 py-2 text-sm font-bold text-right">Total Biaya Vendor Non-Rekanan:</td>
                <td class="px-4 py-2 text-sm font-bold text-right text-yellow-700 dark:text-yellow-300">
                  Rp {{ number_format($invoice->event->nonPartnerCharges->sum('charge_amount'), 0, ',', '.') }}
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      @endif

      {{-- Menampilkan error atau pesan sukses --}}
      @if (session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
      </div>
      @endif

      @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
      </div>
      @endif

      {{-- Pesan jika voucher dibatalkan - hanya muncul sesaat setelah pembatalan --}}
      @if (session('success') && str_contains(session('success'), 'Voucher berhasil dibatalkan'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Pemberitahuan:</strong>
        <span class="block sm:inline">Voucher yang sebelumnya diterapkan pada faktur ini telah dibatalkan.</span>
        <p class="text-sm mt-1">Alasan: "{{ $invoice->voucher_invalidation_reason }}" pada {{ $invoice->voucher_invalidated_at->format('d M Y H:i') }}.</p>
        <p class="text-sm mt-1">Total tagihan Anda telah disesuaikan.</p>
      </div>
      @endif

      {{-- Debug info --}}
      <!-- 
      Debug: voucher_id={{ $invoice->voucher_id ?? 'null' }}, 
      voucher_invalidated_at={{ $invoice->voucher_invalidated_at ? 'not null' : 'null' }},
      voucher_discount_amount={{ $invoice->voucher_discount_amount ?? 0 }}
      -->

      @if ($invoice->voucher_id && !$invoice->voucher_invalidated_at)
      {{-- Jika sudah ada voucher dan belum dibatalkan --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-lg font-bold mb-4">Detail Voucher Diterapkan</h3>
          <p class="text-green-600 font-medium">
            Voucher <strong>{{ $invoice->voucher ? $invoice->voucher->code : 'Tidak diketahui' }}</strong> telah diterapkan.
            (Diskon Rp {{ number_format($invoice->voucher_discount_amount, 0, ',', '.') }})
          </p>
          @if($invoice->voucher)
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
            Tipe: {{ $invoice->voucher->type == 'fixed' ? 'Potongan Tetap' : 'Persentase' }} ({{ $invoice->voucher->value }}{{ $invoice->voucher->type == 'percentage' ? '%' : '' }})
          </p>
          @endif

          {{-- Tombol Batalkan Voucher --}}
          <div class="mt-4">
            <button
              type="button"
              @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', {
                  detail: {
                      formId: 'invalidate-voucher-form-{{ $invoice->id }}',
                      title: 'Batalkan Voucher',
                      message: 'Apakah Anda yakin ingin membatalkan voucher ini dari faktur? Tindakan ini akan menghapus diskon dan mungkin mengubah status pembayaran faktur.',
                      needsReason: true,
                      reasonLabel: 'Alasan Pembatalan',
                      reasonPlaceholder: 'Cth: Voucher tidak valid, kesalahan input, dll.',
                      reasonFieldName: 'invalidation_reason'
                  }
              }))"
              class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
              Batalkan Voucher
            </button>
          </div>

          {{-- Form tersembunyi untuk aksi pembatalan voucher --}}
          <form id="invalidate-voucher-form-{{ $invoice->id }}" action="{{ route('invoice.invalidateVoucher', $invoice) }}" method="POST" class="hidden needs-confirmation">
            @csrf
            <input type="hidden" name="invalidation_reason" id="invalidation_reason" value="">
          </form>
        </div>
      </div>
      @elseif($invoice->voucher_invalidated_at)
      {{-- Jika voucher pernah diterapkan tetapi sudah dibatalkan - tampilkan form voucher kembali --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-lg font-bold mb-4">Masukkan Kode Voucher Baru</h3>
          <form id="apply-voucher-form-{{ $invoice->id }}" action="{{ route('invoice.applyVoucher', $invoice) }}" method="POST" class="needs-confirmation" data-confirmation-title="Terapkan Voucher" data-confirmation-message="Apakah Anda yakin ingin menerapkan voucher ini ke faktur?">
            @csrf
            <div class="flex items-center gap-2">
              <x-text-input id="voucher_code" class="block mt-1 w-full" type="text" name="voucher_code" placeholder="Masukkan kode voucher..." required />
              <x-primary-button type="submit">
                Terapkan
              </x-primary-button>
            </div>
            @if ($errors->has('voucher_code'))
            <x-input-error :messages="$errors->get('voucher_code')" class="mt-2" />
            @endif
          </form>
        </div>
      </div>
      @else
      {{-- Jika belum ada voucher sama sekali --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-lg font-bold mb-4">Punya Kode Voucher?</h3>
          <form id="apply-voucher-form-{{ $invoice->id }}" action="{{ route('invoice.applyVoucher', $invoice) }}" method="POST" class="needs-confirmation" data-confirmation-title="Terapkan Voucher" data-confirmation-message="Apakah Anda yakin ingin menerapkan voucher ini ke faktur?">
            @csrf
            <div class="flex items-center gap-2">
              <x-text-input id="voucher_code" class="block mt-1 w-full" type="text" name="voucher_code" placeholder="Masukkan kode..." required />
              <x-primary-button type="submit">
                Terapkan
              </x-primary-button>
            </div>
            @if ($errors->has('voucher_code'))
            <x-input-error :messages="$errors->get('voucher_code')" class="mt-2" />
            @endif
          </form>
        </div>
      </div>
      @endif

      {{-- 2. FORM CATAT PEMBAYARAN --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-lg font-bold mb-4">Catat Pembayaran Baru</h3>

          <form id="payment-form-{{ $invoice->id }}" action="{{ route('payments.store', $invoice) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ">
              <div>
                <x-input-label for="amount" :value="__('Jumlah (Rp)')" />
                <x-text-input id="amount" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="number" name="amount" value="{{ old('amount') }}" required />
              </div>
              <div>
                <x-input-label for="payment_date" :value="__('Tanggal Bayar')" />
                <x-text-input id="payment_date" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="date" name="payment_date" value="{{ date('Y-m-d') }}" required />
              </div>
              <div>
                <x-input-label for="payment_method" :value="__('Metode Bayar')" />
                <select name="payment_method" id="payment_method" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                  <option value="Bank Transfer">Bank Transfer</option>
                  <option value="Cash">Cash</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
              </div>
            </div>
            <div class="mt-4">
              <x-input-label for="notes" :value="__('Catatan (Cth: DP 30%)')" />
              <x-text-input id="notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="notes" value="{{ old('notes') }}" />
            </div>
            <button type="submit" 
                    class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
              Simpan Pembayaran
            </button>
          </form>
        </div>
      </div>

      {{-- 3. RIWAYAT PEMBAYARAN --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-lg font-bold mb-4">Riwayat Pembayaran</h3>
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left ...">Tanggal</th>
                <th class="px-6 py-3 text-left ...">Jumlah</th>
                <th class="px-6 py-3 text-left ...">Metode</th>
                <th class="px-6 py-3 text-left ...">Catatan</th>
                <th class="px-6 py-3 text-right ...">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              @forelse ($invoice->payments->sortByDesc('payment_date') as $payment)
              <tr>
                <td class="px-6 py-4">{{ $payment->payment_date }}</td>
                <td class="px-6 py-4">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td class="px-6 py-4">{{ $payment->payment_method }}</td>
                <td class="px-6 py-4">{{ $payment->notes }}</td>
                <td class="px-6 py-4 text-right">
                  {{-- Tombol Hapus dengan konfirmasi modal global --}}
                  <button
                    type="button"
                    @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', {
                        detail: {
                            formId: 'delete-form-payment-{{ $payment->id }}',
                            title: 'Konfirmasi Hapus Pembayaran',
                            message: 'Apakah Anda yakin ingin menghapus catatan pembayaran ini? Tindakan ini tidak dapat dibatalkan.'
                        }
                    }))"
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 inline-block">
                    Hapus
                  </button>

                  {{-- Form tersembunyi untuk aksi hapus --}}
                  <form id="delete-form-payment-{{ $payment->id }}" action="{{ route('payments.destroy', $payment) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="px-6 py-4 text-center">Belum ada catatan pembayaran.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</x-app-layout>