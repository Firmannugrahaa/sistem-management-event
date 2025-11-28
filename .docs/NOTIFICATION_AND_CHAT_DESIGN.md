# 📋 Notification & Chat System Design

## 🔔 **Part 1: Notification System**

### **User Story:**
Ketika Admin/Owner mengubah status ClientRequest, **Client/User** akan menerima notifikasi di dashboard mereka.

### **Database Schema:**

```sql
CREATE TABLE notifications (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,  -- Penerima notifikasi
    type VARCHAR(50),  -- 'status_update', 'event_created', 'message', dll
    title VARCHAR(255),
    message TEXT,
    link VARCHAR(255) NULL,  -- Link ke halaman terkait
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### **Notification Types:**
1. `status_update` - Status ClientRequest diubah
2. `event_created` - Event dibuat dari ClientRequest
3. `recommendation_sent` - Vendor recommendation dikirim
4. `staff_assigned` - Staff ditugaskan
5. `message_received` - Pesan baru di chat

### **Flow:**
```
Admin update status ClientRequest
    ↓
Trigger: Create Notification
    ↓
Notification::create([
    'user_id' => $clientRequest->user_id,
    'type' => 'status_update',
    'title' => 'Status Permintaan Diperbarui',
    'message' => "Status permintaan Anda untuk {$clientRequest->event_type} telah diubah menjadi {$newStatus}",
    'link' => route('client.requests.show', $clientRequest->id),
    'is_read' => false
])
    ↓
Client Dashboard menampilkan notifikasi
```

### **UI Components:**
1. **Bell Icon** di navbar dengan badge count unread
2. **Dropdown Notifications** saat klik bell
3. **Notification List Page** untuk melihat semua notifikasi
4. **Highlight unread** dengan background berbeda

---

## 💬 **Part 2: Chat System Design**

### **User Story:**
Semua pihak yang terlibat dalam sebuah Event (Client, Admin, Vendors) bisa saling berkomunikasi via chat internal.

### **Database Schema:**

#### **1. chat_rooms TABLE**
```sql
CREATE TABLE chat_rooms (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    event_id BIGINT NOT NULL,  -- Link ke event
    name VARCHAR(255),  -- "Event: Wedding John & Jane"
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);
```

#### **2. chat_participants TABLE** (Many-to-Many)
```sql
CREATE TABLE chat_participants (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    chat_room_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,  -- User yang terlibat (Client, Admin, Staff, Vendor)
    joined_at TIMESTAMP,
    last_read_at TIMESTAMP NULL,  -- Untuk tracking unread messages
    
    FOREIGN KEY (chat_room_id) REFERENCES chat_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_participant (chat_room_id, user_id)
);
```

#### **3. chat_messages TABLE**
```sql
CREATE TABLE chat_messages (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    chat_room_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,  -- Pengirim
    message TEXT NOT NULL,
    attachment_path VARCHAR(255) NULL,  -- Optional file attachment
    is_system_message BOOLEAN DEFAULT FALSE,  -- e.g., "John joined the chat"
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (chat_room_id) REFERENCES chat_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_room_created (chat_room_id, created_at)  -- For efficient message loading
);
```

### **Participants Auto-Add Logic:**

Ketika **Event dibuat**, otomatis buat chat room dan add participants:
1. **Client** (dari event->client_email atau client_request->user_id)
2. **Admin/Owner** yang membuat event
3. **Assigned Staff** (jika ada)
4. **Vendors** yang di-assign ke event

```php
// EventController::store() - After creating event
$chatRoom = ChatRoom::create([
    'event_id' => $event->id,
    'name' => 'Chat: ' . $event->event_name
]);

// Add participants
$participants = [];

// 1. Add Client
if ($event->clientRequest && $event->clientRequest->user_id) {
    $participants[] = $event->clientRequest->user_id;
}

// 2. Add Event Creator (Admin/Owner)
$participants[] = auth()->id();

// 3. Add assigned staff (if any)
// ... logic untuk ambil staff

// 4. Add vendors
foreach ($event->vendors as $vendor) {
    if ($vendor->user_id) {
        $participants[] = $vendor->user_id;
    }
}

// Insert participants
foreach (array_unique($participants) as $userId) {
    ChatParticipant::create([
        'chat_room_id' => $chatRoom->id,
        'user_id' => $userId,
        'joined_at' => now()
    ]);
}
```

### **Chat UI Components:**

#### **1. Chat Icon pada Event Detail Page**
```blade
<a href="{{ route('chat.room', $event->id) }}" class="...">
    <i class="fas fa-comments"></i> Chat Grup
    @if($unreadCount > 0)
        <span class="badge">{{ $unreadCount }}</span>
    @endif
</a>
```

#### **2. Chat Room Interface**
```
┌─────────────────────────────────────────┐
│ Chat: Wedding John & Jane          [X]  │
├─────────────────────────────────────────┤
│ Participants (4):                       │
│  👤 John (Client)                       │
│  👨‍💼 Admin Budi                          │
│  🏢 Catering Vendor                     │
│  🏢 Venue Vendor                        │
├─────────────────────────────────────────┤
│ ┌─ Messages ─────────────────────────┐ │
│ │                                     │ │
│ │ [Admin Budi] 10:00                 │ │
│ │ Halo, konfirmasi venue sudah OK    │ │
│ │                                     │ │
│ │ [You (John)] 10:05                 │ │
│ │ Terima kasih! Bagaimana dg menu?   │ │
│ │                                     │ │
│ │ [Catering Vendor] 10:10            │ │
│ │ Menu sudah disiapkan, cek email    │ │
│ │                                     │ │
│ └─────────────────────────────────────┘ │
├─────────────────────────────────────────┤
│ [Type a message...] [📎] [Send]         │
└─────────────────────────────────────────┘
```

### **Real-time Features (Optional dengan Pusher/Echo):**
1. **Live message updates** - baru

 terkirim langsung muncul
2. **Typing indicator** - "Budi is typing..."
3. **Online status** - Siapa yang online

### **Non-Real-time Alternative (Simpler):**
1. **Polling** - setiap 5 detik refresh messages
2. **Manual Refresh Button**
3. **Notification** ketika ada pesan baru

---

## 🛠️ **Implementation Steps:**

### **Phase 1: Notification System** (Priority High)
1. ✅ Create migration `create_notifications_table`
2. ⏳ Create Notification model
3. ⏳ Update ClientRequestController::updateStatus() - trigger notification
4. ⏳ Create notification component di navbar
5. ⏳ Create notification dropdown
6. ⏳ Create notification page

### **Phase 2: Chat System** (Priority Medium)
1. ⏳ Create migrations (chat_rooms, chat_participants, chat_messages)
2. ⏳ Create models (ChatRoom, ChatParticipant, ChatMessage)
3. ⏳ Create ChatController
4. ⏳ Auto-create chat room when event created
5. ⏳ Create chat UI (room list, message view, send form)
6. ⏳ Add chat link to event detail page

### **Phase 3: Real-time (Optional)**
1. ⏳ Install Laravel Echo & Pusher
2. ⏳ Create broadcast events
3. ⏳ Add real-time listeners

---

## 📝 **Next Actions:**

**Mau saya lanjutkan implementasi?**

1. **Notification System** (lebih simple, cepat)
2. **Chat System** (butuh waktu lebih lama)
3. **Keduanya sekaligus** (step by step)

**Catatan:** 
- Notification bisa selesai hari ini
- Chat basic (tanpa real-time) bisa selesai hari ini
- Chat dengan real-time butuh setup Pusher (optional, bisa nanti)

Silakan pilih prioritas! 🚀
