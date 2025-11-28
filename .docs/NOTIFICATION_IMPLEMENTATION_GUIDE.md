# 🔔 Notification System - Implementation Guide

## ✅ Progress: 70% Complete

### **Sudah Selesai:**
1. ✅ Migration`notifications` table - `database/migrations/2025_11_27_151230_create_notifications_table.php`
2. ✅ Model `Notification` - `app/Models/Notification.php`
3. ✅ User model relationships - `notifications()` & `unreadNotifications()`

### **Perlu Diselesaikan:**

#### **Step 1: Fix ClientRequestController.php**

File `app/Http/Controllers/ClientRequestController.php` corrupt di baris 206-207. 

**Manual Fix:**
1. Buka file `app/Http/Controllers/ClientRequestController.php`
2. Hapus baris 206 yang berisi `*/`
3. Tambahkan closing statement untuk method `update()` di baris 204:
   ```php
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
   
       return response()->json([
           'success' => true,
           'message' => 'Status updated successfully',
       ]);
   }
   
   /**
    * Assign staff to request via AJAX
   ```

#### **Step 2: Run Migration**

```bash
php artisan migrate
```

#### **Step 3: Create Notification Bell Component**

**File:** `resources/views/components/notification-bell.blade.php`

```blade
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
```

#### **Step 4: Add Bell to Navigation**

**File:** `resources/views/layouts/navigation.blade.php`

Find the user dropdown section and add before it:

```blade
<!-- Notification Bell -->
<x-notification-bell />
```

#### **Step 5: Create NotificationController**

**File:** `app/Http/Controllers/NotificationController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return redirect($notification->link ?? route('dashboard'));
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['is_read' => true]);
        
        return back()->with('success', 'All notifications marked as read');
    }

    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification deleted');
    }
}
```

#### **Step 6: Add Routes**

**File:** `routes/web.php`

```php
// Notifications
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-read');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])
        ->name('notifications.destroy');
});
```

#### **Step 7: Create Notifications Index Page**

**File:** `resources/views/notifications/index.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">All Notifications</h1>
                @if(auth()->user()->unreadNotifications()->count() > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-blue-600 hover:underline">
                            Mark all as read
                        </button>
                    </form>
                @endif
            </div>

            <div class="space-y-3">
                @forelse($notifications as $notification)
                    <div class="flex items-start gap-4 p-4 rounded-lg border {{ !$notification->is_read ? 'bg-blue-50 border-blue-200' : 'border-gray-200' }}">
                        <span class="text-3xl">{{ $notification->icon }}</span>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $notification->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($notification->link)
                                <a href="{{ $notification->link }}" class="text-blue-600 hover:underline text-sm">View</a>
                            @endif
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-8">No notifications</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 🚀 **Testing:**

1. Admin update status ClientRequest
2. Client login → Lihat bell icon dengan badge count
3. Klik bell → Lihat dropdown notifications
4. Klik notification → Redirect ke detail request
5. Check notifications page

---

## 📝 **Next: Chat System (Phase 2)**

Setelah notification selesai, refer ke `.docs/NOTIFICATION_AND_CHAT_DESIGN.md` untuk implementasi chat system.
