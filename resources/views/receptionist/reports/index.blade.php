@extends('receptionist.layouts.app')

@section('title', 'Reports & Analytics')

@section('breadcrumb')
    <li class="breadcrumb-item active">Reports & Analytics</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Reports & Analytics</h1>
    <p class="page-subtitle">View operational reports and business metrics</p>
</div>

<!-- Quick Stats -->
<div class="row mb-30">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stat-card">
            <i class="fas fa-shopping-bag stat-icon"></i>
            <div class="stat-value">{{ $todayOrders }}</div>
            <div class="stat-label">Orders Today</div>
            <div class="stat-change">
                <i class="fas fa-info-circle"></i> {{ $monthlyOrders }} this month
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stat-card">
            <i class="fas fa-rupiah-sign stat-icon"></i>
            <div class="stat-value">₹{{ number_format($todayRevenue, 0) }}</div>
            <div class="stat-label">Today's Revenue</div>
            <div class="stat-change">
                <i class="fas fa-info-circle"></i> ₹{{ number_format($monthlyRevenue, 0) }} this month
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stat-card">
            <i class="fas fa-hourglass stat-icon"></i>
            <div class="stat-value">{{ $pendingStitching }}</div>
            <div class="stat-label">Pending Stitching</div>
            <div class="stat-change">
                <i class="fas fa-exclamation"></i> {{ $completedStitching }} completed
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stat-card">
            <i class="fas fa-money-bill-wave stat-icon"></i>
            <div class="stat-value">₹{{ number_format($collectedPayments, 0) }}</div>
            <div class="stat-label">Collected</div>
            <div class="stat-change">
                <i class="fas fa-exclamation"></i> ₹{{ number_format($pendingPayments, 0) }} pending
            </div>
        </div>
    </div>
</div>

