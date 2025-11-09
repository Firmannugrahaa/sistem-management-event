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
use App\Http\Controllers\TeamController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Team Management
    Route::resource('team', TeamController::class)->only(['index', 'create', 'store', 'destroy'])->parameters(['team' => 'member']);

    // User-facing Invoice History
    Route::get('/my-invoices', [InvoiceController::class, 'index'])->name('invoices.index');

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
use App\Http\Controllers\SuperUserPermissionController;

Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

// SuperUser permission management routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('superuser')->name('superuser.')->middleware('role:SuperUser')->group(function () {
        Route::get('/permissions', [SuperUserPermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [SuperUserPermissionController::class, 'update'])->name('permissions.update');
        
        // User management
        Route::get('/users', [App\Http\Controllers\SuperUser\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\SuperUser\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\SuperUser\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [App\Http\Controllers\SuperUser\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\SuperUser\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\SuperUser\UserController::class, 'destroy'])->name('users.destroy');
        
        // Roles management
        Route::get('/roles', [App\Http\Controllers\SuperUser\RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [App\Http\Controllers\SuperUser\RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [App\Http\Controllers\SuperUser\RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [App\Http\Controllers\SuperUser\RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [App\Http\Controllers\SuperUser\RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [App\Http\Controllers\SuperUser\RoleController::class, 'destroy'])->name('roles.destroy');
        
        // Dashboard overview
        Route::get('/dashboard', [App\Http\Controllers\SuperUser\DashboardController::class, 'index'])->name('dashboard.index');
        
        // Invoice management
        Route::get('/invoices', [App\Http\Controllers\SuperUser\InvoiceController::class, 'index'])->name('invoices.index');
    });
});

require __DIR__.'/auth.php';
