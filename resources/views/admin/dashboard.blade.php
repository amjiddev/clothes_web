@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">Dashboard</h1>
                <p class="text-muted">Welcome back! Here's your business overview.</p>
            </div>
        </div>
    </div>

    <!-- Top Statistics -->
    <div class="row mb-4">
        <!-- Today's Revenue -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Today's Revenue</p>
                            <h3 class="mb-0">₹{{ number_format($todayRevenue, 2) }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i>
                                {{ number_format($revenueGrowth, 1) }}% from last month
                            </small>
                        </div>
                        <div class="text-primary opacity-50 ms-3">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month Revenue -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">This Month Revenue</p>
                            <h3 class="mb-0">₹{{ number_format($thisMonthRevenue, 2) }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i>
                                {{ number_format($thisMonthRevenue - ($thisMonthRevenue - $todayRevenue), 2) }} today
                            </small>
                        </div>
                        <div class="text-info opacity-50 ms-3">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Total Orders</p>
                            <h3 class="mb-0">{{ $totalOrders }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i>
                                {{ number_format($ordersPercentage, 1) }}% from last month
                            </small>
                        </div>
                        <div class="text-warning opacity-50 ms-3">
                            <i class="fas fa-shopping-bag fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Total Customers</p>
                            <h3 class="mb-0">{{ $totalCustomers }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i>
                                {{ number_format($customersGrowth, 1) }}% growth
                            </small>
                        </div>
                        <div class="text-success opacity-50 ms-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics -->
    <div class="row mb-4">
        <!-- Pending Orders -->
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Pending Orders</p>
                    <h4 class="mb-0">{{ $pendingOrders }}</h4>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-decoration-none small">View Details →</a>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Completed Orders</p>
                    <h4 class="mb-0">{{ $completedOrders }}</h4>
                    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="text-decoration-none small">View Details →</a>
                </div>
            </div>
        </div>

        <!-- Active Products -->
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Active Products</p>
                    <h4 class="mb-0">{{ $activeProducts }} / {{ $totalProducts }}</h4>
                    <a href="{{ route('admin.products.index') }}" class="text-decoration-none small">Manage →</a>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Out of Stock</p>
                    <h4 class="mb-0 text-danger">{{ $outOfStockProducts }}</h4>
                    <a href="{{ route('admin.inventory.index') }}" class="text-decoration-none small">Check Inventory →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Metrics -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Staff Overview</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tailors</span>
                        <strong>{{ $tailorsCount }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Receptionists</span>
                        <strong>{{ $receptionistsCount }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Payment Status</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Successful Payments</span>
                        <strong class="text-success">{{ $totalPayments }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Failed Payments</span>
                        <strong class="text-danger">{{ $failedPayments }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Recent Orders</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">#{{ $order->id }}</a></td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>₹{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No orders yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Stitching Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Recent Stitching Orders</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Tailor</th>
                                    <th>Status</th>
                                    <th>Delivery Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentStitchingOrders as $stitching)
                                <tr>
                                    <td><a href="{{ route('admin.stitching-orders.show', $stitching->id) }}" class="text-decoration-none">#{{ $stitching->id }}</a></td>
                                    <td>{{ $stitching->order?->user->name ?? 'N/A' }}</td>
                                    <td>{{ $stitching->tailor?->name ?? 'Unassigned' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $stitching->stitching_status === 'completed' ? 'success' : 'primary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $stitching->stitching_status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $stitching->completion_date?->format('M d, Y') ?? 'Not set' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No stitching orders yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-header {
        padding: 20px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .page-title {
        font-size: 28px;
        font-weight: 600;
        margin: 0;
    }

    .card {
        border-radius: 0.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