<!-- Report Cards -->
<div class="row">
    <!-- Daily Orders Report -->
    <div class="col-lg-6 mb-4">
        <div class="table-card report-card">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="fas fa-calendar-day"></i> Daily Orders Report
                </h5>
            </div>
            <div style="padding: 20px;">
                <p class="text-muted mb-3">View orders placed on a specific date with status breakdown and revenue analysis.</p>
                <div class="report-stats mb-3">
                    <div class="stat-mini">
                        <span class="label">Today's Orders</span>
                        <span class="value">{{ $todayOrders }}</span>
                    </div>
                    <div class="stat-mini">
                        <span class="label">Today's Revenue</span>
                        <span class="value">₹{{ number_format($todayRevenue, 0) }}</span>
                    </div>
                </div>
                <a href="{{ route('receptionist.reports.daily-orders') }}" class="btn btn-custom btn-primary-custom w-100">
                    <i class="fas fa-chart-pie"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Monthly Sales Report -->
    <div class="col-lg-6 mb-4">
        <div class="table-card report-card">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="fas fa-chart-bar"></i> Monthly Sales Report
                </h5>
            </div>
            <div style="padding: 20px;">
                <p class="text-muted mb-3">Track daily sales trends and order patterns throughout the month.</p>
                <div class="report-stats mb-3">
                    <div class="stat-mini">
                        <span class="label">Monthly Orders</span>
                        <span class="value">{{ $monthlyOrders }}</span>
                    </div>
                    <div class="stat-mini">
                        <span class="label">Monthly Revenue</span>
                        <span class="value">₹{{ number_format($monthlyRevenue, 0) }}</span>
                    </div>
                </div>
                <a href="{{ route('receptionist.reports.monthly-sales') }}" class="btn btn-custom btn-primary-custom w-100">
                    <i class="fas fa-line-chart"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Pending Stitching Report -->
    <div class="col-lg-6 mb-4">
        <div class="table-card report-card">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="fas fa-scissors"></i> Pending Stitching Report
                </h5>
            </div>
            <div style="padding: 20px;">
                <p class="text-muted mb-3">Monitor stitching orders by status and tailor assignments within a date range.</p>
                <div class="report-stats mb-3">
                    <div class="stat-mini">
                        <span class="label">Pending Orders</span>
                        <span class="value">{{ $pendingStitching }}</span>
                    </div>
                    <div class="stat-mini">
                        <span class="label">Completed</span>
                        <span class="value">{{ $completedStitching }}</span>
                    </div>
                </div>
                <a href="{{ route('receptionist.reports.pending-stitching') }}" class="btn btn-custom btn-primary-custom w-100">
                    <i class="fas fa-tasks"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Completed Orders Report -->
    <div class="col-lg-6 mb-4">
        <div class="table-card report-card">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="fas fa-check-circle"></i> Completed Orders Report
                </h5>
            </div>
            <div style="padding: 20px;">
                <p class="text-muted mb-3">View all completed orders with revenue and fulfillment details within a date range.</p>
                <div class="report-stats mb-3">
                    <div class="stat-mini">
                        <span class="label">Completed</span>
                        <span class="value">{{ $completedOrders }}</span>
                    </div>
                    <div class="stat-mini">
                        <span class="label">Total Revenue</span>
                        <span class="value">₹{{ number_format($collectedPayments, 0) }}</span>
                    </div>
                </div>
                <a href="{{ route('receptionist.reports.completed-orders') }}" class="btn btn-custom btn-primary-custom w-100">
                    <i class="fas fa-clipboard-check"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Payment Collection Report -->
    <div class="col-lg-6 mb-4">
        <div class="table-card report-card">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="fas fa-money-bill-wave"></i> Payment Collection Report
                </h5>
            </div>
            <div style="padding: 20px;">
                <p class="text-muted mb-3">Track payment collections, pending amounts, and transaction status with detailed breakdown.</p>
                <div class="report-stats mb-3">
                    <div class="stat-mini">
                        <span class="label">Collected</span>
                        <span class="value">₹{{ number_format($collectedPayments, 0) }}</span>
                    </div>
                    <div class="stat-mini">
                        <span class="label">Pending</span>
                        <span class="value">₹{{ number_format($pendingPayments, 0) }}</span>
                    </div>
                </div>
                <a href="{{ route('receptionist.reports.payment-collection') }}" class="btn btn-custom btn-primary-custom w-100">
                    <i class="fas fa-receipt"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Note: Financial Reports Restricted -->
    <div class="col-lg-6 mb-4">
        <div class="table-card alert-card">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="fas fa-lock"></i> Financial Reports
                </h5>
            </div>
            <div style="padding: 20px;">
                <p class="text-muted mb-3">
                    <i class="fas fa-info-circle"></i> Financial profit and cost analysis reports are restricted to administrators only.
                </p>
                <p class="text-muted small">
                    As a receptionist, you can access operational and sales reports for order and payment management purposes.
                </p>
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

    .stat-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .stat-icon {
        font-size: 2.5rem;
        color: var(--accent-color);
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: bold;
        color: #1a1a1a;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin: 5px 0;
    }

    .stat-change {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 8px;
    }

    .report-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .report-card:hover {
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.15);
        transform: translateY(-2px);
    }

    .report-stats {
        display: flex;
        gap: 10px;
    }

    .stat-mini {
        flex: 1;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        text-align: center;
    }

    .stat-mini .label {
        display: block;
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .stat-mini .value {
        display: block;
        font-size: 1.1rem;
        font-weight: bold;
        color: var(--accent-color);
    }

    .alert-card {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe5a6 100%);
        border: 1px solid #ffc107;
    }

    .alert-card .text-muted {
        color: #856404 !important;
    }

    .btn-custom {
        border: none;
        border-radius: 6px;
        padding: 10px 20px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-primary-custom {
        background-color: var(--accent-color);
        color: white;
    }

    .btn-primary-custom:hover {
        background-color: darken(var(--accent-color), 10%);
        transform: translateY(-2px);
    }
</style>
@endsection
