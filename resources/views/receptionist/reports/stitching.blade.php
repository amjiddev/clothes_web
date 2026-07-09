@extends('receptionist.layouts.app')

@section('title', 'Stitching Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Stitching Report</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Stitching Report</h1>
        <p class="page-subtitle">Track stitching orders and tailor performance</p>
    </div>
    <form method="POST" action="{{ route('receptionist.reports.export') }}" class="d-inline">
        @csrf
        <input type="hidden" name="type" value="stitching">
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
                            <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tailor</label>
                        <select name="tailor_id" class="form-select">
                            <option value="">All Tailors</option>
                            @foreach($tailors as $tailor)
                                <option value="{{ $tailor->id }}" {{ request('tailor_id') == $tailor->id ? 'selected' : '' }}>
                                    {{ $tailor->name }}
                                </option>
                            @endforeach
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
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-hashtag stat-icon"></i>
            <div class="stat-value">{{ $totalOrders }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-check-circle stat-icon"></i>
            <div class="stat-value">{{ $completedOrders }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-hourglass stat-icon"></i>
            <div class="stat-value">{{ $pendingOrders }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-rupiah-sign stat-icon"></i>
            <div class="stat-value">₹{{ number_format($totalCost, 0) }}</div>
            <div class="stat-label">Total Cost</div>
        </div>
    </div>
</div>

<!-- Stitching Orders Table -->
<div class="table-card">
    <div class="table-header">
        <h5 class="table-title">Stitching Orders</h5>
        <span class="badge bg-secondary">{{ $stitchingOrders->count() }} Records</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Tailor</th>
                    <th>Garment Type</th>
                    <th>Status</th>
                    <th>Cost</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stitchingOrders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('receptionist.stitching-orders.show', $order->id) }}" style="color: var(--accent-color); text-decoration: none;">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td>{{ $order->order?->customer?->name ?? 'N/A' }}</td>
                        <td>
                            @if($order->tailor)
                                <span class="badge bg-success">{{ $order->tailor->name }}</span>
                            @else
                                <span class="badge bg-warning">Unassigned</span>
                            @endif
                        </td>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->garment_type)) }}</td>
                        <td>
                            @php
                                $statusClass = match($order->stitching_status) {
                                    'pending' => 'status-pending',
                                    'assigned' => 'status-assigned',
                                    'in_progress' => 'status-in-progress',
                                    'completed' => 'status-completed',
                                    default => 'status-pending',
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order->stitching_status)) }}</span>
                        </td>
                        <td>₹{{ number_format($order->estimated_cost, 2) }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('receptionist.stitching-orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No stitching orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
