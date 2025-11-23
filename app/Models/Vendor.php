<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ServiceType;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_type_id',
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

    // Relasi One-to-One (inverse): Vendor ini dimiliki oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi One-to-One (inverse): Vendor ini memiliki satu ServiceType
    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    // Relasi Many-to-Many: Vendor bisa menyediakan banyak layanan
    public function services()
    {
        return $this->belongsToMany(Service::class, 'vendor_services')
                    ->withPivot('price', 'description', 'is_available')
                    ->withTimestamps();
    }
}
