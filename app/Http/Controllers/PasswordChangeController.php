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

        // Role-based redirection after password change
        if ($user->hasRole('Client')) {
            return redirect()->route('landing.page')->with('success', 'Your password has been changed successfully.');
        } else {
            return redirect()->route('dashboard')->with('success', 'Your password has been changed successfully.');
        }
    }
}
