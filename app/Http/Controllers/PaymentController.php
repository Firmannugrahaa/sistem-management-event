<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Simpan catatan pembayaran baru
     */
    public function store(Request $request, Invoice $invoice)
    {
        $balanceDue = $invoice->balance_due;

        if ($balanceDue <= 0) {
            return back()->with('error', ' Tagihan sudah lunas, Tidak bisa mencatat pembayaran');
        }


        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                // INI ADALAH PERBAIKANNYA:
                'max:' . $balanceDue
            ],
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ], [
            'amount.max' => 'Jumlah pembayaran tidak boleh melebihi sisa tagihan (Rp ' . number_format($balanceDue, 0, ',', '.') . ').'
        ]);

        // Tambahkan invoice_id
        $validated['invoice_id'] = $invoice->id;
        $validated['status'] = 'Verified';
        Payment::create($validated);

        // Update status invoice (logika sederhana)
        $newBalance = $invoice->refresh()->balance_due; // Refresh relasi

        $invoice->load('payments');

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
