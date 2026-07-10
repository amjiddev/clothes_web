@extends('receptionist.layouts.app')

@section('title', 'Payment Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Payment Management</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-credit-card me-2"></i>Payment Management
            </h1>
            <p class="text-muted">View and manage all order payments</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.payments.export') }}" class="btn btn-outline-secondary">
                <i class="fas fa-download me-2"></i>Export Report
            </a>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Search & Filter Section -->
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form action="{{ route('receptionist.payments.index') }}" method="GET" class="row g-3">
            <!-- Search Input -->
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-search me-2"></i>Search
                </label>
                <input type="text" name="search" class="form-control" placeholder="Order # or Customer Name..." value="{{ request('search') }}">
            </div>

            <!-- Status Filter -->
            <div class="col-md-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-filter me-2"></i>Status
                </label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <!-- Payment Method Filter -->
            <div class="col-md-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-wallet me-2"></i>Method
                </label>
                <select name="method" class="form-select">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Card</option>
                    <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="online" {{ request('method') === 'online' ? 'selected' : '' }}>Online</option>
                </select>
            </div>

            <!-- Date From -->
            <div class="col-md-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-calendar-alt me-2"></i>From Date
                </label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>

            <!-- Date To -->
            <div class="col-md-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-calendar-alt me-2"></i>To Date
                </label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <!-- Filter Buttons -->
            <div class="col-md-12 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('receptionist.payments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-1">Total Payments</p>
                        <h4 class="mb-0 fw-bold">{{ $payments->total() }}</h4>
                    </div>
                    <div class="text-primary" style="font-size: 2.5rem;">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-1">This Page</p>
                        <h4 class="mb-0 fw-bold">{{ $payments->count() }}</h4>
                    </div>
                    <div class="text-success" style="font-size: 2.5rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-1">Total Amount (This Page)</p>
                        <h4 class="mb-0 fw-bold">PKR {{ number_format($payments->pluck('amount')->sum(), 2) }}</h4>
                    </div>
                    <div class="text-info" style="font-size: 2.5rem;">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="card border-0 shadow">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Payment ID</th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Date</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="ps-4">
                        <strong>#{{ $payment->id }}</strong>
                    </td>
                    <td>
                        <a href="{{ route('receptionist.orders.show', $payment->order) }}" class="text-decoration-none fw-bold">
                            {{ $payment->order->order_number }}
                        </a>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $payment->order->user->name }}</div>
                        <small class="text-muted">{{ $payment->order->user->email }}</small>
                    </td>
                    <td>
                        <strong class="text-success">PKR {{ number_format($payment->amount, 2) }}</strong>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-wallet me-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        </span>
                    </td>
                    <td>
                        {{ $payment->processed_at ? $payment->processed_at->format('M d, Y') : '-' }}
                    </td>
                    <td>
                        @if($payment->status === 'completed')
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Completed
                        </span>
                        @elseif($payment->status === 'pending')
                        <span class="badge bg-warning">
                            <i class="fas fa-clock me-1"></i>Pending
                        </span>
                        @else
                        <span class="badge bg-danger">
                            <i class="fas fa-times-circle me-1"></i>Failed
                        </span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('receptionist.payments.show', $payment) }}" 
                               class="btn btn-outline-info" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('receptionist.orders.show', $payment->order) }}" 
                               class="btn btn-outline-primary" title="View Order">
                                <i class="fas fa-receipt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted">No payments found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
    <div class="card-footer border-top">
        {{ $payments->links() }}
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
