<?php

namespace App\Providers;

use App\Http\Middleware\CheckSuperUser;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Ticket' => 'App\Policies\TicketPolicy',
        'App\Models\Event' => 'App\Policies\EventPolicy',
        'App\Models\Guest' => 'App\Policies\GuestPolicy',
        'App\Models\Invoice' => 'App\Policies\InvoicePolicy',
        'App\Models\Payment' => 'App\Policies\PaymentPolicy',
        'App\Models\Vendor' => 'App\Policies\VendorPolicy',
        'App\Models\Voucher' => 'App\Policies\VoucherPolicy',
        'App\Models\Venue' => 'App\Policies\VenuePolicy',
        'App\Models\CompanySetting' => 'App\Policies\CompanySettingPolicy',
        'App\Models\ClientRequest' => 'App\Policies\ClientRequestPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register the superuser middleware
        $this->app['router']->aliasMiddleware('superuser', CheckSuperUser::class);
        
        // Optional: Global gate check that respects SuperUser role
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('SuperUser')) {
                return true;
            }
        });
        
        // Define gate for accessing settings - checks if user has the specific permission, is SuperUser, or is Owner
        Gate::define('access-settings', function ($user) {
            // SuperUser should always have access
            if ($user->hasRole('SuperUser')) {
                return true;
            }
            // Owner should have access to settings too
            if ($user->hasRole('Owner')) {
                return true;
            }
            // Check if user has the specific access-settings permission
            return $user->can('access-settings');
        });
    }
}