<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StitchingStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'stitching_order_id',
        'old_status',
        'new_status',
        'changed_by_tailor_id',
        'changed_at',
        'notes',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * Get the stitching order
     */
    public function stitchingOrder()
    {
        return $this->belongsTo(StitchingOrder::class, 'stitching_order_id');
    }

    /**
     * Get the tailor who made the change
     */
    public function tailor()
    {
        return $this->belongsTo(User::class, 'changed_by_tailor_id');
    }

    /**
     * Get status color for display
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => '#ffc107',          // Yellow
            'stitching_started' => '#17a2b8', // Blue
            'cutting_completed' => '#20c997', // Cyan
            'stitching_in_progress' => '#6f42c1', // Purple
            'quality_checking' => '#e83e8c',   // Pink
            'completed' => '#28a745',       // Green
            'delivered' => '#0275d8',       // Light Blue
        ];
        
        return $colors[$this->new_status] ?? '#6c757d';
    }

    /**
     * Get status badge class for display
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'stitching_started' => 'badge-info',
            'cutting_completed' => 'badge-success',
            'stitching_in_progress' => 'badge-primary',
            'quality_checking' => 'badge-danger',
            'completed' => 'badge-success',
            'delivered' => 'badge-info',
        ];
        
        return $badges[$this->new_status] ?? 'badge-secondary';
    }

    /**
     * Get human-readable status text
     */
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'stitching_started' => 'Stitching Started',
            'cutting_completed' => 'Cutting Completed',
            'stitching_in_progress' => 'Stitching In Progress',
            'quality_checking' => 'Quality Checking',
            'completed' => 'Completed',
            'delivered' => 'Delivered',
        ];
        
        return $statuses[$this->new_status] ?? ucfirst(str_replace('_', ' ', $this->new_status));
    }

    /**
     * Get status icon for timeline
     */
    public function getStatusIconAttribute()
    {
        $icons = [
            'pending' => 'fa-hourglass-start',
            'stitching_started' => 'fa-play-circle',
            'cutting_completed' => 'fa-cut',
            'stitching_in_progress' => 'fa-needle',
            'quality_checking' => 'fa-magnifying-glass',
            'completed' => 'fa-check-circle',
            'delivered' => 'fa-shipping-fast',
        ];
        
        return $icons[$this->new_status] ?? 'fa-circle';
    }
}
