<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use App\Models\LeadRecommendation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends Controller
{
    /**
     * Client Dashboard - List of Requests
     */
    public function index(Request $request)
    {
        $query = ClientRequest::where('user_id', Auth::id())
            ->with(['recommendations' => function($q) {
                $q->latest();
            }]);

        // Filter by Status (Detailed Status maps better to user view)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('detailed_status', $request->status);
        }

        // Filter by Date
        if ($request->filled('date')) {
             $query->whereDate('event_date', $request->date);
        }

        // Search by ID or Event Type
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('event_type', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('client.dashboard.index', compact('requests'));
    }

    /**
     * Show Request Detail
     */
    public function show(ClientRequest $clientRequest)
    {
        // Security: Ensure this request belongs to the logged-in user
        if ($clientRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $clientRequest->load([
            'recommendations.items', 
            'event',
            'eventPackage.items.vendorCatalogItem.vendor.serviceType',
            'eventPackage.items.vendorPackage.vendor.serviceType',
            'vendor.serviceType'
        ]);

        return view('client.dashboard.show', compact('clientRequest'));
    }

    /**
     * Update Request (Safe Edit for Client)
     */
    public function update(Request $request, ClientRequest $clientRequest)
    {
        // Security check
        if ($clientRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'groom_name' => 'nullable|string|max:255',
            'bride_name' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        $clientRequest->update([
            'groom_name' => $validated['groom_name'] ?? $clientRequest->groom_name,
            'bride_name' => $validated['bride_name'] ?? $clientRequest->bride_name,
            'message' => $validated['message'] ?? $clientRequest->message,
        ]);

        return back()->with('success', 'Data booking berhasil diperbarui.');
    }

    /**
     * Show Recommendation Detail
     */
    public function showRecommendation(LeadRecommendation $recommendation)
    {
        // Security check
        if ($recommendation->clientRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow viewing if sent
        if ($recommendation->status === 'draft') {
            abort(404);
        }

        $recommendation->load(['items', 'creator']);

        return view('client.dashboard.recommendation', compact('recommendation'));
    }

    /**
     * Respond to Recommendation (Accept/Reject)
     */
    public function respondRecommendation(Request $request, LeadRecommendation $recommendation)
    {
        if ($recommendation->clientRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'action' => 'required|in:accept,reject,revision',
            'feedback' => 'nullable|string|required_if:action,reject,revision',
        ]);

        DB::beginTransaction();

        try {
            $statusMap = [
                'accept' => 'accepted',
                'reject' => 'rejected',
                'revision' => 'revision_requested',
            ];

            $newStatus = $statusMap[$validated['action']];

            $recommendation->update([
                'status' => $newStatus,
                'responded_at' => now(),
                'client_feedback' => $validated['feedback'] ?? null,
            ]);

            // Update Lead Status based on action
            $clientRequest = $recommendation->clientRequest;
            
            if ($newStatus === 'accepted') {
                $clientRequest->update(['detailed_status' => 'approved']);
                // Notify Admin: Recommendation Accepted!
            } elseif ($newStatus === 'revision_requested') {
                $clientRequest->update(['detailed_status' => 'revision_requested']);
                // Notify Admin: Revision Requested
            } elseif ($newStatus === 'rejected') {
                $clientRequest->update(['detailed_status' => 'rejected']);
            }

            ActivityLog::log(
                'client_response',
                $clientRequest,
                "Client responded to recommendation '{$recommendation->title}': " . ucfirst($validated['action']),
                ['feedback' => $validated['feedback'] ?? null]
            );

            DB::commit();

            return back()->with('success', 'Your response has been submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit response.');
        }
    }

    /**
     * Accept a specific recommendation item
     */
    public function acceptItem(Request $request, \App\Models\RecommendationItem $item)
    {
        // Security check
        if ($item->recommendation->clientRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $item->update(['status' => 'accepted', 'rejection_reason' => null]);

        // Auto-assign logic
        $clientRequest = $item->recommendation->clientRequest;
        
        // If it's a main vendor role, update client request
        if ($item->vendor_id) {
            // Check if this category matches main vendor roles
            if (in_array(strtolower($item->category), ['venue', 'organized', 'wedding organizer', 'wo'])) {
                $clientRequest->vendor_id = $item->vendor_id;
                $clientRequest->save();
            }
        }
        
        // Check for readiness transition
        if ($clientRequest->hasCompleteData() && $clientRequest->detailed_status === 'recommendation_sent') {
             $clientRequest->detailed_status = 'ready_to_confirm';
             $clientRequest->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Reject a specific recommendation item
     */
    public function rejectItem(Request $request, \App\Models\RecommendationItem $item)
    {
         if ($item->recommendation->clientRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $item->update([
            'status' => 'rejected', 
            'rejection_reason' => $request->input('reason')
        ]);

        return response()->json(['success' => true]);
    }
}