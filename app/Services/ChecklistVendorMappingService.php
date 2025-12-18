<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\ClientChecklist;
use App\Models\ClientChecklistItem;
use Illuminate\Support\Facades\Log;

class ChecklistVendorMappingService
{
    /**
     * Mapping between vendor categories and checklist item keywords
     */
    private static array $categoryMappings = [
        'Catering' => ['Catering utama', 'Tes menu'],
        'Dekorasi' => ['Dekorasi'],
        'Decoration' => ['Dekorasi'],
        'MUA' => ['MUA pengantin'],
        'Make Up Artist' => ['MUA pengantin'],
        'Makeup Artist' => ['MUA pengantin'],
        'Fotografer' => ['Fotografer & videografer'],
        'Videografer' => ['Fotografer & videografer'],
        'Photographer' => ['Fotografer & videografer'],
        'Videographer' => ['Fotografer & videografer'],
        'Photography' => ['Fotografer & videografer'],
        'Entertainment' => ['Entertainment (band / wedding singer)'],
        'Band' => ['Entertainment (band / wedding singer)'],
        'Wedding Singer' => ['Entertainment (band / wedding singer)'],
        'Singer' => ['Entertainment (band / wedding singer)'],
        'Venue' => ['Booking venue akad', 'Booking venue resepsi'],
        'MC' => ['Penentuan MC & susunan acara'],
        'Master of Ceremony' => ['Penentuan MC & susunan acara'],
    ];

    /**
     * Auto-check checklist items when vendor is confirmed for an event
     *
     * @param int $eventId
     * @param Vendor $vendor
     * @return void
     */
    public static function autoCheckItems(int $eventId, Vendor $vendor): void
    {
        // Find checklist for this event
        $checklist = ClientChecklist::where('event_id', $eventId)->first();
        
        if (!$checklist) {
            Log::info("ChecklistVendorMapping: No checklist found for event ID: {$eventId}");
            return;
        }

        // Get checklist item titles to check based on vendor category
        $itemTitles = self::$categoryMappings[$vendor->category] ?? [];
        
        if (empty($itemTitles)) {
            Log::info("ChecklistVendorMapping: No mapping found for vendor category: {$vendor->category}");
            return;
        }

        $checkedCount = 0;
        
        // Auto-check matching items
        foreach ($itemTitles as $title) {
            $item = $checklist->items()
                ->where('title', 'LIKE', "%{$title}%")
                ->where('is_checked', false)
                ->first();
            
            if ($item) {
                $item->update(['is_checked' => true]);
                $checkedCount++;
                Log::info("ChecklistVendorMapping: Auto-checked '{$title}' for event ID: {$eventId}, vendor: {$vendor->brand_name}");
            }
        }

        if ($checkedCount > 0) {
            // Update checklist status
            $checklist->updateStatus();
            Log::info("ChecklistVendorMapping: Updated checklist status for event ID: {$eventId}. Checked {$checkedCount} items.");
        }
    }

    /**
     * Uncheck items when vendor is removed/cancelled
     *
     * @param int $eventId
     * @param Vendor $vendor
     * @return void
     */
    public static function uncheckItems(int $eventId, Vendor $vendor): void
    {
        $checklist = ClientChecklist::where('event_id', $eventId)->first();
        
        if (!$checklist) {
            return;
        }

        $itemTitles = self::$categoryMappings[$vendor->category] ?? [];
        
        if (empty($itemTitles)) {
            return;
        }

        $uncheckedCount = 0;
        
        foreach ($itemTitles as $title) {
            $item = $checklist->items()
                ->where('title', 'LIKE', "%{$title}%")
                ->where('is_checked', true)
                ->first();
            
            if ($item) {
                $item->update(['is_checked' => false]);
                $uncheckedCount++;
                Log::info("ChecklistVendorMapping: Unchecked '{$title}' for event ID: {$eventId}, vendor: {$vendor->brand_name}");
            }
        }

        if ($uncheckedCount > 0) {
            $checklist->updateStatus();
            Log::info("ChecklistVendorMapping: Updated checklist status for event ID: {$eventId}. Unchecked {$uncheckedCount} items.");
        }
    }

    /**
     * Get all available category mappings
     *
     * @return array
     */
    public static function getMappings(): array
    {
        return self::$categoryMappings;
    }
}
