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

        // Redirect client/user roles to their specific dashboard since they shouldn't see revenue reports
        if ($user->hasRole(['Client', 'User'])) {
            return redirect()->route('client.dashboard');
        }
        // For Vendor roles, calculate revenue from their assigned events
        else if ($user->hasRole('Vendor')) {
            // Determine the correct ID to use for querying events.
            $eventOwnerId = $user->id;

            // --- Basic Stats ---
            $myEvents = Event::where('user_id', $eventOwnerId)->withCount('guests')->get();
            $totalEvents = $myEvents->count();
            $upcomingEventsCount = $myEvents->where('start_time', '>', now())->count();
            $totalGuests = $myEvents->sum('guests_count');
            $recentEvents = $myEvents->sortByDesc('created_at')->take(5);

            // --- Revenue Calculation for Vendors ---
            $revenueQuery = Payment::query();

            // For vendors, get revenue from events they are assigned to
            $vendor = $user->vendor; // Assuming a user has one vendor profile
            if ($vendor) {
                $revenueQuery->whereHas('invoice.event.vendors', function ($query) use ($vendor) {
                    $query->where('vendor_id', $vendor->id);
                });
            } else {
                // If the user is a vendor but has no vendor profile, they have no revenue
                $revenueQuery->whereRaw('1 = 0');
            }

            // Apply filters
            $period = $request->input('filter_period', 'monthly'); // Default to monthly
            $selectRaw = "SUM(amount) as total_revenue";
            $groupBy = "";
            $orderBy = "";

            switch ($period) {
                case 'daily':
                    $selectRaw .= ", TO_CHAR(payment_date, 'YYYY-MM-DD') as period";
                    $groupBy = "period";
                    $orderBy = "period";
                    break;
                case 'weekly':
                    $selectRaw .= ", TO_CHAR(payment_date, 'YYYY-WW') as period";
                    $groupBy = "period";
                    $orderBy = "period";
                    break;
                case 'monthly':
                    $selectRaw .= ", TO_CHAR(payment_date, 'YYYY-MM') as period";
                    $groupBy = "period";
                    $orderBy = "period";
                    break;
                case 'yearly':
                    $selectRaw .= ", TO_CHAR(payment_date, 'YYYY') as period";
                    $groupBy = "period";
                    $orderBy = "period";
                    break;
            }

            $revenueOverTime = $revenueQuery->selectRaw($selectRaw)
                ->groupBy($groupBy)
                ->orderBy($orderBy)
                ->get();

            $totalRevenue = $revenueOverTime->sum('total_revenue');

            $revenueData = [
                'labels' => $revenueOverTime->pluck('period'),
                'data' => $revenueOverTime->pluck('total_revenue'),
            ];

            return view('user_dashboard', compact(
                'totalEvents',
                'upcomingEventsCount',
                'totalGuests',
                'recentEvents',
                'totalRevenue',
                'revenueData'
            ));
        }
        // For SuperUser and Owner roles, show the revenue dashboard
        else if ($user->hasRole('SuperUser') || $user->hasRole('Owner')) {
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
            $revenueQuery = Payment::query();

            if ($user->hasRole('SuperUser')) {
                // SuperUser can see all payment data
                // No additional restrictions needed
            } else {
                // For Owners and sub-users, get revenue from events owned by the Owner
                $revenueQuery->whereHas('invoice.event', function ($query) use ($eventOwnerId) {
                    $query->where('user_id', $eventOwnerId);
                });
            }

            // Apply filters
            $period = $request->input('filter_period', 'monthly'); // Default to monthly
            $selectRaw = "SUM(amount) as total_revenue";
            $groupBy = "";
            $orderBy = "";

            switch ($period) {
                case 'daily':
                    $selectRaw .= ", TO_CHAR(payment_date, 'YYYY-MM-DD') as period";
                    $groupBy = "period";
                    $orderBy = "period";
                    break;
                case 'weekly':
                    $selectRaw .= ", TO_CHAR(payment_date, 'YYYY-WW') as period";
                    $groupBy = "period";
                    $orderBy = "period";
                    break;
                case 'monthly':
                    $selectRaw .= ", TO_CHAR(payment_date, 'YYYY-MM') as period";
                    $groupBy = "period";
                    $orderBy = "period";
                    break;
                case 'yearly':
                    $selectRaw .= ", TO_CHAR(payment_date, 'YYYY') as period";
                    $groupBy = "period";
                    $orderBy = "period";
                    break;
            }

            $revenueOverTime = $revenueQuery->selectRaw($selectRaw)
                ->groupBy($groupBy)
                ->orderBy($orderBy)
                ->get();

            $totalRevenue = $revenueOverTime->sum('total_revenue');

            $revenueData = [
                'labels' => $revenueOverTime->pluck('period'),
                'data' => $revenueOverTime->pluck('total_revenue'),
            ];

            return view('user_dashboard', compact(
                'totalEvents',
                'upcomingEventsCount',
                'totalGuests',
                'recentEvents',
                'totalRevenue',
                'revenueData'
            ));
        }
        // For other roles, redirect to appropriate dashboard or show basic info
        else {
            // Default behavior for other roles
            $eventOwnerId = $user->id; // Use user's own ID for other roles

            $myEvents = Event::where('user_id', $eventOwnerId)->withCount('guests')->get();
            $totalEvents = $myEvents->count();
            $upcomingEventsCount = $myEvents->where('start_time', '>', now())->count();
            $totalGuests = $myEvents->sum('guests_count');
            $recentEvents = $myEvents->sortByDesc('created_at')->take(5);

            // For non-financial roles, show minimal revenue data
            $revenueData = [
                'labels' => [],
                'data' => []
            ];
            $totalRevenue = 0;

            return view('user_dashboard', compact(
                'totalEvents',
                'upcomingEventsCount',
                'totalGuests',
                'recentEvents',
                'totalRevenue',
                'revenueData'
            ));
        }
    }
}
