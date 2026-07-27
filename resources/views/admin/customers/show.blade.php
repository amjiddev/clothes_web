@extends('admin.layouts.app')

@section('title', 'Customer Profile - ' . $customer->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">{{ $customer->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    @if($customer->profile_photo_path)
                    <img src="{{ $customer->profile_photo_url }}" alt="{{ $customer->name }}" 
                         class="rounded-circle me-3" width="80" height="80" style="object-fit: cover;">
                    @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user text-muted fa-2x"></i>
                    </div>
                    @endif
                    <div>
                        <h1 class="page-title mb-0">{{ $customer->name }}</h1>
                        <p class="text-muted mb-0">{{ $customer->email }}</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    @can('edit_customers')
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Customer
                    </a>
                    @endcan
                    @can('block_customers')
                    @if($customer->is_blocked)
                    <form action="{{ route('admin.customers.unblock', $customer) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Unblock Customer
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.customers.block', $customer) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to block this customer?')">
                            <i class="fas fa-ban me-2"></i>Block Customer
                        </button>
                    </form>
                    @endif
                    @endcan
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

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column: Profile Info & Stats -->
        <div class="col-lg-4">
            <!-- Profile Information Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i>Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <p class="mb-0"><strong>{{ $customer->name }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="mb-0"><strong>{{ $customer->email }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Member Since</label>
                        <p class="mb-0"><strong>{{ $customer->created_at->format('M d, Y') }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Last Login</label>
                        <p class="mb-0">
                            @if($customer->last_login_at)
                            <strong>{{ $customer->last_login_at->diffForHumans() }}</strong>
                            @else
                            <span class="text-muted">Never</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="form-label text-muted">Status</label>
                        @if($customer->is_blocked)
                        <p class="mb-0">
                            <span class="badge bg-danger">
                                <i class="fas fa-ban me-1"></i>Blocked
                            </span>
                        </p>
                        @else
                        <p class="mb-0">
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-primary">{{ $stats['total_orders'] }}</h2>
                        <p class="text-muted mb-0">Total Orders</p>
                    </div>
                    <hr>
                    <div class="text-center mb-4">
                        <h3 class="text-success">Rs. {{ number_format($stats['total_spending'], 2) }}</h3>
                        <p class="text-muted mb-0">Total Spending</p>
                    </div>
                    <hr>
                    <div class="text-center mb-4">
                        <h3 class="text-info">Rs. {{ number_format($stats['average_order_value'], 2) }}</h3>
                        <p class="text-muted mb-0">Average Order Value</p>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <p class="mb-1">{{ $stats['completed_orders'] }}</p>
                            <p class="text-muted mb-0"><small>Completed</small></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1">{{ $stats['pending_orders'] }}</p>
                            <p class="text-muted mb-0"><small>Pending</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Orders, Measurements, Stitching -->
        <div class="col-lg-8">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                        <i class="fas fa-shopping-cart me-2"></i>Orders ({{ $stats['total_orders'] }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="measurements-tab" data-bs-toggle="tab" data-bs-target="#measurements" type="button" role="tab">
                        <i class="fas fa-ruler-combined me-2"></i>Measurements ({{ $stats['total_measurements'] }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="stitching-tab" data-bs-toggle="tab" data-bs-target="#stitching" type="button" role="tab">
                        <i class="fas fa-needle me-2"></i>Stitching ({{ $stats['stitching_orders'] }})
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Orders Tab -->
                <div class="tab-pane fade show active" id="orders" role="tabpanel">
                    <div class="card border-0 shadow">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="fw-bold">Order #</th>
                                        <th class="fw-bold">Type</th>
                                        <th class="fw-bold text-end">Total</th>
                                        <th class="fw-bold text-center">Status</th>
                                        <th class="fw-bold text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customer->orders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $order->type }}</span>
                                        </td>
                                        <td class="text-end">
                                            <strong>Rs. {{ number_format($order->total, 2) }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $order->status_badge }}">
                                                {{ $order->status_text }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p>No orders yet</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Measurements Tab -->
                <div class="tab-pane fade" id="measurements" role="tabpanel">
                    <div class="card border-0 shadow">
                        @if($customer->measurements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="fw-bold">Title</th>
                                        <th class="fw-bold text-center">Chest</th>
                                        <th class="fw-bold text-center">Waist</th>
                                        <th class="fw-bold text-center">Hips</th>
                                        <th class="fw-bold text-center">Sleeve</th>
                                        <th class="fw-bold text-center">Default</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->measurements as $measurement)
                                    <tr>
                                        <td>
                                            <strong>{{ $measurement->title }}</strong>
                                            @if($measurement->is_default)
                                            <br><span class="badge bg-success">Default</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $measurement->chest }} cm</td>
                                        <td class="text-center">{{ $measurement->waist }} cm</td>
                                        <td class="text-center">{{ $measurement->hips }} cm</td>
                                        <td class="text-center">{{ $measurement->sleeve_length }} cm</td>
                                        <td class="text-center">
                                            @if($measurement->is_default)
                                            <i class="fas fa-check text-success"></i>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-ruler-combined fa-3x mb-3"></i>
                            <p>No measurements saved</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Stitching Orders Tab -->
                <div class="tab-pane fade" id="stitching" role="tabpanel">
                    <div class="card border-0 shadow">
                        @if($stitchingOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="fw-bold">Garment Type</th>
                                        <th class="fw-bold">Service</th>
                                        <th class="fw-bold text-end">Cost</th>
                                        <th class="fw-bold text-center">Status</th>
                                        <th class="fw-bold text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stitchingOrders as $stitching)
                                    <tr>
                                        <td>
                                            <strong>{{ $stitching->garment_type ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $stitching->service_option ?? 'Custom' }}</span>
                                        </td>
                                        <td class="text-end">
                                            <strong>Rs. {{ number_format($stitching->estimated_cost, 2) }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $stitching->status_badge }}">
                                                {{ $stitching->status_text }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted">{{ $stitching->created_at->format('M d, Y') }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-needle fa-3x mb-3"></i>
                            <p>No stitching orders</p>
                        </div>
                        @endif
                    </div>
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

.card {
    border-radius: 0.5rem;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 3px solid transparent;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #000;
    border-color: #000;
    background-color: transparent;
}

.nav-tabs .nav-link:hover {
    color: #000;
}
</style>
@endsection
