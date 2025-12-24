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
// Test route for debugging
Route::get('/test', function() {
    return 'Server is working!';
});

Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing.page');

// Public Vendor Profile
Route::get('/vendor/{id}/profile', [\App\Http\Controllers\VendorBusinessProfileController::class, 'show'])
    ->name('vendor.profile.show');

// Public Booking Form Routes (No Auth Required)
Route::get('/book-now', [\App\Http\Controllers\Public\PublicBookingController::class, 'showForm'])
    ->name('public.booking.form');
Route::post('/book-now', [\App\Http\Controllers\Public\PublicBookingController::class, 'storeBooking'])
    ->name('public.booking.store');
Route::get('/booking-confirmation/{clientRequest}', [\App\Http\Controllers\Public\PublicBookingController::class, 'showConfirmation'])
    ->name('public.booking.confirmation')
    ->middleware(['auth']);

Route::get('/booking-confirmation/{clientRequest}', [\App\Http\Controllers\Public\PublicBookingController::class, 'showConfirmation'])
    ->name('public.booking.confirmation')
    ->middleware(['auth']);
Route::post('/book-now/ajax', [\App\Http\Controllers\Public\PublicBookingController::class, 'storeBookingAjax'])
    ->middleware('auth')
    ->name('public.booking.store.ajax');


// Public Catalog Item Detail
Route::get('/catalog/item/{id}', [\App\Http\Controllers\Public\PublicCatalogController::class, 'show'])
    ->name('public.catalog.item.show');

// Event Packages (Admin Created)
Route::get('/packages/{slug}', [\App\Http\Controllers\EventPackageController::class, 'show'])
    ->name('event-packages.show');


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

