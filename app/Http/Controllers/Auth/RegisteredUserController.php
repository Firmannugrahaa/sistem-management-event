<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class, 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign 'Client' role to newly registered users (User role also works interchangeably)
        $clientRole = Role::firstOrCreate(['name' => 'Client']);
        $user->assignRole($clientRole);

        event(new Registered($user));
        Auth::login($user);

        // Check if there's a pending booking from public form
        $pendingBooking = session('pending_booking');
        
        if ($pendingBooking) {
            // Create ClientRequest automatically
            $clientRequest = \App\Models\ClientRequest::create([
                'user_id' => $user->id,
                'client_name' => $pendingBooking['client_name'],
                'client_email' => $pendingBooking['client_email'],
                'client_phone' => $pendingBooking['client_phone'],
                'event_date' => $pendingBooking['event_date'],
                'budget' => $pendingBooking['budget'] ?? null,
                'event_type' => $pendingBooking['event_type'],
                'message' => $pendingBooking['message'] ?? null,
                'vendor_id' => $pendingBooking['vendor_id'] ?? null,
                'status' => 'pending',
                'detailed_status' => 'new',
                'request_source' => 'public_booking_form',
            ]);

            // Clear the session
            session()->forget('pending_booking');

            // Redirect with modal flag
            return redirect()->route('landing.page')
                ->with('booking_created', true)
                ->with('show_redirect_modal', true)
                ->with('success', 'Akun berhasil dibuat dan booking Anda telah dikirim!');
        }

        // Regular registration (no booking) - redirect to dashboard
        return redirect()->route('client.dashboard')
            ->with('success', 'Selamat datang! Akun Anda berhasil dibuat.');
    }

}
