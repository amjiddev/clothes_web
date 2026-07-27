@extends('receptionist.layouts.app')

@section('title', 'Completed Orders Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Completed Orders Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-check-circle me-2"></i>Completed Orders Report</h1>
                <p class="text-muted">View all completed orders with revenue and fulfillment details</p>
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
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate->toDateString() }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate->toDateString() }}">
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
                    <h6 class="text-muted mb-2">Total Completed</h6>
                    <h3 class="mb-0 text-success">{{ $summary['total_completed'] }}</h3>
                    <small class="text-muted">orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Revenue</h6>
                    <h3 class="mb-0 text-success">Rs. {{ number_format($summary['total_revenue'], 2) }}</h3>
                    <small class="text-muted">generated</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Avg Order Value</h6>
                    <h3 class="mb-0 text-info">Rs. {{ number_format($summary['average_order_value'], 2) }}</h3>
                    <small class="text-muted">per order</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Stitching Charges</h6>
                    <h3 class="mb-0 text-warning">Rs. {{ number_format($summary['total_stitching_charges'], 2) }}</h3>
                    <small class="text-muted">charged</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Completion Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="completionChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Payment Status</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check text-success me-2"></i>Paid Orders</span>
                            <span class="badge bg-success">{{ $summary['paid_count'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-hourglass text-warning me-2"></i>Pending Payment</span>
                            <span class="badge bg-warning">{{ $summary['pending_payment_count'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Completed Orders Details</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Subtotal</th>
                        <th>Stitching</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Completed Date</th>
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
                        <td>Rs. {{ number_format($order->subtotal, 2) }}</td>
                        <td>Rs. {{ number_format($order->stitching_charge, 2) }}</td>
                        <td><strong>Rs. {{ number_format($order->total, 2) }}</strong></td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td>{{ $order->updated_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('receptionist.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No completed orders found</td>
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
    // Completion Chart
    const ctx = document.getElementById('completionChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels'] ?? []),
            datasets: [
                {
                    label: 'Revenue (Rs.)',
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
                        text: 'Revenue (Rs.)',
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
