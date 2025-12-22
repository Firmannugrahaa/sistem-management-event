<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\LeadRecommendation;
use App\Models\RecommendationItem;
use App\Models\Vendor;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    /**
     * Show form to create recommendation
     */
    public function create(ClientRequest $clientRequest)
    {
        $this->authorize('update', $clientRequest);

        // Get available vendors with service type for category filtering
        $vendorsQuery = Vendor::with('serviceType')->get();
        
        // Group by category for display (legacy)
        $vendors = $vendorsQuery->groupBy('category');
        
        // Create flat list with service type for JS filtering
        $vendorsForJs = $vendorsQuery->map(function($vendor) {
            return [
                'id' => $vendor->id,
                'name' => $vendor->brand_name,
                'category' => $vendor->serviceType?->name ?? $vendor->category ?? 'Other',
            ];
        })->values();
        
        // Define common categories
        $categories = [
            'Venue', 'Catering', 'Decoration', 'Photography', 
            'Videography', 'MUA', 'Entertainment', 
            'Attire', 'Invitation', 'Souvenir', 'WO/Organizer', 'Documentation', 'Other'
        ];

        return view('recommendations.create', compact('clientRequest', 'vendors', 'vendorsForJs', 'categories'));
    }

    /**
     * Store new recommendation
     */
    public function store(Request $request, ClientRequest $clientRequest)
    {
        $this->authorize('update', $clientRequest);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.category' => 'required|string',
            'items.*.vendor_id' => 'nullable|exists:vendors,id',
            'items.*.external_vendor_name' => 'nullable|required_without:items.*.vendor_id|string',
            'items.*.service_name' => 'nullable|string',
            'items.*.recommendation_type' => 'required|in:primary,alternative,upgrade',
            'items.*.estimated_price' => 'nullable|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Create Header
            $recommendation = LeadRecommendation::create([
                'client_request_id' => $clientRequest->id,
                'created_by' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => 'sent', // Auto-send so it appears on client dashboard
                'sent_at' => now(),
                'total_estimated_budget' => collect($validated['items'])->sum('estimated_price'),
            ]);

            // Create Items
            foreach ($validated['items'] as $index => $item) {
                RecommendationItem::create([
                    'lead_recommendation_id' => $recommendation->id,
                    'vendor_id' => $item['vendor_id'] ?? null,
                    'external_vendor_name' => $item['external_vendor_name'] ?? null,
                    'category' => $item['category'],
                    'service_name' => $item['service_name'] ?? null,
                    'recommendation_type' => $item['recommendation_type'] ?? 'primary',
                    'estimated_price' => $item['estimated_price'] ?? 0,
                    'notes' => $item['notes'] ?? null,
                    'status' => 'pending',
                    'client_response' => 'pending', // Required for client dashboard display
                    'order' => $index,
                ]);
            }

            // Update Lead Status to recommendation_sent since we auto-send
            $clientRequest->detailed_status = 'recommendation_sent';
            $clientRequest->status = 'on_process';
            $clientRequest->save();

            ActivityLog::log(
                'created_recommendation',
                $clientRequest,
                "Recommendation '{$recommendation->title}' created and sent by " . Auth::user()->name
            );

            DB::commit();

            return redirect()->route('client-requests.show', $clientRequest)
                ->with('success', 'Rekomendasi berhasil dibuat dan dikirim ke client!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create recommendation', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to create recommendation: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show recommendation details
     */
    public function show(LeadRecommendation $recommendation)
    {
        $this->authorize('view', $recommendation->clientRequest);
        
        $recommendation->load(['items.vendor', 'creator']);
        
        return view('recommendations.show', compact('recommendation'));
    }

    /**
     * Send recommendation to client
     */
    public function send(LeadRecommendation $recommendation)
    {
        $this->authorize('update', $recommendation->clientRequest);

        if ($recommendation->status !== 'draft') {
            return back()->with('error', 'Only draft recommendations can be sent.');
        }

        DB::beginTransaction();

        try {
            $recommendation->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $clientRequest = $recommendation->clientRequest;
            $clientRequest->update([
                'detailed_status' => 'recommendation_sent',
                'status' => 'on_process'
            ]);

            \App\Models\Notification::create([
                'user_id' => $clientRequest->user_id,
                'type' => 'recommendation_sent',
                'title' => 'Rekomendasi Vendor Baru',
                'message' => "Admin telah memberikan rekomendasi vendor untuk acara Anda: {$recommendation->title}",
                'link' => route('client.recommendations.show', $recommendation->id),
                'is_read' => false,
            ]); 

            ActivityLog::log(
                'sent_recommendation',
                $clientRequest,
                "Recommendation '{$recommendation->title}' sent to client by " . Auth::user()->name
            );

            DB::commit();

            return back()->with('success', 'Recommendation sent to client successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to send recommendation.');
        }
    }

    /**
     * Client accepts a recommendation item
     */
    public function acceptItem(Request $request, RecommendationItem $item)
    {
        // Check authorization (client or admin)
        // For simplicity assuming logged in user is client owner or admin
        
        $item->update(['status' => 'accepted', 'rejection_reason' => null]);
        
        // Auto-assign to Client Request if it's a main vendor role
        $clientRequest = $item->recommendation->clientRequest;
        
        // If the item has a vendor_id, we can assign it
        if ($item->vendor_id) {
            // Check if this category matches main vendor roles
            if (in_array(strtolower($item->category), ['venue', 'organized', 'wedding organizer', 'wo'])) {
                $clientRequest->vendor_id = $item->vendor_id;
                $clientRequest->save();
            }
        }

        // Check if we should update status to ready_to_confirm
        if ($clientRequest->hasCompleteData() && $clientRequest->detailed_status === 'recommendation_sent') {
            $clientRequest->detailed_status = 'ready_to_confirm';
            $clientRequest->save();
        }

        return response()->json(['success' => true, 'message' => 'Item accepted']);
    }

    /**
     * Client rejects a recommendation item
     */
    public function rejectItem(Request $request, RecommendationItem $item)
    {
        $item->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('reason')
        ]);

        return response()->json(['success' => true, 'message' => 'Item rejected']);
    }

    /**
     * Delete recommendation
     */
    public function destroy(LeadRecommendation $recommendation)
    {
        $this->authorize('update', $recommendation->clientRequest);
        
        $clientRequest = $recommendation->clientRequest;
        
        DB::beginTransaction();
        try {
            $recommendation->delete(); // Items should cascade if DB formatted correctly, but usually fine
            
            // Revert status if no other active recommendations
            $remainingSent = $clientRequest->recommendations()->where('status', 'sent')->exists();
            if (!$remainingSent && $clientRequest->detailed_status === 'recommendation_sent') {
                $clientRequest->update(['detailed_status' => 'need_recommendation']);
            }
            
            DB::commit();
            
            return redirect()->route('client-requests.show', $clientRequest)
                ->with('success', 'Recommendation deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete recommendation: ' . $e->getMessage());
        }
    }
}
