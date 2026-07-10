@extends('receptionist.layouts.app')

@section('title', 'Tailor Orders')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.tailors.index') }}">Tailors</a></li>
    <li class="breadcrumb-item active">{{ $tailor->user->name }} Orders</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-tasks me-2"></i>{{ $tailor->user->name }} - Assigned Orders
            </h1>
            <p class="text-muted">View all stitching orders assigned to this tailor</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.tailors.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form action="{{ route('receptionist.tailors.tailor-orders', $tailor) }}" method="GET" class="row g-3">
            <!-- Status Filter -->
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-filter me-2"></i>Status
                </label>
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="assigned" {{ $status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="ready_for_fitting" {{ $status === 'ready_for_fitting' ? 'selected' : '' }}>Ready for Fitting</option>
                    <option value="in_fitting" {{ $status === 'in_fitting' ? 'selected' : '' }}>In Fitting</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="col-md-8 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('receptionist.tailors.tailor-orders', $tailor) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Statistics -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-1">Total Orders</p>
                        <h4 class="mb-0 fw-bold">{{ $orders->total() }}</h4>
                    </div>
                    <div class="text-info" style="font-size: 2rem;">
                        <i class="fas fa-boxes"></i>
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
                        <h4 class="mb-0 fw-bold">{{ $orders->count() }}</h4>
                    </div>
                    <div class="text-primary" style="font-size: 2rem;">
                        <i class="fas fa-list"></i>
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
                        <p class="text-muted small mb-1">Tailor Status</p>
                        @if($tailor->status === 'active')
                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                        @elseif($tailor->status === 'on_leave')
                        <span class="badge bg-warning"><i class="fas fa-calendar-times me-1"></i>On Leave</span>
                        @else
                        <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Inactive</span>
                        @endif
                    </div>
                    <div class="text-success" style="font-size: 2rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card border-0 shadow">
    @if($orders->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Order ID</th>
                    <th>Customer</th>
                    <th>Garment Type</th>
                    <th>Assigned Date</th>
                    <th>Status</th>
                    <th>Cost</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="ps-4">
                        <strong>#{{ $order->order->order_number }}</strong>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $order->order->user->name }}</div>
                        <small class="text-muted">{{ $order->order->user->email }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">
                            {{ $order->garment_type ?? 'Custom' }}
                        </span>
                    </td>
                    <td>
                        @if($order->assigned_date)
                        {{ $order->assigned_date->format('M d, Y') }}
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $order->status_badge }}">
                            <i class="fas fa-circle-notch me-1"></i>{{ $order->status_text }}
                        </span>
                    </td>
                    <td>
                        <strong>PKR {{ number_format($order->estimated_cost, 2) }}</strong>
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('receptionist.orders.show', $order->order) }}" 
                               class="btn btn-outline-info" title="View Order">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-outline-primary" 
                                    data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}" 
                                    title="View Details">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Order Details Modal -->
                <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Order #{{ $order->order->order_number }} Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p class="text-muted small mb-1">Customer Name</p>
                                        <p class="fw-bold">{{ $order->order->user->name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted small mb-1">Status</p>
                                        <p>
                                            <span class="badge bg-{{ $order->status_badge }}">
                                                {{ $order->status_text }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p class="text-muted small mb-1">Garment Type</p>
                                        <p class="fw-bold">{{ $order->garment_type ?? 'Custom' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted small mb-1">Estimated Cost</p>
                                        <p class="fw-bold">PKR {{ number_format($order->estimated_cost, 2) }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p class="text-muted small mb-1">Assigned Date</p>
                                        <p class="fw-bold">{{ $order->assigned_date?->format('M d, Y - h:i A') ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted small mb-1">Start Date</p>
                                        <p class="fw-bold">{{ $order->start_date?->format('M d, Y - h:i A') ?? '-' }}</p>
                                    </div>
                                </div>

                                @if($order->additional_instructions)
                                <div class="mb-3">
                                    <p class="text-muted small mb-1">Special Instructions</p>
                                    <p class="fw-bold">{{ $order->additional_instructions }}</p>
                                </div>
                                @endif

                                @if($order->tailor_notes)
                                <div class="mb-0">
                                    <p class="text-muted small mb-1">Tailor Notes</p>
                                    <p class="fw-bold">{{ $order->tailor_notes }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="{{ route('receptionist.orders.show', $order->order) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>View Full Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="card-footer border-top">
        {{ $orders->links() }}
    </div>
    @endif
    @else
    <div class="card-body text-center py-4">
        <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
        <p class="text-muted">No stitching orders found for this tailor</p>
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
