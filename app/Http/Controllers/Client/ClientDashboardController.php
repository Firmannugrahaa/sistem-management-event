<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Vendor;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get client's events
        $events = Event::where('user_id', $user->id)->latest()->get();
        $eventCount = $events->count();

        // Get events by status
        $runningEvents = Event::where('user_id', $user->id)
                              ->whereIn('status', ['running', 'active', 'in_progress'])
                              ->get();
        $completedEvents = Event::where('user_id', $user->id)
                                ->whereIn('status', ['completed', 'finished', 'done'])
                                ->get();
        $cancelledEvents = Event::where('user_id', $user->id)
                                ->whereIn('status', ['cancelled', 'canceled', 'batal'])
                                ->get();
        $pendingEvents = Event::where('user_id', $user->id)
                              ->whereIn('status', ['pending', 'planned', 'upcoming'])
                              ->get();

        // Get available venues for selection
        $venues = Venue::all();

        // Get available vendors for selection
        $vendors = Vendor::all();

        // Check if the client has events that need payment
        $pendingInvoices = Invoice::whereHas('event', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'pending')->get();

        return view('client.dashboard', compact(
            'events',
            'eventCount',
            'runningEvents',
            'completedEvents',
            'cancelledEvents',
            'pendingEvents',
            'venues',
            'vendors',
            'pendingInvoices'
        ));
    }
}