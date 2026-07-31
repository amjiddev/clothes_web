@extends('admin.layouts.app')

@section('title', 'Tailor Performance Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Tailor Performance</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-user-tie me-2"></i>Tailor Performance Report</h1>
                <p class="text-muted">Tailor productivity and performance metrics</p>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Top 10 Performing Tailors</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tailor Name</th>
                        <th class="text-center">Orders Assigned</th>
                        <th class="text-center">Rank</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topTailors as $index => $tailor)
                    <tr>
                        <td><strong>{{ $tailor->name }}</strong></td>
                        <td class="text-center">{{ $tailor->stitchingOrders_count }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary">
                                @if($index === 0)
                                    🥇 #1
                                @elseif($index === 1)
                                    🥈 #2
                                @elseif($index === 2)
                                    🥉 #3
                                @else
                                    #{{ $index + 1 }}
                                @endif
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-3 text-muted">No tailor data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Tailor Performance -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detailed Tailor Performance</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tailor Name</th>
                        <th class="text-center">Total Orders</th>
                        <th class="text-center">Completed</th>
                        <th class="text-center">In Progress</th>
                        <th class="text-center">Pending</th>
                        <th class="text-center">Completion Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tailors as $tailor)
                    @php
                        $stats = $tailorStats[$tailor->id] ?? [];
                        $completionRate = $stats['completion_rate'] ?? 0;
                        $bgColor = $completionRate >= 80 ? 'bg-success' : ($completionRate >= 60 ? 'bg-info' : 'bg-warning');
                    @endphp
                    <tr>
                        <td><strong>{{ $tailor->name }}</strong></td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark">{{ $stats['total'] ?? 0 }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ $stats['completed'] ?? 0 }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $stats['in_progress'] ?? 0 }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark">{{ $stats['pending'] ?? 0 }}</span>
                        </td>
                        <td class="text-center">
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar {{ $bgColor }}" style="width: {{ $completionRate }}%">
                                    {{ round($completionRate) }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-3 text-muted">No tailor performance data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light">
            {{ $tailors->links() }}
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
