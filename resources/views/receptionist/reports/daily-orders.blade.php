@extends('receptionist.layouts.app')

@section('title', 'Daily Orders Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Daily Orders Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-calendar-day me-2"></i>Daily Orders Report</h1>
                <p class="text-muted">Orders placed on {{ $date->format('M d, Y') }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('receptionist.reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $date->toDateString() }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Order Type</label>
                    <select name="order_type" class="form-select">
                        <option value="all">All Types</option>
                        <option value="ready_made" {{ $orderType === 'ready_made' ? 'selected' : '' }}>Cloth Only</option>
                        <option value="stitching" {{ $orderType === 'stitching' ? 'selected' : '' }}>Stitching Only</option>
                        <option value="combined" {{ $orderType === 'combined' ? 'selected' : '' }}>Cloth + Stitching</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="all">All Status</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="ready" {{ $status === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Orders</h6>
                    <h3 class="mb-0 text-primary">{{ $summary['total_orders'] }}</h3>
                    <small class="text-muted">orders placed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Revenue</h6>
                    <h3 class="mb-0 text-success">₹{{ number_format($summary['total_revenue'], 2) }}</h3>
                    <small class="text-muted">revenue generated</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Avg Order Value</h6>
                    <h3 class="mb-0 text-info">₹{{ number_format($summary['average_order_value'], 2) }}</h3>
                    <small class="text-muted">per order</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Pending Payments</h6>
                    <h3 class="mb-0 text-warning">₹{{ number_format($summary['pending_payment_amount'], 2) }}</h3>
                    <small class="text-muted">to collect</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Orders by Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Status Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Completed</span>
                            <span class="badge bg-success">{{ $summary['completed'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>In Progress</span>
                            <span class="badge bg-primary">{{ $summary['in_progress'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Confirmed</span>
                            <span class="badge bg-info">{{ $summary['confirmed'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Ready</span>
                            <span class="badge bg-secondary">{{ $summary['ready'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Pending</span>
                            <span class="badge bg-warning">{{ $summary['pending'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Cancelled</span>
                            <span class="badge bg-danger">{{ $summary['cancelled'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Order Details</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->user->name ?? 'Unknown' }}</td>
                        <td>
                            @if($order->type === 'ready_made')
                                <span class="badge bg-light text-dark">Cloth</span>
                            @elseif($order->type === 'stitching')
                                <span class="badge bg-light text-dark">Stitching</span>
                            @else
                                <span class="badge bg-light text-dark">Cloth + Stitching</span>
                            @endif
                        </td>
                        <td><strong>₹{{ number_format($order->total, 2) }}</strong></td>
                        <td>
                            <span class="badge bg-{{ $order->status_badge_attribute ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('H:i') }}</td>
                        <td>
                            <a href="{{ route('receptionist.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No orders found for this date</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Chart
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json($chartData['labels'] ?? []),
            datasets: [{
                data: @json($chartData['data'] ?? []),
                backgroundColor: @json($chartData['backgroundColor'] ?? ['#28a745', '#ffc107', '#17a2b8', '#007bff', '#6610f2', '#dc3545']),
                borderColor: '#fff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endsection
