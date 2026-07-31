@extends('receptionist.layouts.app')

@section('title', 'Assignment Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.tailors.index') }}">Tailors</a></li>
    <li class="breadcrumb-item active">Assignment Details</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-check-circle me-2"></i>Assignment Confirmed
            </h1>
            <p class="text-muted">Stitching order has been successfully assigned to tailor</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.tailors.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<!-- Success Alert -->
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Success!</strong> Stitching order has been assigned to {{ $stitchingOrder->tailor->name }} successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<div class="row">
    <!-- Assignment Summary -->
    <div class="col-lg-8">
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-success text-white border-0">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-tasks me-2"></i>Assignment Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted small fw-bold mb-2">Order Number</h6>
                        <p class="h5 mb-0">{{ $stitchingOrder->order->order_number }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted small fw-bold mb-2">Status</h6>
                        <p class="h5 mb-0">
                            <span class="badge bg-info">{{ $stitchingOrder->status_text }}</span>
                        </p>
                    </div>
                </div>

                <hr>

                <!-- Order Details -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="fw-bold mb-3">Order Information</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Customer Name</p>
                        <p class="fw-bold">{{ $stitchingOrder->order->user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Email</p>
                        <p class="fw-bold">
                            <a href="mailto:{{ $stitchingOrder->order->user->email }}">{{ $stitchingOrder->order->user->email }}</a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Phone</p>
                        <p class="fw-bold">
                            <a href="tel:{{ $stitchingOrder->order->user->phone }}">{{ $stitchingOrder->order->user->phone }}</a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Garment Type</p>
                        <p class="fw-bold">{{ $stitchingOrder->garment_type ?? 'Custom Stitching' }}</p>
                    </div>
                </div>

                <hr>

                <!-- Tailor Assignment -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="fw-bold mb-3">Assigned Tailor</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Tailor Name</p>
                        <p class="h5 fw-bold">{{ $stitchingOrder->tailor->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Contact</p>
                        <p class="fw-bold">
                            <a href="tel:{{ $stitchingOrder->tailor->phone }}">{{ $stitchingOrder->tailor->phone ?? 'N/A' }}</a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Specialization</p>
                        @php
                        $tailorRecord = \App\Models\Tailor::where('user_id', $stitchingOrder->tailor_id)->first();
                        @endphp
                        <p class="fw-bold"><span class="badge bg-light text-dark">{{ $tailorRecord->specialization ?? 'General' }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Assignment Date</p>
                        <p class="fw-bold">{{ $stitchingOrder->assigned_date->format('M d, Y') }}</p>
                    </div>
                </div>

                <hr>

                <!-- Dates -->
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Order Created</p>
                        <p class="fw-bold">{{ $stitchingOrder->created_at->format('M d, Y - h:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Last Updated</p>
                        <p class="fw-bold">{{ $stitchingOrder->updated_at->format('M d, Y - h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Instructions -->
        @if($stitchingOrder->additional_instructions)
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-comment me-2"></i>Special Instructions
                </h6>
            </div>
            <div class="card-body">
                <p>{{ $stitchingOrder->additional_instructions }}</p>
            </div>
        </div>
        @endif

        <!-- Measurement Profile -->
        @if($stitchingOrder->measurement)
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-ruler-combined me-2"></i>Measurement Profile
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-3"><strong>Profile Name:</strong> {{ $stitchingOrder->measurement->profile_name }}</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold small mb-2">Upper Body</h6>
                        <ul class="small list-unstyled">
                            @if($stitchingOrder->measurement->chest)
                            <li>Chest: {{ $stitchingOrder->measurement->chest }} cm</li>
                            @endif
                            @if($stitchingOrder->measurement->shoulder)
                            <li>Shoulder: {{ $stitchingOrder->measurement->shoulder }} cm</li>
                            @endif
                            @if($stitchingOrder->measurement->sleeve_length)
                            <li>Sleeve: {{ $stitchingOrder->measurement->sleeve_length }} cm</li>
                            @endif
                            @if($stitchingOrder->measurement->neck)
                            <li>Neck: {{ $stitchingOrder->measurement->neck }} cm</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold small mb-2">Lower Body</h6>
                        <ul class="small list-unstyled">
                            @if($stitchingOrder->measurement->waist)
                            <li>Waist: {{ $stitchingOrder->measurement->waist }} cm</li>
                            @endif
                            @if($stitchingOrder->measurement->trouser_length)
                            <li>Trouser Length: {{ $stitchingOrder->measurement->trouser_length }} cm</li>
                            @endif
                            @if($stitchingOrder->measurement->bottom)
                            <li>Hip: {{ $stitchingOrder->measurement->bottom }} cm</li>
                            @endif
                            @if($stitchingOrder->measurement->thigh)
                            <li>Thigh: {{ $stitchingOrder->measurement->thigh }} cm</li>
                            @endif
                        </ul>
                    </div>
                </div>

                @if($stitchingOrder->measurement->design_image)
                <hr>
                <div>
                    <p class="small fw-bold mb-2">Design Image</p>
                    <img src="{{ $stitchingOrder->measurement->design_image_url }}" alt="Design" class="img-fluid" style="max-height: 200px;">
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Next Steps -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-tasks me-2"></i>Next Steps
                </h6>
            </div>
            <div class="card-body">
                <ol class="small ps-3">
                    <li class="mb-2">Tailor receives the assigned order</li>
                    <li class="mb-2">Tailor accepts or reviews the order</li>
                    <li class="mb-2">Stitching work begins</li>
                    <li class="mb-2">Regular progress updates</li>
                    <li>Order completion and delivery</li>
                </ol>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <a href="{{ route('receptionist.orders.show', $stitchingOrder->order) }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-eye me-2"></i>View Full Order
                </a>
                <a href="{{ route('receptionist.tailors.show', $stitchingOrder->tailor->tailor) }}" class="btn btn-outline-secondary w-100 mb-2">
                    <i class="fas fa-user-tie me-2"></i>View Tailor Profile
                </a>
                <a href="{{ route('receptionist.tailors.index') }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-list me-2"></i>Back to Tailors
                </a>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card border-0 shadow">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-history me-2"></i>Assignment Timeline
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-success">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="small fw-bold mb-1">Order Assigned</h6>
                            <p class="text-muted small mb-0">{{ $stitchingOrder->assigned_date->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-secondary">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="small fw-bold mb-1">Awaiting Tailor Response</h6>
                            <p class="text-muted small mb-0">Tailor will accept soon</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-light text-secondary">
                            <i class="fas fa-hourglass"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="small fw-bold mb-1">In Progress</h6>
                            <p class="text-muted small mb-0">Coming next</p>
                        </div>
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

.timeline {
    position: relative;
    padding: 1rem 0 0 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
}

.timeline-content {
    padding-left: 0.5rem;
}
</style>
@endsection