// Client Dashboard and Requests Routes
Route::middleware(['auth', 'verified', 'role:Client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Client\ClientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/requests/{clientRequest}', [App\Http\Controllers\Client\ClientDashboardController::class, 'show'])->name('requests.show');
    Route::get('/requests/{clientRequest}', [App\Http\Controllers\Client\ClientDashboardController::class, 'show'])->name('requests.show');
    Route::put('/requests/{clientRequest}', [App\Http\Controllers\Client\ClientDashboardController::class, 'update'])->name('requests.update');
    Route::get('/recommendations/{recommendation}', [App\Http\Controllers\Client\ClientDashboardController::class, 'showRecommendation'])->name('recommendations.show');
    
    // Review Routes
    Route::post('/events/{event}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

// Client Checklist Routes
Route::middleware(['auth', 'verified', 'role:Client'])->group(function () {
    Route::get('/client/checklist/{clientRequest}', [App\Http\Controllers\Client\ChecklistController::class, 'index'])
        ->name('client.checklist');
    Route::get('/client/checklist/{clientRequest}/timeline', [App\Http\Controllers\Client\ChecklistController::class, 'timeline'])
        ->name('client.checklist.timeline');
    Route::post('/client/checklist/{checklist}/item', [App\Http\Controllers\Client\ChecklistController::class, 'storeItem'])
        ->name('client.checklist.item.store');
    Route::patch('/client/checklist/item/{item}', [App\Http\Controllers\Client\ChecklistController::class, 'updateItem'])
        ->name('client.checklist.item.update');
    Route::delete('/client/checklist/item/{item}', [App\Http\Controllers\Client\ChecklistController::class, 'destroyItem'])
        ->name('client.checklist.item.destroy');

    // Client Recommendation Item Response
    Route::post('/client/recommendation-items/{item}/respond', [App\Http\Controllers\Client\ClientDashboardController::class, 'respondRecommendationItem'])
        ->name('client.recommendation-items.respond');
});






Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Forced Password Change
    Route::get('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'create'])->name('password.change');
    Route::post('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'store'])->name('password.change.update');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

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

    // Staff Dashboard & Events (Role: Staff)
    Route::prefix('staff')->middleware('role:Staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Staff\StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('/events', [App\Http\Controllers\Staff\StaffDashboardController::class, 'events'])->name('events.index');
        Route::get('/events/{event}', [App\Http\Controllers\Staff\StaffDashboardController::class, 'showEvent'])->name('events.show');
        
        // Staff Tasks
        Route::get('/events/{event}/tasks', [App\Http\Controllers\Staff\StaffTaskController::class, 'index'])->name('events.tasks');
        Route::post('/events/{event}/tasks/{task}/status', [App\Http\Controllers\Staff\StaffTaskController::class, 'updateStatus'])->name('tasks.update-status');
        Route::post('/events/{event}/tasks/{task}/proof', [App\Http\Controllers\Staff\StaffTaskController::class, 'uploadProof'])->name('tasks.upload-proof');
    });


    // Event Management
    Route::resource('events', App\Http\Controllers\EventController::class);
    Route::post('events/{event}/crew', [App\Http\Controllers\EventCrewController::class, 'store'])->name('events.crew.store');
    Route::delete('events/{event}/crew/{crew}', [App\Http\Controllers\EventCrewController::class, 'destroy'])->name('events.crew.destroy');
    
    // Event Tasks (Admin)
    Route::get('events/{event}/tasks', [App\Http\Controllers\EventTaskController::class, 'index'])->name('events.tasks.index');
    Route::post('events/{event}/tasks', [App\Http\Controllers\EventTaskController::class, 'store'])->name('events.tasks.store');
    Route::put('events/{event}/tasks/{task}', [App\Http\Controllers\EventTaskController::class, 'update'])->name('events.tasks.update');
    Route::delete('events/{event}/tasks/{task}', [App\Http\Controllers\EventTaskController::class, 'destroy'])->name('events.tasks.destroy');

    // Client Requests / Leads Management
    Route::resource('client-requests', App\Http\Controllers\ClientRequestController::class);
    Route::post('client-requests/{clientRequest}/update-status', [App\Http\Controllers\ClientRequestController::class, 'updateStatus'])
        ->name('client-requests.update-status');
    Route::post('client-requests/{clientRequest}/assign-staff', [App\Http\Controllers\ClientRequestController::class, 'assignStaff'])
        ->name('client-requests.assign-staff');
    Route::post('client-requests/{clientRequest}/convert-to-event', [App\Http\Controllers\ClientRequestController::class, 'convertToEvent'])
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
    Route::delete('recommendations/{recommendation}', [App\Http\Controllers\RecommendationController::class, 'destroy'])
        ->name('recommendations.destroy');

    // Company Products & Packages (Owner/Admin Only)
    Route::middleware(['role:Owner|Admin|SuperUser'])->prefix('company')->name('company.')->group(function () {
        Route::resource('products', App\Http\Controllers\CompanyProductController::class);
        Route::resource('packages', App\Http\Controllers\CompanyPackageController::class);
        Route::resource('portfolios', App\Http\Controllers\CompanyPortfolioController::class);
        Route::delete('/portfolios/images/{id}', [App\Http\Controllers\CompanyPortfolioController::class, 'destroyImage'])->name('portfolios.images.destroy');
        Route::post('/portfolios/images/{id}/toggle-gallery', [App\Http\Controllers\CompanyPortfolioController::class, 'toggleGalleryStatus'])->name('portfolios.images.toggle-gallery');
    });
    
    // Service Types Management (Owner/Admin Only)
    Route::middleware(['role:Owner|Admin|SuperUser'])->group(function () {
        Route::resource('service-types', App\Http\Controllers\ServiceTypeController::class)->except(['show']);
        Route::post('service-types/quick-store', [App\Http\Controllers\ServiceTypeController::class, 'quickStore'])->name('service-types.quick-store');
        Route::get('api/service-types', [App\Http\Controllers\ServiceTypeController::class, 'list'])->name('api.service-types.list');
    });

    // Event Packages Management (Admin/Owner Only)
    Route::middleware(['role:Owner|Admin|SuperUser'])->resource('event-packages', App\Http\Controllers\EventPackageController::class)->except(['show']);

    // Landing Gallery Management (Admin/Owner Only)
    Route::middleware(['role:Owner|Admin|SuperUser'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('landing-gallery', App\Http\Controllers\Admin\LandingGalleryController::class);
        Route::post('landing-gallery/{landingGallery}/approve', [App\Http\Controllers\Admin\LandingGalleryController::class, 'approve'])->name('landing-gallery.approve');
        Route::post('landing-gallery/{landingGallery}/reject', [App\Http\Controllers\Admin\LandingGalleryController::class, 'reject'])->name('landing-gallery.reject');
        Route::post('landing-gallery/{landingGallery}/toggle-featured', [App\Http\Controllers\Admin\LandingGalleryController::class, 'toggleFeatured'])->name('landing-gallery.toggle-featured');
        Route::post('landing-gallery/{landingGallery}/toggle-active', [App\Http\Controllers\Admin\LandingGalleryController::class, 'toggleActive'])->name('landing-gallery.toggle-active');
        Route::post('landing-gallery/bulk-approve', [App\Http\Controllers\Admin\LandingGalleryController::class, 'bulkApprove'])->name('landing-gallery.bulk-approve');
        Route::post('landing-gallery/bulk-delete', [App\Http\Controllers\Admin\LandingGalleryController::class, 'bulkDelete'])->name('landing-gallery.bulk-delete');
    });

    // Trash Management (SuperUser Only)
    Route::middleware('role:SuperUser')->prefix('admin/trash')->name('admin.trash.')->group(function () {
        Route::get('/', [App\Http\Controllers\TrashController::class, 'index'])->name('index');
        Route::get('/client-requests', [App\Http\Controllers\TrashController::class, 'clientRequests'])->name('client-requests');
        Route::post('/client-requests/{id}/restore', [App\Http\Controllers\TrashController::class, 'restoreClientRequest'])->name('restore-client-request');
        Route::delete('/client-requests/{id}/force-delete', [App\Http\Controllers\TrashController::class, 'forceDeleteClientRequest'])->name('force-delete-client-request');
        Route::post('/restore-bulk', [App\Http\Controllers\TrashController::class, 'restoreBulk'])->name('restore-bulk');
    });

    // Checklist Template Management (SuperUser + Permission-based for Admin/Owner)
    Route::middleware('can:manage-checklist-templates')->prefix('admin/checklist-templates')->name('admin.checklist-templates.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'store'])->name('store');
        Route::get('/{template}', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'show'])->name('show');
        Route::get('/{template}/edit', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'edit'])->name('edit');
        Route::put('/{template}', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'update'])->name('update');
        Route::delete('/{template}', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'destroy'])->name('destroy');
        
        // Template Item Management
        Route::post('/{template}/items', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'storeItem'])->name('items.store');
        Route::put('/items/{item}', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'updateItem'])->name('items.update');
        Route::delete('/items/{item}', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'destroyItem'])->name('items.destroy');
        Route::post('/{template}/items/reorder', [App\Http\Controllers\Admin\ChecklistTemplateController::class, 'reorderItems'])->name('items.reorder');
    });

    // Client Portal (For Clients/Users)
    Route::middleware('role:Client|User')->prefix('portal')->name('client.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Client\ClientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/requests', [App\Http\Controllers\Client\ClientDashboardController::class, 'index'])->name('requests.index'); // Alias for booking list
        Route::get('/requests/{clientRequest}', [App\Http\Controllers\Client\ClientDashboardController::class, 'show'])->name('requests.show');
        Route::put('/requests/{clientRequest}', [App\Http\Controllers\Client\ClientDashboardController::class, 'update'])->name('requests.update');
        Route::get('/recommendations/{recommendation}', [App\Http\Controllers\Client\ClientDashboardController::class, 'showRecommendation'])->name('recommendations.show');
        Route::post('/recommendations/{recommendation}/respond', [App\Http\Controllers\Client\ClientDashboardController::class, 'respondRecommendation'])->name('recommendations.respond');
        Route::post('/recommendations/items/{item}/accept', [App\Http\Controllers\Client\ClientDashboardController::class, 'acceptItem'])->name('recommendations.items.accept');
        Route::post('/recommendations/items/{item}/reject', [App\Http\Controllers\Client\ClientDashboardController::class, 'rejectItem'])->name('recommendations.items.reject');
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
    Route::patch('events/{event}/update-status', [EventController::class, 'updateStatus'])->name('events.updateStatus');
    Route::patch('events/{event}/vendors/{vendor}/status', [EventController::class, 'updateVendorStatus'])->name('events.updateVendorStatus');
    Route::post('/events/{event}/vendors/{vendor}/detach', [EventController::class, 'detachVendor'])->name('events.detach-vendor');
    
    // Event Vendor Items Management
    Route::get('/events/{event}/vendors/{vendor}/items', [App\Http\Controllers\EventVendorItemController::class, 'index'])->name('events.vendor-items.index');
    Route::post('/events/{event}/vendors/{vendor}/items', [App\Http\Controllers\EventVendorItemController::class, 'store'])->name('events.vendor-items.store');
    Route::delete('/event-vendor-items/{item}', [App\Http\Controllers\EventVendorItemController::class, 'destroy'])->name('events.vendor-items.destroy');

    Route::get('vendors/{vendor}/offerings', [VendorController::class, 'getOfferings'])->name('vendors.offerings');
    Route::resource('vendors', VendorController::class);

    // General Services Management - accessible to Owner and Admin roles
    Route::resource('services', \App\Http\Controllers\ServiceController::class)
        ->middleware('can:manage_services');

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
        // Vendor Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Vendor\VendorDashboardController::class, 'index'])
            ->name('dashboard');
        
        Route::get('/profile', function() {
            return redirect()->route('vendors.index');
        })->name('profile');

        Route::get('/events', function () {
            $user = auth()->user();
            // Mark vendor assignment notifications as read
            $user->unreadNotifications()->where('type', 'vendor_assignment')->update(['is_read' => true]);

            $vendor = $user->vendor;
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
            ->middleware('role:Vendor|Owner|Admin');
        Route::put('/business-profile', [\App\Http\Controllers\VendorBusinessProfileController::class, 'update'])
            ->name('business-profile.update')
            ->middleware('role:Vendor|Owner|Admin');
        
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
