@extends('receptionist.layouts.app')

@section('title', 'Customers')

@section('breadcrumb')
    <li class="breadcrumb-item active">Customers</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-users me-2"></i>Customers
            </h1>
            <p class="text-muted">Manage and view all customers</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Customer
            </a>
        </div>
    </div>
</div>

<!-- Success Message -->
@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Search & Filter Section -->
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form action="{{ route('receptionist.customers.index') }}" method="GET" class="row g-3">
            <!-- Search Input -->
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    <i class="fas fa-search me-2"></i>Search
                </label>
                <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
            </div>

            <!-- Status Filter -->
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    <i class="fas fa-filter me-2"></i>Status
                </label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="col-md-12 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('receptionist.customers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Customers Table -->
<div class="card border-0 shadow">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">
                        <i class="fas fa-user me-2"></i>Name
                    </th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total Orders</th>
                    <th>Last Order</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td class="ps-4 fw-bold">
                        <i class="fas fa-user-circle me-2 text-primary"></i>{{ $customer->name }}
                    </td>
                    <td>
                        <code class="bg-light px-2 py-1">{{ $customer->email }}</code>
                    </td>
                    <td>
                        {{ $customer->addresses()->first()?->city ?? '-' }}
                    </td>
                    <td>
                        <span class="badge bg-info">
                            {{ $customer->orders()->count() }}
                        </span>
                    </td>
                    <td>
                        @php
                            $lastOrder = $customer->orders()->latest()->first();
                        @endphp
                        @if($lastOrder)
                            {{ $lastOrder->created_at->format('M d, Y') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($customer->is_blocked)
                            <span class="badge bg-danger">
                                <i class="fas fa-ban me-1"></i>Blocked
                            </span>
                        @else
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Active
                            </span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('receptionist.customers.show', $customer) }}" 
                               class="btn btn-outline-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('receptionist.customers.edit', $customer) }}" 
                               class="btn btn-outline-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('receptionist.orders.create', ['customer_id' => $customer->id]) }}" 
                               class="btn btn-outline-success" title="Create Order">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted">No customers found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
    <div class="card-footer border-top">
        {{ $customers->links() }}
    </div>
    @endif
</div>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

.btn-group-sm .btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endsection
