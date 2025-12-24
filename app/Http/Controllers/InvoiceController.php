<?php

namespace App\Http\Controllers;

use App\Notifications\VoucherInvalidatedNotification;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\Voucher;
use App\Exports\InvoiceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class InvoiceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the user's invoices.
     */
    public function index()
    {
        $user = auth()->user();
        
        $query = Invoice::with('event');

        // Jika user bukan Admin/Owner/SuperUser, batasi hanya invoice miliknya
        if (!$user->hasRole(['Admin', 'Owner', 'SuperUser'])) {
             $query->whereHas('event', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        $invoices = $query->latest()->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Tampilkan halaman detail invoice
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->refresh();

        return view('invoices.show', compact('invoice'));
    }

    public function export(Invoice $invoice, $format)
    {
        $this->authorize('view', $invoice);

        $sanitizedInvoiceNumber = preg_replace('/[\/\\\?\*$$  $$\:<>|]/', '_', $invoice->invoice_number);
        $filename = 'invoice-' . $invoice->id . '-' . $sanitizedInvoiceNumber . '-' . now()->format('Y-m-d') . '.' . $format;

        try {
            return match ($format) {
                'pdf' => \Maatwebsite\Excel\Facades\Excel::download(new InvoiceExport($invoice), $filename, \Maatwebsite\Excel\Excel::DOMPDF),
                'xls' => \Maatwebsite\Excel\Facades\Excel::download(new InvoiceExport($invoice), $filename, \Maatwebsite\Excel\Excel::XLS),
                'docx' => $this->exportToWord($invoice),
                default => back()->with('error', 'Format ekspor tidak didukung.'),
            };
        } catch (\Exception $e) {
            // Log only the error without exposing sensitive details
            \Log::error('Invoice export failed', [
                'invoice_id' => $invoice->id,
                'format' => $format,
                'error' => \get_class($e)
            ]);
            return back()->with('error', 'Gagal ekspor: proses gagal.');
        }
    }

    private function exportToWord(Invoice $invoice)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        $html = view('invoices.export-word', compact('invoice'))->render();

        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

        // Sanitasi nama file untuk menghindari karakter invalid
        $sanitizedInvoiceNumber = preg_replace('/[\/\\\?\*$$  $$\:<>|]/', '_', $invoice->invoice_number);
        $filename = 'invoice-' . $invoice->id . '-' . $sanitizedInvoiceNumber . '.docx';

        // Tambahkan ini: Buat writer untuk Word2007 (DOCX)
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path($filename));

        return response()->download(storage_path($filename))->deleteFileAfterSend(true);
    }

    public function applyVoucher(Request $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $invoice->load('event');

        $request->validate([
            'voucher_code' => 'required|string|exists:vouchers,code'
        ]);

        $voucher = Voucher::where('code', $request->voucher_code)->first();

        if (!$voucher) {
            return back()->with('error', 'Kode voucher tidak valid.');
        }
        if ($voucher->status != 'active') {
            return back()->with('error', 'Voucher ini sudah tidak aktif atau dibatalkan.');
        }
        if ($voucher->expires_at && $voucher->expires_at < now()) {
            return back()->with('error', 'Voucher sudah kadaluarsa.');
        }
        if ($invoice->voucher_id) {
            return back()->with('error', 'Faktur ini sudah memiliki voucher yang diterapkan.');
        }

        $discountAmount = 0;
        if ($voucher->type == 'percentage') {
            $discountAmount = ($invoice->total_amount * $voucher->value) / 100;
        } else {
            $discountAmount = $voucher->value;
        }

        if ($discountAmount > $invoice->total_amount) {
            $discountAmount = $invoice->total_amount;
        }

        $invoice->update([
            'voucher_id' => $voucher->id,
            'voucher_discount_amount' => $discountAmount,
            'original_total_before_voucher' => $invoice->total_amount,
            'voucher_invalidated_at' => null,  // Reset invalidated_at when applying new voucher
            'voucher_invalidation_reason' => null,  // Also reset the reason
        ]);

        $invoice->refresh();
        if ($invoice->balance_due <= 0) {
            $invoice->update(['status' => 'Paid']);
        }

        return back()->with('success', 'Voucher berhasil diterapkan.');
    }

    public function invalidateVoucher(Request $request, Invoice $invoice)
    {
        $this->authorize('invalidateVoucher', $invoice); // Using the specific policy method.

        $invoice->load(['voucher', 'event.user']);

        // PENTING: Validasi request
        $request->validate([
            'invalidation_reason' => 'required|string|max:255',
        ], [
            'invalidation_reason.required' => 'Alasan pembatalan voucher harus diisi.',
            'invalidation_reason.max' => 'Alasan pembatalan voucher terlalu panjang (maksimal 255 karakter).',
        ]);

        $reason = $request->input('invalidation_reason');

        DB::beginTransaction();

        try {
            // Simpan state sebelumnya
            $previousVoucherTotal = $invoice->voucher_discount_amount ?? 0;
            $oldStatus = $invoice->status;

            // PENTING: Reset voucher fields dengan benar
            $invoice->update([
                'voucher_id' => null,
                'voucher_discount_amount' => 0,
                'original_total_before_voucher' => null,  // Reset juga field ini
                'voucher_invalidation_reason' => $reason,
                'voucher_invalidated_at' => now(),
            ]);

            // Recalculate status
            if ($oldStatus === 'Paid' && $invoice->balance_due > 0) {
                $invoice->update(['status' => 'Underpaid']);
            } elseif ($oldStatus === 'Underpaid' && $invoice->balance_due <= 0) {
                $invoice->update(['status' => 'Paid']);
            }

            // Kirim notifikasi
            if ($invoice->event && $invoice->event->user) {
                $invoice->event->user->notify(new VoucherInvalidatedNotification($invoice->refresh(), $reason));
            } elseif ($invoice->user) {
                $invoice->user->notify(new VoucherInvalidatedNotification($invoice->refresh(), $reason));
            }

            DB::commit();

            // PENTING: Refresh sebelum redirect
            $invoice->refresh();

            return redirect()->route('invoice.show', $invoice->id)->with('success', 'Voucher berhasil dibatalkan dari faktur dan pelanggan telah diberitahu.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error invalidating voucher for invoice', [
                'invoice_id' => $invoice->id,
                'error' => \get_class($e)
            ]);
            return redirect()->route('invoice.show', $invoice->id)
                ->with('error', 'Gagal membatalkan voucher. Silakan coba lagi.');
        }
    }

    /**
     * Preview invoice for printing
     */
    public function preview(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->refresh();

        return view('invoices.preview', compact('invoice'));
    }
}
