<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StitchingOrder;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the receptionist dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalCustomers = User::where(function($query) {
            $query->doesntHave('roles')
                  ->orWhereHas('roles', function($q) {
                      $q->where('name', 'customer');
                  });
        })->count();

        // Today's orders
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();

        // Ready for delivery orders
        $readyForDelivery = Order::where('status', 'ready_for_delivery')->count();

        // Get stitching orders statistics
        $totalStitchingOrders = StitchingOrder::count();
        $pendingStitchingOrders = StitchingOrder::where('stitching_status', 'pending')->count();
        $assignedStitchingOrders = StitchingOrder::where('stitching_status', 'assigned')->count();
        $inProgressStitchingOrders = StitchingOrder::where('stitching_status', 'in_progress')->count();

        // Get payment statistics (count for pending payments)
        $totalPayments = Payment::count();
        $paidPayments = Payment::where('status', 'completed')->count();
        $pendingPaymentsCount = Payment::where('status', 'pending')->count();
        $failedPayments = Payment::where('status', 'failed')->count();

        // Calculate total pending payment amount
        $pendingPaymentsAmount = Payment::where('status', 'pending')->sum('amount');

        // Get recent orders with user relationship
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->user->name ?? 'N/A',
                    'order_type' => $order->type ?? 'custom',
                    'amount' => $order->total ?? 0,
                    'status' => $order->status ?? 'pending',
                    'date' => $order->created_at?->format('M d, Y') ?? 'N/A',
                ];
            })->toArray();

        // Get recent stitching orders with relationships
        $recentStitchingOrders = StitchingOrder::with('order.user', 'tailor')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($stitchingOrder) {
                return [
                    'id' => $stitchingOrder->id,
                    'customer_name' => $stitchingOrder->order->user->name ?? 'N/A',
                    'garment_type' => $stitchingOrder->garment_type ?? 'unknown',
                    'tailor' => $stitchingOrder->tailor->name ?? 'Unassigned',
                    'delivery_date' => $stitchingOrder->delivery_date?->format('M d, Y') ?? 'N/A',
                    'status' => $stitchingOrder->stitching_status ?? 'pending',
                ];
            })->toArray();

        // Get pending payments data
        $pendingPaymentsData = Payment::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('receptionist.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalCustomers',
            'todayOrders',
            'readyForDelivery',
            'totalStitchingOrders',
            'pendingStitchingOrders',
            'assignedStitchingOrders',
            'inProgressStitchingOrders',
            'totalPayments',
            'paidPayments',
            'pendingPaymentsCount',
            'failedPayments',
            'recentOrders',
            'recentStitchingOrders',
            'pendingPaymentsData'
        ) + ['pendingPayments' => $pendingPaymentsAmount]);
    }
}
