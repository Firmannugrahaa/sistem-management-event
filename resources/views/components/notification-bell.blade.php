@auth
@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $notifications = auth()->user()->notifications()->limit(5)->get();
@endphp

<div x-data="{ open: false }" class="relative">
    <!-- Bell Icon Button -->
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            @if($unreadCount > 0)
                <a href="{{ route('notifications.mark-all-read') }}" 
                   class="text-xs text-blue-600 hover:underline"
                   onclick="event.preventDefault(); fetch(this.href, {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(() => location.reload())">
                    Mark all read
                </a>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <a href="{{ $notification->link ?? '#' }}" 
                   class="block px-4 py-3 hover:bg-gray-50 transition {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl flex-shrink-0">{{ $notification->icon }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($notification->message, 80) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notification->is_read)
                            <span class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0 mt-1"></span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="px-4 py-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-sm">No notifications yet</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200">
            <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-blue-600 hover:underline">
                View all notifications
            </a>
        </div>
    </div>
</div>
@endauth
