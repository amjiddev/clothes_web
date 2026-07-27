@extends('receptionist.layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Welcome, {{ Auth::user()->name }}!</h1>
    <p class="page-subtitle">Manage customers, orders, and stitching tasks efficiently</p>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="{{ route('receptionist.orders.create') }}" class="quick-action-btn">
        <i class="fas fa-plus"></i>
        <span>Create New Order</span>
    </a>
    <a href="{{ route('receptionist.customers.create') }}" class="quick-action-btn">
        <i class="fas fa-user-plus"></i>
        <span>Add Customer</span>
    </a>
    <a href="{{ route('receptionist.measurements.create') }}" class="quick-action-btn">
        <i class="fas fa-plus"></i>
        <span>Add Measurement</span>
    </a>
    <a href="{{ route('receptionist.stitching-orders.index') }}" class="quick-action-btn">
        <i class="fas fa-hand-holding-heart"></i>
        <span>Assign Tailor</span>
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-30">
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="stat-card">
            <i class="fas fa-users stat-icon"></i>
            <div class="stat-value">{{ $totalCustomers }}</div>
            <div class="stat-label">Total Customers</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> Active Customers
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-3">
        <div class="stat-card">
            <i class="fas fa-shopping-bag stat-icon"></i>
            <div class="stat-value">{{ $todayOrders }}</div>
            <div class="stat-label">Today's Orders</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> New Orders
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-3">
        <div class="stat-card">
            <i class="fas fa-hourglass-half stat-icon"></i>
            <div class="stat-value">{{ $pendingStitchingOrders }}</div>
            <div class="stat-label">Pending Stitching Orders</div>
            <div class="stat-change negative">
                <i class="fas fa-exclamation"></i> Attention Needed
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-3">
        <div class="stat-card">
            <i class="fas fa-check-circle stat-icon"></i>
            <div class="stat-value">{{ $completedOrders }}</div>
            <div class="stat-label">Completed Orders</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> Today
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-3">
        <div class="stat-card">
            <i class="fas fa-credit-card stat-icon"></i>
            <div class="stat-value">Rs. {{ $pendingPayments }}</div>
            <div class="stat-label">Pending Payments</div>
            <div class="stat-change negative">
                <i class="fas fa-exclamation"></i> Amount Due
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-3">
        <div class="stat-card">
            <i class="fas fa-box stat-icon"></i>
            <div class="stat-value">{{ $readyForDelivery }}</div>
            <div class="stat-label">Ready For Delivery</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> Ready to Ship
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="row mb-30">
    <div class="col-12">
        <div class="table-card">
            <div class="table-header">
                <h5 class="table-title">Recent Orders</h5>
                <a href="{{ route('receptionist.orders.index') }}" class="btn btn-custom btn-primary-custom btn-sm">
                    View All <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Order Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('receptionist.orders.show', $order['id']) }}" style="color: var(--accent-color); text-decoration: none;">
                                        #{{ $order['id'] }}
                                    </a>
                                </td>
                                <td>{{ $order['customer_name'] }}</td>
                                <td>{{ ucfirst($order['order_type']) }}</td>
                                <td>Rs. {{ number_format($order['amount'], 2) }}</td>
                                <td>
                                    @php
                                        $statusClass = match($order['status']) {
                                            'pending' => 'status-pending',
                                            'completed' => 'status-completed',
                                            'assigned' => 'status-assigned',
                                            'in_progress' => 'status-in-progress',
                                            'ready_for_delivery' => 'status-ready',
                                            default => 'status-pending',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order['status'])) }}</span>
                                </td>
                                <td>{{ $order['date'] }}</td>
                                <td>
                                    <a href="{{ route('receptionist.orders.show', $order['id']) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <i class="fas fa-inbox"></i> No recent orders
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Stitching Orders -->
<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="table-header">
                <h5 class="table-title">Recent Stitching Orders</h5>
                <a href="{{ route('receptionist.stitching-orders.index') }}" class="btn btn-custom btn-primary-custom btn-sm">
                    View All <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Garment Type</th>
                            <th>Tailor</th>
                            <th>Delivery Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentStitchingOrders as $stitchingOrder)
                            <tr>
                                <td>{{ $stitchingOrder['customer_name'] }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $stitchingOrder['garment_type'])) }}</td>
                                <td>
                                    @if($stitchingOrder['tailor'] !== 'Unassigned')
                                        <span class="badge bg-success">{{ $stitchingOrder['tailor'] }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ $stitchingOrder['tailor'] }}</span>
                                    @endif
                                </td>
                                <td>{{ $stitchingOrder['delivery_date'] }}</td>
                                <td>
                                    @php
                                        $statusClass = match($stitchingOrder['status']) {
                                            'pending' => 'status-pending',
                                            'assigned' => 'status-assigned',
                                            'in_progress' => 'status-in-progress',
                                            'completed' => 'status-completed',
                                            default => 'status-pending',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $stitchingOrder['status'])) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('receptionist.stitching-orders.show', $stitchingOrder['id']) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-inbox"></i> No recent stitching orders
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-css')
<style>
    .mb-30 {
        margin-bottom: 30px;
    }

    .stat-card {
        animation: fadeInUp 0.5s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('extra-js')
<script>
    // Animate stat cards on page load
    document.querySelectorAll('.stat-card').forEach((card, index) => {
        card.style.animation = `fadeInUp 0.5s ease-out ${index * 0.1}s both`;
    });

    // Table sorting and interactions
    document.querySelectorAll('.table-hover tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(212, 175, 55, 0.05)';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
</script>
@endsection
