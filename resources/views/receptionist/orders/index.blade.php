@extends('receptionist.layouts.app')

@section('title', 'Orders')

@section('breadcrumb')
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-shopping-bag me-2"></i>Orders
            </h1>
            <p class="text-muted">Manage and track all customer orders</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Order
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
        <form action="{{ route('receptionist.orders.index') }}" method="GET" class="row g-3">
            <!-- Search Input -->
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-search me-2"></i>Search
                </label>
                <input type="text" name="search" class="form-control" placeholder="Order # or Customer Name..." value="{{ request('search') }}">
            </div>

            <!-- Status Filter -->
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-filter me-2"></i>Order Status
                </label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-credit-card me-2"></i>Payment Status
                </label>
                <select name="payment_status" class="form-select">
                    <option value="">All</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="col-md-12 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('receptionist.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card border-0 shadow">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Order ID</th>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Order Status</th>
                    <th>Payment</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="ps-4 fw-bold">
                        <i class="fas fa-receipt me-2"></i>{{ $order->order_number }}
                    </td>
                    <td>
                        {{ $order->user->name }}<br>
                        <small class="text-muted">{{ $order->user->email }}</small>
                    </td>
                    <td>
                        <span class="badge bg-info">
                            {{ ['ready_made' => 'Cloth', 'stitching' => 'Stitching', 'combined' => 'Both'][$order->type] ?? $order->type }}
                        </span>
                    </td>
                    <td>₹{{ number_format($order->total, 2) }}</td>
                    <td>
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'confirmed' => 'info',
                                'in_progress' => 'primary',
                                'ready' => 'success',
                                'delivered' => 'success',
                                'cancelled' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td>
                        @if($order->payment_status === 'paid')
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Paid
                            </span>
                        @elseif($order->payment_status === 'pending')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-hourglass-half me-1"></i>Pending
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times me-1"></i>Failed
                            </span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('receptionist.orders.show', $order) }}" 
                               class="btn btn-outline-info" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('receptionist.orders.edit', $order) }}" 
                               class="btn btn-outline-warning" title="Edit Order">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-outline-primary" type="button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#statusModal{{ $order->id }}"
                                    title="Update Status">
                                <i class="fas fa-sync"></i>
                            </button>
                            <button class="btn btn-outline-success" type="button" 
                                    onclick="window.open('{{ route('receptionist.orders.show', $order) }}', '_blank')"
                                    title="Print Invoice">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Status Update Modal for each order -->
                <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Update Status - {{ $order->order_number }}</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('receptionist.orders.update-status', $order) }}">
                                @csrf
                                <div class="modal-body">
                                    <select name="status" class="form-select form-select-sm" required>
                                        <option value="">Select Status</option>
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="assigned_to_tailor" {{ $order->status === 'assigned_to_tailor' ? 'selected' : '' }}>Assigned to Tailor</option>
                                        <option value="stitching_started" {{ $order->status === 'stitching_started' ? 'selected' : '' }}>Stitching Started</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="quality_check" {{ $order->status === 'quality_check' ? 'selected' : '' }}>Quality Check</option>
                                        <option value="ready_for_delivery" {{ $order->status === 'ready_for_delivery' ? 'selected' : '' }}>Ready for Delivery</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted">No orders found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="card-footer border-top">
        {{ $orders->links() }}
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
