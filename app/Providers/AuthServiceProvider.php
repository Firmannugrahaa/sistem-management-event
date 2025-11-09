<?php

namespace App\Providers;

use App\Http\Middleware\CheckSuperUser;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

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
    }
}