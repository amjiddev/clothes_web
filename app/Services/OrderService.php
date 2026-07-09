<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StitchingOrder;
use App\Models\Product;

class OrderService
{
    /**
     * Create a new order with items
     */
    public function createOrder(array $data): Order
    {
        $order = new Order();
        $order->user_id = $data['user_id'];
        $order->type = $data['type'];
        $order->payment_method = $data['payment_method'];
        $order->notes = $data['notes'] ?? null;
        $order->save();

        $subtotal = 0;

        // Add order items for ready_made and combined orders
        if (in_array($data['type'], ['ready_made', 'combined'])) {
            foreach ($data['items'] ?? [] as $item) {
                $product = Product::find($item['product_id']);
                
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $product->price;
                $orderItem->save();

                $subtotal += $product->price * $item['quantity'];

                // Decrease stock
                $product->decrement('stock_quantity', $item['quantity']);
            }
        }

        // Calculate totals
        $order->subtotal = $subtotal;
        $order->stitching_charge = $data['stitching_charge'] ?? 0;
        $order->discount = $data['discount'] ?? 0;
        $order->tax = (($subtotal + ($data['stitching_charge'] ?? 0)) - ($data['discount'] ?? 0)) * 0.18; // 18% tax
        $order->total = $order->subtotal + $order->stitching_charge + $order->tax - $order->discount;
        $order->save();

        // Create stitching order if type is stitching or combined
        if (in_array($data['type'], ['stitching', 'combined'])) {
            StitchingOrder::create([
                'order_id' => $order->id,
                'measurement_id' => $data['measurement_id'] ?? null,
                'garment_type' => $data['garment_type'] ?? null,
                'fabric_details' => $data['fabric_details'] ?? null,
                'special_instructions' => $data['special_instructions'] ?? null,
            ]);
        }

        return $order;
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);
        
        // Sync with stitching order if applicable
        if ($order->stitchingOrder) {
            $this->syncStitchingOrderStatus($order);
        }

        return $order;
    }

    /**
     * Sync stitching order status with main order
     */
    private function syncStitchingOrderStatus(Order $order): void
    {
        $stitchingOrder = $order->stitchingOrder;

        if ($order->status === 'in_progress' && $stitchingOrder->stitching_status === 'assigned') {
            $stitchingOrder->update(['stitching_status' => 'in_progress']);
        }
    }

    /**
     * Calculate order totals
     */
    public function calculateTotals(Order $order): array
    {
        $subtotal = $order->orderItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        $tax = ($subtotal + $order->stitching_charge - $order->discount) * 0.18;
        $total = $subtotal + $order->stitching_charge + $tax - $order->discount;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ];
    }

    /**
     * Cancel order and restore stock
     */
    public function cancelOrder(Order $order): Order
    {
        // Restore stock
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock_quantity', $item->quantity);
        }

        $order->update(['status' => 'cancelled']);

        return $order;
    }

    /**
     * Get order summary
     */
    public function getOrderSummary(Order $order): array
    {
        return [
            'order_number' => $order->order_number,
            'customer' => $order->user->name,
            'type' => $order->type,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'items_count' => $order->orderItems->count(),
            'subtotal' => $order->subtotal,
            'tax' => $order->tax,
            'stitching_charge' => $order->stitching_charge,
            'discount' => $order->discount,
            'total' => $order->total,
            'created_at' => $order->created_at,
        ];
    }
}
