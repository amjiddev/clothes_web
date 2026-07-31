@extends('receptionist.layouts.app')

@section('title', 'Order Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Order Report</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Order Report</h1>
        <p class="page-subtitle">Detailed analysis of all orders</p>
    </div>
    <form method="POST" action="{{ route('receptionist.reports.export') }}" class="d-inline">
        @csrf
        <input type="hidden" name="type" value="orders">
        <button type="submit" name="format" value="csv" class="btn btn-custom btn-primary-custom">
            <i class="fas fa-download"></i> Export as CSV
        </button>
    </form>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="ready_for_delivery" {{ request('status') === 'ready_for_delivery' ? 'selected' : '' }}>Ready for Delivery</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Order Type</label>
                        <select name="order_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="regular" {{ request('order_type') === 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="custom" {{ request('order_type') === 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-custom btn-primary-custom w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Summary Stats -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <i class="fas fa-hashtag stat-icon"></i>
            <div class="stat-value">{{ $totalOrders }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <i class="fas fa-rupiah-sign stat-icon"></i>
            <div class="stat-value">Rs. {{ number_format($totalRevenue, 0) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <i class="fas fa-chart-line stat-icon"></i>
            <div class="stat-value">Rs. {{ number_format($averageOrderValue, 0) }}</div>
            <div class="stat-label">Average Order Value</div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="table-card">
    <div class="table-header">
        <h5 class="table-title">Orders</h5>
        <span class="badge bg-secondary">{{ $orders->count() }} Records</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('receptionist.orders.show', $order->id) }}" style="color: var(--accent-color); text-decoration: none;">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td>{{ $order->customer?->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($order->order_type ?? 'Regular') }}</td>
                        <td>Rs. {{ number_format($order->total, 2) }}</td>
                        <td>
                            @php
                                $statusClass = match($order->status) {
                                    'pending' => 'status-pending',
                                    'completed' => 'status-completed',
                                    'ready_for_delivery' => 'status-ready',
                                    'cancelled' => 'status-danger',
                                    default => 'status-pending',
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('receptionist.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
