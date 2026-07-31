<?php

namespace App\Http\Controllers\Tailor;

use App\Http\Controllers\Controller;
use App\Models\StitchingOrder;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StitchingOrderController extends Controller
{
    /**
     * Display a listing of assigned stitching orders
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = StitchingOrder::where('tailor_id', $user->id)
            ->with(['order.customer', 'order.orderItems', 'measurement']);

        // Filter by status groups
        $statusFilter = $request->get('status_filter');
        if ($statusFilter) {
            switch ($statusFilter) {
                case 'pending':
                    $query->where('stitching_status', 'pending');
                    break;
                case 'stitching_started':
                    $query->whereIn('stitching_status', ['assigned', 'in_progress']);
                    break;
                case 'in_progress':
                    $query->whereIn('stitching_status', ['ready_for_fitting', 'in_fitting']);
                    break;
                case 'completed':
                    $query->where('stitching_status', 'completed');
                    break;
            }
        }

        // Search by order ID, customer name, or product name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('order', function ($orderQuery) use ($search) {
                    $orderQuery->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', "%{$search}%")
                                        ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('orderItems', function ($itemQuery) use ($search) {
                            $itemQuery->where('product_name', 'like', "%{$search}%");
                        });
                })
                ->orWhere('garment_type', 'like', "%{$search}%");
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'assigned_date');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'completion_date') {
            $query->orderBy('completion_date', $sortOrder);
        } else if ($sortBy === 'customer_name') {
            $query->orderBy('tailor_id', $sortOrder) // Placeholder for sorting
                  ->orderBy('assigned_date', $sortOrder);
        } else if ($sortBy === 'due_date') {
            $query->orderBy('completion_date', $sortOrder);
        } else {
            $query->orderBy($sortBy ?? 'assigned_date', $sortOrder);
        }

        $orders = $query->paginate(15)->appends($request->query());

        // Get statistics
        $stats = $this->getOrderStatistics($user->id);

        $statusFilters = [
            'pending' => 'Pending',
            'stitching_started' => 'Stitching Started',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
        ];

        return view('tailor.stitching-orders.index', [
            'orders' => $orders,
            'statusFilters' => $statusFilters,
            'currentStatusFilter' => $statusFilter,
            'searchQuery' => $request->get('search'),
            'stats' => $stats,
        ]);
    }

    /**
     * Get order statistics for dashboard
     */
    private function getOrderStatistics($tailorId)
    {
        $query = StitchingOrder::where('tailor_id', $tailorId);

        return [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('stitching_status', 'pending')->count(),
            'stitching_started' => (clone $query)->whereIn('stitching_status', ['assigned', 'in_progress'])->count(),
            'in_progress' => (clone $query)->whereIn('stitching_status', ['ready_for_fitting', 'in_fitting'])->count(),
            'completed' => (clone $query)->where('stitching_status', 'completed')->count(),
        ];
    }

    /**
     * Display a specific stitching order
     */
    public function show($id)
    {
        $user = Auth::user();
        $order = StitchingOrder::where('tailor_id', $user->id)
            ->with(['order.customer', 'order.orderItems', 'measurement'])
            ->findOrFail($id);

        // Get related order items for detailed product info
        $orderItems = $order->order->orderItems ?? collect();

        return view('tailor.stitching-orders.show', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);
    }

    /**
     * Update stitching order status
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $order = StitchingOrder::where('tailor_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'stitching_status' => 'required|in:pending,assigned,in_progress,ready_for_fitting,in_fitting,ready,completed,cancelled',
            'tailor_notes' => 'nullable|string|max:1000',
        ]);

        // Update status
        $order->stitching_status = $validated['stitching_status'];

        // Update notes if provided
        if ($request->has('tailor_notes')) {
            $order->tailor_notes = $validated['tailor_notes'];
        }

        // Set dates based on status
        if ($validated['stitching_status'] === 'in_progress' && !$order->start_date) {
            $order->start_date = now();
        } elseif ($validated['stitching_status'] === 'completed' && !$order->completion_date) {
            $order->completion_date = now();
        }

        $order->save();

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Add tailor notes to order
     */
    public function addNotes(Request $request, $id)
    {
        $user = Auth::user();
        $order = StitchingOrder::where('tailor_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'tailor_notes' => 'required|string|max:1000',
        ]);

        $order->tailor_notes = $validated['tailor_notes'];
        $order->save();

        return back()->with('success', 'Notes added successfully!');
    }
}
