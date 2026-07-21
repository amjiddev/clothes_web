<?php

namespace App\Traits;

use App\Models\TailorNotification;
use App\Services\NotificationService;

trait NotifiableTrait
{
    /**
     * Get notification service instance
     */
    protected function notificationService(): NotificationService
    {
        return app(NotificationService::class);
    }

    /**
     * Get unread tailor notifications for this user (if it's a tailor)
     */
    public function unreadTailorNotifications()
    {
        if (!$this->isTailor()) {
            return collect();
        }

        return TailorNotification::where('user_id', $this->id)
            ->whereNull('read_at')
            ->latest()
            ->get();
    }

    /**
     * Get unread tailor notification count (if it's a tailor)
     */
    public function unreadTailorNotificationsCount(): int
    {
        if (!$this->isTailor()) {
            return 0;
        }

        return TailorNotification::getUnreadCount($this->id);
    }

    /**
     * Mark all tailor notifications as read
     */
    public function markAllTailorNotificationsAsRead()
    {
        return $this->notificationService()->markAllAsRead($this);
    }

    /**
     * Get all tailor notifications with optional filters
     */
    public function getAllTailorNotifications($perPage = 20, $filters = [])
    {
        return $this->notificationService()->getAllNotifications($this, $perPage, $filters);
    }

    /**
     * Get tailor notification statistics
     */
    public function getTailorNotificationStats()
    {
        return $this->notificationService()->getStatistics($this);
    }

    /**
     * Delete all tailor notifications
     */
    public function deleteAllTailorNotifications(): int
    {
        return $this->notificationService()->deleteAllNotifications($this);
    }
}
