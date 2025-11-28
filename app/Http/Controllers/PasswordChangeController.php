<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PasswordChangeController extends Controller
{
    /**
     * Show the form for changing the password.
     */
    public function create()
    {
        return view('auth.change-password');
    }

    /**
     * Update the user's password.
     */
    public function store(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = auth()->user();

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        // Check if user is Vendor
        if ($user->hasRole('Vendor')) {
            // Vendor: Logout and redirect to login with message
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('success', 'Password Anda berhasil diubah! Silakan login dengan password baru Anda.')
                ->with('password_changed', true);
        }

        // Role-based redirection for non-vendor users (no logout needed)
        if ($user->hasRole('User')) {
            // Client user -> landing page
            return redirect()->route('landing.page')->with('success', 'Password Anda berhasil diubah!');
        } else {
            // Other roles (Owner, Admin, Staff, SuperUser) -> main dashboard
            return redirect()->route('dashboard')->with('success', 'Password Anda berhasil diubah!');
        }
    }
}
