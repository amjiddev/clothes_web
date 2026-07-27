@extends('receptionist.layouts.app')

@section('title', $customer->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">{{ $customer->name }}</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-user-circle me-2"></i>{{ $customer->name }}
            </h1>
            <p class="text-muted">View customer details and history</p>
        </div>
        <div class="col-auto">
            <div class="btn-group" role="group">
                <a href="{{ route('receptionist.customers.edit', $customer) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                <a href="{{ route('receptionist.orders.create', ['customer_id' => $customer->id]) }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Create Order
                </a>
            </div>
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

<div class="row">
    <!-- Customer Information Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Customer Information
                </h5>
            </div>
            <div class="card-body">
                <!-- Customer ID -->
                <div class="mb-3">
                    <small class="text-muted d-block fw-bold">Customer ID</small>
                    <code class="bg-light px-2 py-1">{{ $customer->id }}</code>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <small class="text-muted d-block fw-bold">Email</small>
                    <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                        {{ $customer->email }}
                    </a>
                </div>

                <!-- City -->
                <div class="mb-3">
                    <small class="text-muted d-block fw-bold">City</small>
                    <p class="mb-0">{{ $customer->addresses()->first()?->city ?? '-' }}</p>
                </div>

                <!-- Address -->
                <div class="mb-3">
                    <small class="text-muted d-block fw-bold">Address</small>
                    <p class="mb-0">{{ $customer->addresses()->first()?->address ?? '-' }}</p>
                </div>

                <!-- Joined Date -->
                <div class="mb-3">
                    <small class="text-muted d-block fw-bold">Joined</small>
                    <p class="mb-0">{{ $customer->created_at->format('M d, Y') }}</p>
                </div>

                <!-- Status -->
                <div class="mb-0">
                    <small class="text-muted d-block fw-bold">Status</small>
                    @if($customer->is_blocked)
                        <span class="badge bg-danger">
                            <i class="fas fa-ban me-1"></i>Blocked
                        </span>
                    @else
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>Active
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="card border-0 shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block fw-bold">Total Orders</small>
                    <h4 class="mb-0">{{ $totalOrders }}</h4>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block fw-bold">Completed Orders</small>
                    <h4 class="mb-0">{{ $completedOrders }}</h4>
                </div>

                <div class="mb-0">
                    <small class="text-muted d-block fw-bold">Total Spent</small>
                    <h4 class="mb-0">Rs. {{ number_format($totalSpent, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Content -->
    <div class="col-lg-8">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" 
                        type="button" role="tab" aria-controls="orders" aria-selected="true">
                    <i class="fas fa-shopping-bag me-2"></i>Order History
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="measurements-tab" data-bs-toggle="tab" data-bs-target="#measurements" 
                        type="button" role="tab" aria-controls="measurements" aria-selected="false">
                    <i class="fas fa-ruler-vertical me-2"></i>Measurements
                </button>
            </li>
        </ul>

        <!-- Orders Tab -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                <div class="card border-0 shadow">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Order ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst(str_replace('_', ' ', $order->type ?? 'custom')) }}
                                        </span>
                                    </td>
                                    <td>Rs. {{ number_format($order->total ?? 0, 2) }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status) {
                                                'pending' => 'bg-warning',
                                                'confirmed' => 'bg-info',
                                                'in_progress' => 'bg-primary',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('receptionist.orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                                        <p class="text-muted">No orders found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Measurements Tab -->
            <div class="tab-pane fade" id="measurements" role="tabpanel" aria-labelledby="measurements-tab">
                <div class="card border-0 shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-ruler-vertical me-2"></i>Measurement Profiles
                        </h5>
                        <a href="{{ route('receptionist.measurements.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Add Measurement
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Profile Name</th>
                                    <th>Chest</th>
                                    <th>Waist</th>
                                    <th>Length</th>
                                    <th>Created Date</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($measurements as $measurement)
                                <tr>
                                    <td class="ps-4 fw-bold">
                                        @if($measurement->is_default)
                                            <i class="fas fa-star text-warning me-2"></i>
                                        @endif
                                        {{ $measurement->title ?? 'Default' }}
                                    </td>
                                    <td>{{ $measurement->chest ?? '-' }} cm</td>
                                    <td>{{ $measurement->waist ?? '-' }} cm</td>
                                    <td>{{ $measurement->length ?? '-' }} cm</td>
                                    <td>{{ $measurement->created_at->format('M d, Y') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('receptionist.measurements.show', $measurement) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                                        <p class="text-muted">No measurements found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

.nav-tabs .nav-link {
    color: #495057;
    border-color: transparent;
}

.nav-tabs .nav-link.active {
    background-color: #f8f9fa;
    border-bottom-color: #d4af37;
    color: #d4af37;
}
</style>
@endsection
