@extends('admin.layouts.app')

@section('title', 'Sales Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Sales Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-chart-line me-2"></i>Sales Report</h1>
                <p class="text-muted">Revenue analysis and sales performance</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.reports.export', ['type' => 'sales', 'period' => $period]) }}" class="btn btn-primary">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Revenue</h6>
                    <h3 class="mb-0 text-success">${{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Orders</h6>
                    <h3 class="mb-0 text-info">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Avg Order Value</h6>
                    <h3 class="mb-0 text-warning">${{ number_format($avgOrderValue, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Date Range</h6>
                    <small class="text-muted">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales by Type -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Sales by Type</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-right">Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($byType as $type)
                                <tr>
                                    <td>
                                        @if($type->type === 'ready_made')
                                            Cloth Only
                                        @elseif($type->type === 'stitching')
                                            Stitching Only
                                        @else
                                            Cloth + Stitching
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $type->count }}</td>
                                    <td class="text-right"><strong>${{ number_format($type->total, 2) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Sales Chart Data -->
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Daily Sales Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-right">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyData as $day)
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                                    <td class="text-center">{{ $day->count }}</td>
                                    <td class="text-right"><strong>${{ number_format($day->total, 2) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Recent Sales</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order Number</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td><strong>{{ $sale->order_number }}</strong></td>
                        <td>{{ $sale->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            @if($sale->type === 'ready_made')
                                <span class="badge bg-light text-dark">Cloth Only</span>
                            @elseif($sale->type === 'stitching')
                                <span class="badge bg-light text-dark">Stitching Only</span>
                            @else
                                <span class="badge bg-light text-dark">Cloth + Stitching</span>
                            @endif
                        </td>
                        <td><strong>${{ number_format($sale->total, 2) }}</strong></td>
                        <td>
                            @if($sale->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($sale->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-3 text-muted">No sales data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light">
            {{ $sales->links() }}
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
