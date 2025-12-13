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
