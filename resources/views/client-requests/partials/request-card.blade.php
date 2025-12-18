<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition cursor-pointer request-card" data-request-id="{{ $request->id }}">
    <!-- Header dengan Status Badge -->
    <div class="flex items-start justify-between mb-3">
        <div class="flex-1">
            <h4 class="font-semibold text-gray-900 text-sm">{{ $request->client_name }}</h4>
            <p class="text-xs text-gray-500 mt-1">{{ $request->client_email }}</p>
        </div>
        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $request->status_badge_color }}">
            {{ $request->status_text }}
        </span>
    </div>

    <!-- Event Details -->
    <div class="space-y-2 mb-3">
        <div class="flex items-center text-xs text-gray-600">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>{{ $request->event_date->format('d M Y') }}</span>
        </div>

        <div class="flex items-center text-xs text-gray-600">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            <span>{{ $request->event_type }}</span>
        </div>

        @if($request->budget)
        <div class="flex items-center text-xs text-gray-600">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Rp {{ number_format($request->budget, 0, ',', '.') }}</span>
        </div>
        @endif

        @if($request->assignee)
        <div class="flex items-center text-xs text-gray-600">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>{{ $request->assignee->name }}</span>
        </div>
        @endif
    </div>

    @if($request->message)
    <div class="mb-3">
        <p class="text-xs text-gray-500 line-clamp-2">{{ $request->message }}</p>
    </div>
    @endif

    <!-- Actions -->
    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
        <div class="flex items-center space-x-2">
            <!-- Status Change Buttons -->
            @if($request->status == 'pending')
                <button class="status-change-btn text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition" data-request-id="{{ $request->id }}" data-status="on_process">
                    Process
                </button>
            @elseif($request->status == 'on_process')
                <button class="status-change-btn text-xs px-2 py-1 bg-green-50 text-green-700 rounded hover:bg-green-100 transition" data-request-id="{{ $request->id }}" data-status="done">
                    Complete
                </button>
            @endif
        </div>

        <div class="flex items-center space-x-1">
            <a href="{{ route('client-requests.show', $request) }}" class="p-1 text-gray-400 hover:text-blue-600 transition" title="View Details">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>

            @can('update', $request)
            <a href="{{ route('client-requests.edit', $request) }}" class="p-1 text-gray-400 hover:text-yellow-600 transition" title="Edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            @endcan

            @can('delete', $request)
            <form action="{{ route('client-requests.destroy', $request) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data booking ini? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-1 text-gray-400 hover:text-red-600 transition" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
            @endcan
        </div>
    </div>

    <!-- Time stamp -->
    <div class="mt-2 pt-2 border-t border-gray-100">
        <p class="text-xs text-gray-400">
            Created {{ $request->created_at->diffForHumans() }}
        </p>
    </div>
</div>
