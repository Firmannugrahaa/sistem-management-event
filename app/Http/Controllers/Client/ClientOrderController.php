<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Vendor;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientOrderController extends Controller
{
    public function storeSelections(Request $request)
    {
        $request->validate([
            'selected_venue_id' => 'required|exists:venues,id',
            'selected_vendor_ids' => 'required|array|min:1',
            'selected_vendor_ids.*' => 'exists:vendors,id',
        ]);

        // Store selections in session
        $venue = Venue::find($request->selected_venue_id);
        $vendors = Vendor::whereIn('id', $request->selected_vendor_ids)->get();

        session([
            'selected_venue' => [
                'id' => $venue->id,
                'name' => $venue->name
            ],
            'selected_vendors' => $vendors->map(function($vendor) {
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->user ? $vendor->user->name : $vendor->contact_person
                ];
            })->toArray()
        ]);

        return redirect()->route('client.order.review');
    }

    public function review()
    {
        // The selections are stored in session from the landing page
        $selectedVenue = session('selected_venue');
        $selectedVendors = session('selected_vendors');

        if (!$selectedVenue || empty($selectedVendors)) {
            return redirect()->route('client.landing')->with('error', 'Please select a venue and at least one vendor first.');
        }

        // Get the actual venue and vendor models for display
        $venue = Venue::find($selectedVenue['id']);
        $vendors = Vendor::whereIn('id', collect($selectedVendors)->pluck('id'))->get();

        // Calculate total cost (venue only, as vendors don't have direct price field)
        $totalCost = $venue ? $venue->price : 0;
        // Vendors will have pricing handled separately through event-vendor agreements

        return view('client.order-review', compact(
            'venue',
            'vendors',
            'totalCost'
        ));
    }
    
    public function confirm(Request $request)
    {
        $selectedVenue = session('selected_venue') ?? request()->session()->get('selected_venue');
        $selectedVendors = session('selected_vendors') ?? request()->session()->get('selected_vendors');
        
        if (!$selectedVenue || empty($selectedVendors)) {
            return redirect()->route('client.landing')->with('error', 'Please select a venue and at least one vendor first.');
        }
        
        // Start transaction to ensure data consistency
        DB::transaction(function () use ($selectedVenue, $selectedVendors) {
            $user = Auth::user();
            
            // Create event first
            $event = Event::create([
                'name' => 'Event for ' . $user->name,
                'user_id' => $user->id,
                'description' => 'Event created through client portal',
                'start_time' => now()->addWeeks(4), // Default to 4 weeks from now
                'end_time' => now()->addWeeks(4)->addHours(4), // 4 hours event
                'status' => 'planned',
                'location' => $selectedVenue['name'],
            ]);
            
            // Link selected venue to the event
            $event->venue_id = $selectedVenue['id'];
            $event->save();
            
            // Attach selected vendors to the event
            foreach ($selectedVendors as $vendorData) {
                $event->vendors()->attach($vendorData['id'], [
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Generate invoice for the event
            $this->generateInvoice($event, $selectedVenue, $selectedVendors);
            
            // Clear the selections from session
            $request->session()->forget(['selected_venue', 'selected_vendors']);
        });
        
        return redirect()->route('client.dashboard')->with('success', 'Your order has been confirmed and invoice generated!');
    }
    
    private function generateInvoice($event, $selectedVenue, $selectedVendors)
    {
        $user = Auth::user();
        $totalAmount = 0;

        // Get venue price
        $venue = \App\Models\Venue::find($selectedVenue['id']);
        $totalAmount += $venue ? $venue->price : 0;

        // For now, we'll use a default price of 0 since vendors don't have a direct price field
        // In a real implementation, you might have a separate pricing system
        foreach ($selectedVendors as $vendorData) {
            // Vendor pricing would typically be determined when creating the event-vendor relationship
            // For now, we'll add 0 to the total and the actual pricing would be handled separately
        }

        // Create invoice
        $invoice = Invoice::create([
            'user_id' => $event->user_id, // Use the event owner's ID
            'event_id' => $event->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'due_date' => now()->addDays(7), // Due in 7 days
            'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'issue_date' => now()->toDateString(),
        ]);

        return $invoice;
    }
}