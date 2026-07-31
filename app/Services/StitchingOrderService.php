<?php

namespace App\Services;

use App\Models\StitchingOrder;
use App\Models\User;
use App\Http\Controllers\Tailor\NotificationController;

class StitchingOrderService
{
    /**
     * Assign stitching order to tailor
     */
    public function assignToTailor(StitchingOrder $stitchingOrder, int $tailorId): StitchingOrder
    {
        $tailor = User::find($tailorId);
        
        if (!$tailor || !$tailor->hasRole('tailor')) {
            throw new \Exception('Invalid tailor selected.');
        }

        $stitchingOrder->update([
            'tailor_id' => $tailorId,
            'stitching_status' => 'assigned',
            'assigned_date' => now(),
        ]);

        // Send notification to tailor
        NotificationController::notifyOrderAssigned(
            $tailorId,
            $stitchingOrder->id,
            $stitchingOrder->order->order_number ?? "Order #" . $stitchingOrder->id
        );

        return $stitchingOrder;
    }

    /**
     * Update stitching status
     */
    public function updateStatus(StitchingOrder $stitchingOrder, string $status, array $data = []): StitchingOrder
    {
        $oldStatus = $stitchingOrder->stitching_status;
        $updateData = ['stitching_status' => $status];

        if (isset($data['tailor_notes'])) {
            $updateData['tailor_notes'] = $data['tailor_notes'];
        }

        if ($status === 'in_progress' && !$stitchingOrder->start_date) {
            $updateData['start_date'] = now();
        } elseif ($status === 'ready_for_fitting') {
            $updateData['fitting_date'] = $data['fitting_date'] ?? now();
        } elseif ($status === 'completed') {
            $updateData['completion_date'] = now();
        }

        $stitchingOrder->update($updateData);

        // Send notification to tailor about status update
        if ($stitchingOrder->tailor_id && $oldStatus !== $status) {
            NotificationController::notifyStatusUpdated(
                $stitchingOrder->tailor_id,
                $stitchingOrder->id,
                $stitchingOrder->order->order_number ?? "Order #" . $stitchingOrder->id,
                $status
            );
        }

        // Update related order status
        if ($status === 'completed') {
            $stitchingOrder->order->update(['status' => 'ready']);
        }

        return $stitchingOrder;
    }

    /**
     * Get tailor workload
     */
    public function getTailorWorkload(User $tailor): array
    {
        $stitchingOrders = StitchingOrder::where('tailor_id', $tailor->id)->get();

        return [
            'total_assigned' => $stitchingOrders->count(),
            'in_progress' => $stitchingOrders->where('stitching_status', 'in_progress')->count(),
            'ready_for_fitting' => $stitchingOrders->where('stitching_status', 'ready_for_fitting')->count(),
            'completed' => $stitchingOrders->where('stitching_status', 'completed')->count(),
            'pending' => $stitchingOrders->where('stitching_status', 'assigned')->count(),
        ];
    }

    /**
     * Get stitching order timeline
     */
    public function getOrderTimeline(StitchingOrder $stitchingOrder): array
    {
        return [
            'created' => $stitchingOrder->created_at,
            'assigned' => $stitchingOrder->assigned_date,
            'started' => $stitchingOrder->start_date,
            'fitting_scheduled' => $stitchingOrder->fitting_date,
            'completed' => $stitchingOrder->completion_date,
        ];
    }

    /**
     * Get stitching order summary
     */
    public function getOrderSummary(StitchingOrder $stitchingOrder): array
    {
        return [
            'order_number' => $stitchingOrder->order->order_number,
            'customer' => $stitchingOrder->order->user->name,
            'tailor' => $stitchingOrder->tailor?->name,
            'garment_type' => $stitchingOrder->garment_type,
            'status' => $stitchingOrder->stitching_status,
            'measurement' => $stitchingOrder->measurement?->title,
            'special_instructions' => $stitchingOrder->special_instructions,
            'tailor_notes' => $stitchingOrder->tailor_notes,
            'timeline' => $this->getOrderTimeline($stitchingOrder),
        ];
    }

    /**
     * Get pending stitching orders count
     */
    public function getPendingCount(): int
    {
        return StitchingOrder::where('stitching_status', 'pending')->count();
    }

    /**
     * Get unassigned stitching orders
     */
    public function getUnassignedOrders()
    {
        return StitchingOrder::where('tailor_id', null)
                            ->where('stitching_status', 'pending')
                            ->with('order.user')
                            ->get();
    }
}
