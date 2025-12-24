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
        try {
            \Log::info('Dashboard accessed');
            $user = auth()->user();
            \Log::info('User: ' . $user->id . ' - Role: ' . $user->roles->pluck('name')->implode(','));

            // Redirect Staff to their dedicated dashboard
            if ($user->hasRole('Staff')) {
                \Log::info('Redirecting Staff to staff dashboard');
                return redirect()->route('staff.dashboard');
            }

            // Redirect client/user roles to their specific dashboard since they shouldn't see revenue reports
            if ($user->hasRole(['Client', 'User'])) {
                \Log::info('Redirecting Client/User to client dashboard');
                return redirect()->route('client.dashboard');
            }

            // Redirect Vendor to their dedicated dashboard
            if ($user->hasRole('Vendor')) {
                \Log::info('Redirecting Vendor to vendor dashboard');
                return redirect()->route('vendor.dashboard');
            }

        // For SuperUser and Owner roles, show the revenue dashboard
        if ($user->hasRole('SuperUser') || $user->hasRole('Owner')) {
            \Log::info('Processing Owner/SuperUser dashboard');
            
            // Increase memory limit for dashboard processing
            ini_set('memory_limit', '1024M');
            
            // Determine the correct ID to use for querying events.
            // If user is an Owner, use their own ID. If they are a sub-user, use their owner's ID.
            $eventOwnerId = $user->hasRole('Owner') ? $user->id : $user->owner_id;
            \Log::info('Event Owner ID: ' . $eventOwnerId);

            // --- Basic Stats ---
            \Log::info('Fetching events...');
            // Select only necessary columns to reduce memory usage
            $myEvents = Event::where('user_id', $eventOwnerId)
                ->select('id', 'event_name', 'start_time', 'created_at')
                ->withCount('guests')
                ->get();
            \Log::info('Events fetched: ' . $myEvents->count());
            
            $totalEvents = $myEvents->count();
            $upcomingEventsCount = $myEvents->where('start_time', '>', now())->count();
            $totalGuests = $myEvents->sum('guests_count');
            $recentEvents = $myEvents->sortByDesc('created_at')->take(5)->values();
            \Log::info('Basic stats calculated');

            // --- Revenue Calculation ---
            \Log::info('Starting revenue calculation...');
            $revenueQuery = Payment::query();

            if ($user->hasRole('SuperUser')) {
                \Log::info('SuperUser - no revenue restrictions');
                // SuperUser can see all payment data
                // No additional restrictions needed
            } else {
                \Log::info('Owner - limiting to owner events');
                // For Owners and sub-users, get revenue from events owned by the Owner
                $revenueQuery->whereHas('invoice.event', function ($query) use ($eventOwnerId) {
                    $query->where('user_id', $eventOwnerId);
                });
            }

            // Apply filters
            \Log::info('Applying period filters...');
            $period = $request->input('filter_period', 'monthly'); // Default to monthly
            \Log::info('Period selected: ' . $period);
            
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

            \Log::info('Executing revenue query...');
            \Log::info('Query: ' . $revenueQuery->toSql());
            
            $revenueOverTime = $revenueQuery->selectRaw($selectRaw)
                ->groupBy($groupBy)
                ->orderBy($orderBy)
                ->get();
            
            \Log::info('Revenue query completed. Results: ' . $revenueOverTime->count());

            $totalRevenue = $revenueOverTime->sum('total_revenue');

            $revenueData = [
                'labels' => $revenueOverTime->pluck('period'),
                'data' => $revenueOverTime->pluck('total_revenue'),
            ];

            \Log::info('About to render user_dashboard view');
            \Log::info('Current memory usage: ' . memory_get_usage(true) / 1024 / 1024 . ' MB');
            \Log::info('Peak memory usage: ' . memory_get_peak_usage(true) / 1024 / 1024 . ' MB');
            
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
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}
