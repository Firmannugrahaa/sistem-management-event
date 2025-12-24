<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\Vendor;
use App\Models\Venue;
use App\Models\EventPackage;
use App\Models\RecommendationItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with(['user', 'venue'])
            ->latest()
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // 1. If no mode selected, show Wizard
        if (!$request->has('client_request_id') && !$request->has('direct_booking') && !$request->has('package_id')) {
            // Get approved requests that don't have an event yet
            $approvedRequests = ClientRequest::whereIn('detailed_status', ['approved', 'recommendation_sent'])
                ->whereDoesntHave('event')
                ->orderBy('event_date', 'asc')
                ->get();
                
            return view('events.wizard', compact('approvedRequests'));
        }

        // 2. Prepare Data for Form
        $venues = Venue::orderBy('name')->get();
        $vendorVenues = Vendor::where('service_type_id', 22)
                                ->with(['user', 'services' => function($query) {
                                    $query->wherePivot('is_available', true);
                                }])
                                ->get();

        $clientRequest = null;
        $venueData = null;
        
        if ($request->has('client_request_id')) {
            $clientRequest = ClientRequest::findOrFail($request->client_request_id);
            
            // Extract venue data from client's booking
            $venueData = $this->extractVenueFromClientRequest($clientRequest);
        }

        $package = null;
        if ($request->has('package_id')) {
            $package = EventPackage::with('items.vendorCatalogItem', 'items.vendorPackage')->find($request->package_id);
        }

        return view('events.create', compact('venues', 'vendorVenues', 'clientRequest', 'package', 'venueData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'venue_type' => 'required|in:none,standard,vendor',
            'venue_id' => 'nullable|exists:venues,id',
            'vendor_venue_id' => 'nullable|exists:vendors,id',
            'vendor_venue_name' => 'nullable|string|max:255',
            'vendor_venue_price' => 'nullable|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string',
            'client_request_id' => 'nullable|exists:client_requests,id', // New field
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // 1. Handle Client Request Logic
            $clientRequestId = $validated['client_request_id'] ?? null;

            // If Direct Booking (No ID provided), create a new Client Request automatically
            if (!$clientRequestId) {
                // We need client details for direct booking
                if (empty($validated['client_name']) || empty($validated['client_email'])) {
                    throw new \Exception('Client Name and Email are required for direct booking.');
                }

                // Check/Create User
                $user = \App\Models\User::where('email', $validated['client_email'])->first();
                if (!$user) {
                    $password = \Illuminate\Support\Str::random(10);
                    $user = \App\Models\User::create([
                        'name' => $validated['client_name'],
                        'email' => $validated['client_email'],
                        'password' => \Illuminate\Support\Facades\Hash::make($password),
                        'phone_number' => $validated['client_phone'] ?? null,
                    ]);
                    $user->assignRole('User');
                }

                $newRequest = ClientRequest::create([
                    'user_id' => $user->id,
                    'client_name' => $validated['client_name'],
                    'client_email' => $validated['client_email'],
                    'client_phone' => $validated['client_phone'],
                    'event_date' => $validated['start_time'], // Use start time as event date
                    'event_type' => 'Direct Booking', // Default type
                    'status' => 'done', // Mark as done immediately
                    'detailed_status' => 'converted_to_event',
                    'request_source' => 'direct_booking',
                ]);
                $clientRequestId = $newRequest->id;
            } else {
                // Update existing request status
                $existingRequest = ClientRequest::find($clientRequestId);
                $existingRequest->update([
                    'status' => 'done',
                    'detailed_status' => 'converted_to_event'
                ]);
            }

            // 2. Prepare Event Data
            $eventData = [
                'client_request_id' => $clientRequestId, // Link to request
                'event_name' => $validated['event_name'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'description' => $validated['description'] ?? null,
                'client_name' => $validated['client_name'] ?? null,
                'client_phone' => $validated['client_phone'] ?? null,
                'client_email' => $validated['client_email'] ?? null,
                'client_address' => $validated['client_address'] ?? null,
            ];

            // Set venue_id based on venue_type selection
            if ($validated['venue_type'] === 'standard' && !empty($validated['venue_id'])) {
                $eventData['venue_id'] = $validated['venue_id'];
            } else {
                $eventData['venue_id'] = null;
            }

            // 3. Create the event
            $event = $request->user()->events()->create($eventData);

            // 4. Handle Vendor Venue
            if ($validated['venue_type'] === 'vendor' && !empty($validated['vendor_venue_id'])) {
                $event->vendors()->attach($validated['vendor_venue_id'], [
                    'agreed_price' => $validated['vendor_venue_price'] ?? 0,
                    'contract_details' => "Venue: " . ($validated['vendor_venue_name'] ?? 'Vendor Venue'),
                    'status' => 'Confirmed',
                    'source' => 'venue_selection',
                ]);
                
                // Auto-check checklist items for venue vendor
                $vendor = \App\Models\Vendor::find($validated['vendor_venue_id']);
                if ($vendor) {
                    \App\Services\ChecklistVendorMappingService::autoCheckItems($event->id, $vendor);
                }
            }

            // 5. Attach ALL vendors from ClientRequest recommendations
            if ($clientRequestId) {
                $clientRequest = ClientRequest::with(['recommendations.items.vendor'])->find($clientRequestId);
                
                if ($clientRequest) {
                    // Get all accepted/pending recommendation items with vendors
                    foreach ($clientRequest->recommendations as $recommendation) {
                        foreach ($recommendation->items as $item) {
                            // Skip if no vendor (external vendor) or already attached (venue)
                            if (!$item->vendor_id) {
                                continue;
                            }
                            
                            // Skip if this vendor was already attached as venue
                            if ($validated['venue_type'] === 'vendor' 
                                && $validated['vendor_venue_id'] == $item->vendor_id) {
                                continue;
                            }
                            
                            // Check if vendor is not already attached to event
                            if (!$event->vendors()->where('vendor_id', $item->vendor_id)->exists()) {
                                $event->vendors()->attach($item->vendor_id, [
                                    'agreed_price' => $item->estimated_price ?? 0,
                                    'contract_details' => $item->service_name ?? $item->category ?? 'Vendor Service',
                                    'status' => $item->status === 'accepted' ? 'Confirmed' : 'Negotiation',
                                    'source' => $item->status === 'accepted' ? 'recommendation' : 'client_selection',
                                ]);
                                
                                // Auto-check checklist items for this vendor
                                if ($item->vendor && $item->status === 'accepted') {
                                    \App\Services\ChecklistVendorMappingService::autoCheckItems($event->id, $item->vendor);
                                }
                            }
                        }
                    }
                    
                    // Also transfer non-partner vendor charges
                    if ($clientRequest->nonPartnerCharges) {
                        foreach ($clientRequest->nonPartnerCharges as $charge) {
                            $charge->update(['event_id' => $event->id]);
                        }
                    }
                }
            }

            // 6. Auto-copy assigned staff from Client Request to Event Crews
            if ($clientRequestId) {
                $clientRequest = ClientRequest::find($clientRequestId);
                
                // Check if client request has an assigned staff member
                if ($clientRequest && $clientRequest->assigned_to) {
                    // Create event crew entry for the assigned staff
                    \App\Models\EventCrew::create([
                        'event_id' => $event->id,
                        'user_id' => $clientRequest->assigned_to,
                        'role' => 'Staff', // Match system role
                    ]);
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('events.index')->with('success', 'Event created successfully (Linked to Client Request).');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Failed to create event: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     * (Halaman detail event untuk menampilkan daftar tamu)
     */
    public function show(Event $event)
    {
        $this->authorize('view', $event);

        $event->load([
            'guests.ticket', 
            'vendors.serviceType', 
            'crews.user', 
            'vendorItems.itemable',
            'clientRequest.eventPackage.items.vendorCatalogItem.vendor',
            'clientRequest.recommendations.items.vendor',
            'clientRequest.nonPartnerCharges'
        ]);

        $all_vendors = Vendor::orderBy('brand_name')->get();
        $all_users = \App\Models\User::orderBy('name')->get();
        
        // Build comprehensive vendor summary
        $vendorSummary = $this->buildVendorSummary($event);
        
        return view('events.show', compact('event', 'all_vendors', 'all_users', 'vendorSummary'));
    }
    
    /**
     * Build comprehensive vendor summary for event detail
     */
    private function buildVendorSummary(Event $event): array
    {
        $summary = [
            'vendors' => [],
            'external_vendors' => [],
            'non_partner_charges' => [],
            'total' => 0
        ];
        
        // 1. Attached vendors (from package, recommendation, manual)
        foreach ($event->vendors as $vendor) {
            $vendorData = [
                'id' => $vendor->id,
                'name' => $vendor->brand_name ?? $vendor->name,
                'category' => $vendor->serviceType?->name ?? $vendor->category ?? 'Lainnya',
                'source' => $vendor->pivot->source ?? 'manual',
                'agreed_price' => $vendor->pivot->agreed_price ?? 0,
                'status' => $vendor->pivot->status ?? 'pending',
                'items' => [],
                'subtotal' => $vendor->pivot->agreed_price ?? 0
            ];
            
            // Get vendor items/add-ons
            $vendorItems = $event->vendorItems()->where('vendor_id', $vendor->id)->get();
            foreach ($vendorItems as $item) {
                $itemPrice = $item->price * $item->quantity;
                $vendorData['items'][] = [
                    'name' => $item->itemable?->name ?? 'Item',
                    'description' => $item->notes,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $itemPrice
                ];
                // Note: items might already be included in agreed_price, check your business logic
            }
            
            $summary['vendors'][] = $vendorData;
            $summary['total'] += $vendorData['subtotal'];
        }
        
        // 2. External vendors from recommendations 
        if ($event->clientRequest) {
            foreach ($event->clientRequest->recommendations as $rec) {
                foreach ($rec->items as $item) {
                    if ($item->client_response === 'approved' && !$item->vendor_id && $item->external_vendor_name) {
                        $extData = [
                            'name' => $item->external_vendor_name,
                            'category' => $item->category,
                            'price' => $item->estimated_price ?? 0,
                            'notes' => $item->notes
                        ];
                        $summary['external_vendors'][] = $extData;
                        $summary['total'] += $extData['price'];
                    }
                }
            }
            
            // 3. Non-partner charges
            foreach ($event->clientRequest->nonPartnerCharges ?? [] as $charge) {
                $chargeData = [
                    'description' => $charge->description,
                    'amount' => $charge->amount
                ];
                $summary['non_partner_charges'][] = $chargeData;
                $summary['total'] += $chargeData['amount'];
            }
        }
        
        return $summary;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        $venues = Venue::orderBy('name')->get();
        $vendorVenues = Vendor::where('service_type_id', 22) // Service type ID 22 is 'Venue'
                                ->with(['user', 'services' => function($query) {
                                    $query->wherePivot('is_available', true);
                                }])
                                ->get();

        // Determine the current venue type for the event
        $currentVenueType = 'none';
        if ($event->venue_id) {
            $currentVenueType = 'standard';
        } else {
            // Check if there's a vendor assigned as venue
            $vendorVenue = $event->vendors()->whereHas('serviceType', function($q) {
                $q->where('name', 'Venue');
            })->first();
            if ($vendorVenue) {
                $currentVenueType = 'vendor';
            }
        }

        return view('events.edit', compact('event', 'venues', 'vendorVenues', 'currentVenueType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'venue_type' => 'required|in:none,standard,vendor',
            'venue_id' => 'nullable|exists:venues,id',
            'vendor_venue_id' => 'nullable|exists:vendors,id',
            'vendor_venue_name' => 'nullable|string|max:255',
            'vendor_venue_price' => 'nullable|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string',
        ]);

        // Handle venue selection logic
        $eventData = [
            'event_name' => $validated['event_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'description' => $validated['description'] ?? null,
            'client_name' => $validated['client_name'] ?? null,
            'client_phone' => $validated['client_phone'] ?? null,
            'client_email' => $validated['client_email'] ?? null,
            'client_address' => $validated['client_address'] ?? null,
        ];

        // Set venue_id based on venue_type selection
        if ($validated['venue_type'] === 'standard' && !empty($validated['venue_id'])) {
            $eventData['venue_id'] = $validated['venue_id'];
        } else {
            $eventData['venue_id'] = null; // No standard venue selected
        }

        // Update the event
        $event->update($eventData);

        // Remove any existing vendor venue assignments
        $existingVenueVendors = $event->vendors()->whereHas('serviceType', function($q) {
            $q->where('name', 'Venue');
        })->get();

        foreach ($existingVenueVendors as $vendor) {
            $event->vendors()->detach($vendor->id);
        }

        // If vendor venue is selected, assign the vendor as a venue vendor to the event
        if ($validated['venue_type'] === 'vendor' && !empty($validated['vendor_venue_id'])) {
            $event->vendors()->attach($validated['vendor_venue_id'], [
                'agreed_price' => $validated['vendor_venue_price'] ?? 0,
                'contract_details' => "Venue: " . ($validated['vendor_venue_name'] ?? 'Vendor Venue'),
                'status' => 'Confirmed', // or Negotiation based on your business logic
            ]);
        }

        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus.');
    }

    public function assignVendor(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'agreed_price' => 'nullable|numeric|min:0',
            'contract_details' => 'nullable|string',
        ]);

        $event->vendors()->attach($request->vendor_id, [
            'agreed_price' => $request->agreed_price,
            'contract_details' => $request->contract_details,
            'status' => 'Negotiation',
        ]);
        
        // Auto-check checklist items if status is confirmed
        // Note: For 'Negotiation' status, items will be checked when status changes to 'Confirmed'
        // You can add status update endpoint to handle this

        return back()->with('success', 'Vendor berhasil ditugaskan ke event.');
    }


    public function detachVendor(Event $event, Vendor $vendor)
    {
        $this->authorize('update', $event);

        $event->vendors()->detach($vendor->id);

        return back()->with('success', 'Vendor berhasil dihapus dari event.');
    }
    public function generateInvoice(Event $event)
    {
        $this->authorize('update', $event);

        // 1. Hitung total biaya dari pivot table vendor
        // Kita panggil relasi vendors() dan SUM 'agreed_price'
        $vendorTotal = $event->vendors()->sum('agreed_price');
        
        // 2. Hitung biaya non-partner vendor charges
        $nonPartnerTotal = $event->nonPartnerCharges()->sum('charge_amount');
        
        // 3. Total = vendor costs + non-partner charges
        $totalAmount = $vendorTotal + $nonPartnerTotal;

        // 4. Data untuk Invoice
        $invoiceData = [
            'total_amount' => $totalAmount,
            'status' => 'Unpaid' // Set status jadi Unpaid
        ];

        // 5. Cek apakah event ini SUDAH punya invoice?
        if ($event->invoice) {
            // Jika sudah, update saja
            $event->invoice->update($invoiceData);
        } else {
            // Jika belum, buat baru
            $invoiceData['event_id'] = $event->id;
            $invoiceData['invoice_number'] = 'INV-' . $event->id . '-' . strtoupper(Str::random(4));
            $invoiceData['issue_date'] = now();
            $invoiceData['due_date'] = $event->start_time; // Jatuh tempo = tgl event

            Invoice::create($invoiceData);
        }

        return back()->with('success', 'Invoice berhasil di-generate/diperbarui.');
    }

    /**
     * Update vendor status for an event
     */
    public function updateVendorStatus(Request $request, Event $event, Vendor $vendor)
    {
        $request->validate([
            'status' => 'required|in:Negotiation,Confirmed,Cancelled',
        ]);

        // Get current status
        $currentPivot = $event->vendors()->where('vendor_id', $vendor->id)->first();
        $oldStatus = $currentPivot ? $currentPivot->pivot->status : null;

        // Update vendor status in pivot table
        $event->vendors()->updateExistingPivot($vendor->id, [
            'status' => $request->status,
        ]);

        // Handle checklist auto-check based on status change
        if ($request->status === 'Confirmed' && $oldStatus !== 'Confirmed') {
            // Auto-check checklist items when vendor is confirmed
            \App\Services\ChecklistVendorMappingService::autoCheckItems($event->id, $vendor);
        } elseif ($request->status === 'Cancelled' && $oldStatus === 'Confirmed') {
            // Uncheck checklist items when vendor is cancelled (was previously confirmed)
            \App\Services\ChecklistVendorMappingService::uncheckItems($event->id, $vendor);
        }

        return back()->with('success', "Status vendor berhasil diubah menjadi {$request->status}.");
    }

    /**
     * Extract venue data from client request based on priority:
     * 1. Package venue (read-only)
     * 2. Accepted recommendation venue
     * 3. Manual/external venue selection
     */
    private function extractVenueFromClientRequest(ClientRequest $clientRequest): ?array
    {
        // Priority 1: From Event Package
        if ($clientRequest->event_package_id) {
            $package = EventPackage::with(['items.vendorCatalogItem.vendor', 'items.vendorPackage.vendor'])
                ->find($clientRequest->event_package_id);
            
            if ($package) {
                foreach ($package->items as $item) {
                    // Check vendorCatalogItem first
                    $vendor = $item->vendorCatalogItem?->vendor;
                    
                    // Fallback to vendorPackage
                    if (!$vendor && $item->vendorPackage) {
                        $vendor = $item->vendorPackage->vendor;
                    }
                    
                    if ($vendor && strtolower($vendor->category ?? '') === 'venue') {
                        return [
                            'type' => 'vendor',
                            'vendor_id' => $vendor->id,
                            'vendor_name' => $vendor->brand_name ?? $vendor->name ?? 'Venue',
                            'price' => $item->unit_price ?? $item->total_price ?? 0,
                            'source' => 'package',
                            'read_only' => true,
                        ];
                    }
                }
            }
        }
        
        // Priority 2: From accepted Recommendation (partner vendor)
        $acceptedVenueItem = RecommendationItem::whereHas('recommendation', function($q) use ($clientRequest) {
            $q->where('client_request_id', $clientRequest->id);
        })
        ->where(function($q) {
            $q->where('category', 'Venue')
              ->orWhere('category', 'venue')
              ->orWhere('category', 'VENUE');
        })
        ->where('status', 'accepted')
        ->with('vendor')
        ->first();
        
        if ($acceptedVenueItem) {
            // Partner vendor venue
            if ($acceptedVenueItem->vendor) {
                return [
                    'type' => 'vendor',
                    'vendor_id' => $acceptedVenueItem->vendor->id,
                    'vendor_name' => $acceptedVenueItem->vendor->brand_name ?? $acceptedVenueItem->vendor->name ?? 'Venue',
                    'price' => $acceptedVenueItem->estimated_price ?? 0,
                    'source' => 'recommendation',
                    'read_only' => false,
                ];
            }
            
            // External/non-partner venue
            if ($acceptedVenueItem->external_vendor_name) {
                return [
                    'type' => 'external',
                    'external_name' => $acceptedVenueItem->external_vendor_name,
                    'price' => $acceptedVenueItem->estimated_price ?? 0,
                    'source' => 'manual',
                    'read_only' => false,
                ];
            }
        }
        
        // Priority 3: Check for pending venue recommendations (client's own selection)
        $pendingVenueItem = RecommendationItem::whereHas('recommendation', function($q) use ($clientRequest) {
            $q->where('client_request_id', $clientRequest->id);
        })
        ->where(function($q) {
            $q->where('category', 'Venue')
              ->orWhere('category', 'venue')
              ->orWhere('category', 'VENUE');
        })
        ->with('vendor')
        ->first();
        
        if ($pendingVenueItem && $pendingVenueItem->vendor) {
            return [
                'type' => 'vendor',
                'vendor_id' => $pendingVenueItem->vendor->id,
                'vendor_name' => $pendingVenueItem->vendor->brand_name ?? $pendingVenueItem->vendor->name ?? 'Venue',
                'price' => $pendingVenueItem->estimated_price ?? 0,
                'source' => 'client_selection',
                'read_only' => false,
            ];
        }
        
        return null;
    }

    /**
     * Update event status (manual override or revert to auto)
     */
    public function updateStatus(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'status' => 'nullable|in:Planning,Confirmed,Ongoing,Completed,Cancelled',
        ]);

        if (empty($validated['status'])) {
            // Revert to auto-calculation
            $event->update([
                'status' => null,
                'manual_status_override' => false,
            ]);
            return back()->with('success', 'Status reverted to smart auto-calculation.');
        }

        // Prevent setting 'Completed' if invoice is not paid
        if ($validated['status'] === 'Completed') {
            $invoice = $event->invoice;
            
            if (!$invoice) {
                // Determine if we should allow completion without invoice?
                // For now, allow it but maybe warn? Or blocking?
                // Let's assume blocking for now as specified
                return back()->with('error', 'Cannot complete event. No invoice generated yet.');
            }

            // Ensure dynamic balance is used
            $balanceDue = $invoice->balance_due;
            
            if ($balanceDue > 0) {
                 return back()->with('error', 'Cannot complete event. Outstanding invoice balance of Rp ' . number_format($balanceDue, 0, ',', '.') . ' exists. Please settle the payment first.');
            }
        }

        $event->update([
            'status' => $validated['status'],
            'manual_status_override' => true,
        ]);

        // [SYNC] Update Client Request to 'Completed' if Event is 'Completed'
        if ($validated['status'] === 'Completed' && $event->clientRequest) {
            $event->clientRequest->update([
                'detailed_status' => 'completed'
            ]);
        }

        return back()->with('success', 'Event status updated to ' . $validated['status']);
    }
}
