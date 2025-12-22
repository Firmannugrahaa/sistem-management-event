<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistTemplateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'category',
        'title',
        'order',
        'days_before_event',
        'priority',
        'is_flexible',
    ];

    protected $casts = [
        'is_flexible' => 'boolean',
    ];

    /**
     * Get the template that owns this item.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'template_id');
    }
}
