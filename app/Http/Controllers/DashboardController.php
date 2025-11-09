<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Determine the correct ID to use for querying events.
        // If user is an Owner, use their own ID. If they are a sub-user, use their owner's ID.
        $eventOwnerId = $user->hasRole('Owner') ? $user->id : $user->owner_id;

        // --- Basic Stats ---
        $myEvents = Event::where('user_id', $eventOwnerId)->withCount('guests')->get();
        $totalEvents = $myEvents->count();
        $upcomingEventsCount = $myEvents->where('start_time', '>', now())->count();
        $totalGuests = $myEvents->sum('guests_count');
        $recentEvents = $myEvents->sortByDesc('created_at')->take(5);

        // --- Revenue Calculation ---
        $revenueQuery = Payment::query()
            ->whereHas('invoice.event', function ($query) use ($eventOwnerId) {
                $query->where('user_id', $eventOwnerId);
            });

        // Apply filters
        if ($request->filled('filter_event_id')) {
            $revenueQuery->whereHas('invoice', function ($query) use ($request) {
                $query->where('event_id', $request->filter_event_id);
            });
        }
        if ($request->filled('filter_period')) {
            $period = $request->filter_period;
            if ($period == 'daily') {
                $revenueQuery->whereDate('payment_date', today());
            } elseif ($period == 'monthly') {
                $revenueQuery->whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year);
            } elseif ($period == 'yearly') {
                $revenueQuery->whereYear('payment_date', now()->year);
            }
        }

        $totalRevenue = $revenueQuery->sum('amount');

        // Get events for the filter dropdown
        $eventsForFilter = Event::where('user_id', $eventOwnerId)
            ->orderBy('event_name')
            ->get();

        return view('user_dashboard', compact(
            'totalEvents',
            'upcomingEventsCount',
            'totalGuests',
            'recentEvents',
            'totalRevenue',
            'eventsForFilter'
        ));
    }
}
