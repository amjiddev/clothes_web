@extends('receptionist.layouts.app')

@section('title', 'Tailor Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.tailors.index') }}">Tailors</a></li>
    <li class="breadcrumb-item active">{{ $tailor->user->name }}</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-user-tie me-2"></i>{{ $tailor->user->name }}
            </h1>
            <p class="text-muted">Tailor Profile & Statistics</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.tailors.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Tailor Information -->
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="card border-0 shadow mb-4">
            <div class="card-body text-center">
                @if($tailor->profile_image_url)
                <img src="{{ $tailor->profile_image_url }}" alt="{{ $tailor->user->name }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                    <i class="fas fa-user-tie fa-3x text-muted"></i>
                </div>
                @endif
                
                <h5 class="card-title mb-1">{{ $tailor->user->name }}</h5>
                
                @if($tailor->status === 'active')
                <span class="badge bg-success mb-3">
                    <i class="fas fa-check-circle me-1"></i>Active
                </span>
                @elseif($tailor->status === 'on_leave')
                <span class="badge bg-warning mb-3">
                    <i class="fas fa-calendar-times me-1"></i>On Leave
                </span>
                @else
                <span class="badge bg-danger mb-3">
                    <i class="fas fa-times-circle me-1"></i>Inactive
                </span>
                @endif

                <p class="text-muted small mb-3">ID: #{{ $tailor->user_id }}</p>

                <hr>

                <div class="text-start">
                    <p class="mb-2">
                        <strong class="text-muted">Phone:</strong><br>
                        <a href="tel:{{ $tailor->phone }}" class="text-primary">{{ $tailor->phone }}</a>
                    </p>
                    <p class="mb-2">
                        <strong class="text-muted">Email:</strong><br>
                        <a href="mailto:{{ $tailor->user->email }}" class="text-primary">{{ $tailor->user->email }}</a>
                    </p>
                    <p class="mb-2">
                        <strong class="text-muted">Specialization:</strong><br>
                        <span class="badge bg-light text-dark">{{ $tailor->specialization ?? 'General Tailoring' }}</span>
                    </p>
                    <p class="mb-0">
                        <strong class="text-muted">Experience:</strong><br>
                        {{ $tailor->experience_years ?? 0 }} years
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow">
            <div class="card-body">
                <a href="{{ route('receptionist.tailors.tailor-orders', $tailor) }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-tasks me-2"></i>View Orders
                </a>
                <a href="{{ route('receptionist.tailors.view-dashboard', $tailor) }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-chart-line me-2"></i>View Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics & Details -->
    <div class="col-lg-8">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-muted small mb-1">Active Orders</p>
                                <h3 class="mb-0 text-primary fw-bold">{{ $activeOrders }}</h3>
                            </div>
                            <div class="text-primary" style="font-size: 2.5rem;">
                                <i class="fas fa-spinner"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-muted small mb-1">Completed Orders</p>
                                <h3 class="mb-0 text-success fw-bold">{{ $completedOrders }}</h3>
                            </div>
                            <div class="text-success" style="font-size: 2.5rem;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-muted small mb-1">Pending Orders</p>
                                <h3 class="mb-0 text-warning fw-bold">{{ $pendingOrders }}</h3>
                            </div>
                            <div class="text-warning" style="font-size: 2.5rem;">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-muted small mb-1">Total Assigned</p>
                                <h3 class="mb-0 text-info fw-bold">{{ $activeOrders + $completedOrders + $pendingOrders }}</h3>
                            </div>
                            <div class="text-info" style="font-size: 2.5rem;">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bio/Skills -->
        @if($tailor->bio || $tailor->skills)
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-file-alt me-2"></i>Profile Information
                </h6>
            </div>
            <div class="card-body">
                @if($tailor->bio)
                <div class="mb-3">
                    <h6 class="fw-bold text-muted">Bio</h6>
                    <p>{{ $tailor->bio }}</p>
                </div>
                @endif

                @if($tailor->skills && is_array($tailor->skills) && count($tailor->skills) > 0)
                <div>
                    <h6 class="fw-bold text-muted">Skills</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($tailor->skills as $skill)
                        <span class="badge bg-light text-dark">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Recent Assignments -->
        @if($recentAssignments->count() > 0)
        <div class="card border-0 shadow">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-history me-2"></i>Recent Assignments
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Order</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAssignments as $assignment)
                        <tr>
                            <td class="ps-4">
                                <strong>#{{ $assignment->order->order_number }}</strong>
                            </td>
                            <td>{{ $assignment->order->user->name }}</td>
                            <td>
                                <span class="badge bg-{{ $assignment->status_badge }}">
                                    {{ $assignment->status_text }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('receptionist.orders.show', $assignment->order) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="card border-0 shadow">
            <div class="card-body text-center text-muted py-4">
                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                <p>No orders assigned yet</p>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

.card {
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endsection
