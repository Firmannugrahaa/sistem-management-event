<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventTaskController extends Controller
{
    /**
     * Display tasks for an event (Admin view)
     */
    public function index(Event $event)
    {
        $this->authorize('view', $event);
        
        $event->load(['tasks.assignedTo', 'tasks.createdBy', 'crews.user']);
        
        // Get all crew members for assignment dropdown
        $crewMembers = $event->crews()->with('user')->get()->pluck('user');
        
        return view('events.tasks.index', compact('event', 'crewMembers'));
    }

    /**
     * Store a new task
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);
        
        $validated['event_id'] = $event->id;
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';
        
        EventTask::create($validated);
        
        // TODO: Send notification to assigned user
        
        return back()->with('success', 'Task assigned successfully');
    }

    /**
     * Update task status (Admin can update any field)
     */
    public function update(Request $request, Event $event, EventTask $task)
    {
        $this->authorize('update', $event);
        
        if ($task->event_id !== $event->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'priority' => 'sometimes|in:low,medium,high',
            'due_date' => 'nullable|date',
            'assigned_to' => 'sometimes|exists:users,id',
        ]);
        
        $task->update($validated);
        
        return back()->with('success', 'Task updated successfully');
    }

    /**
     * Delete a task
     */
    public function destroy(Event $event, EventTask $task)
    {
        $this->authorize('update', $event);
        
        if ($task->event_id !== $event->id) {
            abort(404);
        }
        
        $task->delete();
        
        return back()->with('success', 'Task deleted successfully');
    }
}
