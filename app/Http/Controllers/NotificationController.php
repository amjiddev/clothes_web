<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ContactSubmission;
use App\Models\CustomerMeasurement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    /**
     * Get all unseen notifications (orders, contact messages, measurements)
     * Used for the notification bell dropdown
     */
    public function getNotifications(Request $request)
    {
        $allNotifications = [];
        $errors = [];
        
        try {
            // Pre-generate all possible routes to avoid exceptions inside map() closures
            $adminOrderShowRoute = null;
            $adminContactShowRoute = null;
            $measurementRoute = null;
            
            try {
                $adminOrderShowRoute = route('admin.orders.show', ['id' => ':ID:']);
            } catch (\Exception $e) {
                $adminOrderShowRoute = '/admin/orders/:ID:';
            }
            
            try {
                $adminContactShowRoute = route('admin.contact-submissions.show', ['contact_submission' => ':ID:']);
            } catch (\Exception $e) {
                $adminContactShowRoute = '/admin/contact-submissions/:ID:';
            }
            
            try {
                $measurementRoute = route('receptionist.measurements.show', ['measurement' => ':ID:']);
            } catch (\Exception $e) {
                try {
                    $measurementRoute = route('admin.measurements.show', ['id' => ':ID:']);
                } catch (\Exception $e2) {
                    $measurementRoute = '/receptionist/measurements/:ID:';
                }
            }
            
            // Try to get orders - fetch all and filter in code if column doesn't exist
            try {
                // IMPORTANT: Apply unseen scope BEFORE limit, not after!
                // Try to use unseen scope, fall back to all if column doesn't exist
                try {
                    $unseenOrders = Order::with('user')->unseen()->latest('created_at')->limit(5)->get();
                } catch (\Exception $scopeError) {
                    \Log::warning('Order.unseen scope failed, fetching all orders: ' . $scopeError->getMessage());
                    $unseenOrders = Order::with('user')->latest('created_at')->limit(5)->get();
                }
                
                $unseenOrders = $unseenOrders->map(function ($order) use ($adminOrderShowRoute) {
                    return [
                        'id' => $order->id,
                        'type' => 'order',
                        'title' => 'New Order #' . $order->order_number,
                        'subtitle' => 'Customer: ' . ($order->user?->name ?? 'Unknown'),
                        'description' => 'Total: Rs. ' . number_format($order->total ?? 0, 2),
                        'timestamp' => $order->created_at?->diffForHumans() ?? 'unknown',
                        'created_at' => $order->created_at,
                        'url' => str_replace(':ID:', $order->id, $adminOrderShowRoute),
                        'icon' => 'fas fa-shopping-cart',
                        'color' => '#3498db',
                    ];
                });
                $allNotifications = array_merge($allNotifications, $unseenOrders->toArray());
            } catch (\Exception $e) {
                $errors[] = 'Orders error: ' . $e->getMessage();
                \Log::error('NotificationController - Orders query failed: ' . $e->getMessage());
            }

            // Try to get messages
            try {
                // IMPORTANT: Apply unread scope BEFORE limit, not after!
                try {
                    $messages = ContactSubmission::where('type', 'message')->unread()->latest('created_at')->limit(5)->get();
                } catch (\Exception $scopeError) {
                    \Log::warning('ContactSubmission.unread scope failed, fetching all messages: ' . $scopeError->getMessage());
                    $messages = ContactSubmission::where('type', 'message')->latest('created_at')->limit(5)->get();
                }
                
                $messages = $messages->map(function ($message) use ($adminContactShowRoute) {
                    $messageText = $message->message ?? 'No message content';
                    return [
                        'id' => $message->id,
                        'type' => 'contact_message',
                        'title' => 'New Message from ' . ($message->full_name ?? 'Unknown'),
                        'subtitle' => $message->email ?? 'No email',
                        'description' => substr($messageText, 0, 60) . (strlen($messageText) > 60 ? '...' : ''),
                        'timestamp' => $message->created_at?->diffForHumans() ?? 'unknown',
                        'created_at' => $message->created_at,
                        'url' => str_replace(':ID:', $message->id, $adminContactShowRoute),
                        'icon' => 'fas fa-envelope',
                        'color' => '#27ae60',
                    ];
                })->toArray();
                
                // IMPORTANT: Apply unread scope BEFORE limit, not after!
                try {
                    $tailoringRequests = ContactSubmission::where('type', 'tailoring_request')->unread()->latest('created_at')->limit(5)->get();
                } catch (\Exception $scopeError) {
                    \Log::warning('ContactSubmission tailoring unread scope failed: ' . $scopeError->getMessage());
                    $tailoringRequests = ContactSubmission::where('type', 'tailoring_request')->latest('created_at')->limit(5)->get();
                }
                
                $tailoringRequests = $tailoringRequests->map(function ($request) use ($adminContactShowRoute) {
                    $messageText = $request->message ?? 'Tailoring request';
                    return [
                        'id' => $request->id,
                        'type' => 'contact_message',
                        'title' => 'Tailoring Request from ' . ($request->full_name ?? 'Unknown'),
                        'subtitle' => $request->email ?? 'No email',
                        'description' => substr($messageText, 0, 60) . (strlen($messageText) > 60 ? '...' : ''),
                        'timestamp' => $request->created_at?->diffForHumans() ?? 'unknown',
                        'created_at' => $request->created_at,
                        'url' => str_replace(':ID:', $request->id, $adminContactShowRoute),
                        'icon' => 'fas fa-scissors',
                        'color' => '#9b59b6',
                    ];
                })->toArray();
                
                // Merge both arrays
                $allNotifications = array_merge($allNotifications, $messages, $tailoringRequests);
            } catch (\Exception $e) {
                $errors[] = 'Messages error: ' . $e->getMessage();
                \Log::error('NotificationController - Messages query failed: ' . $e->getMessage());
            }

            // Try to get measurements
            try {
                // IMPORTANT: Apply unseen scope BEFORE limit, not after!
                try {
                    $unseenMeasurements = CustomerMeasurement::with('user')->unseen()->latest('created_at')->limit(5)->get();
                } catch (\Exception $scopeError) {
                    \Log::warning('CustomerMeasurement.unseen scope failed, fetching all measurements: ' . $scopeError->getMessage());
                    $unseenMeasurements = CustomerMeasurement::with('user')->latest('created_at')->limit(5)->get();
                }
                
                $unseenMeasurements = $unseenMeasurements->map(function ($measurement) use ($measurementRoute) {
                    return [
                        'id' => $measurement->id,
                        'type' => 'measurement',
                        'title' => 'New Measurement from ' . ($measurement->user?->name ?? 'Unknown'),
                        'subtitle' => 'Profile: ' . ($measurement->profile_name ?? 'Unnamed'),
                        'description' => 'Measurement #' . $measurement->id,
                        'timestamp' => $measurement->created_at?->diffForHumans() ?? 'unknown',
                        'created_at' => $measurement->created_at,
                        'url' => str_replace(':ID:', $measurement->id, $measurementRoute),
                        'icon' => 'fas fa-ruler',
                        'color' => '#e74c3c',
                    ];
                })->toArray();
                $allNotifications = array_merge($allNotifications, $unseenMeasurements);
            } catch (\Exception $e) {
                $errors[] = 'Measurements error: ' . $e->getMessage();
                \Log::error('NotificationController - Measurements query failed: ' . $e->getMessage());
            }

            // Get counts with fallback
            $ordersCount = 0;
            $messagesCount = 0;
            $tailoringCount = 0;
            $measurementsCount = 0;
            
            try {
                $ordersCount = Order::unseen()->count() ?? 0;
            } catch (\Exception $e) {
                // If unseen scope fails, just count all orders
                try {
                    $ordersCount = Order::count() ?? 0;
                } catch (\Exception $e2) {
                    $errors[] = 'Orders count error: ' . $e->getMessage();
                }
            }
            
            try {
                $messagesCount = ContactSubmission::unread()->where('type', 'message')->count() ?? 0;
            } catch (\Exception $e) {
                try {
                    $messagesCount = ContactSubmission::where('type', 'message')->count() ?? 0;
                } catch (\Exception $e2) {
                    $errors[] = 'Messages count error: ' . $e->getMessage();
                }
            }
            
            try {
                $tailoringCount = ContactSubmission::unread()->where('type', 'tailoring_request')->count() ?? 0;
            } catch (\Exception $e) {
                try {
                    $tailoringCount = ContactSubmission::where('type', 'tailoring_request')->count() ?? 0;
                } catch (\Exception $e2) {
                    $errors[] = 'Tailoring count error: ' . $e->getMessage();
                }
            }
            
            try {
                $measurementsCount = CustomerMeasurement::unseen()->count() ?? 0;
            } catch (\Exception $e) {
                // If unseen scope fails, just count all measurements
                try {
                    $measurementsCount = CustomerMeasurement::count() ?? 0;
                } catch (\Exception $e2) {
                    $errors[] = 'Measurements count error: ' . $e->getMessage();
                }
            }

            $totalCount = $ordersCount + $messagesCount + $tailoringCount + $measurementsCount;

            // Sort all notifications by created_at timestamp (latest first)
            usort($allNotifications, function ($a, $b) {
                // Extract created_at from timestamp string if available
                // Otherwise use current time as fallback
                $aTime = $a['created_at'] ?? now();
                $bTime = $b['created_at'] ?? now();
                
                // Convert to timestamps for comparison
                $aTimestamp = strtotime($aTime);
                $bTimestamp = strtotime($bTime);
                
                // Return comparison (latest first - descending order)
                return $bTimestamp <=> $aTimestamp;
            });

            return response()->json([
                'success' => true,
                'notifications' => $allNotifications,
                'unread_count' => $totalCount,
                'orders_count' => $ordersCount,
                'messages_count' => $messagesCount,
                'tailoring_count' => $tailoringCount,
                'measurements_count' => $measurementsCount,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            \Log::error('NotificationController::getNotifications Fatal Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Fatal error: ' . $e->getMessage(),
                'errors' => $errors,
            ], 500);
        }
    }

    /**
     * Get unread count for bell badge
     */
    public function getUnreadCount(Request $request)
    {
        try {
            $ordersCount = 0;
            $messagesCount = 0;
            $measurementsCount = 0;

            try {
                $ordersCount = Order::unseen()->count() ?? 0;
            } catch (\Exception $e) {
                try {
                    $ordersCount = Order::count() ?? 0;
                } catch (\Exception $e2) {
                    \Log::warning('Orders count failed: ' . $e->getMessage());
                }
            }

            try {
                $messagesCount = ContactSubmission::unread()->count() ?? 0;
            } catch (\Exception $e) {
                try {
                    $messagesCount = ContactSubmission::count() ?? 0;
                } catch (\Exception $e2) {
                    \Log::warning('Messages count failed: ' . $e->getMessage());
                }
            }

            try {
                $measurementsCount = CustomerMeasurement::unseen()->count() ?? 0;
            } catch (\Exception $e) {
                try {
                    $measurementsCount = CustomerMeasurement::count() ?? 0;
                } catch (\Exception $e2) {
                    \Log::warning('Measurements count failed: ' . $e->getMessage());
                }
            }

            $totalCount = $ordersCount + $messagesCount + $measurementsCount;

            return response()->json([
                'success' => true,
                'total' => $totalCount,
                'orders' => $ordersCount,
                'messages' => $messagesCount,
                'measurements' => $measurementsCount,
            ]);
        } catch (\Exception $e) {
            \Log::error('NotificationController::getUnreadCount Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark a specific notification as seen
     */
    public function markAsSeen(Request $request)
    {
        $request->validate([
            'type' => 'required|in:order,contact_message,measurement',
            'id' => 'required|integer',
        ]);

        try {
            $type = $request->input('type');
            $id = $request->input('id');

            if ($type === 'order') {
                $order = Order::findOrFail($id);
                $order->markAsSeen();
            } elseif ($type === 'contact_message') {
                $message = ContactSubmission::findOrFail($id);
                $message->markAsRead();
            } elseif ($type === 'measurement') {
                $measurement = CustomerMeasurement::findOrFail($id);
                $measurement->markAsSeen();
            }

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as seen',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as seen: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Mark all notifications as seen
     */
    public function markAllAsSeen(Request $request)
    {
        try {
            // Mark all unseen orders as seen
            Order::unseen()->update([
                'is_seen' => true,
                'seen_at' => now(),
            ]);

            // Mark all unread contact messages as seen
            ContactSubmission::unread()->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            // Mark all unseen measurements as seen
            CustomerMeasurement::unseen()->update([
                'is_seen' => true,
                'seen_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as seen',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as seen: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get notification details for a specific notification
     */
    public function getNotificationDetails(Request $request)
    {
        $request->validate([
            'type' => 'required|in:order,contact_message,measurement',
            'id' => 'required|integer',
        ]);

        $type = $request->input('type');
        $id = $request->input('id');

        try {
            if ($type === 'order') {
                $data = Order::with('user', 'orderItems.product')->findOrFail($id);
            } elseif ($type === 'contact_message') {
                $data = ContactSubmission::findOrFail($id);
            } elseif ($type === 'measurement') {
                $data = CustomerMeasurement::with('user')->findOrFail($id);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }
    }
}
