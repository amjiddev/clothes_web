<?php

namespace App\Http\Controllers\Tailor;

use App\Http\Controllers\Controller;
use App\Models\TailorNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display notifications page
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = TailorNotification::where('user_id', $user->id);

        // Filter by read/unread
        if ($request->has('filter') && $request->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($request->has('filter') && $request->filter === 'read') {
            $query->whereNotNull('read_at');
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->query());

        $unreadCount = TailorNotification::getUnreadCount($user->id);
        $types = [
            TailorNotification::TYPE_ORDER_ASSIGNED => 'Order Assigned',
            TailorNotification::TYPE_ORDER_APPROACHING => 'Order Approaching',
            TailorNotification::TYPE_STATUS_UPDATED => 'Status Updated',
            TailorNotification::TYPE_DESIGN_UPLOADED => 'Design Uploaded',
        ];

        return view('tailor.notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'types' => $types,
            'currentFilter' => $request->get('filter'),
            'currentType' => $request->get('type'),
        ]);
    }

    /**
     * Get unread notifications for dropdown
     */
    public function getUnread()
    {
        $user = Auth::user();
        
        $notifications = TailorNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'action_url' => $notification->action_url,
                    'icon' => $notification->getIcon(),
                    'color' => $notification->getColor(),
                    'created_at' => $notification->created_at->diffForHumans(),
                    'is_read' => $notification->isRead(),
                ];
            });

        $unreadCount = TailorNotification::getUnreadCount($user->id);

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = TailorNotification::where('id', $id)
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $notification->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Mark multiple notifications as read
     */
    public function markMultipleAsRead(Request $request)
    {
        $user = Auth::user();
        $ids = $request->get('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No notifications selected']);
        }

        TailorNotification::whereIn('id', $ids)
            ->where('user_id', $user->id)
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        TailorNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        $user = Auth::user();

        $notification = TailorNotification::where('id', $id)
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $notification->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification deleted');
    }

    /**
     * Clear all notifications
     */
    public function clearAll()
    {
        $user = Auth::user();

        TailorNotification::where('user_id', $user->id)->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications cleared');
    }

    /**
     * Get notification statistics
     */
    public function getStats()
    {
        $user = Auth::user();

        $stats = [
            'unread' => TailorNotification::getUnreadCount($user->id),
            'total' => TailorNotification::where('user_id', $user->id)->count(),
            'by_type' => TailorNotification::where('user_id', $user->id)
                ->groupBy('type')
                ->selectRaw('type, COUNT(*) as count')
                ->get()
                ->pluck('count', 'type')
                ->toArray(),
        ];

        return response()->json($stats);
    }

    /**
     * Create a notification (used internally)
     */
    public static function createNotification($userId, $type, $title, $message, $data = [], $actionUrl = null, $icon = null, $color = null)
    {
        return TailorNotification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'icon' => $icon,
            'color' => $color,
        ]);
    }

    /**
     * Notify when order is assigned
     */
    public static function notifyOrderAssigned($userId, $orderId, $orderNumber)
    {
        return static::createNotification(
            $userId,
            TailorNotification::TYPE_ORDER_ASSIGNED,
            'New Order Assigned',
            "Order #$orderNumber has been assigned to you",
            ['order_id' => $orderId],
            route('tailor.stitching-orders.show', $orderId),
            'fas fa-clipboard-check',
            '#3498db'
        );
    }

    /**
     * Notify when order delivery is approaching
     */
    public static function notifyOrderApproaching($userId, $orderId, $orderNumber, $daysLeft = 1)
    {
        return static::createNotification(
            $userId,
            TailorNotification::TYPE_ORDER_APPROACHING,
            'Order Delivery Approaching',
            "Order #$orderNumber delivery is in $daysLeft day(s)",
            ['order_id' => $orderId, 'days_left' => $daysLeft],
            route('tailor.stitching-orders.show', $orderId),
            'fas fa-hourglass-end',
            '#f39c12'
        );
    }

    /**
     * Notify when order status is updated
     */
    public static function notifyStatusUpdated($userId, $orderId, $orderNumber, $newStatus)
    {
        return static::createNotification(
            $userId,
            TailorNotification::TYPE_STATUS_UPDATED,
            'Order Status Updated',
            "Order #$orderNumber status changed to: " . ucfirst(str_replace('_', ' ', $newStatus)),
            ['order_id' => $orderId, 'status' => $newStatus],
            route('tailor.stitching-orders.show', $orderId),
            'fas fa-sync-alt',
            '#9b59b6'
        );
    }

    /**
     * Notify when customer uploads design
     */
    public static function notifyDesignUploaded($userId, $orderId, $orderNumber, $customerName)
    {
        return static::createNotification(
            $userId,
            TailorNotification::TYPE_DESIGN_UPLOADED,
            'Design Uploaded',
            "$customerName uploaded a design for Order #$orderNumber",
            ['order_id' => $orderId],
            route('tailor.designs.show', $orderId),
            'fas fa-image',
            '#27ae60'
        );
    }

    /**
     * Batch notify multiple tailors about order assignment
     */
    public static function notifyMultipleTailors($tailorIds, $orderId, $orderNumber)
    {
        foreach ($tailorIds as $tailorId) {
            static::notifyOrderAssigned($tailorId, $orderId, $orderNumber);
        }
    }

    /**
     * Notify about order ready for collection
     */
    public static function notifyOrderReady($userId, $orderId, $orderNumber)
    {
        return static::createNotification(
            $userId,
            'order_ready',
            'Order Ready for Collection',
            "Order #$orderNumber is ready for collection",
            ['order_id' => $orderId],
            route('tailor.stitching-orders.show', $orderId),
            'fas fa-check-circle',
            '#27ae60'
        );
    }
}
