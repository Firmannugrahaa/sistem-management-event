<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('venues', VenueController::class);
    Route::resource('events', EventController::class)->except(['show']);
    Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('events/{event}/guests/import', [GuestController::class, 'showImportForm'])->name('events.guests.import.form');
    Route::get('/events/{event}/guests/{guest}/edit', [GuestController::class, 'edit'])
        ->name('events.guests.edit');
    Route::patch('/events/{event}/guests/{guest}', [GuestController::class, 'update'])
        ->name('events.guests.update');
    Route::post('events/{event}/guests/import', [GuestController::class, 'import'])->name('events.guests.import');
    Route::resource('events.guests', GuestController::class)->except(['index']);
    Route::post('events/{event}/assign-vendor', [EventController::class, 'assignVendor'])->name('events.assignVendor');
    Route::delete('/events/{event}/vendors/{vendor}', [EventController::class, 'detachVendor'])
        ->name('events.detachVendor');
    Route::resource('vendors', VendorController::class);

    // --- INVOICE & PAYMENT ROUTES ---

    // 1. Route untuk trigger generate/update invoice
    Route::post('events/{event}/generate-invoice', [EventController::class, 'generateInvoice'])
        ->name('events.generateInvoice');

    // 2. Route untuk menampilkan halaman invoice
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])
        ->name('invoice.show');

    // 3. Route untuk menyimpan catatan pembayaran baru
    Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])
        ->name('payments.store');

    // 4. Route untuk menghapus catatan pembayaran
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])
        ->name('payments.destroy');

    //5. Route untuk voucher
    Route::resource('vouchers', VoucherController::class);
    //6. Route untuk apply voucher
    Route::post('invoices/{invoice}/apply-voucher', [InvoiceController::class, 'applyVoucher'])
        ->name('invoice.applyVoucher');
});
Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

require __DIR__ . '/auth.php';
