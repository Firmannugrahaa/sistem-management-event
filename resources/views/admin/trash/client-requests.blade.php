@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Trash - Client Requests
                </h1>
                <p class="mt-1 text-sm text-gray-600">Deleted items can be restored or permanently deleted</p>
            </div>
            <a href="{{ route('admin.trash.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                ← Back to Trash
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
            <p class="text-sm text-green-700">✓ {{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <p class="text-sm text-red-700">✗ {{ session('error') }}</p>
        </div>
        @endif

        @if(session('warning'))
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <p class="text-sm text-yellow-700">⚠ {{ session('warning') }}</p>
        </div>
        @endif

        @if($trashedRequests->count() > 0)
        <!-- Bulk Actions Bar -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
            <form action="{{ route('admin.trash.restore-bulk') }}" method="POST" id="bulk-restore-form">
                @csrf
                <input type="hidden" name="model" value="client_requests">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Restore Selected
                        </button>
                        <span class="text-sm text-gray-600" id="selected-count">0 items selected</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        Total: {{ $trashedRequests->total() }} deleted items
                    </div>
                </div>
            </form>
        </div>

        <!-- Trash Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($trashedRequests as $request)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $request->id }}" 
                                   form="bulk-restore-form" class="item-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $request->id }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $request->client_name }}</div>
                            <div class="text-xs text-gray-500">{{ $request->client_email }}</div>
                            <div class="text-xs text-gray-500">{{ $request->client_phone }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $request->event_type }}</div>
                            <div class="text-xs text-gray-500">{{ $request->event_date->format('d M Y') }}</div>
                            @if($request->budget)
                            <div class="text-xs text-green-600 font-medium">Rp {{ number_format($request->budget, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $request->detailed_status_badge_color }}">
                                {{ $request->detailed_status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">{{ $request->deletedBy?->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-500">{{ $request->deleted_at->format('d M Y, H:i') }}</div>
                            <div class="text-xs text-gray-400">{{ $request->deleted_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <!-- Restore -->
                                <form action="{{ route('admin.trash.restore-client-request', $request->id) }}" 
                                      method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Restore this client request?')"
                                            class="px-3 py-1.5 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition flex items-center"
                                            title="Restore">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Restore
                                    </button>
                                </form>
                                
                                <!-- Force Delete -->
                                <form action="{{ route('admin.trash.force-delete-client-request', $request->id) }}" 
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('⚠️ PERMANENT DELETE!\n\nThis will PERMANENTLY delete this client request.\nThis action CANNOT be undone!\n\nAre you absolutely sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition flex items-center"
                                            title="Delete Forever">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        Delete Forever
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{$trashedRequests->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-16 text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h3 class="text-lg font-semibold text-gray-900">Trash is Empty!</h3>
            <p class="text-gray-600 mt-2">No deleted client requests found.</p>
            <a href="{{ route('client-requests.index') }}" class="mt-4 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Go to Leads Dashboard
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Select All Checkbox
document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
    updateSelectedCount();
});

// Individual Checkboxes
document.querySelectorAll('.item-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        // Update select-all state
        const allCheckboxes = document.querySelectorAll('.item-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
        document.getElementById('select-all').checked = allCheckboxes.length === checkedCheckboxes.length;
        
        updateSelectedCount();
    });
});

function updateSelectedCount() {
    const count = document.querySelectorAll('.item-checkbox:checked').length;
    document.getElementById('selected-count').textContent = `${count} item${count !== 1 ? 's' : ''} selected`;
}

// Prevent accidental form submission
document.getElementById('bulk-restore-form')?.addEventListener('submit', function(e) {
    const count = document.querySelectorAll('.item-checkbox:checked').length;
    if (count === 0) {
        e.preventDefault();
        alert('Please select at least one item to restore.');
        return false;
    }
    return confirm(`Restore ${count} item${count !== 1 ? 's' : ''}?`);
});
</script>
@endpush
@endsection
