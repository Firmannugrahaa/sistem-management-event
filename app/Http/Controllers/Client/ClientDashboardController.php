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
    public function index()
    {
        $requests = ClientRequest::where('user_id', Auth::id())
            ->with(['recommendations' => function($q) {
                $q->latest();
            }])
            ->orderBy('created_at', 'desc')
            ->get();

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

        $clientRequest->load(['recommendations.items', 'event']);

        return view('client.dashboard.show', compact('clientRequest'));
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
}