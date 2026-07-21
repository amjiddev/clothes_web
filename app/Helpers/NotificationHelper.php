<?php

namespace App\Helpers;

use App\Models\TailorNotification;
use App\Models\User;
use App\Services\NotificationService;

class NotificationHelper
{
    /**
     * Get notification service
     */
    public static function service(): NotificationService
    {
        return app(NotificationService::class);
    }

    /**
     * Get unread notifications count for tailor
     */
    public static function unreadCount(User $tailor): int
    {
        return TailorNotification::getUnreadCount($tailor->id);
    }

    /**
     * Get recent unread notifications
     */
    public static function recent(User $tailor, int $limit = 5)
    {
        return TailorNotification::where('user_id', $tailor->id)
            ->whereNull('read_at')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get notification type label
     */
    public static function typeLabel(string $type): string
    {
        $labels = [
            TailorNotification::TYPE_ORDER_ASSIGNED => 'Order Assigned',
            TailorNotification::TYPE_ORDER_APPROACHING => 'Order Approaching',
            TailorNotification::TYPE_STATUS_UPDATED => 'Status Updated',
            TailorNotification::TYPE_DESIGN_UPLOADED => 'Design Uploaded',
            'order_ready' => 'Order Ready',
        ];

        return $labels[$type] ?? ucfirst(str_replace('_', ' ', $type));
    }

    /**
     * Get notification color for type
     */
    public static function color(string $type): string
    {
        $colors = [
            TailorNotification::TYPE_ORDER_ASSIGNED => '#3498db',
            TailorNotification::TYPE_ORDER_APPROACHING => '#f39c12',
            TailorNotification::TYPE_STATUS_UPDATED => '#9b59b6',
            TailorNotification::TYPE_DESIGN_UPLOADED => '#27ae60',
            'order_ready' => '#1abc9c',
        ];

        return $colors[$type] ?? '#95a5a6';
    }

    /**
     * Get notification icon for type
     */
    public static function icon(string $type): string
    {
        $icons = [
            TailorNotification::TYPE_ORDER_ASSIGNED => 'fas fa-clipboard-check',
            TailorNotification::TYPE_ORDER_APPROACHING => 'fas fa-hourglass-end',
            TailorNotification::TYPE_STATUS_UPDATED => 'fas fa-sync-alt',
            TailorNotification::TYPE_DESIGN_UPLOADED => 'fas fa-image',
            'order_ready' => 'fas fa-check-circle',
        ];

        return $icons[$type] ?? 'fas fa-bell';
    }

    /**
     * Check if notification type badge should be shown
     */
    public static function shouldShowBadge(TailorNotification $notification): bool
    {
        return $notification->isUnread();
    }

    /**
     * Get notification data field
     */
    public static function getData(TailorNotification $notification, string $key, $default = null)
    {
        return $notification->data[$key] ?? $default;
    }

    /**
     * Format notification for JSON response
     */
    public static function toJson(TailorNotification $notification): array
    {
        return [
            'id' => $notification->id,
            'type' => $notification->type,
            'title' => $notification->title,
            'message' => $notification->message,
            'action_url' => $notification->action_url,
            'icon' => $notification->getIcon(),
            'color' => $notification->getColor(),
            'is_read' => $notification->isRead(),
            'created_at' => $notification->created_at->diffForHumans(),
            'created_at_iso' => $notification->created_at->toIso8601String(),
        ];
    }
}
