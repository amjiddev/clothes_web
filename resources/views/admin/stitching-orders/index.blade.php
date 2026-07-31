@extends('admin.layouts.app')

@section('title', 'Stitching Orders Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Stitching Orders</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-needle me-2"></i>Stitching Orders</h1>
                <p class="text-muted">Manage all tailoring and stitching orders</p>
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
            <form action="{{ route('admin.stitching-orders.index') }}" method="GET" class="row g-3">
                <!-- Search Input -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Order # or customer..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Service Type Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Service Type</label>
                    <select name="service_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="Shalwar Kameez" {{ request('service_type') === 'Shalwar Kameez' ? 'selected' : '' }}>Shalwar Kameez</option>
                        <option value="Suit" {{ request('service_type') === 'Suit' ? 'selected' : '' }}>Suit</option>
                        <option value="Kurta" {{ request('service_type') === 'Kurta' ? 'selected' : '' }}>Kurta</option>
                        <option value="Waistcoat" {{ request('service_type') === 'Waistcoat' ? 'selected' : '' }}>Waistcoat</option>
                        <option value="Sherwani" {{ request('service_type') === 'Sherwani' ? 'selected' : '' }}>Sherwani</option>
                        <option value="Custom" {{ request('service_type') === 'Custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>

                <!-- Tailor Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Tailor</label>
                    <select name="tailor_id" class="form-select">
                        <option value="">All Tailors</option>
                        @forelse(\App\Models\User::role('tailor')->get() as $tailor)
                        <option value="{{ $tailor->id }}" {{ request('tailor_id') == $tailor->id ? 'selected' : '' }}>{{ $tailor->name }}</option>
                        @empty
                        <option disabled>No tailors available</option>
                        @endforelse
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="ready_for_fitting" {{ request('status') === 'ready_for_fitting' ? 'selected' : '' }}>Fitting</option>
                        <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <a href="{{ route('admin.stitching-orders.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stitching Orders Table -->
    <div class="card border-0 shadow">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="fw-bold">Order ID</th>
                        <th class="fw-bold">Customer</th>
                        <th class="fw-bold text-center">Service Type</th>
                        <th class="fw-bold">Tailor</th>
                        <th class="fw-bold text-center">Measurement</th>
                        <th class="fw-bold">Delivery Date</th>
                        <th class="fw-bold text-center">Status</th>
                        <th class="fw-bold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stitchingOrders as $stitchingOrder)
                    <tr>
                        <!-- Order ID -->
                        <td>
                            <a href="{{ route('admin.stitching-orders.show', $stitchingOrder) }}" class="text-decoration-none fw-bold text-dark">
                                {{ $stitchingOrder->order->order_number }}
                            </a>
                        </td>

                        <!-- Customer -->
                        <td>
                            <div>
                                <strong>{{ $stitchingOrder->order->user->name }}</strong><br>
                                <small class="text-muted">{{ $stitchingOrder->order->user->email }}</small>
                            </div>
                        </td>

                        <!-- Service Type -->
                        <td class="text-center">
                            <span class="badge bg-info">
                                <i class="fas fa-needle me-1"></i>{{ $stitchingOrder->garment_type }}
                            </span>
                        </td>

                        <!-- Tailor -->
                        <td>
                            @if($stitchingOrder->tailor)
                            <div>
                                <strong>{{ $stitchingOrder->tailor->name }}</strong><br>
                                <small class="text-muted">Assigned: {{ $stitchingOrder->assigned_date?->format('M d') ?? 'N/A' }}</small>
                            </div>
                            @else
                            <span class="badge bg-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>Unassigned
                            </span>
                            @endif
                        </td>

                        <!-- Measurement -->
                        <td class="text-center">
                            @if($stitchingOrder->measurement)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Assigned
                            </span>
                            @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-times-circle me-1"></i>None
                            </span>
                            @endif
                        </td>

                        <!-- Delivery Date -->
                        <td>
                            @if($stitchingOrder->order->delivery_date)
                            <small class="text-muted">{{ $stitchingOrder->order->delivery_date->format('M d, Y') }}</small>
                            @else
                            <small class="text-muted text-danger">Not set</small>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            <span class="badge bg-{{ $stitchingOrder->status_badge }}">
                                {{ $stitchingOrder->getStatusTextAttribute() }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.stitching-orders.show', $stitchingOrder) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($stitchingOrder->canAssignToTailor())
                                @can('assign_stitching_orders')
                                <a href="{{ route('admin.stitching-orders.assign-tailor', $stitchingOrder) }}" class="btn btn-sm btn-primary" title="Assign Tailor">
                                    <i class="fas fa-user-tie"></i>
                                </a>
                                @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No stitching orders found.</p>
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
                        Showing {{ $stitchingOrders->firstItem() ?? 0 }} to {{ $stitchingOrders->lastItem() ?? 0 }} of {{ $stitchingOrders->total() }} stitching orders
                    </small>
                </div>
                <div class="col-auto">
                    {{ $stitchingOrders->links() }}
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
