<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class LandingGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'landing_gallery';

    protected $fillable = [
        'image_path',
        'title',
        'description',
        'category',
        'source',
        'vendor_id',
        'uploaded_by',
        'is_featured',
        'display_order',
        'is_active',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'display_order' => 'integer',
    ];

    protected $appends = ['image_url', 'status_badge'];

    /**
     * Relationship: Gallery item uploaded by user
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Relationship: Gallery item approved by admin
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relationship: Gallery item from vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }
        return null;
    }

    /**
     * Get status badge for admin panel
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'approved' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>',
            'rejected' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rejected</span>',
        ];

        return $badges[$this->approval_status] ?? '';
    }

    /**
     * Scope: Get approved and active items
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved')
                     ->where('is_active', true);
    }

    /**
     * Scope: Get pending items
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope: Get featured items
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: Order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Filter by category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Approve this gallery item
     */
    public function approve($approvedBy = null)
    {
        $this->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy ?? auth()->id(),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Reject this gallery item
     */
    public function reject($reason = null, $rejectedBy = null)
    {
        $this->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_by' => $rejectedBy ?? auth()->id(),
            'approved_at' => null,
        ]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured()
    {
        $this->update(['is_featured' => !$this->is_featured]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive()
    {
        $this->update(['is_active' => !$this->is_active]);
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute()
    {
        $labels = [
            'wedding' => 'Pernikahan',
            'birthday' => 'Ulang Tahun',
            'corporate' => 'Korporat',
            'engagement' => 'Lamaran',
            'other' => 'Lainnya',
        ];

        return $labels[$this->category] ?? 'Lainnya';
    }
}
