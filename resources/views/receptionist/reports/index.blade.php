@extends('receptionist.layouts.app')

@section('title', 'Reports')

@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Reports & Analytics</h1>
    <p class="page-subtitle">View key business metrics and generate custom reports</p>
</div>

<!-- Quick Stats -->
<div class="row mb-30">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stat-card">
            <i class="fas fa-shopping-bag stat-icon"></i>
            <div class="stat-value">{{ $todayOrders }}</div>
            <div class="stat-label">Orders Today</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> {{ $monthlyOrders }} this month
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stat-card">
            <i class="fas fa-rupiah-sign stat-icon"></i>
            <div class="stat-value">₹{{ number_format($todayRevenue, 0) }}</div>
            <div class="stat-label">Today's Revenue</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> ₹{{ number_format($monthlyRevenue, 0) }} this month
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stat-card">
            <i class="fas fa-hourglass stat-icon"></i>
            <div class="stat-value">{{ $pendingOrders }}</div>
            <div class="stat-label">Pending Orders</div>
            <div class="stat-change negative">
                <i class="fas fa-exclamation"></i> Awaiting Action
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stat-card">
            <i class="fas fa-credit-card stat-icon"></i>
            <div class="stat-value">₹{{ number_format($pendingPayments, 0) }}</div>
            <div class="stat-label">Pending Payments</div>
            <div class="stat-change negative">
                <i class="fas fa-exclamation"></i> Amount Due
            </div>
        </div>
    </div>
</div>

<!-- Report Options -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="table-card">
            <div class="table-header">
                <h5 class="table-title">Order Reports</h5>
            </div>
            <div style="padding: 20px;">
                <p class="text-muted mb-3">Generate detailed order reports with filters and export options.</p>
                <a href="{{ route('receptionist.reports.orders') }}" class="btn btn-custom btn-primary-custom w-100 mb-2">
                    <i class="fas fa-file-csv"></i> View Order Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="table-card">
            <div class="table-header">
                <h5 class="table-title">Stitching Reports</h5>
            </div>
            <div style="padding: 20px;">
                <p class="text-muted mb-3">Track stitching orders, tailor performance, and completion status.</p>
                <a href="{{ route('receptionist.reports.stitching') }}" class="btn btn-custom btn-primary-custom w-100 mb-2">
                    <i class="fas fa-file-csv"></i> View Stitching Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="table-card">
            <div class="table-header">
                <h5 class="table-title">Customer Reports</h5>
            </div>
            <div style="padding: 20px;">
                <p class="text-muted mb-3">Analyze customer data, order history, and measurements.</p>
                <a href="{{ route('receptionist.reports.customers') }}" class="btn btn-custom btn-primary-custom w-100 mb-2">
                    <i class="fas fa-file-csv"></i> View Customer Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="table-card">
            <div class="table-header">
                <h5 class="table-title">Payment Reports</h5>
            </div>
            <div style="padding: 20px;">
                <p class="text-muted mb-3">Monitor payment status, pending amounts, and transaction history.</p>
                <a href="{{ route('receptionist.reports.payments') }}" class="btn btn-custom btn-primary-custom w-100 mb-2">
                    <i class="fas fa-file-csv"></i> View Payment Report
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-css')
<style>
    .mb-30 {
        margin-bottom: 30px;
    }
</style>
@endsection
