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

        // Get available vendors grouped by category
        $vendors = Vendor::all()->groupBy('category');
        
        // Define common categories
        $categories = [
            'Venue', 'Catering', 'Decoration', 'Photography', 
            'Videography', 'Makeup Artist', 'Entertainment', 
            'Attire', 'Invitation', 'Souvenir', 'Other'
        ];

        return view('recommendations.create', compact('clientRequest', 'vendors', 'categories'));
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
                'status' => 'draft',
                'total_estimated_budget' => collect($validated['items'])->sum('estimated_price'),
            ]);

            // Create Items
            foreach ($validated['items'] as $index => $item) {
                RecommendationItem::create([
                    'lead_recommendation_id' => $recommendation->id,
                    'vendor_id' => $item['vendor_id'] ?? null,
                    'external_vendor_name' => $item['external_vendor_name'] ?? null,
                    'category' => $item['category'],
                    'estimated_price' => $item['estimated_price'] ?? 0,
                    'notes' => $item['notes'] ?? null,
                    'order' => $index,
                ]);
            }

            // Update Lead Status
            if ($clientRequest->detailed_status !== 'recommendation_sent') {
                $clientRequest->detailed_status = 'need_recommendation'; // Or keep current
                $clientRequest->save();
            }

            ActivityLog::log(
                'created_recommendation',
                $clientRequest,
                "Recommendation '{$recommendation->title}' created by " . Auth::user()->name
            );

            DB::commit();

            return redirect()->route('client-requests.show', $clientRequest)
                ->with('success', 'Recommendation draft created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create recommendation', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to create recommendation. Please try again.')->withInput();
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

            // TODO: Send Email to Client
            // Mail::to($clientRequest->client_email)->send(new RecommendationSentMail($recommendation));

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
}
