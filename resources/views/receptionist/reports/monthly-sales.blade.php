@extends('receptionist.layouts.app')

@section('title', 'Monthly Sales Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Monthly Sales Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-chart-bar me-2"></i>Monthly Sales Report</h1>
                <p class="text-muted">{{ $startDate->format('M Y') }} - Sales trends and patterns</p>
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
                <div class="col-md-4">
                    <label class="form-label fw-bold">Month</label>
                    <input type="month" name="month" class="form-control" value="{{ $month }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Order Type</label>
                    <select name="order_type" class="form-select">
                        <option value="all">All Types</option>
                        <option value="ready_made" {{ $orderType === 'ready_made' ? 'selected' : '' }}>Cloth Only</option>
                        <option value="stitching" {{ $orderType === 'stitching' ? 'selected' : '' }}>Stitching Only</option>
                        <option value="combined" {{ $orderType === 'combined' ? 'selected' : '' }}>Cloth + Stitching</option>
                    </select>
                </div>
                <div class="col-md-4">
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
                    <h6 class="text-muted mb-2">Total Sales</h6>
                    <h3 class="mb-0 text-success">₹{{ number_format($summary['total_sales'], 2) }}</h3>
                    <small class="text-muted">revenue generated</small>
                </div>
            </div>
        </div>
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
                    <h6 class="text-muted mb-2">Avg Order Value</h6>
                    <h3 class="mb-0 text-info">₹{{ number_format($summary['average_order_value'], 2) }}</h3>
                    <small class="text-muted">per order</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Daily Average</h6>
                    <h3 class="mb-0 text-warning">₹{{ number_format($summary['daily_average'], 2) }}</h3>
                    <small class="text-muted">per day</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Daily Sales Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Status Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Completed</span>
                            <span class="badge bg-success">{{ $summary['completed_orders'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>In Progress</span>
                            <span class="badge bg-primary">{{ $summary['in_progress_orders'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Confirmed</span>
                            <span class="badge bg-info">{{ $summary['confirmed_orders'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Pending</span>
                            <span class="badge bg-warning">{{ $summary['pending_orders'] }}</span>
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
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
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
                        <td>
                            <a href="{{ route('receptionist.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No orders found for this month</td>
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
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels'] ?? []),
            datasets: [
                {
                    label: 'Sales (₹)',
                    data: @json($chartData['sales'] ?? []),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y',
                },
                {
                    label: 'Orders',
                    data: @json($chartData['orders'] ?? []),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Sales (₹)',
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Orders Count',
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                },
            }
        }
    });
</script>
@endsection
