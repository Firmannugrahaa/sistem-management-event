<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        // Security Check: Ensure user is the client of this event
        if ($event->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Security Check: Ensure event is Completed
        if ($event->status !== 'Completed') {
            return back()->with('error', 'Reviews can only be submitted for completed events.');
        }

        // Security Check: Ensure vendor actually worked on this event
        $isVendorAssigned = $event->vendors()->where('vendors.id', $request->vendor_id)->exists();
        if (!$isVendorAssigned) {
             return back()->with('error', 'This vendor was not assigned to your event.');
        }

        Review::create([
            'event_id' => $event->id,
            'vendor_id' => $request->vendor_id,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
