<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'name',
    ];

    /**
     * Get the template items for this template.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ChecklistTemplateItem::class, 'template_id');
    }
}
