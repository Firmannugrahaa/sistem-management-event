<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tasks - {{ $event->event_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Back Button --}}
            <div class="mb-4">
                <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    ← Back to Event Details
                </a>
            </div>

            {{-- Add Task Form --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">➕ Assign New Task</h3>
                    <form action="{{ route('events.tasks.store', $event) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="assigned_to" value="Assign To" />
                                <select id="assigned_to" name="assigned_to" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">-- Select Crew Member --</option>
                                    @foreach($crewMembers as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="priority" value="Priority" />
                                <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="title" value="Task Title" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="description" value="Description" />
                                <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                            </div>
                            <div>
                                <x-input-label for="due_date" value="Due Date (Optional)" />
                                <x-text-input id="due_date" name="due_date" type="datetime-local" class="mt-1 block w-full" />
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-primary-button>Assign Task</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tasks List --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">📋 All Tasks</h3>
                    
                    @if($event->tasks->count() > 0)
                        <div class="space-y-4">
                            @foreach($event->tasks->groupBy('status') as $status => $tasks)
                                <div>
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2 capitalize">{{ $status }} ({{ $tasks->count() }})</h4>
                                    <div class="space-y-2">
                                        @foreach($tasks as $task)
                                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $task->isOverdue() ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <h5 class="font-semibold text-gray-900 dark:text-gray-100">{{ $task->title }}</h5>
                                                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $task->priority_color }}">
                                                                {{ ucfirst($task->priority) }}
                                                            </span>
                                                            @if($task->isOverdue())
                                                                <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                                                    Overdue
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if($task->description)
                                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $task->description }}</p>
                                                        @endif
                                                        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                                            <span>👤 {{ $task->assignedTo->name }}</span>
                                                            @if($task->due_date)
                                                                <span>📅 Due: {{ $task->due_date->format('d M Y H:i') }}</span>
                                                            @endif
                                                            @if($task->completed_at)
                                                                <span>✅ Completed: {{ $task->completed_at->format('d M Y H:i') }}</span>
                                                            @endif
                                                        </div>
                                                        @if($task->notes)
                                                            <div class="mt-2 p-2 bg-gray-100 dark:bg-gray-700 rounded text-sm">
                                                                <strong>Notes:</strong> {{ $task->notes }}
                                                            </div>
                                                        @endif
                                                        @if($task->proof_url)
                                                            <div class="mt-2">
                                                                <a href="{{ Storage::url($task->proof_url) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                                                    📷 View Proof
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <form action="{{ route('events.tasks.destroy', [$event, $task]) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-8">No tasks assigned yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
