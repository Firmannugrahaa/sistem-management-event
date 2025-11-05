<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Voucher;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Tampilkan halamatotal_amountn detail invoice
     */
    public function show(Invoice $invoice)
    {
        // Load relasi event dan payments
        $invoice->load('event', 'payments');

        // Kirim data ke view
        return view('invoices.show', compact('invoice'));
    }
    public function applyVoucher(Request $request, Invoice $invoice)
    {
        $request->validate([
            'voucher_code' => 'required|string|exists:vouchers,code'
        ]);

        // 1. Cari voucher
        $voucher = Voucher::where('code', $request->voucher_code)->first();

        // 2. Validasi (opsional: cek kadaluarsa, batas pakai, dll)
        if (!$voucher) {
            return back()->with('error', 'Kode voucher tidak valid.');
        }
        if ($voucher->status != 'active') {
            return back()->with('error', 'Voucher ini sudah tidak aktif atau dibatalkan.');
        }
        if ($voucher->expires_at && $voucher->expires_at < now()) {
            return back()->with('error', 'Voucher sudah kadaluarsa.');
        }


        // 3. Hitung diskon
        $discountAmount = 0;
        if ($voucher->type == 'percentage') {
            $discountAmount = ($invoice->total_amount * $voucher->value) / 100;
        } else { // type == 'fixed'
            $discountAmount = $voucher->value;
        }

        // Jangan sampai diskon > total tagihan
        if ($discountAmount > $invoice->total_amount) {
            $discountAmount = $invoice->total_amount;
        }

        // 4. Update Invoice
        $invoice->update([
            'discount_amount' => $discountAmount
        ]);

        // 5. Update status (jika diskon membuat lunas)
        if ($invoice->balance_due <= 0) {
            $invoice->update(['status' => 'Paid']);
        }

        return back()->with('success', 'Voucher berhasil diterapkan.');
    }
}
