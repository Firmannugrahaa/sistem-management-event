<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\Vendor;
use App\Models\Venue;
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
        if (!$request->has('client_request_id') && !$request->has('direct_booking')) {
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
        if ($request->has('client_request_id')) {
            $clientRequest = ClientRequest::findOrFail($request->client_request_id);
        }

        return view('events.create', compact('venues', 'vendorVenues', 'clientRequest'));
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
                ]);
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

        $event->load('guests.ticket', 'vendors');

        $all_vendors = Vendor::orderBy('name')->get();
        return view('events.show', compact('event', 'all_vendors'));
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
        $totalAmount = $event->vendors()->sum('agreed_price');

        // 2. Data untuk Invoice
        $invoiceData = [
            'total_amount' => $totalAmount, //
            'status' => 'Unpaid' // Set status jadi Unpaid
        ];

        // 3. Cek apakah event ini SUDAH punya invoice?
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
}
