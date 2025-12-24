<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientRequestController extends Controller
{
    /**
     * Display a listing of the resource (Kanban-style board)
     */
    /**
     * Display a listing of the resource (Kanban-style board)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Mark 'new_booking' notifications as read logic
        $user->unreadNotifications()->where('type', 'new_booking')->update(['is_read' => true]);

        $viewType = $request->get('view', 'all');

        // Simplified query to debug relationship
        $query = ClientRequest::query();
        
        // Eager load relationships one by one to isolate error
        $query->with('user');
        $query->with('assignee'); // This is the problematic one
        $query->with('vendor');

        // Basic role filtering
        if ($user->hasRole('Staff')) {
            $query->where('assigned_to', $user->id);
        } elseif ($user->hasRole('Vendor')) {
            $query->where('vendor_id', $user->vendor->id ?? 0);
        } elseif ($user->hasRole('Client') || $user->hasRole('User')) {
            $query->where('user_id', $user->id);
        }

        $pendingRequests = (clone $query)->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $onProcessRequests = (clone $query)->where('status', 'on_process')->orderBy('created_at', 'desc')->get();
        $doneRequests = (clone $query)->where('status', 'done')->orderBy('created_at', 'desc')->get();

        // Stats - Use the filtered query to count
        $totalRequests = (clone $query)->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $onProcessCount = (clone $query)->where('status', 'on_process')->count();
        $doneCount = (clone $query)->where('status', 'done')->count();

        $canAssign = $user->hasRole('SuperUser') || $user->hasRole('Owner') || $user->hasRole('Admin');
        $staffMembers = $canAssign ? User::role(['Admin', 'Staff'])->get() : collect();

        return view('client-requests.index', compact(
            'pendingRequests',
            'onProcessRequests',
            'doneRequests',
            'totalRequests',
            'pendingCount',
            'onProcessCount',
            'doneCount',
            'canAssign',
            'staffMembers',
            'viewType'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::all();
        return view('client-requests.create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|max:20',
            'event_date' => 'required|date|after:today',
            'budget' => 'nullable|numeric|min:0',
            'event_type' => 'required|string|max:255',
            'message' => 'nullable|string',
            'vendor_id' => 'nullable|exists:vendors,id',
            'request_source' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            // 1. Check if user exists or create new one
            $user = User::where('email', $validated['client_email'])->first();
            $isNewUser = false;
            $generatedPassword = null;

            if (!$user) {
                $isNewUser = true;
                $generatedPassword = \Illuminate\Support\Str::random(10); // Generate random password
                
                $user = User::create([
                    'name' => $validated['client_name'],
                    'email' => $validated['client_email'],
                    'password' => \Illuminate\Support\Facades\Hash::make($generatedPassword),
                    'phone_number' => $validated['client_phone'],
                ]);

                // Assign role 'User' (Client)
                $user->assignRole('User');
            }

            // 2. Create Client Request linked to User
            $clientRequest = ClientRequest::create([
                'user_id' => $user->id, // Link to user
                'client_name' => $validated['client_name'],
                'client_email' => $validated['client_email'],
                'client_phone' => $validated['client_phone'],
                'event_date' => $validated['event_date'],
                'budget' => $validated['budget'],
                'event_type' => $validated['event_type'],
                'message' => $validated['message'],
                'status' => 'pending',
                'detailed_status' => 'new',
                'vendor_id' => $validated['vendor_id'] ?? null,
                'request_source' => $validated['request_source'] ?? 'admin_input',
            ]);

            DB::commit();

            $message = 'Client request created successfully.';
            if ($isNewUser) {
                $message .= " New account created. Password: {$generatedPassword} (Please inform the client)";
            }

            return redirect()->route('client-requests.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create request: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ClientRequest $clientRequest)
    {
        $this->authorize('view', $clientRequest);
        
        $clientRequest->load([
            'user', 
            'assignedTo', 
            'vendor.serviceType',
            'recommendations.items.vendor.serviceType',
            'nonPartnerCharges'
        ]);
        
        $staffMembers = User::role(['Admin', 'Staff'])->get();
        $vendors = Vendor::all();
        
        // Determine if this is a package booking
        $isPackageBooking = str_contains($clientRequest->message ?? '', '[Paket:');
        $packageInfo = null;
        $packageVendors = collect();
        
        if ($isPackageBooking) {
            preg_match('/\[Paket: (.+?)\]/', $clientRequest->message, $matches);
            if (!empty($matches[1])) {
                $packageInfo = \App\Models\EventPackage::where('name', $matches[1])
                    ->with(['items.vendorCatalogItem.vendor.serviceType', 'items.vendorPackage.vendor.serviceType'])
                    ->first();
                    
                if ($packageInfo) {
                    foreach ($packageInfo->items as $item) {
                        $vendor = $item->vendorCatalogItem?->vendor ?? $item->vendorPackage?->vendor;
                        if ($vendor) {
                            $packageVendors->push([
                                'vendor' => $vendor,
                                'category' => $vendor->serviceType?->name ?? 'Lainnya',
                                'item_name' => $item->vendorCatalogItem?->name ?? $item->vendorPackage?->name ?? '',
                                'price' => $item->vendorCatalogItem?->price ?? $item->vendorPackage?->price ?? 0,
                                'source' => 'package',
                            ]);
                        }
                    }
                }
            }
        }
        
        // Build vendor summary from recommendations
        $recommendationVendors = collect();
        foreach ($clientRequest->recommendations as $rec) {
            foreach ($rec->items as $item) {
                $recommendationVendors->push([
                    'vendor' => $item->vendor,
                    'category' => $item->category,
                    'item_name' => $item->vendor?->brand_name ?? $item->external_vendor_name ?? '',
                    'price' => $item->estimated_price,
                    'source' => 'recommendation',
                    'status' => $item->client_response ?? 'pending',
                    'recommendation_status' => $rec->status,
                    'notes' => $item->notes,
                ]);
            }
        }
        
        // Get service types for category grouping
        $serviceTypes = \App\Models\ServiceType::orderBy('name')->get();
        
        return view('client-requests.show', compact(
            'clientRequest', 
            'staffMembers', 
            'vendors',
            'isPackageBooking',
            'packageInfo',
            'packageVendors',
            'recommendationVendors',
            'serviceTypes'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientRequest $clientRequest)
    {
        $this->authorize('update', $clientRequest);
        
        $staffMembers = User::role(['Admin', 'Staff'])->get();
        $vendors = Vendor::all();
        
        return view('client-requests.edit', compact('clientRequest', 'staffMembers', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClientRequest $clientRequest)
    {
        $this->authorize('update', $clientRequest);
        
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|max:20',
            'event_date' => 'required|date',
            'budget' => 'nullable|numeric|min:0',
            'event_type' => 'required|string|max:255',
            'message' => 'nullable|string',
            'status' => 'required|in:pending,on_process,done',
            'assigned_to' => 'nullable|exists:users,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'notes' => 'nullable|string',
        ]);

        // Track when first responded
        if ($clientRequest->status === 'pending' && $validated['status'] !== 'pending' && !$clientRequest->responded_at) {
            $validated['responded_at'] = now();
        }

        // Sync detailed_status with status
        if ($validated['status'] === 'on_process' && in_array($clientRequest->detailed_status, ['new', 'pending', 'contacted'])) {
            $validated['detailed_status'] = 'contacted';
        }
        if ($validated['status'] === 'done') {
            // Map 'done' to a valid detailed_status enum value
            // Using 'approved' as the generic success state since 'done' is not in the enum
            $validated['detailed_status'] = 'approved';
        }

        $clientRequest->update($validated);

        return redirect()->route('client-requests.index')
            ->with('success', 'Client request updated successfully.');
    }

    /**
     * Update status via AJAX (for Kanban drag-drop)
     */
    public function updateStatus(Request $request, ClientRequest $clientRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,on_process,done',
        ]);

        $oldStatus = $clientRequest->status;
        $newStatus = $validated['status'];

        // Track when first responded
        if ($clientRequest->status === 'pending' && $validated['status'] !== 'pending' && !$clientRequest->responded_at) {
            $clientRequest->responded_at = now();
        }

        $clientRequest->status = $newStatus;
        
        // Sync detailed_status with status change
        if ($newStatus === 'on_process' && in_array($clientRequest->detailed_status, ['new', 'pending', 'contacted'])) {
            $clientRequest->detailed_status = 'on_process';
        }
        if ($newStatus === 'done') {
            $clientRequest->detailed_status = 'done';
        }

        $clientRequest->save();

        // 🔔 CREATE NOTIFICATION for client
        if ($clientRequest->user_id && $oldStatus !== $newStatus) {
            \App\Models\Notification::create([
                'user_id' => $clientRequest->user_id,
                'type' => 'status_update',
                'title' => 'Status Permintaan Diperbarui',
                'message' => "Status permintaan Anda untuk event '{$clientRequest->event_type}' telah diubah dari '{$oldStatus}' menjadi '{$newStatus}'.",
                'link' => route('client-requests.show', $clientRequest->id),
                'data' => [
                    'client_request_id' => $clientRequest->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]
            ]);
        }

        // 🔔 NOTIFICATION FOR VENDOR (When status -> on_process)
        if ($newStatus === 'on_process' && $clientRequest->vendor_id) {
             $vendor = \App\Models\Vendor::find($clientRequest->vendor_id);
             if ($vendor && $vendor->user) {
                 \App\Models\Notification::create([
                     'user_id' => $vendor->user->id,
                     'type' => 'vendor_assignment',
                     'title' => 'Event Baru: On Process',
                     'message' => 'Anda terlibat dalam event baru yang sedang diproses.',
                     'link' => route('vendor.events.index'), 
                     'is_read' => false,
                     'data' => ['client_request_id' => $clientRequest->id]
                 ]);
             }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
        ]);
    }

    /**
     * Convert booking to event (explicit conversion)
     */
    public function convertToEvent(ClientRequest $clientRequest)
    {
        $this->authorize('update', $clientRequest);
        
        // Check if already converted
        if ($clientRequest->event) {
            return back()->with('error', 'Booking ini sudah dikonversi menjadi Event.');
        }
        
        // Validate readiness
        if (!$clientRequest->isReadyToConvert()) {
            $checklist = $clientRequest->getReadinessChecklist();
            $incomplete = collect($checklist)
                ->filter(fn($item) => !$item['completed'])
                ->pluck('label')
                ->join(', ');
                
            return back()->with('error', 
                "Booking belum siap dikonversi. Yang masih perlu dilengkapi: {$incomplete}");
        }
        
        \DB::beginTransaction();
        try {
            // Determine Venue ID from assigned vendor or recommendations
        $venueId = null;
        if ($clientRequest->vendor && ($clientRequest->vendor->serviceType->name ?? '') === 'Venue') {
            $venueId = $clientRequest->vendor_id;
        } else {
            // Check accepted recommendations for Venue
            $venueItem = \App\Models\RecommendationItem::whereHas('recommendation', function($q) use ($clientRequest) {
                $q->where('client_request_id', $clientRequest->id);
            })->where('status', 'accepted')
              ->where('category', 'Venue')
              ->first();
              
            if ($venueItem && $venueItem->vendor_id) {
                $venueId = $venueItem->vendor_id;
            }
        }

        // Create Event
        $event = \App\Models\Event::create([
            'client_request_id' => $clientRequest->id,
            'user_id' => $clientRequest->user_id,
            'venue_id' => $venueId,
            'event_name' => $clientRequest->event_type . ' - ' . $clientRequest->client_name,
            'description' => $clientRequest->message . "\n\nBudget: Rp " . number_format($clientRequest->budget, 0, ',', '.'),
            'start_time' => $clientRequest->event_date,
            'end_time' => $clientRequest->event_date->copy()->addHours(6), // Default 6 hours duration
            'client_name' => $clientRequest->client_name,
            'client_email' => $clientRequest->client_email,
            'client_phone' => $clientRequest->client_phone,
            'status' => 'planning', // Events start in planning stage
        ]);
            
        // ✅ FIX: Update booking status with correct values
        // detailed_status must be 'converted_to_event' (from detailed_status constraint)
        // status must be 'done' (from status enum: pending, on_process, done)
        $clientRequest->update([
            'detailed_status' => 'converted_to_event',
            'status' => 'done'
        ]);
            
            // Attach vendors from package if exists
            if ($clientRequest->eventPackage) {
                $this->attachPackageVendorsToEvent($event, $clientRequest->eventPackage);
            }
            
            // Attach single vendor if selected
            if ($clientRequest->vendor_id) {
                // Check if not already attached (e.g. from package)
                if (!$event->vendors()->where('vendor_id', $clientRequest->vendor_id)->exists()) {
                    $event->vendors()->attach($clientRequest->vendor_id, [
                        'role' => $clientRequest->vendor->serviceType->name ?? 'Vendor',
                        'status' => 'confirmed',
                        'source' => 'client_choice'
                    ]);
                    
                    // Auto-check checklist items for this vendor
                    \App\Services\ChecklistVendorMappingService::autoCheckItems($event->id, $clientRequest->vendor);
                }
            }
            
            // Attach Accepted Recommendation Items (approved by client)
            $acceptedItems = \App\Models\RecommendationItem::whereHas('recommendation', function($q) use ($clientRequest) {
                $q->where('client_request_id', $clientRequest->id);
            })->where('client_response', 'approved')->whereNotNull('vendor_id')->get();

            foreach ($acceptedItems as $item) {
                if (!$event->vendors()->where('vendor_id', $item->vendor_id)->exists()) {
                    $event->vendors()->attach($item->vendor_id, [
                        'role' => $item->category,
                        'status' => 'confirmed',
                        'source' => 'recommendation',
                        'agreed_price' => $item->estimated_price ?? 0
                    ]);
                    
                    // Auto-check checklist items for this vendor
                    $vendor = \App\Models\Vendor::find($item->vendor_id);
                    if ($vendor) {
                        \App\Services\ChecklistVendorMappingService::autoCheckItems($event->id, $vendor);
                    }
                }
            }
            
            // Link checklist to this event
            $checklist = \App\Models\ClientChecklist::where('client_request_id', $clientRequest->id)->first();
            if ($checklist && !$checklist->event_id) {
                $checklist->update(['event_id' => $event->id]);
            }
            
            // Send notification to client
            // Send notification to client
            \App\Models\Notification::create([
                'user_id' => $clientRequest->user_id,
                'type' => 'event_created',
                'title' => 'Event Anda Telah Dikonfirmasi! 🎉',
                'message' => "Event {$event->event_name} pada {$event->start_time->format('d M Y')} telah dibuat dan siap dijalankan.",
                'link' => route('events.show', $event->id),
                'is_read' => false,
            ]);
            
            // Notify all involved vendors
            foreach ($event->vendors as $vendor) {
                if ($vendor->user) {
                    \App\Models\Notification::create([
                        'user_id' => $vendor->user->id,
                        'type' => 'event_confirmed',
                        'title' => 'Event Dikonfirmasi',
                        'message' => "Event {$event->event_name} pada {$event->start_time->format('d M Y')} telah dikonfirmasi. Anda terlibat sebagai {$vendor->pivot->role}.",
                        'link' => route('events.show', $event->id),
                        'is_read' => false,
                    ]);
                }
            }
            
            \DB::commit();
            
            return redirect()
                ->route('events.show', $event->id)
                ->with('success', '✓ Event berhasil dibuat dari booking ini!');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to convert booking to event: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat event: ' . $e->getMessage());
        }
    }

    /**
     * Attach vendors from package to event
     */
    private function attachPackageVendorsToEvent(\App\Models\Event $event, \App\Models\EventPackage $package)
    {
        $attachedVendors = [];
        
        foreach ($package->items as $item) {
            $vendor = null;
            $itemable = null;
            $itemName = '';
            $itemPrice = 0;
            
            if ($item->vendorCatalogItem && $item->vendorCatalogItem->vendor) {
                $vendor = $item->vendorCatalogItem->vendor;
                $itemable = $item->vendorCatalogItem;
                $itemName = $item->vendorCatalogItem->name;
                $itemPrice = $item->vendorCatalogItem->price;
            } elseif ($item->vendorPackage && $item->vendorPackage->vendor) {
                $vendor = $item->vendorPackage->vendor;
                $itemable = $item->vendorPackage;
                $itemName = $item->vendorPackage->name;
                $itemPrice = $item->vendorPackage->price;
            }
            
            if ($vendor && !in_array($vendor->id, $attachedVendors)) {
                $price = 0;
                if ($item->vendorCatalogItem) $price = $item->vendorCatalogItem->price;
                elseif ($item->vendorPackage) $price = $item->vendorPackage->price;

                $event->vendors()->attach($vendor->id, [
                    'role' => $vendor->serviceType->name ?? 'Vendor',
                    'status' => 'confirmed',
                    'source' => 'package',
                    'agreed_price' => $price
                ]);
                $attachedVendors[] = $vendor->id;
            }
            
            // Create EventVendorItem for detailed scope tracking
            if ($vendor && $itemable) {
                \App\Models\EventVendorItem::create([
                    'event_id' => $event->id,
                    'vendor_id' => $vendor->id,
                    'itemable_type' => get_class($itemable),
                    'itemable_id' => $itemable->id,
                    'quantity' => $item->quantity ?? 1,
                    'price' => $itemPrice,
                    'notes' => "Dari paket: {$package->name}"
                ]);
            }
        }
    }

    /**
     * Assign staff to request via AJAX
     */
    public function assignStaff(Request $request, ClientRequest $clientRequest)
    {
        $user = Auth::user();
        
        if (!$user->hasAnyRole(['SuperUser', 'Owner', 'Admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'notes' => 'nullable|string'
        ]);

        $oldAssignee = $clientRequest->assignedTo;
        $newAssignee = User::find($validated['assigned_to']);

        // Update assignment
        $clientRequest->assigned_to = $validated['assigned_to'];
        
        // Update priority if provided
        if (isset($validated['priority'])) {
            $clientRequest->priority = $validated['priority'];
        }

        // Update status if it was 'new'
        if ($clientRequest->detailed_status === 'new') {
            $clientRequest->detailed_status = 'contacted';
            $clientRequest->status = 'on_process'; // Move to On Process column
        }

        // Add internal note if provided
        if (!empty($validated['notes'])) {
            $currentNotes = $clientRequest->notes ?? '';
            $timestamp = now()->format('d/m/Y H:i');
            $adminName = $user->name;
            $newNote = "[{$timestamp}] Assigned by {$adminName}: {$validated['notes']}";
            $clientRequest->notes = $currentNotes ? $currentNotes . "\n\n" . $newNote : $newNote;
        }

        $clientRequest->save();

        // Log activity
        \App\Models\ActivityLog::log(
            'assigned',
            $clientRequest,
            "Lead assigned to {$newAssignee->name} by {$user->name}",
            [
                'previous_assignee' => $oldAssignee?->name,
                'new_assignee' => $newAssignee->name,
                'priority' => $clientRequest->priority
            ]
        );

        // Send notification to new assignee
        try {
            $newAssignee->notify(new \App\Notifications\LeadAssignedNotification($clientRequest));
        } catch (\Exception $e) {
            // Log notification failure but don't fail the request
            \Log::error('Failed to send assignment notification', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Staff assigned successfully',
            'assignee_name' => $newAssignee->name,
            'status_html' => $clientRequest->status_badge_color, // For UI update
        ]);
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(ClientRequest $clientRequest)
    {
        $this->authorize('delete', $clientRequest);
        
        // Use DB transaction for data integrity
        DB::beginTransaction();
        
        try {
            // Track who deleted it
            $clientRequest->update([
                'deleted_by' => auth()->id()
            ]);
            
            // Backup critical data before soft delete
            $backupData = [
                'id' => $clientRequest->id,
                'client_name' => $clientRequest->client_name,
                'client_email' => $clientRequest->client_email,
                'status' => $clientRequest->status,
                'detailed_status' => $clientRequest->detailed_status,
                'event_type' => $clientRequest->event_type,
                'event_date' => $clientRequest->event_date?->format('Y-m-d'),
            ];
            
            // Soft delete
            $clientRequest->delete();
            
            // Log activity for audit trail (SECURITY REQUIREMENT)
            \App\Models\ActivityLog::log(
                'deleted',
                $clientRequest,
                "Client request #{$clientRequest->id} ({$clientRequest->client_name}) moved to trash by " . auth()->user()->name,
                $backupData
            );
            
            DB::commit();
            
            return redirect()->route('client-requests.index')
                ->with('success', 'Client request moved to trash. Can be restored by SuperUser.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error for debugging (SECURITY: Don't expose to user)
            \Log::error('Failed to delete client request', [
                'id' => $clientRequest->id,
                'error' => $e->getMessage(),
                'user' => auth()->id(),
                'ip' => request()->ip(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to delete client request. Please try again.');
        }
    }
}
