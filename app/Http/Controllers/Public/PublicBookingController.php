<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use App\Models\NonPartnerVendorCharge;
use App\Models\ServiceType;
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
        // Get only service types that have active vendors (show readiness, not emptiness)
        $serviceTypes = \App\Models\ServiceType::whereHas('vendors', function($query) {
            $query->where('is_active', true);
        })->with(['vendors' => function($query) {
            $query->where('is_active', true)
                  ->orderBy('brand_name')
                  ->with(['catalogItems' => function($q) {
                      $q->orderBy('price', 'asc')->limit(10);
                  }, 'packages' => function($q) {
                      $q->orderBy('price', 'asc')->with('services');
                  }, 'publishedPortfolios']);
        }])->orderBy('name')->get();
        
        // Load all active packages for the new "Natural Flow" booking system
        $allPackages = \App\Models\EventPackage::where('is_active', true)
            ->with([
                'items.vendorCatalogItem.vendor.serviceType',
                'items.vendorPackage.vendor.serviceType'
            ])
            ->get();

        // Check if package_id is provided (legacy/link support)
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
        
        // Get authenticated user data with clientProfile relationship if available
        $user = auth()->user();
        $activeBooking = null;
        
        if ($user) {
            $user->load('clientProfile');
            
            // Check for any active booking (pending, in_review, approved, in_progress)
            $activeBooking = \App\Models\ClientRequest::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'in_review', 'approved', 'in_progress'])
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        // Check for existing bookings with legacy package detection
        if ($user && $package) {
            // Get user's existing client requests (bookings)
            $existingBookings = \App\Models\ClientRequest::where('user_id', $user->id)
                ->where('status', '!=', 'cancelled')
                ->get();
            
            $hasSamePackage = false;
            $hasOtherBooking = false;
            
            if ($existingBookings->isNotEmpty()) {
                // Check if user has booking for the same package
                // We check: 1) package name in message, 2) event_type match
                foreach ($existingBookings as $booking) {
                    $packageNameInMessage = stripos($booking->message ?? '', $package->name) !== false;
                    $sameEventType = $booking->event_type === $package->event_type;
                    
                    // Consider it same package if the package name appears in booking message
                    // or if booking has same event type (as a heuristic)
                    if ($packageNameInMessage || $sameEventType) {
                        $hasSamePackage = true;
                        break;
                    }
                }
                
                // If user has bookings but not for the same package
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
        
        // Get company settings for contact info (WhatsApp)
        $companySettings = \App\Models\CompanySetting::first();
        
        return view('public.booking-form', compact('serviceTypes', 'package', 'packageVendors', 'user', 'nonPartnerCharges', 'allPackages', 'companySettings', 'activeBooking'));
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
            'package_id' => 'nullable|exists:event_packages,id',
            'service_selections' => 'nullable|array', // NEW: Service-centric format
        ]);

        // If package_id is provided, prepend package info to message for tracking
        $message = $validated['message'] ?? '';
        if (isset($validated['package_id']) && $validated['package_id']) {
            $package = \App\Models\EventPackage::find($validated['package_id']);
            if ($package) {
                $packageInfo = "[Paket: {$package->name}] ";
                $message = $packageInfo . $message;
            }
        }

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
                'message' => $message,
                'vendor_id' => $validated['vendor_id'] ?? null,
                'event_package_id' => $validated['package_id'] ?? null,
                // ✅ FIX: Save couple names for wedding/engagement
                'groom_name' => $validated['groom_name'] ?? null,
                'bride_name' => $validated['bride_name'] ?? null,
                'fill_couple_later' => $validated['fill_couple_later'] ?? false,
                'status' => 'pending',
                'detailed_status' => 'new',
                'request_source' => 'public_booking_form',
            ]);
            
            // NEW: Save service selections to message for admin reference
            $serviceSelections = $request->input('service_selections', []);
            if (!empty($serviceSelections)) {
                $servicesSummary = $this->formatServiceSelectionsSummary($serviceSelections);
                $clientRequest->update([
                    'message' => $message . "\n\n--- Layanan Dipilih ---\n" . $servicesSummary
                ]);
            }

            // Save non-partner vendor charges if any
            $this->saveNonPartnerCharges($request, $clientRequest);

            // Redirect ke client dashboard dengan success
            return redirect()->route('client.dashboard')
                ->with('success', 'Booking Anda berhasil dikirim! Tim kami akan segera menghubungi Anda.');
        }

        // User belum login - save ke session dan redirect ke register
        $validated['message'] = $message; // Include enhanced message
        // Save vendor selections (both formats) to session for processing after registration
        $validated['service_selections'] = $request->input('service_selections', []);
        $validated['vendors'] = $request->input('vendors', []);
        $validated['non_partner_vendors'] = $request->input('non_partner_vendors', []);
        Session::put('pending_booking', $validated);

        return redirect()->route('register')
            ->with('booking_intent', true)
            ->with('info', 'Silakan daftar atau login untuk melanjutkan booking Anda.');
    }

    /**
     * Save non-partner vendor charges from the booking form
     */
    private function saveNonPartnerCharges(Request $request, ClientRequest $clientRequest): void
    {
        // Handle new non_partner_vendors array format
        $nonPartnerVendors = $request->input('non_partner_vendors', []);
        
        if (!empty($nonPartnerVendors) && is_array($nonPartnerVendors)) {
            foreach ($nonPartnerVendors as $vendor) {
                // Only create if essential fields are present
                if (!empty($vendor['category']) && !empty($vendor['vendor_name'])) {
                    NonPartnerVendorCharge::create([
                        'client_request_id' => $clientRequest->id,
                        'event_id' => null, // Will be set when event is created
                        'service_type' => $vendor['category'],
                        'vendor_name' => $vendor['vendor_name'],
                        'vendor_contact' => ($vendor['contact_person'] ?? '') . 
                                          (isset($vendor['phone']) ? ' - ' . $vendor['phone'] : ''),
                        'notes' => $vendor['notes'] ?? null,
                        'charge_amount' => $vendor['charge'] ?? 600000, // Default Rp600k
                    ]);
                }
            }
        }
        
        // Legacy support: Handle old vendors[typeId][vendor_id] = 'non-partner' format
        $vendors = $request->input('vendors', []);
        
        foreach ($vendors as $serviceTypeId => $vendorData) {
            // Check if this is a non-partner vendor selection (old format)
            if (isset($vendorData['vendor_id']) && $vendorData['vendor_id'] === 'non-partner') {
                // Get service type name
                $serviceType = ServiceType::find($serviceTypeId);
                $serviceTypeName = $serviceType ? $serviceType->name : 'Other';
                
                // Only create charge if vendor name is provided
                if (!empty($vendorData['non_partner_name'])) {
                    NonPartnerVendorCharge::create([
                        'client_request_id' => $clientRequest->id,
                        'event_id' => null, // Will be set when event is created
                        'service_type' => $serviceTypeName,
                        'vendor_name' => $vendorData['non_partner_name'],
                        'vendor_contact' => $vendorData['non_partner_contact'] ?? null,
                        'notes' => $vendorData['non_partner_notes'] ?? null,
                        'charge_amount' => $vendorData['non_partner_charge'] ?? 600000,
                    ]);
                }
            }
        }
    }

    /**
     * Store booking via AJAX (for multi-step form)
     */
    public function storeBookingAjax(Request $request)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu.'
            ], 401);
        }

        try {
            $validated = $request->validate([
                'client_name' => 'required|string|max:255',
                'client_email' => 'required|email|max:255',
                'client_phone' => 'required|string|max:20',
                'event_date' => 'required|date|after:today',
                'budget' => 'nullable|numeric|min:0',
                'event_type' => 'required|string|max:255',
                'message' => 'nullable|string',
                'package_id' => 'nullable|exists:event_packages,id',
                'cpp_name' => 'nullable|string|max:255',
                'cpw_name' => 'nullable|string|max:255',
                'fill_couple_later' => 'nullable|boolean',
            ]);

            // Generate unique booking number
            $bookingNumber = 'BK-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));

            // If package_id is provided, prepend package info to message
            $message = $validated['message'] ?? '';
            if (isset($validated['package_id']) && $validated['package_id']) {
                $package = \App\Models\EventPackage::find($validated['package_id']);
                if ($package) {
                    $message = "[Paket: {$package->name}] " . $message;
                }
            }

            // Create the client request
            $clientRequest = ClientRequest::create([
                'user_id' => auth()->id(),
                'client_name' => $validated['client_name'],
                'client_email' => $validated['client_email'],
                'client_phone' => $validated['client_phone'],
                'event_date' => $validated['event_date'],
                'budget' => $validated['budget'] ?? null,
                'event_type' => $validated['event_type'],
                'message' => $message,
                'cpp_name' => $validated['cpp_name'] ?? null,
                'cpw_name' => $validated['cpw_name'] ?? null,
                'fill_couple_later' => $validated['fill_couple_later'] ?? false,
                'booking_number' => $bookingNumber,
                'event_package_id' => $validated['package_id'] ?? null,  // ✅ FIXED
                'status' => 'pending',
                'detailed_status' => 'new',
                'request_source' => 'public_booking_form',
            ]);

            // Save non-partner vendor charges if any
            $this->saveNonPartnerCharges($request, $clientRequest);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat!',
                'booking_number' => $bookingNumber,
                'booking_id' => $clientRequest->id,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Booking AJAX error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.',
            ], 500);
        }
    }
    
    /**
     * Format service selections into readable summary for admin
     */
    private function formatServiceSelectionsSummary(array $serviceSelections): string
    {
        $summary = [];
        
        foreach ($serviceSelections as $typeId => $selection) {
            $line = "• {$selection['category_name']}: {$selection['item_name']} ({$selection['vendor_name']})";
            
            // Add qty if applicable  
            if (isset($selection['qty']) && $selection['qty'] > 1) {
                $line .= " - {$selection['qty']} unit";
            }
            
            // Add price
            $line .= " - Rp " . number_format($selection['subtotal'] ?? $selection['price'], 0, ',', '.');
            
            // Mark if from package
            if ($selection['locked'] ?? false) {
                $line .= " [Paket]";
            }
            
            $summary[] = $line;
        }
        
        return implode("\n", $summary);
    }
}
