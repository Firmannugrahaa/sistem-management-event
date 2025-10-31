<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Tampilkan halaman detail invoice
     */
    public function show(Invoice $invoice)
    {
        // Load relasi event dan payments
        $invoice->load('event', 'payments');

        // Kirim data ke view
        return view('invoices.show', compact('invoice'));
    }
}
