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
     * Store the booking request
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

        // Check if user is already logged in
        if (auth()->check()) {
            // User sudah login - langsung create ClientRequest
            $clientRequest = ClientRequest::create([
                'user_id' => auth()->id(),
                'client_name' => $validated['client_name'],
                'client_email' => $validated['client_email'],
                'client_phone' => $validated['client_phone'],
                'event_date' => $validated['event_date'],
                'budget' => $validated['budget'] ?? null,
                'event_type' => $validated['event_type'],
                'message' => $validated['message'] ?? null,
                'vendor_id' => $validated['vendor_id'] ?? null,
                'status' => 'pending',
                'detailed_status' => 'new',
                'request_source' => 'public_booking_form',
            ]);

            // Redirect ke client dashboard dengan success
            return redirect()->route('client.dashboard')
                ->with('success', 'Booking Anda berhasil dikirim! Tim kami akan segera menghubungi Anda.');
        }

        // User belum login - save ke session dan redirect ke register
        Session::put('pending_booking', $validated);

        return redirect()->route('register')
            ->with('booking_intent', true)
            ->with('info', 'Silakan daftar atau login untuk melanjutkan booking Anda.');
    }
}
