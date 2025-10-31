<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Simpan catatan pembayaran baru
     */
    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Tambahkan invoice_id
        $validated['invoice_id'] = $invoice->id;

        Payment::create($validated);

        // Update status invoice (logika sederhana)
        $invoice->load('payments'); // Refresh relasi
        if ($invoice->balance_due <= 0) {
            $invoice->update(['status' => 'Paid']);
        } else {
            $invoice->update(['status' => 'Partially Paid']);
        }

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    /**
     * Hapus catatan pembayaran
     */
    public function destroy(Payment $payment)
    {
        // Simpan invoice ID sebelum dihapus
        $invoice = $payment->invoice;

        $payment->delete();

        // Hitung ulang status invoice
        $invoice->load('payments');
        if ($invoice->balance_due <= 0) {
            $invoice->update(['status' => 'Paid']);
        } elseif ($invoice->paid_amount > 0) {
            $invoice->update(['status' => 'Partially Paid']);
        } else {
            $invoice->update(['status' => 'Unpaid']);
        }

        return back()->with('success', 'Catatan pembayaran dihapus.');
    }
}
