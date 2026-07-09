<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tailor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'specialization',
        'skills',
        'bio',
        'profile_image',
        'hourly_rate',
        'experience_years',
        'status',
        'total_orders',
        'average_rating',
        'completed_orders',
        'pending_orders',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'skills' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stitchingOrders()
    {
        return $this->user->tailorAssignments();
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function getWorkloadAttribute()
    {
        return $this->stitchingOrders()
            ->whereIn('stitching_status', ['pending', 'assigned', 'in_progress', 'ready_for_fitting', 'in_fitting'])
            ->count();
    }

    /**
     * Get total assigned orders
     */
    public function getTotalAssignedOrders()
    {
        return $this->user->tailorAssignments()->count();
    }

    /**
     * Get completed orders
     */
    public function getCompletedOrders()
    {
        return $this->user->tailorAssignments()
            ->where('stitching_status', 'completed')
            ->count();
    }

    /**
     * Get pending orders
     */
    public function getPendingOrders()
    {
        return $this->user->tailorAssignments()
            ->whereIn('stitching_status', ['pending', 'assigned', 'in_progress', 'ready_for_fitting', 'in_fitting'])
            ->count();
    }

    /**
     * Get active orders count
     */
    public function getActiveOrders()
    {
        return $this->user->tailorAssignments()
            ->whereIn('stitching_status', ['assigned', 'in_progress', 'ready_for_fitting', 'in_fitting'])
            ->count();
    }

    /**
     * Get profile image URL
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return null;
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'success',
            'inactive' => 'danger',
            'on_leave' => 'warning',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }
}
