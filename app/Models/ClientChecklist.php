<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ClientChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_request_id',
        'event_id',
        'status',
    ];

    /**
     * Get the client request that owns this checklist.
     */
    public function clientRequest(): BelongsTo
    {
        return $this->belongsTo(ClientRequest::class);
    }

    /**
     * Get the event that owns this checklist.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the checklist items for this checklist.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ClientChecklistItem::class, 'checklist_id');
    }

    /**
     * Calculate progress percentage.
     */
    protected function progress(): Attribute
    {
        return Attribute::make(
            get: function () {
                $total = $this->items()->count();
                if ($total === 0) {
                    return 0;
                }
                $checked = $this->items()->where('is_checked', true)->count();
                return round(($checked / $total) * 100);
            }
        );
    }

    /**
     * Update checklist status based on items.
     */
    public function updateStatus(): void
    {
        $total = $this->items()->count();
        $checked = $this->items()->where('is_checked', true)->count();

        if ($checked === 0) {
            $this->status = 'not_started';
        } elseif ($checked === $total) {
            $this->status = 'completed';
        } else {
            $this->status = 'in_progress';
        }

        $this->save();
    }
}
