<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class ClientChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_id',
        'category',
        'title',
        'is_custom',
        'is_checked',
        'notes',
        'order',
        'days_before_event',
        'suggested_date',
        'custom_due_date',
        'priority',
        'is_flexible',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'is_checked' => 'boolean',
        'is_flexible' => 'boolean',
        'suggested_date' => 'date',
        'custom_due_date' => 'date',
    ];

    /**
     * Get the checklist that owns this item.
     */
    public function checklist(): BelongsTo
    {
        return $this->belongsTo(ClientChecklist::class, 'checklist_id');
    }

    /**
     * Get the vendors associated with this checklist item.
     */
    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'checklist_item_vendor')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get effective due date (custom overrides suggested).
     */
    public function getDueDateAttribute()
    {
        return $this->custom_due_date ?? $this->suggested_date;
    }

    /**
     * Check if item is overdue.
     */
    public function getIsOverdueAttribute()
    {
        if (!$this->is_checked && $this->due_date) {
            return Carbon::parse($this->due_date)->isPast();
        }
        return false;
    }

    /**
     * Calculate and save suggested date based on event date.
     */
    public function calculateSuggestedDate($eventDate)
    {
        if ($this->days_before_event) {
            $this->suggested_date = Carbon::parse($eventDate)
                ->subDays($this->days_before_event);
            $this->save();
        }
    }

    /**
     * Get priority badge color.
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'CRITICAL' => 'red',
            'IMPORTANT' => 'orange',
            'NICE_TO_HAVE' => 'yellow',
            default => 'gray',
        };
    }
}
