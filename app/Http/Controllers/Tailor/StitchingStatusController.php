<?php

namespace App\Http\Controllers\Tailor;

use App\Http\Controllers\Controller;
use App\Models\StitchingOrder;
use App\Models\StitchingStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StitchingStatusController extends Controller
{
    /**
     * Get available status transitions for current user
     */
    public function getAvailableTransitions($id)
    {
        $user = Auth::user();
        $order = StitchingOrder::where('tailor_id', $user->id)->findOrFail($id);

        $transitions = $this->getAllowedTransitions($order->stitching_status);

        return response()->json([
            'current_status' => $order->stitching_status,
            'available_transitions' => $transitions,
        ]);
    }

    /**
     * Update stitching order status with history tracking
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $order = StitchingOrder::where('tailor_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'new_status' => 'required|in:pending,stitching_started,cutting_completed,stitching_in_progress,quality_checking,completed,delivered',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if transition is allowed
        $allowedTransitions = $this->getAllowedTransitions($order->stitching_status);
        if (!in_array($validated['new_status'], $allowedTransitions)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status transition from ' . $order->stitching_status . ' to ' . $validated['new_status'],
            ], 422);
        }

        $oldStatus = $order->stitching_status;
        $order->stitching_status = $validated['new_status'];

        // Set appropriate date fields based on status
        $this->setStatusDates($order, $validated['new_status']);

        $order->save();

        // Record status history
        StitchingStatusHistory::create([
            'stitching_order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $validated['new_status'],
            'changed_by_tailor_id' => $user->id,
            'changed_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully to ' . ucfirst(str_replace('_', ' ', $validated['new_status'])),
            'order' => [
                'id' => $order->id,
                'stitching_status' => $order->stitching_status,
            ],
        ]);
    }

    /**
     * Get status history timeline for an order
     */
    public function getStatusTimeline($id)
    {
        $user = Auth::user();
        $order = StitchingOrder::where('tailor_id', $user->id)
            ->with(['statusHistory.tailor'])
            ->findOrFail($id);

        $timeline = [];

        // Add initial creation event
        $timeline[] = [
            'id' => 'created',
            'date' => $order->created_at,
            'status' => 'created',
            'status_text' => 'Order Created',
            'tailor_name' => 'System',
            'icon' => 'fa-plus-circle',
            'color' => '#6c757d',
            'notes' => 'Stitching order created',
        ];

        // Add status history entries
        foreach ($order->statusHistory as $history) {
            $timeline[] = [
                'id' => $history->id,
                'date' => $history->changed_at,
                'status' => $history->new_status,
                'status_text' => $history->status_text,
                'old_status' => $history->old_status,
                'tailor_name' => $history->tailor?->name ?? 'Unknown',
                'icon' => $history->status_icon,
                'color' => $history->status_color,
                'notes' => $history->notes,
            ];
        }

        return response()->json([
            'timeline' => $timeline,
            'current_status' => $order->stitching_status,
        ]);
    }

    /**
     * Get allowed transitions based on current status
     */
    private function getAllowedTransitions($currentStatus)
    {
        $transitions = [
            'pending' => ['stitching_started'],
            'stitching_started' => ['cutting_completed'],
            'cutting_completed' => ['stitching_in_progress'],
            'stitching_in_progress' => ['quality_checking'],
            'quality_checking' => ['completed'],
            'completed' => ['delivered'],
            'delivered' => [],  // Final status, no transitions
        ];

        return $transitions[$currentStatus] ?? [];
    }

    /**
     * Set appropriate date fields based on new status
     */
    private function setStatusDates(StitchingOrder $order, $newStatus)
    {
        switch ($newStatus) {
            case 'stitching_started':
                if (!$order->start_date) {
                    $order->start_date = now();
                }
                break;
            case 'quality_checking':
                // Mark the intermediate step
                break;
            case 'completed':
                if (!$order->completion_date) {
                    $order->completion_date = now();
                }
                break;
        }
    }

    /**
     * Display status management index page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = StitchingOrder::where('tailor_id', $user->id)
            ->with(['order.customer', 'statusHistory']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('stitching_status', $request->status);
        }

        // Search by order number or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('order', function ($orderQuery) use ($search) {
                    $orderQuery->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', "%{$search}%");
                        });
                });
            });
        }

        // Sort by latest first
        $orders = $query->orderBy('updated_at', 'desc')->paginate(15)->appends($request->query());

        // Get status statistics
        $stats = [
            'pending' => (clone $query)->where('stitching_status', 'pending')->count(),
            'stitching_started' => (clone $query)->where('stitching_status', 'stitching_started')->count(),
            'cutting_completed' => (clone $query)->where('stitching_status', 'cutting_completed')->count(),
            'stitching_in_progress' => (clone $query)->where('stitching_status', 'stitching_in_progress')->count(),
            'quality_checking' => (clone $query)->where('stitching_status', 'quality_checking')->count(),
            'completed' => (clone $query)->where('stitching_status', 'completed')->count(),
            'delivered' => (clone $query)->where('stitching_status', 'delivered')->count(),
        ];

        $statusLabels = [
            'pending' => 'Pending',
            'stitching_started' => 'Stitching Started',
            'cutting_completed' => 'Cutting Completed',
            'stitching_in_progress' => 'Stitching In Progress',
            'quality_checking' => 'Quality Checking',
            'completed' => 'Completed',
            'delivered' => 'Delivered',
        ];

        return view('tailor.status.index', [
            'orders' => $orders,
            'stats' => $stats,
            'statusLabels' => $statusLabels,
            'currentStatus' => $request->get('status'),
            'searchQuery' => $request->get('search'),
        ]);
    }

    /**
     * Display status management index page
     */
    public function show($id)
    {
        $user = Auth::user();
        $order = StitchingOrder::where('tailor_id', $user->id)
            ->with(['statusHistory.tailor', 'order.customer', 'measurement'])
            ->findOrFail($id);

        $allowedTransitions = $this->getAllowedTransitions($order->stitching_status);

        $statusLabels = [
            'pending' => 'Pending',
            'stitching_started' => 'Stitching Started',
            'cutting_completed' => 'Cutting Completed',
            'stitching_in_progress' => 'Stitching In Progress',
            'quality_checking' => 'Quality Checking',
            'completed' => 'Completed',
            'delivered' => 'Delivered',
        ];

        return view('tailor.status.show', [
            'order' => $order,
            'allowedTransitions' => $allowedTransitions,
            'statusLabels' => $statusLabels,
            'timeline' => $order->statusHistory,
        ]);
    }
}
