@extends('admin.layouts.app')

@section('title', 'Customers Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Customers</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-users me-2"></i>Customers</h1>
                <p class="text-muted">Manage all customers and view their details</p>
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
            <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-3">
                <!-- Search Input -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Name or email..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>

                <!-- Date From -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>

                <!-- Date To -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>

                <!-- Filter Buttons -->
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card border-0 shadow">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="fw-bold">
                            <i class="fas fa-user me-2"></i>Name
                        </th>
                        <th class="fw-bold">Email</th>
                        <th class="fw-bold text-center">Total Orders</th>
                        <th class="fw-bold text-end">Total Spending</th>
                        <th class="fw-bold text-center">Registered Date</th>
                        <th class="fw-bold text-center">Status</th>
                        <th class="fw-bold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <!-- Customer Name with Avatar -->
                        <td>
                            <div class="d-flex align-items-center">
                                @if($customer->profile_photo_path)
                                <img src="{{ $customer->profile_photo_url }}" alt="{{ $customer->name }}" 
                                     class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                                @endif
                                <strong>{{ $customer->name }}</strong>
                            </div>
                        </td>

                        <!-- Email -->
                        <td>
                            <small>{{ $customer->email }}</small>
                        </td>

                        <!-- Total Orders -->
                        <td class="text-center">
                            <span class="badge bg-info">{{ $customer->orders_count }}</span>
                        </td>

                        <!-- Total Spending -->
                        <td class="text-end">
                            <strong>Rs. {{ number_format($customer->total_spending, 2) }}</strong>
                            @if($customer->orders_count > 0)
                            <br>
                            <small class="text-muted">Avg: Rs. {{ number_format($customer->total_spending / $customer->orders_count, 2) }}</small>
                            @endif
                        </td>

                        <!-- Registered Date -->
                        <td class="text-center">
                            <small class="text-muted">
                                {{ $customer->created_at->format('M d, Y') }}
                            </small>
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            @if($customer->is_blocked)
                            <span class="badge bg-danger">
                                <i class="fas fa-ban me-1"></i>Blocked
                            </span>
                            @else
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('edit_customers')
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete_customers')
                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" 
                                            onclick="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No customers found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted">
                        Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} customers
                    </small>
                </div>
                <div class="col-auto">
                    {{ $customers->links() }}
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

.btn-group .btn {
    padding: 6px 12px;
}
</style>
@endsection
