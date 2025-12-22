<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            My Tasks - {{ $event->event_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Back Button --}}
            <div class="mb-4">
                <a href="{{ route('staff.events.show', $event) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    ← Back to Event Details
                </a>
            </div>

            {{-- Task Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Pending</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $tasks->where('status', 'pending')->count() }}
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">In Progress</div>
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $tasks->where('status', 'in_progress')->count() }}
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Completed</div>
                    <div class="text-2xl font-bold text-green-600">
                        {{ $tasks->where('status', 'completed')->count() }}
                    </div>
                </div>
            </div>

            {{-- Tasks List --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">📋 My Tasks</h3>
                    
                    @if($tasks->count() > 0)
                        <div class="space-y-4">
                            @foreach($tasks as $task)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $task->isOverdue() ? 'bg-red-50 dark:bg-red-900/20 border-red-300' : '' }}" x-data="{ expanded: false }">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h5 class="font-semibold text-gray-900 dark:text-gray-100">{{ $task->title }}</h5>
                                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $task->priority_color }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $task->status_color }}">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                                @if($task->isOverdue())
                                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                                        ⚠️ Overdue
                                                    </span>
                                                @endif
                                            </div>
                                            @if($task->description)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $task->description }}</p>
                                            @endif
                                            <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                                <span>👤 Assigned by: {{ $task->createdBy->name }}</span>
                                                @if($task->due_date)
                                                    <span class="{{ $task->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                                        📅 Due: {{ $task->due_date->format('d M Y H:i') }}
                                                    </span>
                                                @endif
                                                @if($task->completed_at)
                                                    <span class="text-green-600">✅ Completed: {{ $task->completed_at->format('d M Y H:i') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            @if($task->status !== 'completed')
                                                <button @click="expanded = !expanded" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                                    Update Status
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Update Form (Expandable) --}}
                                    <div x-show="expanded" x-collapse class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <form action="{{ route('staff.tasks.update-status', [$event, $task]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <x-input-label for="status_{{ $task->id }}" value="Status" />
                                                    <select id="status_{{ $task->id }}" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                        <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                        <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <x-input-label for="proof_{{ $task->id }}" value="Upload Proof (Optional)" />
                                                    <input id="proof_{{ $task->id }}" name="proof_image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                    @if($task->proof_url)
                                                        <a href="{{ Storage::url($task->proof_url) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 mt-1 inline-block">
                                                            📷 View Current Proof
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="md:col-span-2">
                                                    <x-input-label for="notes_{{ $task->id }}" value="Notes / Comments" />
                                                    <textarea id="notes_{{ $task->id }}" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ $task->notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="mt-4 flex gap-2">
                                                <x-primary-button>Save Update</x-primary-button>
                                                <button type="button" @click="expanded = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Show Notes if exists --}}
                                    @if($task->notes && !$expanded)
                                        <div class="mt-3 p-3 bg-gray-100 dark:bg-gray-700 rounded text-sm">
                                            <strong>My Notes:</strong> {{ $task->notes }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No tasks assigned</h3>
                            <p class="mt-1 text-sm text-gray-500">You don't have any tasks for this event yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
