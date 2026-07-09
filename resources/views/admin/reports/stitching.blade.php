@extends('admin.layouts.app')

@section('title', 'Stitching Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Stitching Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-needle me-2"></i>Stitching Report</h1>
                <p class="text-muted">Stitching orders and tailor performance analysis</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.reports.export', ['type' => 'stitching', 'period' => $period]) }}" class="btn btn-primary">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Stitching Orders</h6>
                    <h3 class="mb-0">{{ $totalStitching }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Completed</h6>
                    <h3 class="mb-0 text-success">{{ $completedStitching }}</h3>
                    <small class="text-muted">
                        {{ $totalStitching > 0 ? round(($completedStitching/$totalStitching)*100) : 0 }}%
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Avg Cost</h6>
                    <h3 class="mb-0 text-info">${{ number_format($avgCost, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Stitching Status Breakdown</h5>
        </div>
        <div class="card-body">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Status</th>
                        <th class="text-center">Count</th>
                        <th class="text-center">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($byStatus as $status)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $status->stitching_status)) }}</td>
                        <td class="text-center">{{ $status->count }}</td>
                        <td class="text-center">
                            <div class="progress" style="height: 20px;">
                                @php
                                    $percentage = $totalStitching > 0 ? ($status->count / $totalStitching) * 100 : 0;
                                @endphp
                                <div class="progress-bar" style="width: {{ $percentage }}%">
                                    {{ round($percentage) }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Stitching Orders -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Recent Stitching Orders</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Tailor</th>
                        <th>Garment Type</th>
                        <th>Estimated Cost</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stitching as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->order->order_number ?? 'N/A' }}</strong>
                        </td>
                        <td>{{ $item->tailor->name ?? 'Unassigned' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $item->garment_type ?? 'N/A')) }}</td>
                        <td>${{ number_format($item->estimated_cost ?? 0, 2) }}</td>
                        <td>
                            @if($item->stitching_status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($item->stitching_status === 'in_progress')
                                <span class="badge bg-primary">In Progress</span>
                            @elseif($item->stitching_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($item->stitching_status === 'ready')
                                <span class="badge bg-info">Ready</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $item->stitching_status)) }}</span>
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-3 text-muted">No stitching orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light">
            {{ $stitching->links() }}
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
    color: #1a1a1a;
    margin: 0;
}
</style>
@endsection
