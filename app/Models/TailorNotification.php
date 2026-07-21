<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\NotificationCreated;

class TailorNotification extends Model
{
    use HasFactory;

    protected $table = 'tailor_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'action_url',
        'icon',
        'color',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Notification types
    const TYPE_ORDER_ASSIGNED = 'order_assigned';
    const TYPE_ORDER_APPROACHING = 'order_approaching';
    const TYPE_STATUS_UPDATED = 'status_updated';
    const TYPE_DESIGN_UPLOADED = 'design_uploaded';

    protected $dispatchesEvents = [
        'created' => NotificationCreated::class,
    ];

    /**
     * Belongs to user (tailor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
        return $this;
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
        return $this;
    }

    /**
     * Check if notification is read
     */
    public function isRead()
    {
        return $this->read_at !== null;
    }

    /**
     * Check if notification is unread
     */
    public function isUnread()
    {
        return $this->read_at === null;
    }

    /**
     * Get unread notifications count
     */
    public static function getUnreadCount($userId)
    {
        return static::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get notification type label
     */
    public function getTypeLabel()
    {
        $labels = [
            self::TYPE_ORDER_ASSIGNED => 'Order Assigned',
            self::TYPE_ORDER_APPROACHING => 'Order Approaching',
            self::TYPE_STATUS_UPDATED => 'Status Updated',
            self::TYPE_DESIGN_UPLOADED => 'Design Uploaded',
        ];
        return $labels[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }

    /**
     * Get notification icon
     */
    public function getIcon()
    {
        return $this->icon ?? 'fas fa-bell';
    }

    /**
     * Get notification color
     */
    public function getColor()
    {
        return $this->color ?? '#3498db';
    }

    /**
     * Scope: Get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope: Get read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope: Get notifications by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Get recent notifications
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Get notifications for user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
