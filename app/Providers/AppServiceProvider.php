<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CompanySetting;
use App\View\Composers\PendingApprovalComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $companySettings = cache()->remember('company_settings', 3600, function () { // Cache for 1 hour
                return \App\Models\CompanySetting::first();
            });
            $view->with('companySettings', $companySettings);
        });

        // Composer for pending approval count
        View::composer('layouts.navigation', PendingApprovalComposer::class);

        // Badge Counts Composer for Navigation
        View::composer('layouts.navigation', function ($view) {
            $leadsBadgeCount = 0;
            $vendorEventsBadgeCount = 0;

            if (auth()->check()) {
                // CRITICAL: Eager load roles to prevent memory exhaustion
                $user = auth()->user()->load('roles');
                
                // Get unread notifications collection
                $notifications = $user->unreadNotifications; 

                if ($user->hasAnyRole(['Admin', 'Owner', 'SuperUser'])) {
                    $leadsBadgeCount = $notifications->where('type', 'new_booking')->count();
                }

                if ($user->hasRole('Vendor')) {
                    $vendorEventsBadgeCount = $notifications->where('type', 'vendor_assignment')->count();
                }
            }

            $view->with('leadsBadgeCount', $leadsBadgeCount);
            $view->with('vendorEventsBadgeCount', $vendorEventsBadgeCount);
        });
    }
}
