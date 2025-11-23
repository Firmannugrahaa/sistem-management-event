<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'address',
        'company_name',
        'job_title',
        'bio',
        'profile_image',
        'website',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array', // Convert JSON to array and vice versa
    ];

    // Define the relationship to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
