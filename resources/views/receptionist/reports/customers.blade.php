@extends('receptionist.layouts.app')

@section('title', 'Customer Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Customer Report</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Customer Report</h1>
        <p class="page-subtitle">Analyze customer data and transaction history</p>
    </div>
    <form method="POST" action="{{ route('receptionist.reports.export') }}" class="d-inline">
        @csrf
        <input type="hidden" name="type" value="customers">
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
                    <div class="col-md-4">
                        <label class="form-label">Sort By</label>
                        <select name="sort_by" class="form-select">
                            <option value="">Default</option>
                            <option value="total_orders" {{ request('sort_by') === 'total_orders' ? 'selected' : '' }}>Total Orders</option>
                            <option value="total_spent" {{ request('sort_by') === 'total_spent' ? 'selected' : '' }}>Total Spent</option>
                            <option value="total_measurements" {{ request('sort_by') === 'total_measurements' ? 'selected' : '' }}>Measurements</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-custom btn-primary-custom w-100">
                            <i class="fas fa-sort"></i> Sort
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Customers Table -->
<div class="table-card">
    <div class="table-header">
        <h5 class="table-title">Customers</h5>
        <span class="badge bg-secondary">{{ count($customers) }} Customers</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total Orders</th>
                    <th>Total Spent</th>
                    <th>Measurements</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>#{{ $customer['id'] }}</td>
                        <td>
                            <a href="{{ route('receptionist.customers.show', $customer['id']) }}" style="color: var(--accent-color); text-decoration: none;">
                                {{ $customer['name'] }}
                            </a>
                        </td>
                        <td>{{ $customer['email'] }}</td>
                        <td>{{ $customer['phone'] ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $customer['total_orders'] }}</span>
                        </td>
                        <td>
                            <strong>Rs. {{ number_format($customer['total_spent'], 2) }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $customer['total_measurements'] }}</span>
                        </td>
                        <td>
                            <a href="{{ route('receptionist.customers.show', $customer['id']) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No customers found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
