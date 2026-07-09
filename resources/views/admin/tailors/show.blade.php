@extends('admin.layouts.app')

@section('title', 'Tailor Profile - ' . $tailor->user->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.tailors.index') }}">Tailors</a></li>
    <li class="breadcrumb-item active">{{ $tailor->user->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    @if($tailor->profile_image)
                    <img src="{{ $tailor->profile_image_url }}" alt="{{ $tailor->user->name }}" 
                         class="rounded-circle me-3" width="80" height="80" style="object-fit: cover;">
                    @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user text-muted fa-2x"></i>
                    </div>
                    @endif
                    <div>
                        <h1 class="page-title mb-0">{{ $tailor->user->name }}</h1>
                        <p class="text-muted mb-0">{{ $tailor->specialization ?? 'Tailor' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.tailors.edit', $tailor) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Tailor
                    </a>
                    <a href="{{ route('admin.tailors.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Profile Info -->
        <div class="col-lg-4">
            <!-- Profile Information -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i>Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <p class="mb-0"><strong>{{ $tailor->user->name }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="mb-0"><a href="mailto:{{ $tailor->user->email }}">{{ $tailor->user->email }}</a></p>
                    </div>
                    @if($tailor->phone)
                    <div class="mb-3">
                        <label class="form-label text-muted">Phone</label>
                        <p class="mb-0"><a href="tel:{{ $tailor->phone }}">{{ $tailor->phone }}</a></p>
                    </div>
                    @endif
                    @if($tailor->specialization)
                    <div class="mb-3">
                        <label class="form-label text-muted">Specialization</label>
                        <p class="mb-0"><span class="badge bg-light text-dark">{{ $tailor->specialization }}</span></p>
                    </div>
                    @endif
                    @if($tailor->experience_years)
                    <div class="mb-3">
                        <label class="form-label text-muted">Experience</label>
                        <p class="mb-0"><strong>{{ $tailor->experience_years }} years</strong></p>
                    </div>
                    @endif
                    <div>
                        <label class="form-label text-muted">Status</label>
                        <p class="mb-0">
                            @if($tailor->status === 'active')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                            @elseif($tailor->status === 'on_leave')
                            <span class="badge bg-warning">
                                <i class="fas fa-clock me-1"></i>On Leave
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>Inactive
                            </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Dashboard Data
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-primary">{{ $tailor->total_assigned ?? 0 }}</h2>
                        <p class="text-muted mb-0">Total Assigned Orders</p>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h3 class="text-success">{{ $tailor->completed ?? 0 }}</h3>
                            <p class="text-muted mb-0"><small>Completed</small></p>
                        </div>
                        <div class="col-6 mb-3">
                            <h3 class="text-warning">{{ $tailor->pending ?? 0 }}</h3>
                            <p class="text-muted mb-0"><small>Pending</small></p>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <p class="text-muted mb-1"><small>Completion Rate</small></p>
                        @php
                        $rate = ($tailor->total_assigned ?? 0) > 0 ? (($tailor->completed ?? 0) / ($tailor->total_assigned ?? 0) * 100) : 0;
                        @endphp
                        <h4 class="text-info">{{ number_format($rate, 1) }}%</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Details & Orders -->
        <div class="col-lg-8">
            <!-- Bio & Skills -->
            @if($tailor->bio || ($tailor->skills && is_array($tailor->skills)))
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>About & Skills
                    </h5>
                </div>
                <div class="card-body">
                    @if($tailor->bio)
                    <div class="mb-3">
                        <label class="form-label text-muted">Bio</label>
                        <p class="mb-0">{{ $tailor->bio }}</p>
                    </div>
                    @endif
                    @if($tailor->skills && is_array($tailor->skills) && count($tailor->skills) > 0)
                    <div>
                        <label class="form-label text-muted">Skills</label>
                        <div>
                            @foreach($tailor->skills as $skill)
                            <span class="badge bg-info me-1 mb-1">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Recent Orders -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Recent Stitching Orders
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="fw-bold">Order</th>
                                <th class="fw-bold">Garment Type</th>
                                <th class="fw-bold text-center">Status</th>
                                <th class="fw-bold text-center">Cost</th>
                                <th class="fw-bold">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->id }}</strong>
                                </td>
                                <td>
                                    {{ $order->garment_type ?? 'N/A' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $order->status_badge }}">
                                        {{ $order->status_text }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    ₹{{ number_format($order->estimated_cost, 2) }}
                                </td>
                                <td>
                                    <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>No stitching orders assigned</p>
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
</style>
@endsection
