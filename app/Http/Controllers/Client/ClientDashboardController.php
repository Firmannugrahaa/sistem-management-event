<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use App\Models\LeadRecommendation;
use App\Models\RecommendationItem;
use App\Models\ActivityLog;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends Controller
{
    /**
     * Client Dashboard - Main page with functional blocks
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all requests for this user
        $allRequests = ClientRequest::where('user_id', $user->id)
            ->with(['recommendations' => function($q) {
                $q->latest();
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get the most recent/active client request with full details
        // Get the most recent/active client request with full details
        $activeRequest = ClientRequest::where('user_id', $user->id)
            ->whereNotIn('detailed_status', ['cancelled']) // Allow completed events to show
            ->latest()
            ->with([
                'eventPackage',
                'eventPackage.items.vendorCatalogItem.vendor',
                'eventPackage.items.vendorPackage.vendor',
                'vendor.serviceType',
                'recommendations' => function($q) {
                    $q->where('status', '!=', 'draft')->latest();
                },
                'recommendations.items.vendor',
                'event.invoice.payments',
                'event.vendors.serviceType', // Load vendors directly from event
                'event.reviews', // Load reviews
                'nonPartnerCharges'
            ])
            ->first();

        // Get pending recommendation items (not yet responded)
        $pendingItems = collect();
        $newRecommendationsCount = 0;
        
        if ($activeRequest) {
            $pendingItems = RecommendationItem::whereHas('recommendation', function($q) use ($activeRequest) {
                $q->where('client_request_id', $activeRequest->id)
                  ->where('status', 'sent');
            })
            ->where('client_response', 'pending')
            ->with(['vendor', 'recommendation'])
            ->get();
            
            $newRecommendationsCount = $pendingItems->count();
        }

        // Calculate progress percentage
        $progressData = $this->calculateProgress($activeRequest);
        
        // Invoice summary
        $invoiceSummary = null;
        if ($activeRequest && $activeRequest->event && $activeRequest->event->invoice) {
            $invoice = $activeRequest->event->invoice;
            $invoiceSummary = [
                'total' => $invoice->calculateActualTotal(),
                'discount' => $invoice->voucher_discount_amount ?? 0,
                'paid' => $invoice->paid_amount ?? 0,
                'remaining' => $invoice->balance_due, 
                'status' => $invoice->status,
                'invoice_id' => $invoice->id,
            ];
        }

        // For Completed events: Identify vendors to review
        $vendorsToReview = collect();
        if ($activeRequest && $activeRequest->detailed_status === 'completed' && $activeRequest->event) {
            $existingReviews = $activeRequest->event->reviews->pluck('vendor_id')->toArray();
            
            $vendorsToReview = $activeRequest->event->vendors->filter(function($vendor) use ($existingReviews) {
                return !in_array($vendor->id, $existingReviews);
            });
        }

        return view('client.dashboard.index', compact(
            'activeRequest',
            'allRequests',
            'pendingItems',
            'newRecommendationsCount',
            'progressData',
            'invoiceSummary',
            'vendorsToReview'
        ));
    }

    /**
     * Calculate progress based on detailed_status
     */
    private function calculateProgress(?ClientRequest $request): array
    {
        if (!$request) {
            return ['percentage' => 0, 'currentStep' => 0, 'steps' => $this->getProgressSteps()];
        }

        $steps = $this->getProgressSteps();
        $statusOrder = [
            'new' => 1,
            'pending' => 1,
            'contacted' => 2,
            'in_progress' => 2,
            'recommendation_sent' => 3,
            'revision_requested' => 3,
            'approved' => 4,
            'converted_to_event' => 5,
            'completed' => 5, // Completed shares the last step
            'done' => 5, // Handle generic 'done' status
        ];

        // Map detailed_status to step
        // Handle generic 'done' or 'completed'
        $statusKey = $request->detailed_status;
        if ($statusKey === 'done') $statusKey = 'completed';

        $currentStep = $statusOrder[$statusKey] ?? 1;
        $percentage = ($currentStep / count($steps)) * 100;

        return [
            'percentage' => min(100, $percentage),
            'currentStep' => $currentStep,
            'steps' => $steps,
        ];
    }

    private function getProgressSteps(): array
    {
        return [
            1 => ['name' => 'Booking', 'status' => 'new'],
            2 => ['name' => 'On Process', 'status' => 'in_progress'],
            3 => ['name' => 'Rekomendasi', 'status' => 'recommendation_sent'],
            4 => ['name' => 'Approved', 'status' => 'approved'],
            5 => ['name' => 'Event Ready', 'status' => 'completed'], // Changed label
        ];
    }

    /**
     * Show Request Detail
     */
    public function show(ClientRequest $clientRequest)
    {
        if ($clientRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $clientRequest->load([
            'eventPackage',
            'eventPackage.items.vendorCatalogItem.vendor.serviceType',
            'eventPackage.items.vendorPackage.vendor.serviceType',
            'vendor.serviceType',
            'recommendations.items.vendor',
            'event'
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

        // Lock check
        if (in_array($clientRequest->detailed_status, ['completed', 'converted_to_event', 'approved'])) {
             return back()->with('error', 'Booking yang sudah disetujui atau selesai tidak dapat diubah.');
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
        if ($recommendation->clientRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($recommendation->status === 'draft') {
            abort(404);
        }

        $recommendation->load(['items.vendor', 'creator']);

        return view('client.dashboard.recommendation', compact('recommendation'));
    }

    /**
     * Respond to individual recommendation item (Approve/Reject)
     */
    public function respondRecommendationItem(Request $request, RecommendationItem $item)
    {
        // Security check
        if ($item->recommendation->clientRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'feedback' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $item->update([
                'client_response' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
                'client_feedback' => $validated['feedback'] ?? null,
                'responded_at' => now(),
            ]);

            $recommendation = $item->recommendation;
            $clientRequest = $recommendation->clientRequest;

            // Log activity
            $vendorName = $item->vendor ? $item->vendor->name : $item->external_vendor_name;
            ActivityLog::log(
                'client_item_response',
                $clientRequest,
                "Client " . ($validated['action'] === 'approve' ? 'approved' : 'rejected') . " {$item->category}: {$vendorName}",
                ['price' => $item->estimated_price, 'feedback' => $validated['feedback'] ?? null]
            );

            // Check if all items in recommendation have been responded
            $pendingCount = $recommendation->items()->where('client_response', 'pending')->count();
            $approvedCount = $recommendation->items()->where('client_response', 'approved')->count();
            $rejectedCount = $recommendation->items()->where('client_response', 'rejected')->count();

            if ($pendingCount === 0) {
                // All items responded
                if ($rejectedCount === $recommendation->items()->count()) {
                    // All rejected
                    $recommendation->update(['status' => 'rejected', 'responded_at' => now()]);
                    $clientRequest->update(['detailed_status' => 'rejected']);
                } elseif ($approvedCount > 0) {
                    // At least some approved
                    $recommendation->update(['status' => 'accepted', 'responded_at' => now()]);
                    $clientRequest->update(['detailed_status' => 'approved']);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $validated['action'] === 'approve' 
                        ? 'Rekomendasi berhasil disetujui!'
                        : 'Rekomendasi ditolak. Admin akan mengirim alternatif.',
                ]);
            }

            return back()->with('success', 
                $validated['action'] === 'approve' 
                    ? 'Rekomendasi berhasil disetujui!'
                    : 'Rekomendasi ditolak.'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }

            return back()->with('error', 'Terjadi kesalahan.');
        }
    }

    /**
     * Legacy: Respond to entire Recommendation (Accept/Reject)
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

            $clientRequest = $recommendation->clientRequest;
            
            if ($newStatus === 'accepted') {
                $clientRequest->update(['detailed_status' => 'approved']);
            } elseif ($newStatus === 'revision_requested') {
                $clientRequest->update(['detailed_status' => 'revision_requested']);
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