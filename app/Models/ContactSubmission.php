<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'full_name',
        'email',
        'phone',
        'subject',
        'message',
        'service_type',
        'garment_type',
        'measurements',
        'special_instructions',
        'design_image',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'measurements' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get message submissions
     */
    public static function getMessages()
    {
        return self::where('type', 'message')->orderBy('created_at', 'desc');
    }

    /**
     * Get tailoring request submissions
     */
    public static function getTailoringRequests()
    {
        return self::where('type', 'tailoring_request')->orderBy('created_at', 'desc');
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark as unread
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Check if read
     */
    public function isRead()
    {
        return $this->is_read === true;
    }

    /**
     * Check if unread
     */
    public function isUnread()
    {
        return $this->is_read === false;
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        return $this->is_read ? '<span class="badge bg-success">Read</span>' : '<span class="badge bg-warning">New</span>';
    }

    /**
     * Get unread contact messages count for admin
     */
    public static function getUnreadCount()
    {
        return static::where('is_read', false)->count();
    }

    /**
     * Scope: Get unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Get read messages
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
}
