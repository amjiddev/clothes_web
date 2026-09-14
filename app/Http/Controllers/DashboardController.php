<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StitchingOrder;
use App\Models\Product;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user is admin
        if (!$user->hasRole('super_admin')) {
            return redirect()->route('home');
        }

        // Get dashboard data
        $dashboardData = $this->getDashboardData();
        return view('admin.dashboard', $dashboardData);
    }

    private function getDashboardData()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Revenue Metrics
        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $thisMonthRevenue = Order::whereBetween('created_at', [
            $thisMonth,
            $thisMonth->copy()->endOfMonth()
        ])->where('status', '!=', 'cancelled')->sum('total');

        $lastMonthRevenue = Order::whereBetween('created_at', [
            $lastMonth,
            $lastMonth->copy()->endOfMonth()
        ])->where('status', '!=', 'cancelled')->sum('total');

        $revenueGrowth = $lastMonthRevenue > 0
            ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        // Order Metrics
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();

        $ordersGrowth = Order::whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();
        $ordersLastMonth = Order::whereMonth('created_at', $today->copy()->subMonth()->month)
            ->whereYear('created_at', $today->year)
            ->count();

        $ordersPercentage = $ordersLastMonth > 0
            ? (($ordersGrowth - $ordersLastMonth) / $ordersLastMonth) * 100
            : 0;

        // Stitching Metrics
        $totalStitchingOrders = StitchingOrder::count();
        $stitchingInProgress = StitchingOrder::whereIn('stitching_status', ['assigned', 'in_progress'])->count();
        $stitchingCompleted = StitchingOrder::where('stitching_status', 'completed')->count();

        // Product Metrics
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count();
        $outOfStockProducts = Product::where('stock_quantity', 0)->count();

        // Customer Metrics
        $totalCustomers = User::whereDoesntHave('roles')
            ->orWhereHas('roles', function ($query) {
                $query->where('name', 'customer');
            })
            ->count();

        $newCustomersThisMonth = User::whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();

        $newCustomersLastMonth = User::whereMonth('created_at', $today->copy()->subMonth()->month)
            ->whereYear('created_at', $today->year)
            ->count();

        $customersGrowth = $newCustomersLastMonth > 0
            ? (($newCustomersThisMonth - $newCustomersLastMonth) / $newCustomersLastMonth) * 100
            : 0;

        // Staff Metrics
        $tailorsCount = User::role('tailor')->count();
        $receptionistsCount = User::role('receptionist')->count();

        // Payment Metrics
        $totalPayments = Payment::where('status', 'completed')->count();
        $successfulPayments = Payment::where('status', 'completed')->sum('amount');
        $failedPayments = Payment::where('status', 'failed')->count();

        // Coupon Metrics
        $activeCoupons = Coupon::where('is_active', true)->count();
        $expiredCoupons = Coupon::where('is_active', false)->count();

        // Recent Orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Recent Stitching Orders
        $recentStitchingOrders = StitchingOrder::with('order', 'tailor', 'measurement')
            ->latest()
            ->take(5)
            ->get();

        // Order Status Distribution
        $orderStatusDistribution = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Payment Method Distribution
        $paymentMethodDistribution = Payment::selectRaw('payment_method, COUNT(*) as count')
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method');

        // Top Products
        $topProducts = Product::selectRaw('products.*, COUNT(order_items.id) as order_count')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id')
            ->orderByDesc('order_count')
            ->take(5)
            ->get();

        // Revenue Chart Data (Last 7 Days)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)
                ->where('status', '!=', 'cancelled')
                ->sum('total');
            $chartData[] = [
                'date' => $date->format('M d'),
                'revenue' => $revenue,
            ];
        }

        return [
            // Metrics
            'todayRevenue' => $todayRevenue,
            'thisMonthRevenue' => $thisMonthRevenue,
            'revenueGrowth' => $revenueGrowth,
            'totalOrders' => $totalOrders,
            'todayOrders' => $todayOrders,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'ordersPercentage' => $ordersPercentage,
            'totalStitchingOrders' => $totalStitchingOrders,
            'stitchingInProgress' => $stitchingInProgress,
            'stitchingCompleted' => $stitchingCompleted,
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'totalCustomers' => $totalCustomers,
            'customersGrowth' => $customersGrowth,
            'tailorsCount' => $tailorsCount,
            'receptionistsCount' => $receptionistsCount,
            'totalPayments' => $totalPayments,
            'successfulPayments' => $successfulPayments,
            'failedPayments' => $failedPayments,
            'activeCoupons' => $activeCoupons,
            'expiredCoupons' => $expiredCoupons,

            // Data
            'recentOrders' => $recentOrders,
            'recentStitchingOrders' => $recentStitchingOrders,
            'orderStatusDistribution' => $orderStatusDistribution,
            'paymentMethodDistribution' => $paymentMethodDistribution,
            'topProducts' => $topProducts,
            'chartData' => json_encode($chartData),
        ];
    }
}
