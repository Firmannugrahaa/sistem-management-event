<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCrew;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventCrewController extends Controller
{
    /**
     * Store a newly created crew member in storage.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event); // Assuming event update policy applies

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|max:255',
        ]);

        // Prevent duplicate if desired? 
        // For now, allow multiple roles? Or check existing?
        // Let's prevent same user same role?
        $exists = $event->crews()->where('user_id', $validated['user_id'])->where('role', $validated['role'])->exists();
        if ($exists) {
            return back()->with('error', 'User already assigned with this role.');
        }

        EventCrew::create([
            'event_id' => $event->id,
            'user_id' => $validated['user_id'],
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'Crew member added successfully.');
    }

    /**
     * Remove the specified crew member from storage.
     */
    public function destroy(Event $event, EventCrew $crew)
    {
        $this->authorize('update', $event);
        
        // Ensure crew belongs to event
        if ($crew->event_id !== $event->id) {
            abort(404);
        }

        $crew->delete();

        return back()->with('success', 'Crew member removed successfully.');
    }
}
