<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use App\Models\EventPackage;
use App\Models\Vendor;
use App\Models\VendorCatalogItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingWizardController extends Controller
{
    /**
     * Start wizard - clear session and show Step 1 (mode selection).
     */
    public function start()
    {
        // Clear any existing wizard data
        session()->forget('booking_wizard');
        
        return view('client.booking.step1-mode');
    }

    /**
     * Store mode selection and redirect to appropriate Step 2.
     */
    public function storeMode(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:package,custom',
        ]);

        session(['booking_wizard.mode' => $request->mode]);

        if ($request->mode === 'package') {
            return redirect()->route('client.booking.packages');
        } else {
            return redirect()->route('client.booking.vendors');
        }
    }

    /**
     * Show event packages (Step 2a).
     */
    public function showPackages()
    {
        // Ensure mode is package
        if (session('booking_wizard.mode') !== 'package') {
            return redirect()->route('client.booking.start');
        }

        $packages = EventPackage::with(['items.vendorPackage.vendor'])
            ->where('is_active', true)
            ->get();

        return view('client.booking.step2a-packages', compact('packages'));
    }

    /**
     * Select package and proceed to Step 3.
     */
    public function selectPackage(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:event_packages,id',
        ]);

        session(['booking_wizard.package_id' => $request->package_id]);

        return redirect()->route('client.booking.details');
    }

    /**
     * Show vendors by category (Step 2b).
     */
    public function showVendors()
    {
        // Ensure mode is custom
        if (session('booking_wizard.mode') !== 'custom') {
            return redirect()->route('client.booking.start');
        }

        // Get the client's owner_id
        $clientOwnerId = Auth::user()->owner_id;
        
        // If client is an owner themselves, use their own ID
        if (!$clientOwnerId && Auth::user()->role === 'owner') {
            $clientOwnerId = Auth::id();
        }

        // DEBUG: Log client owner info
        \Log::info('Booking Wizard - Client Owner ID: ' . ($clientOwnerId ?? 'NULL'));
        \Log::info('Booking Wizard - Auth User Role: ' . (Auth::user()->role ?? 'NULL'));

        // Get all catalog items from vendors
        // TEMPORARILY REMOVED owner_id filter for debugging
        $catalogItems = VendorCatalogItem::with([
                'images',
                'vendor.user', 
                'vendor.portfolios.images'
            ])
            ->whereHas('vendor', function($query) {
                $query->whereNotNull('category');
            })
            ->get();

        // DEBUG: Log count
        \Log::info('Booking Wizard - Total catalog items found: ' . $catalogItems->count());
        
        // DEBUG: Log by category
        $byCategory = $catalogItems->groupBy(function($item) {
            return $item->vendor->category ?? 'NULL';
        });
        foreach ($byCategory as $cat => $items) {
            \Log::info("Booking Wizard - Category '$cat': " . $items->count() . " items");
        }

        $catalogItems = $catalogItems->map(function($item) {
                // Determine image priority:
                // 1. Catalog item's own images (vendor_catalog_images)
                // 2. Vendor portfolio images
                // 3. Vendor logo
                // 4. null
                $image = null;
                
                if ($item->images->isNotEmpty()) {
                    // Primary: catalog item's own images
                    $image = asset('storage/' . $item->images->first()->image_path);
                } elseif ($item->image_url) {
                    // Legacy: image_url field if exists
                    $image = asset('storage/' . $item->image_url);
                } elseif ($item->vendor->portfolios->isNotEmpty()) {
                    // Fallback 1: vendor portfolio first image
                    $portfolio = $item->vendor->portfolios->first();
                    if ($portfolio && $portfolio->images->isNotEmpty()) {
                        $image = asset('storage/' . $portfolio->images->first()->image_path);
                    }
                } elseif ($item->vendor->logo_path) {
                    // Fallback 2: vendor logo
                    $image = asset('storage/' . $item->vendor->logo_path);
                }
                
                return [
                    'id' => $item->id,
                    'vendor_id' => $item->vendor_id,
                    'vendor_name' => $item->vendor->brand_name ?? $item->vendor->name,
                    'category' => $item->vendor->category,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $item->price,
                    'image' => $image,
                ];
            })
            ->groupBy('category');

        // Define which categories are single-select (venue) vs multi-select
        $singleSelectCategories = ['Venue', 'venue', 'VENUE'];

        return view('client.booking.step2b-vendors', compact('catalogItems', 'singleSelectCategories'));
    }

    /**
     * Store catalog item selections and proceed to Step 3.
     */
    public function selectVendors(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'exists:vendor_catalog_items,id',
        ]);

        // Get the selected items with their vendor info
        $selectedItems = VendorCatalogItem::with('vendor')
            ->whereIn('id', $request->items)
            ->get();

        // Extract unique vendor IDs from selected items
        $vendorIds = $selectedItems->pluck('vendor_id')->unique()->values()->toArray();

        // Store both items and vendors in session
        session([
            'booking_wizard.items' => $request->items,
            'booking_wizard.vendors' => $vendorIds,
            'booking_wizard.selected_items_data' => $selectedItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'vendor_id' => $item->vendor_id,
                    'vendor_name' => $item->vendor->brand_name ?? $item->vendor->name,
                    'category' => $item->vendor->category,
                ];
            })->toArray()
        ]);

        return redirect()->route('client.booking.details');
    }

    /**
     * Show event details form (Step 3).
     */
    public function showEventDetails()
    {
        // Ensure previous steps completed
        $mode = session('booking_wizard.mode');
        
        if (!$mode) {
            return redirect()->route('client.booking.start');
        }

        if ($mode === 'package' && !session('booking_wizard.package_id')) {
            return redirect()->route('client.booking.packages');
        }

        if ($mode === 'custom' && !session('booking_wizard.items')) {
            return redirect()->route('client.booking.vendors');
        }

        // Get existing data if user is going back
        $eventDetails = session('booking_wizard.event_details', []);

        return view('client.booking.step3-details', compact('eventDetails'));
    }

    /**
     * Store event details and proceed to Step 4 (review).
     */
    public function storeEventDetails(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_type' => 'required|string|max:100',
            'event_date' => 'required|date|after:today',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'guest_count' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        session(['booking_wizard.event_details' => $validated]);

        return redirect()->route('client.booking.review');
    }

    /**
     * Show review page (Step 4).
     */
    public function showReview()
    {
        // Validate all wizard data exists
        $mode = session('booking_wizard.mode');
        $eventDetails = session('booking_wizard.event_details');

        if (!$mode || !$eventDetails) {
            return redirect()->route('client.booking.start')
                ->with('error', 'Session booking expired. Silakan mulai lagi.');
        }

        if ($mode === 'package') {
            $packageId = session('booking_wizard.package_id');
            if (!$packageId) {
                return redirect()->route('client.booking.packages');
            }

            $package = EventPackage::with(['items.vendorPackage.vendor'])->findOrFail($packageId);
            $estimatedTotal = $package->price;

            return view('client.booking.step4-review', compact('mode', 'eventDetails', 'package', 'estimatedTotal'));
        } else {
            $vendorIds = session('booking_wizard.vendors');
            if (!$vendorIds) {
                return redirect()->route('client.booking.vendors');
            }

            $selectedVendors = Vendor::with('catalogItems')->whereIn('id', $vendorIds)->get();
            
            // Calculate estimated total from vendor catalog items (simplified)
            $estimatedTotal = 0;
            foreach ($selectedVendors as $vendor) {
                if ($vendor->catalogItems->count() > 0) {
                    $estimatedTotal += $vendor->catalogItems->first()->price ?? 0;
                }
            }

            return view('client.booking.step4-review', compact('mode', 'eventDetails', 'selectedVendors', 'estimatedTotal'));
        }
    }

    /**
     * Submit booking - create ClientRequest and clear session.
     */
    public function submit()
    {
        $mode = session('booking_wizard.mode');
        $eventDetails = session('booking_wizard.event_details');

        if (!$mode || !$eventDetails) {
            return redirect()->route('client.booking.start')
                ->with('error', 'Session booking expired. Silakan mulai lagi.');
        }

        DB::beginTransaction();
        try {
            // Create ClientRequest
            $clientRequest = ClientRequest::create([
                'user_id' => Auth::id(),
                'event_name' => $eventDetails['event_name'],
                'event_type' => $eventDetails['event_type'],
                'event_date' => $eventDetails['event_date'],
                'location' => $eventDetails['location'],
                'guest_count' => $eventDetails['guest_count'],
                'notes' => $eventDetails['notes'] ?? null,
                'status' => 'pending',
                'event_package_id' => $mode === 'package' ? session('booking_wizard.package_id') : null,
            ]);

            // If custom mode, attach vendors as recommendations or direct
            if ($mode === 'custom') {
                $vendorIds = session('booking_wizard.vendors');
                
                // Create a recommendation for the client request
                $recommendation = $clientRequest->recommendations()->create([
                    'status' => 'pending',
                    'notes' => 'Auto-created from booking wizard',
                ]);

                // Attach vendors to recommendation
                foreach ($vendorIds as $vendorId) {
                    $vendor = Vendor::find($vendorId);
                    if ($vendor) {
                        $recommendation->items()->create([
                            'vendor_id' => $vendorId,
                            'category' => $vendor->category,
                            'min_price' => $vendor->catalogItems->first()->price ?? 0,
                            'max_price' => $vendor->catalogItems->last()->price ?? 0,
                            'status' => 'pending',
                        ]);
                    }
                }
            }

            DB::commit();

            // Clear wizard session
            session()->forget('booking_wizard');

            return redirect()->route('client.requests.show', $clientRequest)
                ->with('success', 'Booking berhasil dibuat! Tim kami akan segera menghubungi Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('client.booking.review')
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
