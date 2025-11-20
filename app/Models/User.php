<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'avatar',
        'position',
        'department',
        'approval_status',
        'type',
        'status',
        'owner_id',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    /**
     * Scope to get only team members
     */
    public function scopeTeamMembers($query)
    {
        return $query->where('type', 'team_member');
    }

    /**
     * Scope to get only vendors
     */
    public function scopeVendors($query)
    {
        return $query->where('type', 'vendor');
    }

    /**
     * Scope to filter by approval status
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope to filter by approval status
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope to filter by approval status
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }
}
