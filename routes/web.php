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

// Public Vendor Profile
Route::get('/vendor/{id}/profile', [\App\Http\Controllers\VendorBusinessProfileController::class, 'show'])
    ->name('vendor.profile.show');

// Public Booking Form Routes (No Auth Required)
Route::get('/book-now', [\App\Http\Controllers\Public\PublicBookingController::class, 'showForm'])
    ->name('public.booking.form');
Route::post('/book-now', [\App\Http\Controllers\Public\PublicBookingController::class, 'storeBooking'])
    ->name('public.booking.store');


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Client landing page route (vendor and venue selection)
Route::get('/client/landing', [App\Http\Controllers\Client\ClientLandingController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:Client'])->name('client.landing');

// Client order review route
Route::get('/client/order/review', [App\Http\Controllers\Client\ClientOrderController::class, 'review'])
    ->middleware(['auth', 'verified', 'role:Client'])->name('client.order.review');

// Client order selections route
Route::post('/client/order/store-selections', [App\Http\Controllers\Client\ClientOrderController::class, 'storeSelections'])
    ->middleware(['auth', 'verified', 'role:Client'])->name('client.order.store.selections');

// Client order confirmation route
Route::post('/client/order/confirm', [App\Http\Controllers\Client\ClientOrderController::class, 'confirm'])
    ->middleware(['auth', 'verified', 'role:Client'])->name('client.order.confirm');

// Client dashboard route
Route::get('/client/dashboard', [App\Http\Controllers\Client\ClientDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:Client'])->name('client.dashboard');

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

    // Client Requests / Leads Management
    Route::resource('client-requests', App\Http\Controllers\ClientRequestController::class);
    Route::post('client-requests/{clientRequest}/update-status', [App\Http\Controllers\ClientRequestController::class, 'updateStatus'])
        ->name('client-requests.update-status');
    Route::post('client-requests/{clientRequest}/assign-staff', [App\Http\Controllers\ClientRequestController::class, 'assignStaff'])
        ->name('client-requests.assign-staff');
    Route::get('client-requests/{clientRequest}/convert-to-event', [App\Http\Controllers\ClientRequestController::class, 'convertToEvent'])
        ->name('client-requests.convert-to-event');

    // Recommendation System
    Route::get('client-requests/{clientRequest}/recommendations/create', [App\Http\Controllers\RecommendationController::class, 'create'])
        ->name('recommendations.create');
    Route::post('client-requests/{clientRequest}/recommendations', [App\Http\Controllers\RecommendationController::class, 'store'])
        ->name('recommendations.store');
    Route::get('recommendations/{recommendation}', [App\Http\Controllers\RecommendationController::class, 'show'])
        ->name('recommendations.show');
    Route::post('recommendations/{recommendation}/send', [App\Http\Controllers\RecommendationController::class, 'send'])
        ->name('recommendations.send');

    // Trash Management (SuperUser Only)
    Route::middleware('role:SuperUser')->prefix('admin/trash')->name('admin.trash.')->group(function () {
        Route::get('/', [App\Http\Controllers\TrashController::class, 'index'])->name('index');
        Route::get('/client-requests', [App\Http\Controllers\TrashController::class, 'clientRequests'])->name('client-requests');
        Route::post('/client-requests/{id}/restore', [App\Http\Controllers\TrashController::class, 'restoreClientRequest'])->name('restore-client-request');
        Route::delete('/client-requests/{id}/force-delete', [App\Http\Controllers\TrashController::class, 'forceDeleteClientRequest'])->name('force-delete-client-request');
        Route::post('/restore-bulk', [App\Http\Controllers\TrashController::class, 'restoreBulk'])->name('restore-bulk');
    });

    // Client Portal (For Clients)
    Route::middleware('role:User')->prefix('portal')->name('client.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Client\ClientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/requests/{clientRequest}', [App\Http\Controllers\Client\ClientDashboardController::class, 'show'])->name('requests.show');
        Route::get('/recommendations/{recommendation}', [App\Http\Controllers\Client\ClientDashboardController::class, 'showRecommendation'])->name('recommendations.show');
        Route::post('/recommendations/{recommendation}/respond', [App\Http\Controllers\Client\ClientDashboardController::class, 'respondRecommendation'])->name('recommendations.respond');
    });

    // User-facing Invoice History
    Route::get('/my-invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    Route::resource('venues', VenueController::class);
    Route::resource('events', EventController::class)->except(['show']);
    Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('events/{event}/guests/import', [GuestController::class, 'showImportForm'])->name('events.guests.import.form');
    Route::resource('events.guests', GuestController::class)->except(['index']);
    Route::post('events/{event}/guests/import', [GuestController::class, 'import'])->name('events.guests.import');
    Route::post('events/{event}/assign-vendor', [EventController::class, 'assignVendor'])->name('events.assignVendor');
    Route::post('/events/{event}/vendors/{vendor}/detach', [EventController::class, 'detachVendor'])->name('events.detach-vendor');
    
    // Event Vendor Items Management
    Route::get('/events/{event}/vendors/{vendor}/items', [App\Http\Controllers\EventVendorItemController::class, 'index'])->name('events.vendor-items.index');
    Route::post('/events/{event}/vendors/{vendor}/items', [App\Http\Controllers\EventVendorItemController::class, 'store'])->name('events.vendor-items.store');
    Route::delete('/event-vendor-items/{item}', [App\Http\Controllers\EventVendorItemController::class, 'destroy'])->name('events.vendor-items.destroy');

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

    // Vendor Dashboard Routes
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/profile', function() {
            return redirect()->route('vendors.index');
        })->name('profile');

        Route::get('/events', function () {
            $vendor = auth()->user()->vendor;
            if (!$vendor) {
                abort(403, 'Anda bukan vendor');
            }
            $events = $vendor->events()->with('user', 'venue')->paginate(10);
            return view('vendor-dashboard.events.index', compact('events'));
        })->name('events.index');

        Route::get('/reviews', function () {
            return view('vendor-dashboard.reviews.index');
        })->name('reviews.index');

        // Vendor Services Routes
        Route::resource('services', \App\Http\Controllers\VendorServiceController::class);

        // Business Profile Routes
        Route::get('/business-profile', [\App\Http\Controllers\VendorBusinessProfileController::class, 'edit'])
            ->name('business-profile.edit')
            ->middleware('role:Vendor');
        Route::put('/business-profile', [\App\Http\Controllers\VendorBusinessProfileController::class, 'update'])
            ->name('business-profile.update')
            ->middleware('role:Vendor');
        
        // Debug route (temporary - remove in production)
        Route::get('/business-profile/debug', function() {
            return view('vendor.business-profile.debug');
        })->name('business-profile.debug')->middleware('role:Vendor');

        // Portfolio Routes
        Route::resource('portfolios', \App\Http\Controllers\VendorPortfolioController::class);
        Route::delete('/portfolio-images/{id}', [\App\Http\Controllers\VendorPortfolioController::class, 'destroyImage'])
            ->name('portfolio-images.destroy');

        // Product / Service Routes
        Route::resource('products', \App\Http\Controllers\VendorProductController::class);

        // Package Routes
        Route::resource('packages', \App\Http\Controllers\VendorPackageController::class);

        // Catalog Routes (Universal Module)
        Route::prefix('catalog')->name('catalog.')->group(function () {
            Route::resource('categories', \App\Http\Controllers\VendorCatalogCategoryController::class);
            Route::resource('items', \App\Http\Controllers\VendorCatalogItemController::class);
            Route::delete('/items/images/{id}', [\App\Http\Controllers\VendorCatalogItemController::class, 'destroyImage'])->name('items.images.destroy');
        });
    });
});

use App\Http\Controllers\SuperUserPermissionController;

// API routes for vendor dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/api/vendor/services/{id}', [\App\Http\Controllers\ApiVendorController::class, 'getService']);
    Route::get('/api/vendor/{vendorId}/venue-service', [\App\Http\Controllers\ApiVendorController::class, 'getVendorVenueService']);
});

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
    
    // Logo deletion route - must be outside the prefix to match POST method from form
    Route::delete('superuser/settings/logo', [App\Http\Controllers\SettingsController::class, 'deleteLogo'])
        ->middleware(['auth', 'can:access-settings'])
        ->name('superuser.settings.logo.delete');


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
