<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'contact_person',
        'phone_number',
        'address',
    ];

    // Relasi Many-to-Many: Vendor ini bisa bekerja di banyak event
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->withPivot('agreed_price', 'contract_details', 'status');
            
    }
}
