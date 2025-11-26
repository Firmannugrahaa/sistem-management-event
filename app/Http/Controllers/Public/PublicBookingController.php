<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PublicBookingController extends Controller
{
    /**
     * Show the booking form
     */
    public function showForm()
    {
        $vendors = Vendor::all();
        return view('public.booking-form', compact('vendors'));
    }

    /**
     * Store the booking request (before auth)
     */
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|max:20',
            'event_date' => 'required|date|after:today',
            'budget' => 'nullable|numeric|min:0',
            'event_type' => 'required|string|max:255',
            'message' => 'nullable|string',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        // Store booking data in session
        Session::put('pending_booking', $validated);

        // Redirect to login/register with intent
        return redirect()->route('register')
            ->with('booking_intent', true)
            ->with('info', 'Silakan daftar atau login untuk melanjutkan booking Anda.');
    }
}
