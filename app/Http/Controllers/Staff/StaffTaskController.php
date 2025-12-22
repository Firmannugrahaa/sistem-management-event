<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StaffTaskController extends Controller
{
    /**
     * Display my tasks for a specific event
     */
    public function index(Event $event)
    {
        $user = Auth::user();
        
        // Check if user is assigned to this event
        $crew = $event->crews()->where('user_id', $user->id)->first();
        
        if (!$crew) {
            abort(403, 'You are not assigned to this event.');
        }
        
        // Get tasks assigned to this user for this event
        $tasks = $event->tasks()
            ->where('assigned_to', $user->id)
            ->with(['createdBy'])
            ->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->get();
        
        return view('staff.tasks.index', compact('event', 'tasks', 'crew'));
    }

    /**
     * Update task status (Staff can only update their own tasks)
     */
    public function updateStatus(Request $request, Event $event, EventTask $task)
    {
        $user = Auth::user();
        
        // Verify task belongs to this user and event
        if ($task->assigned_to !== $user->id || $task->event_id !== $event->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'notes' => 'nullable|string',
            'proof_image' => 'nullable|image|max:5120', // 5MB max
        ]);
        
        $proofUrl = $task->proof_url;
        
        // Handle proof image upload
        if ($request->hasFile('proof_image')) {
            // Delete old proof if exists
            if ($task->proof_url) {
                Storage::disk('public')->delete($task->proof_url);
            }
            
            $path = $request->file('proof_image')->store('task-proofs', 'public');
            $proofUrl = $path;
        }
        
        // If marking as completed, set completed_at
        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $task->completed_at = now();
        }
        
        $task->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $task->notes,
            'proof_url' => $proofUrl,
            'completed_at' => $task->completed_at,
        ]);
        
        return back()->with('success', 'Task status updated successfully');
    }

    /**
     * Upload proof photo
     */
    public function uploadProof(Request $request, Event $event, EventTask $task)
    {
        $user = Auth::user();
        
        if ($task->assigned_to !== $user->id || $task->event_id !== $event->id) {
            abort(403);
        }
        
        $request->validate([
            'proof_image' => 'required|image|max:5120',
        ]);
        
        // Delete old proof if exists
        if ($task->proof_url) {
            Storage::disk('public')->delete($task->proof_url);
        }
        
        $path = $request->file('proof_image')->store('task-proofs', 'public');
        
        $task->update(['proof_url' => $path]);
        
        return back()->with('success', 'Proof uploaded successfully');
    }
}
