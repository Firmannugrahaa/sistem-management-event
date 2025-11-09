<?php

namespace App\Http\Controllers;

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
    public function create()
    {
        $venues = Venue::orderBy('name')->get();
        return view('events.create', compact('venues'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'venue_id' => 'nullable|exists:venues,id', // 'nullable' lebih baik (sesuai migrasi)
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
        ]);

        $request->user()->events()->create($validated);

        return redirect()->route('events.index')->with('success', 'Event berhasil dibuat.');
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
        return view('events.edit', compact('event', 'venues'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'venue_id' => 'nullable|exists:venues,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
        ]);

        $event->update($validated);

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
