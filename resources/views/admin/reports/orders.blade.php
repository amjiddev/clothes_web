@extends('admin.layouts.app')

@section('title', 'Order Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Order Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-list me-2"></i>Order Report</h1>
                <p class="text-muted">Order status and fulfillment analysis</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.reports.export', ['type' => 'orders', 'period' => $period]) }}" class="btn btn-primary">
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
                    <h6 class="text-muted mb-2">Total Orders</h6>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Completed</h6>
                    <h3 class="mb-0 text-success">{{ $completedOrders }}</h3>
                    <small class="text-muted">
                        {{ $totalOrders > 0 ? round(($completedOrders/$totalOrders)*100) : 0 }}%
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Pending</h6>
                    <h3 class="mb-0 text-warning">{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Order Status Breakdown</h5>
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
                                <td>{{ ucfirst(str_replace('_', ' ', $status->status)) }}</td>
                                <td class="text-center">{{ $status->count }}</td>
                                <td class="text-center">
                                    <div class="progress" style="height: 20px;">
                                        @php
                                            $percentage = $totalOrders > 0 ? ($status->count / $totalOrders) * 100 : 0;
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
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Payment Status Breakdown</h5>
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
                            @foreach($byPaymentStatus as $paymentStatus)
                            <tr>
                                <td>
                                    @if($paymentStatus->payment_status === 'paid')
                                        <span class="badge bg-success">{{ ucfirst($paymentStatus->payment_status) }}</span>
                                    @elseif($paymentStatus->payment_status === 'pending')
                                        <span class="badge bg-warning text-dark">{{ ucfirst($paymentStatus->payment_status) }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($paymentStatus->payment_status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $paymentStatus->count }}</td>
                                <td class="text-center">
                                    <div class="progress" style="height: 20px;">
                                        @php
                                            $percentage = $totalOrders > 0 ? ($paymentStatus->count / $totalOrders) * 100 : 0;
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
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Order Details</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->user->name ?? 'Unknown' }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                        <td>
                            @if($order->status === 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @elseif($order->status === 'in_progress')
                                <span class="badge bg-primary">In Progress</span>
                            @elseif($order->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-3 text-muted">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light">
            {{ $orders->links() }}
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
