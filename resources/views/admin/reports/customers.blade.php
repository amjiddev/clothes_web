@extends('admin.layouts.app')

@section('title', 'Customer Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Customer Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-users me-2"></i>Customer Report</h1>
                <p class="text-muted">Customer behavior and spending analysis</p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Active Customers</h6>
                    <h3 class="mb-0">{{ $totalCustomers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Spent</h6>
                    <h3 class="mb-0">${{ number_format($totalSpent, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Avg Customer Value</h6>
                    <h3 class="mb-0">${{ number_format($totalCustomers > 0 ? $totalSpent / $totalCustomers : 0, 2) }}</h3>
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

    <!-- Top Customers -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Top Customers by Spending</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th class="text-center">Orders</th>
                        <th class="text-right">Total Spent</th>
                        <th class="text-right">Avg Order Value</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>
                            <strong>{{ $customer->name }}</strong>
                        </td>
                        <td>{{ $customer->email }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark">{{ $customer->orders_count }}</span>
                        </td>
                        <td class="text-right">
                            <strong>${{ number_format($customer->orders_sum_total ?? 0, 2) }}</strong>
                        </td>
                        <td class="text-right">
                            ${{ number_format($customer->orders_count > 0 ? ($customer->orders_sum_total ?? 0) / $customer->orders_count : 0, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-3 text-muted">No customers found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light">
            {{ $customers->links() }}
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
