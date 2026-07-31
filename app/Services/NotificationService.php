<?php

namespace App\Services;

use App\Models\TailorNotification;
use App\Models\StitchingOrder;
use App\Models\User;
use App\Http\Controllers\Tailor\NotificationController;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Send order assigned notification
     */
    public function notifyOrderAssigned(User $tailor, StitchingOrder $stitchingOrder): TailorNotification
    {
        return NotificationController::notifyOrderAssigned(
            $tailor->id,
            $stitchingOrder->id,
            $stitchingOrder->order->order_number
        );
    }

    /**
     * Send order status updated notification
     */
    public function notifyStatusUpdated(User $tailor, StitchingOrder $stitchingOrder, string $newStatus): TailorNotification
    {
        return NotificationController::notifyStatusUpdated(
            $tailor->id,
            $stitchingOrder->id,
            $stitchingOrder->order->order_number,
            $newStatus
        );
    }

    /**
     * Send design uploaded notification
     */
    public function notifyDesignUploaded(User $tailor, StitchingOrder $stitchingOrder): TailorNotification
    {
        return NotificationController::notifyDesignUploaded(
            $tailor->id,
            $stitchingOrder->id,
            $stitchingOrder->order->order_number,
            $stitchingOrder->order->user->name
        );
    }

    /**
     * Send order approaching notification
     */
    public function notifyOrderApproaching(User $tailor, StitchingOrder $stitchingOrder): TailorNotification
    {
        $daysLeft = $stitchingOrder->completion_date->diffInDays(Carbon::today());

        return NotificationController::notifyOrderApproaching(
            $tailor->id,
            $stitchingOrder->id,
            $stitchingOrder->order->order_number,
            $daysLeft
        );
    }

    /**
     * Get unread notifications for tailor
     */
    public function getUnreadNotifications(User $tailor, int $limit = 10): Collection
    {
        return TailorNotification::where('user_id', $tailor->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all notifications for tailor
     */
    public function getAllNotifications(User $tailor, int $perPage = 20, array $filters = [])
    {
        $query = TailorNotification::where('user_id', $tailor->id);

        // Apply filters
        if (isset($filters['status']) && $filters['status'] === 'unread') {
            $query->whereNull('read_at');
        } elseif (isset($filters['status']) && $filters['status'] === 'read') {
            $query->whereNotNull('read_at');
        }

        if (isset($filters['type']) && $filters['type']) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get unread count for tailor
     */
    public function getUnreadCount(User $tailor): int
    {
        return TailorNotification::getUnreadCount($tailor->id);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(TailorNotification $notification): bool
    {
        if (!$notification->isRead()) {
            $notification->markAsRead();
            return true;
        }
        return false;
    }

    /**
     * Mark all notifications as read for tailor
     */
    public function markAllAsRead(User $tailor): int
    {
        return TailorNotification::where('user_id', $tailor->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Delete notification
     */
    public function deleteNotification(TailorNotification $notification): bool
    {
        return (bool) $notification->delete();
    }

    /**
     * Delete all notifications for tailor
     */
    public function deleteAllNotifications(User $tailor): int
    {
        return TailorNotification::where('user_id', $tailor->id)->delete();
    }

    /**
     * Get notification statistics for tailor
     */
    public function getStatistics(User $tailor): array
    {
        $query = TailorNotification::where('user_id', $tailor->id);

        return [
            'unread' => $query->whereNull('read_at')->count(),
            'total' => (clone $query)->count(),
            'by_type' => $query->groupBy('type')
                ->selectRaw('type, COUNT(*) as count')
                ->get()
                ->pluck('count', 'type')
                ->toArray(),
            'by_day' => $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(7)
                ->get()
                ->pluck('count', 'date')
                ->toArray(),
        ];
    }

    /**
     * Check if notification already exists for order
     */
    public function notificationExists(User $tailor, StitchingOrder $stitchingOrder, string $type, string $withinMinutes = '60'): bool
    {
        return TailorNotification::where('user_id', $tailor->id)
            ->where('type', $type)
            ->where('data->order_id', $stitchingOrder->id)
            ->where('created_at', '>=', Carbon::now()->subMinutes($withinMinutes))
            ->exists();
    }

    /**
     * Send batch notifications to multiple tailors
     */
    public function notifyMultipleTailors(array $tailorIds, StitchingOrder $stitchingOrder, string $type): int
    {
        $notificationCount = 0;

        foreach ($tailorIds as $tailorId) {
            $tailor = User::find($tailorId);
            if (!$tailor) {
                continue;
            }

            switch ($type) {
                case 'order_assigned':
                    $this->notifyOrderAssigned($tailor, $stitchingOrder);
                    $notificationCount++;
                    break;
                case 'status_updated':
                    $this->notifyStatusUpdated($tailor, $stitchingOrder, $stitchingOrder->stitching_status);
                    $notificationCount++;
                    break;
                case 'design_uploaded':
                    $this->notifyDesignUploaded($tailor, $stitchingOrder);
                    $notificationCount++;
                    break;
                case 'order_approaching':
                    $this->notifyOrderApproaching($tailor, $stitchingOrder);
                    $notificationCount++;
                    break;
            }
        }

        return $notificationCount;
    }
}
