@extends('admin.layouts.app')

@section('title', 'Reports Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-chart-bar me-2"></i>Reports Dashboard</h1>
                <p class="text-muted">View business performance and analytics</p>
            </div>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <!-- Period Selection -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Period</label>
                    <select name="period" class="form-select" onchange="this.form.submit()">
                        <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="custom">Custom Date Range</option>
                    </select>
                </div>

                <!-- Custom Date Range (Hidden by default) -->
                @if($period === 'custom')
                <div class="col-md-3">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" name="from" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" name="to" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Apply Filter
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Sales</h6>
                            <h3 class="mb-0">${{ number_format($stats['total_sales'], 2) }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Orders</h6>
                            <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Stitching Orders</h6>
                            <h3 class="mb-0">{{ $stats['total_stitching'] }}</h3>
                        </div>
                        <div class="text-warning" style="font-size: 2rem;">
                            <i class="fas fa-needle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Completed Stitching</h6>
                            <h3 class="mb-0">{{ $stats['completed_stitching'] }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="row">
        <!-- Sales Report Card -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-chart-line me-2 text-success"></i>Sales Report
                    </h5>
                    <p class="card-text text-muted mb-3">
                        Analyze revenue, order trends, and sales performance
                    </p>
                    <a href="{{ route('admin.reports.sales') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>View Report
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'sales', 'period' => $period]) }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-download me-1"></i>Export PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Order Report Card -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-list me-2 text-info"></i>Order Report
                    </h5>
                    <p class="card-text text-muted mb-3">
                        Track order status, fulfillment, and customer transactions
                    </p>
                    <a href="{{ route('admin.reports.orders') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>View Report
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'orders', 'period' => $period]) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-download me-1"></i>Export PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Stitching Report Card -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-needle me-2 text-warning"></i>Stitching Report
                    </h5>
                    <p class="card-text text-muted mb-3">
                        Monitor stitching orders, tailor assignments, and completion status
                    </p>
                    <a href="{{ route('admin.reports.stitching') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>View Report
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'stitching', 'period' => $period]) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-download me-1"></i>Export PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Customer Report Card -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-users me-2 text-primary"></i>Customer Report
                    </h5>
                    <p class="card-text text-muted mb-3">
                        Analyze customer behavior, spending patterns, and loyalty
                    </p>
                    <a href="{{ route('admin.reports.customers') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Tailor Report Card -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-user-tie me-2 text-danger"></i>Tailor Performance
                    </h5>
                    <p class="card-text text-muted mb-3">
                        Evaluate tailor productivity, quality, and performance metrics
                    </p>
                    <a href="{{ route('admin.reports.tailors') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>View Report
                    </a>
                </div>
            </div>
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

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection
