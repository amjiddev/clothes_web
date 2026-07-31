@extends('admin.layouts.app')

@section('title', 'Orders Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-shopping-bag me-2"></i>Order Management</h1>
                <p class="text-muted">Manage all customer orders</p>
            </div>
            <div class="col-auto">
                @can('create_orders')
                <a href="{{ route('admin.orders.create') }}" class="btn btn-dark">
                    <i class="fas fa-plus me-2"></i>Create Order
                </a>
                @endcan
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
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                <!-- Search Input -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Order # or customer..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Order Type Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="ready_made" {{ request('type') === 'ready_made' ? 'selected' : '' }}>Cloth Only</option>
                        <option value="stitching" {{ request('type') === 'stitching' ? 'selected' : '' }}>Stitching Only</option>
                        <option value="combined" {{ request('type') === 'combined' ? 'selected' : '' }}>Cloth + Stitching</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Processing</option>
                        <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Payment Status Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Payment</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All</option>
                        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="col-md-1 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="fw-bold">
                            <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['sort_by' => 'order_number'])) }}" class="text-decoration-none text-dark">
                                Order ID <i class="fas fa-arrow-down fa-xs"></i>
                            </a>
                        </th>
                        <th class="fw-bold">Customer</th>
                        <th class="fw-bold text-center">Type</th>
                        <th class="fw-bold text-center">Products</th>
                        <th class="fw-bold text-end">Amount</th>
                        <th class="fw-bold text-center">Payment</th>
                        <th class="fw-bold text-center">Status</th>
                        <th class="fw-bold">Date</th>
                        <th class="fw-bold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <!-- Order ID -->
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-bold text-dark">
                                {{ $order->order_number }}
                            </a>
                        </td>

                        <!-- Customer -->
                        <td>
                            <div>
                                <strong>{{ $order->user->name }}</strong><br>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </div>
                        </td>

                        <!-- Order Type -->
                        <td class="text-center">
                            @if($order->type === 'ready_made')
                            <span class="badge bg-info"><i class="fas fa-shirt me-1"></i>Cloth Only</span>
                            @elseif($order->type === 'stitching')
                            <span class="badge bg-warning"><i class="fas fa-needle me-1"></i>Stitching Only</span>
                            @else
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Cloth + Stitching</span>
                            @endif
                        </td>

                        <!-- Products Count -->
                        <td class="text-center">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-box me-1"></i>{{ $order->total_products ?? $order->orderItems->sum('quantity') }}
                            </span>
                        </td>

                        <!-- Amount -->
                        <td class="text-end">
                            <strong class="text-dark">Rs. {{ number_format($order->total, 2) }}</strong>
                        </td>

                        <!-- Payment Status -->
                        <td class="text-center">
                            @if($order->payment_status === 'pending')
                            <span class="badge bg-warning">
                                <i class="fas fa-clock me-1"></i>Pending
                            </span>
                            @elseif($order->payment_status === 'paid')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Paid
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>Failed
                            </span>
                            @endif
                        </td>

                        <!-- Order Status -->
                        <td class="text-center">
                            <span class="badge bg-{{ $order->status_badge }}">
                                {{ $order->getStatusTextAttribute() }}
                            </span>
                        </td>

                        <!-- Date -->
                        <td>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small><br>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('edit_orders')
                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @if(in_array($order->status, ['pending', 'cancelled']))
                                @can('delete_orders')
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" 
                                            onclick="return confirm('Are you sure you want to delete this order?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No orders found.</p>
                                @can('create_orders')
                                <a href="{{ route('admin.orders.create') }}" class="btn btn-dark btn-sm">
                                    <i class="fas fa-plus me-2"></i>Create First Order
                                </a>
                                @endcan
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
                        Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
                    </small>
                </div>
                <div class="col-auto">
                    {{ $orders->links() }}
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
