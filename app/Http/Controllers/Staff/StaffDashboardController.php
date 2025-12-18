<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCrew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    /**
     * Display staff dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get events where user is assigned as crew
        $myEvents = Event::whereHas('crews', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('crews')->get();
        
        // Calculate statistics
        $stats = [
            'total_events' => $myEvents->count(),
            'today_events' => $myEvents->filter(function($event) {
                return $event->start_time->isToday();
            })->count(),
            'this_week_events' => $myEvents->filter(function($event) {
                return $event->start_time->isCurrentWeek();
            })->count(),
            'upcoming_events' => $myEvents->filter(function($event) {
                return $event->start_time->isFuture();
            })->count(),
        ];
        
        // Get my role in each event
        $myRoles = [];
        foreach ($myEvents as $event) {
            $crew = $event->crews->where('user_id', $user->id)->first();
            $myRoles[$event->id] = $crew ? $crew->role : 'Crew';
        }
        
        // Upcoming events (next 7 days)
        $upcomingEvents = $myEvents->filter(function($event) {
            return $event->start_time->isFuture() && $event->start_time->diffInDays(now()) <= 7;
        })->sortBy('start_time')->take(5);
        
        return view('staff.dashboard', compact('stats', 'upcomingEvents', 'myRoles'));
    }
    
    /**
     * Display list of events assigned to staff
     */
    public function events(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'upcoming');
        
        $query = Event::whereHas('crews', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['crews' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }, 'venue']);
        
        // Apply filters
        switch ($filter) {
            case 'today':
                $query->whereDate('start_time', today());
                break;
            case 'upcoming':
                $query->where('start_time', '>=', now());
                break;
            case 'past':
                $query->where('start_time', '<', now());
                break;
            case 'this_week':
                $query->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
        }
        
        $events = $query->orderBy('start_time', 'asc')->paginate(15);
        
        // Get my role for each event
        $myRoles = [];
        foreach ($events as $event) {
            $crew = $event->crews->first();
            $myRoles[$event->id] = $crew ? $crew->role : 'Crew';
        }
        
        return view('staff.events.index', compact('events', 'myRoles', 'filter'));
    }
    
    /**
     * Show specific event detail for staff
     */
    public function showEvent(Event $event)
    {
        $user = Auth::user();
        
        // Check if user is assigned to this event
        $crew = $event->crews()->where('user_id', $user->id)->first();
        
        if (!$crew) {
            abort(403, 'You are not assigned to this event.');
        }
        
        $event->load(['venue', 'guests', 'vendors', 'crews.user']);
        
        return view('staff.events.show', compact('event', 'crew'));
    }
}
