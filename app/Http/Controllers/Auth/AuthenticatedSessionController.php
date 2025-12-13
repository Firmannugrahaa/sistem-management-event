<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // Pass redirect URL to view if it exists
        $redirect = $request->query('redirect');
        return view('auth.login', compact('redirect'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        // Check if there's a redirect parameter
        $redirectUrl = $request->input('redirect');

        // Role-based redirection
        if ($user->hasRole('User') || $user->hasRole('Client')) {
            // If redirect URL exists, redirect there (for booking flow)
            if ($redirectUrl) {
                return redirect($redirectUrl);
            }
            // Otherwise, client users should go to the main landing page after login
            return redirect()->route('landing.page');
        } else if ($user->hasRole('Vendor')) {
            // Vendor users should complete their business profile first
            // Check if profile is complete (you can add more checks here)
            $vendor = $user->vendor;
            
            // Always redirect to profile edit for now to complete/update data
            return redirect()->route('vendor.business-profile.edit')
                ->with('info', 'Silakan lengkapi atau perbarui profil bisnis Anda.');
        } else if ($user->hasRole('SuperUser')) {
            return redirect()->intended(route('superuser.dashboard.index', absolute: false));
        } else {
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
