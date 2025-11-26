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
        }

        $pendingRequests = (clone $query)->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $onProcessRequests = (clone $query)->where('status', 'on_process')->orderBy('created_at', 'desc')->get();
        $doneRequests = (clone $query)->where('status', 'done')->orderBy('created_at', 'desc')->get();

        // Stats
        $totalRequests = ClientRequest::count();
        $pendingCount = ClientRequest::where('status', 'pending')->count();
        $onProcessCount = ClientRequest::where('status', 'on_process')->count();
        $doneCount = ClientRequest::where('status', 'done')->count();

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
        
        $clientRequest->load(['user', 'assignedTo', 'vendor']);
        $staffMembers = User::role(['Admin', 'Staff'])->get();
        $vendors = Vendor::all();
        
        return view('client-requests.show', compact('clientRequest', 'staffMembers', 'vendors'));
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

        // Track when first responded
        if ($clientRequest->status === 'pending' && $validated['status'] !== 'pending' && !$clientRequest->responded_at) {
            $clientRequest->responded_at = now();
        }

        $clientRequest->status = $validated['status'];
        $clientRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
        ]);
    }

    /**
     * Assign staff to request via AJAX
     */
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
     * Convert client request to event
     */
    public function convertToEvent(ClientRequest $clientRequest)
    {
        $user = Auth::user();
        
        // Only Owner, Admin can convert
        if (!$user->hasAnyRole(['SuperUser', 'Owner', 'Admin'])) {
            return redirect()->back()->with('error', 'Unauthorized to convert request to event.');
        }

        // Check if already converted
        if ($clientRequest->isConverted()) {
            return redirect()->route('events.show', $clientRequest->event)
                ->with('info', 'Request ini sudah diconvert menjadi event.');
        }

        // Redirect to create event with pre-filled data
        return redirect()->route('events.create', [
            'client_request_id' => $clientRequest->id,
            'client_name' => $clientRequest->client_name,
            'client_email' => $clientRequest->client_email,
            'client_phone' => $clientRequest->client_phone,
            'event_name' => $clientRequest->event_type . ' - ' . $clientRequest->client_name,
            'start_time' => $clientRequest->event_date->format('Y-m-d'),
            'description' => $clientRequest->message,
        ])->with('success', 'Silakan lengkapi data event. Data dari request sudah diisi otomatis.');
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
