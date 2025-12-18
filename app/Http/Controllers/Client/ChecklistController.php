<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use App\Models\ClientChecklist;
use App\Models\ClientChecklistItem;
use App\Models\ChecklistTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    /**
     * Display checklist for a client request.
     */
    public function index(ClientRequest $clientRequest)
    {
        // Ensure client owns this request
        if ($clientRequest->user_id !== Auth::id()) {
            abort(403);
        }

        // Get or create checklist
        $checklist = ClientChecklist::firstOrCreate(
            ['client_request_id' => $clientRequest->id],
            [
                'event_id' => $clientRequest->event_id ?? null,
                'status' => 'not_started',
            ]
        );

        // If checklist is newly created, populate from template
        if ($checklist->items()->count() === 0) {
            $this->populateFromTemplate($checklist, $clientRequest->event_type);
        }

        // Load items grouped by category
        $items = $checklist->items()->orderBy('order')->get()->groupBy('category');

        return view('client.checklist.index', compact('clientRequest', 'checklist', 'items'));
    }

    /**
     * Populate checklist from template.
     */
    private function populateFromTemplate(ClientChecklist $checklist, string $eventType)
    {
        $template = ChecklistTemplate::where('event_type', $eventType)->first();

        if (!$template) {
            return;
        }

        $templateItems = $template->items()->orderBy('order')->get();

        foreach ($templateItems as $templateItem) {
            ClientChecklistItem::create([
                'checklist_id' => $checklist->id,
                'category' => $templateItem->category,
                'title' => $templateItem->title,
                'is_custom' => false,
                'is_checked' => false,
                'order' => $templateItem->order,
                // Copy timeline fields from template
                'days_before_event' => $templateItem->days_before_event,
                'priority' => $templateItem->priority ?? 'IMPORTANT',
                'is_flexible' => $templateItem->is_flexible ?? true,
            ]);
        }
    }

    /**
     * Add a custom checklist item.
     */
    public function storeItem(Request $request, ClientChecklist $checklist)
    {
        // Ensure client owns this checklist
        if ($checklist->clientRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $maxOrder = $checklist->items()->max('order') ?? 0;

        ClientChecklistItem::create([
            'checklist_id' => $checklist->id,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'is_custom' => true,
            'is_checked' => false,
            'notes' => $validated['notes'] ?? null,
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('client.checklist', $checklist->clientRequest)
            ->with('success', 'Item checklist berhasil ditambahkan!');
    }

    /**
     * Update a checklist item (toggle checked or edit notes).
     */
    public function updateItem(Request $request, ClientChecklistItem $item)
    {
        // Ensure client owns this item
        if ($item->checklist->clientRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'is_checked' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['is_checked'])) {
            $item->is_checked = $validated['is_checked'];
        }

        if (isset($validated['notes'])) {
            $item->notes = $validated['notes'];
        }

        $item->save();

        // Update checklist status
        $item->checklist->updateStatus();

        return redirect()->route('client.checklist', $item->checklist->clientRequest)
            ->with('success', 'Item checklist berhasil diupdate!');
    }

    /**
     * Delete a custom checklist item.
     */
    public function destroyItem(ClientChecklistItem $item)
    {
        // Ensure client owns this item
        if ($item->checklist->clientRequest->user_id !== Auth::id()) {
            abort(403);
        }

        // Only custom items can be deleted
        if (!$item->is_custom) {
            return redirect()->route('client.checklist', $item->checklist->clientRequest)
                ->with('error', 'Item default tidak dapat dihapus!');
        }

        $checklist = $item->checklist;
        $item->delete();

        // Update checklist status
        $checklist->updateStatus();

        return redirect()->route('client.checklist', $checklist->clientRequest)
            ->with('success', 'Item checklist berhasil dihapus!');
    }

    /**
     * Display timeline view for checklist.
     */
    public function timeline(ClientRequest $clientRequest)
    {
        // Ensure client owns this request
        if ($clientRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $checklist = ClientChecklist::where('client_request_id', $clientRequest->id)->first();
        
        if (!$checklist) {
            return redirect()->route('client.checklist', $clientRequest)
                ->with('error', 'Checklist belum tersedia.');
        }

        // Get event date
        $eventDate = $clientRequest->event_date ?? $clientRequest->event?->start_time;
        
        if (!$eventDate) {
            return redirect()->route('client.checklist', $clientRequest)
                ->with('error', 'Tanggal event belum ditentukan. Timeline belum bisa ditampilkan.');
        }

        $daysUntilEvent = \Carbon\Carbon::parse($eventDate)->diffInDays(now());
        
        // Determine booking type
        $bookingType = $this->determineBookingType($daysUntilEvent);
        
        // Calculate suggested dates for all items with compression
        $this->calculateTimelineDates($checklist, $eventDate, $daysUntilEvent);
        
        // Filter and group items based on booking type
        $timelineData = $this->buildTimelineData($checklist, $bookingType, $daysUntilEvent);
        
        // Count critical items
        $criticalItemsCount = $checklist->items()->where('priority', 'CRITICAL')->count();
        
        return view('client.checklist.timeline', compact(
            'clientRequest', 
            'checklist', 
            'eventDate', 
            'daysUntilEvent',
            'bookingType',
            'timelineData',
            'criticalItemsCount'
        ));
    }

    /**
     * Determine booking type based on days until event.
     */
    private function determineBookingType(int $daysUntilEvent): string
    {
        return match(true) {
            $daysUntilEvent >= 180 => 'standard',
            $daysUntilEvent >= 90 => 'moderate',
            $daysUntilEvent >= 60 => 'tight',
            $daysUntilEvent >= 30 => 'rush',
            default => 'emergency',
        };
    }

    /**
     * Calculate suggested dates with timeline compression for rush bookings.
     */
    private function calculateTimelineDates(ClientChecklist $checklist, $eventDate, int $daysUntilEvent)
    {
        $compressionRatio = match(true) {
            $daysUntilEvent >= 180 => 1.0,
            $daysUntilEvent >= 90 => 0.5,
            $daysUntilEvent >= 60 => 0.33,
            $daysUntilEvent >= 30 => 0.20,
            default => 0.10,
        };
        
        foreach ($checklist->items as $item) {
            if ($item->days_before_event && !$item->suggested_date) {
                $scaledDays = round($item->days_before_event * $compressionRatio);
                $item->suggested_date = \Carbon\Carbon::parse($eventDate)
                    ->subDays(max($scaledDays, 1));
                $item->save();
            }
        }
    }

    /**
     * Build timeline data grouped by timeframes.
     */
    private function buildTimelineData(ClientChecklist $checklist, string $bookingType, int $daysUntilEvent)
    {
        // Define timeframes based on booking type
        if ($bookingType === 'emergency') {
            $timeframes = [
                ['label' => 'ASAP (1-3 days)', 'min' => 0, 'max' => 3, 'priority' => 'CRITICAL'],
                ['label' => 'This Week (4-7 days)', 'min' => 4, 'max' => 7, 'priority' => 'CRITICAL'],
                ['label' => 'Before Event (8+ days)', 'min' => 8, 'max' => 999, 'priority' => 'IMPORTANT'],
            ];
        } elseif ($bookingType === 'rush') {
            $timeframes = [
                ['label' => 'This Week', 'min' => 0, 'max' => 7],
                ['label' => 'Next 2 Weeks', 'min' => 8, 'max' => 14],
                ['label' => 'Next Month', 'min' => 15, 'max' => 30],
            ];
        } else {
            $timeframes = [
                ['label' => '6+ Months Before', 'min' => 180, 'max' => 365],
                ['label' => '3-6 Months Before', 'min' => 90, 'max' => 179],
                ['label' => '2-3 Months Before', 'min' => 60, 'max' => 89],
                ['label' => '1-2 Months Before', 'min' => 30, 'max' => 59],
                ['label' => '2-4 Weeks Before', 'min' => 14, 'max' => 29],
                ['label' => '1-2 Weeks Before', 'min' => 7, 'max' => 13],
                ['label' => 'Week of Event', 'min' => 0, 'max' => 6],
            ];
        }

        $timelineData = [];
        foreach ($timeframes as $tf) {
            $query = $checklist->items()
                ->whereBetween('days_before_event', [$tf['min'], $tf['max']]);
            
            // Filter by priority for emergency bookings
            if (isset($tf['priority'])) {
                $query->where('priority', $tf['priority']);
            }
            
            // For rush/emergency, hide flexible items
            if (in_array($bookingType, ['rush', 'emergency'])) {
                $query->where(function($q) {
                    $q->where('is_flexible', false)
                      ->orWhere('priority', 'CRITICAL');
                });
            }
            
            $items = $query->orderBy('days_before_event', 'desc')->get();
            
            if ($items->count() > 0) {
                $completedCount = $items->where('is_checked', true)->count();
                $timelineData[] = [
                    'label' => $tf['label'],
                    'items' => $items,
                    'progress' => $items->count() > 0 ? round($completedCount / $items->count() * 100) : 0,
                    'completed' => $completedCount,
                    'total' => $items->count(),
                ];
            }
        }

        return $timelineData;
    }
}
