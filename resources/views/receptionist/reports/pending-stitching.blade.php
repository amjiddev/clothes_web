@extends('receptionist.layouts.app')

@section('title', 'Pending Stitching Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Pending Stitching Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-scissors me-2"></i>Pending Stitching Report</h1>
                <p class="text-muted">Monitor stitching orders by status and tailor assignments</p>
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
                <div class="col-md-2">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate->toDateString() }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate->toDateString() }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="all">All Status</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="assigned" {{ $status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="ready_for_fitting" {{ $status === 'ready_for_fitting' ? 'selected' : '' }}>Ready for Fitting</option>
                        <option value="in_fitting" {{ $status === 'in_fitting' ? 'selected' : '' }}>In Fitting</option>
                        <option value="ready" {{ $status === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
        <div class="col-md-2">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total</h6>
                    <h3 class="mb-0 text-primary">{{ $summary['total_stitching'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Pending</h6>
                    <h3 class="mb-0 text-warning">{{ $summary['pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Assigned</h6>
                    <h3 class="mb-0 text-info">{{ $summary['assigned'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">In Progress</h6>
                    <h3 class="mb-0 text-primary">{{ $summary['in_progress'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Ready</h6>
                    <h3 class="mb-0 text-success">{{ $summary['ready'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Completed</h6>
                    <h3 class="mb-0 text-success">{{ $summary['completed'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Status Summary</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-hourglass text-warning me-2"></i>Pending</span>
                            <span class="badge bg-warning">{{ $summary['pending'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check-circle text-info me-2"></i>Assigned</span>
                            <span class="badge bg-info">{{ $summary['assigned'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-spinner text-primary me-2"></i>In Progress</span>
                            <span class="badge bg-primary">{{ $summary['in_progress'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-ruler text-secondary me-2"></i>Ready for Fitting</span>
                            <span class="badge bg-secondary">{{ $summary['ready_for_fitting'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-tape text-danger me-2"></i>In Fitting</span>
                            <span class="badge bg-danger">{{ $summary['in_fitting'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check text-success me-2"></i>Ready</span>
                            <span class="badge bg-success">{{ $summary['ready'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check-double text-success me-2"></i>Completed</span>
                            <span class="badge bg-success">{{ $summary['completed'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-times text-danger me-2"></i>Cancelled</span>
                            <span class="badge bg-danger">{{ $summary['cancelled'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stitching Orders Table -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Stitching Orders Details</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Garment Type</th>
                        <th>Assigned Tailor</th>
                        <th>Status</th>
                        <th>Est. Cost</th>
                        <th>Assigned Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stitchingOrders as $stitching)
                    <tr>
                        <td><strong>{{ $stitching->order->order_number ?? 'N/A' }}</strong></td>
                        <td>{{ $stitching->customer()->name ?? 'Unknown' }}</td>
                        <td>{{ ucfirst($stitching->garment_type) }}</td>
                        <td>
                            @if($stitching->tailor)
                                <span class="badge bg-light text-dark">{{ $stitching->tailor->name }}</span>
                            @else
                                <span class="badge bg-warning">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $stitching->status_badge_attribute ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $stitching->stitching_status)) }}
                            </span>
                        </td>
                        <td><strong>Rs. {{ number_format($stitching->estimated_cost, 2) }}</strong></td>
                        <td>{{ $stitching->assigned_date ? $stitching->assigned_date->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('receptionist.stitching-orders.show', $stitching) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No stitching orders found</td>
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
                backgroundColor: @json($chartData['backgroundColor'] ?? []),
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
