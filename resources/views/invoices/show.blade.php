<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Invoice: {{ $invoice->invoice_number }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

      {{-- 1. RINGKASAN INVOICE --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-lg font-bold mb-4">Ringkasan Tagihan</h3>
          <div class="grid grid-cols-3 gap-4">
            <div>
              <p class="text-sm text-gray-500">Total Tagihan</p>
              <p class="text-2xl font-bold">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Telah Dibayar</p>
              <p class="text-2xl font-bold text-green-600">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Sisa Tagihan</p>
              <p class="text-2xl font-bold text-red-600">Rp {{ number_format($invoice->balance_due, 0, ',', '.') }}</p>
            </div>
          </div>
          <hr class="my-4 dark:border-gray-700">
          <p><strong>Event:</strong> {{ $invoice->event->event_name }}</p>
          <p><strong>Status:</strong> <span class="font-bold {{ $invoice->status == 'Paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ $invoice->status }}</span></p>
          <p><strong>Jatuh Tempo:</strong> {{ $invoice->due_date }}</p>
        </div>
      </div>

      {{-- 2. FORM CATAT PEMBAYARAN --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-lg font-bold mb-4">Catat Pembayaran Baru</h3>

          <form action="{{ route('payments.store', $invoice) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ">
              <div>
                <x-input-label for="amount" :value="__('Jumlah (Rp)')" />
                <x-text-input id="amount" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="number" name="amount" :value="old('amount')" required />
              </div>
              <div>
                <x-input-label for="payment_date" :value="__('Tanggal Bayar')" />
                <x-text-input id="payment_date" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="date" name="payment_date" :value="today()->toDateString()" required />
              </div>
              <div>
                <x-input-label for="payment_method" :value="__('Metode Bayar')" />
                <select name="payment_method" id="payment_method" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                  <option>Bank Transfer</option>
                  <option>Cash</option>
                  <option>Lainnya</option>
                </select>
              </div>
            </div>
            <div class="mt-4">
              <x-input-label for="notes" :value="__('Catatan (Cth: DP 30%)')" />
              <x-text-input id="notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="notes" :value="old('notes')" />
            </div>
            <x-primary-button type="submit" class="mt-4">
              Simpan Pembayaran
            </x-primary-button>
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
                  <form action="{{ route('payments.destroy', $payment) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="submit" onclick="return confirm('Yakin hapus catatan ini?')">
                      Hapus
                    </x-danger-button>
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