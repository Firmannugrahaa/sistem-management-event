<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'venue_id',
        'event_name',
        'description',
        'start_time',
        'end_time',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }
    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class)
            ->withPivot('agreed_price', 'contract_details', 'status');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
