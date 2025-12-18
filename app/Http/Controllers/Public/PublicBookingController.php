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
    public function showForm(Request $request)
    {
        // Get all vendors grouped by service type
        $serviceTypes = \App\Models\ServiceType::with(['vendors' => function($query) {
            $query->orderBy('brand_name');
        }])->orderBy('name')->get();
        
        // Check if package_id is provided
        $package = null;
        $packageVendors = []; // Map of service_type_id => vendor_id for pre-filling
        
        if ($request->has('package_id')) {
            $package = \App\Models\EventPackage::with([
                'items.vendorCatalogItem.vendor.serviceType',
                'items.vendorPackage.vendor.serviceType'
            ])->find($request->package_id);
            
            // Extract vendors from package items to pre-fill the form
            if ($package && $package->items) {
                foreach ($package->items as $item) {
                    $vendor = null;
                    if ($item->vendorCatalogItem && $item->vendorCatalogItem->vendor) {
                        $vendor = $item->vendorCatalogItem->vendor;
                    } elseif ($item->vendorPackage && $item->vendorPackage->vendor) {
                        $vendor = $item->vendorPackage->vendor;
                    }
                    
                    if ($vendor && $vendor->service_type_id) {
                        // Store the first vendor of each type (avoid duplicates)
                        if (!isset($packageVendors[$vendor->service_type_id])) {
                            $packageVendors[$vendor->service_type_id] = $vendor->id;
                        }
                    }
                }
            }
        }
        
        // Get authenticated user data if available
        $user = auth()->user();
        
        // Check for existing bookings ONLY if user is logged in and package is selected
        if ($user && $package) {
            // Get user's existing client requests (bookings)
            $existingBookings = \App\Models\ClientRequest::where('user_id', $user->id)
                ->where('status', '!=', 'cancelled')
                ->get();
            
            $hasSamePackage = false;
            $hasOtherBooking = false;
            
            if ($existingBookings->isNotEmpty()) {
                // Check if user has booking for the same package
                // We use package name or event_type as heuristic
                foreach ($existingBookings as $booking) {
                    if ($booking->event_type == $package->event_type || 
                        stripos($booking->client_name, $package->name) !== false) {
                        $hasSamePackage = true;
                        break;
                    }
                }
                
                // If not same package, then must be other booking
                if (!$hasSamePackage) {
                    $hasOtherBooking = true;
                }
            }
            
            // Set session flags for JavaScript popup
            if ($hasSamePackage) {
                session()->flash('booking_check', 'same_package');
                session()->flash('package_name', $package->name);
            } elseif ($hasOtherBooking) {
                session()->flash('booking_check', 'other_package');
                session()->flash('package_name', $package->name);
            }
        }
        
        // Define non-partner charges per category (in Rupiah)
        $nonPartnerCharges = [
            'Venue' => 0, // No charge for venue
            'Catering' => 1200000,
            'MUA' => 600000,
            'FG' => 600000, // Fashion & Grooming
            'Dekorasi' => 800000,
            'Dokumentasi' => 700000,
            'Entertainment' => 500000,
            'default' => 600000 // Default charge for other categories
        ];
        
        return view('public.booking-form', compact('serviceTypes', 'package', 'packageVendors', 'user', 'nonPartnerCharges'));
    }

    /**
     * Store the booking request
     */
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'groom_name' => 'nullable|string|max:255',
            'bride_name' => 'nullable|string|max:255',
            'fill_couple_later' => 'nullable|boolean',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|max:20',
            'event_date' => 'required|date|after:today',
            'budget' => 'nullable|numeric|min:0',
            'event_type' => 'required|string|max:255',
            'message' => 'nullable|string',
            'vendor_id' => 'nullable|exists:vendors,id',
            'event_package_id' => 'nullable|exists:event_packages,id',
        ]);

        // If user is logged in
        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
            $validated['status'] = 'pending';
            $validated['detailed_status'] = 'new';
            $validated['request_source'] = 'website';

            $clientRequest = \App\Models\ClientRequest::create($validated);

            // 🔔 NOTIFICATION: Notify Admin & Owner
            $admins = \App\Models\User::role(['Admin', 'Owner'])->get();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'new_booking',
                    'title' => 'Booking Baru Masuk',
                    'message' => "Request baru dari {$clientRequest->client_name} untuk event {$clientRequest->event_type}",
                    'link' => route('client-requests.show', $clientRequest->id),
                    'is_read' => false,
                    'data' => ['client_request_id' => $clientRequest->id]
                ]);
            }

            // Redirect ke confirmation page
            return redirect()->route('public.booking.confirmation', $clientRequest->id)
                ->with('success', 'Booking Anda berhasil dikirim!');
        }

        // User belum login - save ke session dan redirect ke register
        Session::put('pending_booking', $validated);

        return redirect()->route('register')
            ->with('booking_intent', true)
            ->with('info', 'Silakan daftar atau login untuk melanjutkan booking Anda.');
    }

    /**
     * Show booking confirmation page
     */
    public function showConfirmation(\App\Models\ClientRequest $clientRequest)
    {
        // Ensure user can only see their own bookings
        if ($clientRequest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $package = null;
        // Try to get package info if event_type matches
        if ($clientRequest->event_type) {
            $package = \App\Models\EventPackage::where('event_type', $clientRequest->event_type)->first();
        }

        return view('public.booking-confirmation', compact('clientRequest', 'package'));
    }
}
