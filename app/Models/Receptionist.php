<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receptionist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'department',
        'status',
        'assigned_date',
        'last_action_date',
    ];

    protected $casts = [
        'status' => 'string',
        'assigned_date' => 'datetime',
        'last_action_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'active' ? 'success' : 'danger';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return $this->status === 'active' ? 'Active' : 'Inactive';
    }

    /**
     * Activate receptionist
     */
    public function activate()
    {
        $this->status = 'active';
        $this->last_action_date = now();
        $this->save();
        $this->user->syncRoles('receptionist');
        return $this;
    }

    /**
     * Deactivate receptionist
     */
    public function deactivate()
    {
        $this->status = 'inactive';
        $this->last_action_date = now();
        $this->save();
        $this->user->removeRole('receptionist');
        return $this;
    }

    /**
     * Get receptionist permissions
     */
    public function getPermissions()
    {
        return [
            'view_customers',
            'create_customers',
            'edit_customers',
            'view_all_orders',
            'create_orders',
            'edit_orders',
            'assign_tailors',
            'view_stitching_orders',
        ];
    }

    /**
     * Check if has specific permission
     */
    public function hasPermission($permission)
    {
        return in_array($permission, $this->getPermissions());
    }
}
