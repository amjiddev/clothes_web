@extends('receptionist.layouts.app')

@section('title', 'Measurement Details - ' . $measurement->profile_name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.measurements.index') }}">Measurements</a></li>
    <li class="breadcrumb-item active">{{ $measurement->profile_name }}</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-ruler me-2"></i>{{ $measurement->profile_name }}
            </h1>
            <p class="text-muted">{{ $measurement->user->name }} ({{ $measurement->user->email }})</p>
        </div>
        <div class="col-auto">
            @if(!$measurement->is_default)
            <a href="{{ route('receptionist.measurements.setDefault', $measurement) }}" class="btn btn-outline-success">
                <i class="fas fa-check me-2"></i>Set as Default
            </a>
            @else
            <span class="badge bg-success p-2">
                <i class="fas fa-star me-1"></i>Default Profile
            </span>
            @endif
            <a href="{{ route('receptionist.measurements.edit', $measurement) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('receptionist.measurements.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
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

<div class="row">
    <div class="col-lg-8">
        <!-- Upper Body Measurements -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-person me-2"></i>Upper Body Measurements
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Chest</small>
                        <h5 class="mb-0">
                            {{ $measurement->chest ?? '—' }}
                            @if($measurement->chest)
                            <small class="text-muted">cm</small>
                            @endif
                        </h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Shoulder</small>
                        <h5 class="mb-0">
                            {{ $measurement->shoulder ?? '—' }}
                            @if($measurement->shoulder)
                            <small class="text-muted">cm</small>
                            @endif
                        </h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Sleeve Length</small>
                        <h5 class="mb-0">
                            {{ $measurement->sleeve_length ?? '—' }}
                            @if($measurement->sleeve_length)
                            <small class="text-muted">cm</small>
                            @endif
                        </h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Shirt Length</small>
                        <h5 class="mb-0">
                            {{ $measurement->shirt_length ?? '—' }}
                            @if($measurement->shirt_length)
                            <small class="text-muted">cm</small>
                            @endif
                        </h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Neck</small>
                        <h5 class="mb-0">
                            {{ $measurement->neck ?? '—' }}
                            @if($measurement->neck)
                            <small class="text-muted">cm</small>
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lower Body Measurements -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-person me-2"></i>Lower Body Measurements
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Waist</small>
                        <h5 class="mb-0">
                            {{ $measurement->waist ?? '—' }}
                            @if($measurement->waist)
                            <small class="text-muted">cm</small>
                            @endif
                        </h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Trouser Length</small>
                        <h5 class="mb-0">
                            {{ $measurement->trouser_length ?? '—' }}
                            @if($measurement->trouser_length)
                            <small class="text-muted">cm</small>
                            @endif
                        </h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Bottom/Hip</small>
                        <h5 class="mb-0">
                            {{ $measurement->bottom ?? '—' }}
                            @if($measurement->bottom)
                            <small class="text-muted">cm</small>
                            @endif
                        </h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block mb-1">Thigh</small>
                        <h5 class="mb-0">
                            {{ $measurement->thigh ?? '—' }}
                            @if($measurement->thigh)
                            <small class="text-muted">cm</small>
                            @endif
                        </h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Cuff Size</small>
                        <h5 class="mb-0">
                            {{ $measurement->cuff_size ?? '—' }}
                            @if($measurement->cuff_size)
                            <small class="text-muted">cm</small>
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        @if($measurement->design_image || $measurement->special_instructions || $measurement->notes)
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-plus-circle me-2"></i>Additional Information
                </h6>
            </div>
            <div class="card-body">
                @if($measurement->design_image)
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-image me-2"></i>Design Image
                    </h6>
                    <img src="{{ asset('storage/' . $measurement->design_image) }}" alt="Design" 
                         class="img-fluid rounded border" style="max-width: 100%; max-height: 400px;">
                </div>
                @endif

                @if($measurement->special_instructions)
                <div class="mb-3">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-comment me-2"></i>Special Instructions
                    </h6>
                    <p class="mb-0">{{ $measurement->special_instructions }}</p>
                </div>
                @endif

                @if($measurement->notes)
                <div class="mb-3">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-sticky-note me-2"></i>Notes
                    </h6>
                    <p class="mb-0">{{ $measurement->notes }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Used in Orders -->
        @if($measurement->stitchingOrders->count() > 0)
        <div class="card border-0 shadow">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-shopping-bag me-2"></i>Used in Orders ({{ $measurement->stitchingOrders->count() }})
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Status</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($measurement->stitchingOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('receptionist.orders.show', $order->order) }}">
                                    {{ $order->order->order_number }}
                                </a>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'secondary',
                                        'assigned' => 'info',
                                        'in_progress' => 'primary',
                                        'ready_for_fitting' => 'warning',
                                        'in_fitting' => 'info',
                                        'ready' => 'success',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$order->stitching_status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->stitching_status)) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Customer Information -->
        <div class="card border-0 shadow mb-3">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-user me-2"></i>Customer Information
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <small class="text-muted d-block">Name</small>
                    <strong>{{ $measurement->user->name }}</strong>
                </p>
                <p class="mb-2">
                    <small class="text-muted d-block">Email</small>
                    <strong>{{ $measurement->user->email }}</strong>
                </p>
                <p>
                    <small class="text-muted d-block">Phone</small>
                    <strong>{{ $measurement->user->phone ?? 'Not provided' }}</strong>
                </p>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="card border-0 shadow mb-3">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle me-2"></i>Profile Information
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <small class="text-muted d-block">Created Date</small>
                    <strong>{{ $measurement->created_at->format('M d, Y') }}</strong>
                </p>
                <p class="mb-2">
                    <small class="text-muted d-block">Created At</small>
                    <strong>{{ $measurement->created_at->format('h:i A') }}</strong>
                </p>
                <p class="mb-2">
                    <small class="text-muted d-block">Last Updated</small>
                    <strong>{{ $measurement->updated_at->format('M d, Y h:i A') }}</strong>
                </p>
                <p>
                    <small class="text-muted d-block">Status</small>
                    @if($measurement->is_default)
                    <span class="badge bg-success">
                        <i class="fas fa-star me-1"></i>Default
                    </span>
                    @else
                    <span class="badge bg-secondary">Regular</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card border-0 shadow mb-3">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-chart-bar me-2"></i>Quick Stats
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <small class="text-muted d-block">Used in Orders</small>
                    <h5>{{ $measurement->stitchingOrders->count() }}</h5>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card border-0 shadow mb-3">
            <div class="card-body">
                <a href="{{ route('receptionist.measurements.edit', $measurement) }}" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-edit me-2"></i>Edit Profile
                </a>
                <a href="{{ route('receptionist.measurements.duplicate', $measurement) }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-copy me-2"></i>Duplicate
                </a>
                <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-2"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title">Delete Measurement Profile</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('receptionist.measurements.destroy', $measurement) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="mb-0">
                        Are you sure you want to delete this measurement profile?<br>
                        <strong>{{ $measurement->profile_name }}</strong><br>
                        This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash me-1"></i>Delete Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}
</style>
@endsection
