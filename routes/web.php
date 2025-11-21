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
use App\Http\Controllers\TeamVendorController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\TemanHalalController;
use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing.page');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Forced Password Change
    Route::get('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'create'])->name('password.change');
    Route::post('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'store'])->name('password.change.update');

    // Team Management (Redirected to consolidated view)
    Route::get('team', function () {
        return redirect()->route('team-vendor.index', ['view' => 'team']);
    })->name('team.index');
    Route::get('team/{member}/edit', [TeamController::class, 'edit'])->name('team.edit');
    Route::put('team/{member}', [TeamController::class, 'update'])->name('team.update');
    Route::delete('team/{member}', [TeamController::class, 'destroy'])->name('team.destroy');
    Route::get('team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('team', [TeamController::class, 'store'])->name('team.store');
    Route::post('team/{member}/approve', [TeamController::class, 'approveUser'])->name('team.approve')->middleware('can:user.approve');
    Route::delete('team/{member}/reject', [TeamController::class, 'rejectUser'])->name('team.reject')->middleware('can:user.approve');

    Route::get('team/vendors/create', [TeamController::class, 'createVendor'])->name('team.vendors.create');
    Route::post('team/vendors', [TeamController::class, 'storeVendor'])->name('team.vendors.store');
    Route::post('vendor/{user}/approve', [TeamController::class, 'approveVendor'])->name('vendor.approve')->middleware('can:vendor.approve');
    Route::delete('vendor/{user}/reject', [TeamController::class, 'rejectVendor'])->name('vendor.reject')->middleware('can:vendor.approve');

    // Combined Team and Vendor Management
    Route::get('/manage-team-vendor', [TeamVendorController::class, 'index'])->name('team-vendor.index');

    // User-facing Invoice History
    Route::get('/my-invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    Route::resource('venues', VenueController::class);
    Route::resource('events', EventController::class)->except(['show']);
    Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('events/{event}/guests/import', [GuestController::class, 'showImportForm'])->name('events.guests.import.form');
    Route::resource('events.guests', GuestController::class)->except(['index']);
    Route::post('events/{event}/guests/import', [GuestController::class, 'import'])->name('events.guests.import');
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

    // Route for exporting invoices
    Route::get('invoices/{invoice}/export/{format}', [InvoiceController::class, 'export'])
        ->name('invoices.export');

    // Route for previewing invoices
    Route::get('invoices/{invoice}/preview', [InvoiceController::class, 'preview'])
        ->name('invoices.preview');

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

    //7. Route untuk membatalkan voucher dari invoice
    Route::post('invoices/{invoice}/invalidate-voucher', [InvoiceController::class, 'invalidateVoucher'])
        ->name('invoice.invalidateVoucher');
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

    // Company Settings - accessible to both SuperUser and Owner roles
    Route::middleware(['auth', 'can:access-settings'])->prefix('superuser')->name('superuser.')->group(function () {
        Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    });

    // API routes for location dropdowns using Indonesian address API
    Route::middleware(['auth'])->group(function () {
        Route::get('/api/provinces', [App\Http\Controllers\ApiAddressController::class, 'getProvinces']);
        Route::get('/api/cities/{provinceId}', [App\Http\Controllers\ApiAddressController::class, 'getCitiesByProvince']);
        Route::get('/api/districts/{cityId}', [App\Http\Controllers\ApiAddressController::class, 'getDistrictsByCity']);
        Route::get('/api/villages/{districtId}', [App\Http\Controllers\ApiAddressController::class, 'getVillagesByDistrict']);
        Route::get('/api/search', [App\Http\Controllers\ApiAddressController::class, 'searchLocation']);
    });
});

require __DIR__ . '/auth.php';
