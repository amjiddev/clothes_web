@extends('receptionist.layouts.app')

@section('title', 'Tailor Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.tailors.index') }}">Tailors</a></li>
    <li class="breadcrumb-item active">{{ $tailor->user->name }} Dashboard</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-chart-line me-2"></i>{{ $tailor->user->name }} - Dashboard
            </h1>
            <p class="text-muted">View tailor's workload and order breakdown</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.tailors.show', $tailor) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<!-- Workload Overview -->
<div class="row mb-4">
    <div class="col-lg-3 mb-3">
        <div class="card border-0 shadow h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-1">Assigned Orders</p>
                        <h3 class="mb-0 text-info fw-bold">{{ $workloadBreakdown['assigned'] }}</h3>
                    </div>
                    <div class="text-info" style="font-size: 2.5rem;">
                        <i class="fas fa-inbox"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="card border-0 shadow h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-1">In Progress</p>
                        <h3 class="mb-0 text-warning fw-bold">{{ $workloadBreakdown['started'] + $workloadBreakdown['in_progress'] }}</h3>
                    </div>
                    <div class="text-warning" style="font-size: 2.5rem;">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="card border-0 shadow h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-1">Accepted Orders</p>
                        <h3 class="mb-0 text-primary fw-bold">{{ $workloadBreakdown['accepted'] }}</h3>
                    </div>
                    <div class="text-primary" style="font-size: 2.5rem;">
                        <i class="fas fa-handshake"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="card border-0 shadow h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-1">Completed</p>
                        <h3 class="mb-0 text-success fw-bold">{{ $workloadBreakdown['completed'] }}</h3>
                    </div>
                    <div class="text-success" style="font-size: 2.5rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Workload Breakdown Chart -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-chart-pie me-2"></i>Workload Breakdown
                </h6>
            </div>
            <div class="card-body">
                <canvas id="workloadChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-table me-2"></i>Status Summary
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td>
                                <i class="fas fa-inbox text-info me-2"></i>
                                <strong>Assigned</strong>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-info">{{ $workloadBreakdown['assigned'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-handshake text-primary me-2"></i>
                                <strong>Accepted</strong>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-primary">{{ $workloadBreakdown['accepted'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-stitcher text-warning me-2"></i>
                                <strong>Started</strong>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-warning">{{ $workloadBreakdown['started'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-spinner text-secondary me-2"></i>
                                <strong>In Progress</strong>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-secondary">{{ $workloadBreakdown['in_progress'] }}</span>
                            </td>
                        </tr>
                        <tr class="table-success">
                            <td>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Completed</strong>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-success">{{ $workloadBreakdown['completed'] }}</span>
                            </td>
                        </tr>
                        <tr class="fw-bold">
                            <td>
                                <i class="fas fa-briefcase me-2"></i>
                                Total Orders
                            </td>
                            <td class="text-end">
                                {{ array_sum($workloadBreakdown) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-light border-bottom">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2"></i>Recent Orders
                        </h6>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('receptionist.tailors.tailor-orders', $tailor) }}" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>
            </div>

            @if($recentOrders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Order ID</th>
                            <th>Customer</th>
                            <th>Garment</th>
                            <th>Assigned Date</th>
                            <th>Status</th>
                            <th>Cost</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td class="ps-4">
                                <strong>#{{ $order->order->order_number }}</strong>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $order->order->user->name }}</div>
                                <small class="text-muted">{{ $order->order->user->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $order->garment_type ?? 'Custom' }}
                                </span>
                            </td>
                            <td>
                                @if($order->assigned_date)
                                {{ $order->assigned_date->format('M d, Y') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status_badge }}">
                                    <i class="fas fa-circle-notch me-1"></i>{{ $order->status_text }}
                                </span>
                            </td>
                            <td>
                                <strong>PKR {{ number_format($order->estimated_cost, 2) }}</strong>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('receptionist.orders.show', $order->order) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center py-4">
                <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                <p class="text-muted">No recent orders</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Workload Chart
    const ctx = document.getElementById('workloadChart').getContext('2d');
    const workloadChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                'Assigned',
                'Accepted',
                'Started',
                'In Progress',
                'Completed'
            ],
            datasets: [{
                data: [
                    {{ $workloadBreakdown['assigned'] }},
                    {{ $workloadBreakdown['accepted'] }},
                    {{ $workloadBreakdown['started'] }},
                    {{ $workloadBreakdown['in_progress'] }},
                    {{ $workloadBreakdown['completed'] }}
                ],
                backgroundColor: [
                    '#17a2b8',
                    '#007bff',
                    '#ffc107',
                    '#6c757d',
                    '#28a745'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
});
</script>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

.card {
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endsection
