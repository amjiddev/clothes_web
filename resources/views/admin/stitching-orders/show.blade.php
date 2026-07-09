@extends('admin.layouts.app')

@section('title', 'Stitching Order: ' . $stitchingOrder->order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.stitching-orders.index') }}">Stitching Orders</a></li>
    <li class="breadcrumb-item active">{{ $stitchingOrder->order->order_number }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('admin.stitching-orders.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="fas fa-chevron-left me-2"></i>Back to Stitching Orders
                </a>
                <h1 class="page-title"><i class="fas fa-needle me-2"></i>{{ $stitchingOrder->order->order_number }}</h1>
                <p class="text-muted">Tailoring Order Details</p>
            </div>
            <div class="col-auto">
                @if($stitchingOrder->canAssignToTailor())
                @can('assign_stitching_orders')
                <a href="{{ route('admin.stitching-orders.assign-tailor', $stitchingOrder) }}" class="btn btn-primary">
                    <i class="fas fa-user-tie me-2"></i>Assign Tailor
                </a>
                @endcan
                @endif
                @can('update_stitching_status')
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#statusModal">
                    <i class="fas fa-sync me-2"></i>Update Status
                </button>
                @endcan
            </div>
        </div>
    </div>

    <!-- Status Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow text-center">
                <div class="card-body">
                    <div class="text-muted mb-2">Stitching Status</div>
                    <span class="badge bg-{{ $stitchingOrder->status_badge }} fs-6 p-2">
                        {{ $stitchingOrder->getStatusTextAttribute() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow text-center">
                <div class="card-body">
                    <div class="text-muted mb-2">Progress</div>
                    <h5 class="mb-0">{{ $stitchingOrder->progress_percentage }}%</h5>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $stitchingOrder->progress_percentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow text-center">
                <div class="card-body">
                    <div class="text-muted mb-2">Assigned Tailor</div>
                    @if($stitchingOrder->tailor)
                    <h5 class="mb-0">{{ $stitchingOrder->tailor->name }}</h5>
                    @else
                    <span class="badge bg-warning">Unassigned</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow text-center">
                <div class="card-body">
                    <div class="text-muted mb-2">Est. Cost</div>
                    <h5 class="mb-0">Rs. {{ number_format($stitchingOrder->estimated_cost ?? 0, 2) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Customer Information -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-5 text-muted">Name:</dt>
                                <dd class="col-sm-7"><strong>{{ $stitchingOrder->order->user->name }}</strong></dd>

                                <dt class="col-sm-5 text-muted">Email:</dt>
                                <dd class="col-sm-7">{{ $stitchingOrder->order->user->email }}</dd>

                                <dt class="col-sm-5 text-muted">Order #:</dt>
                                <dd class="col-sm-7">
                                    <a href="{{ route('admin.orders.show', $stitchingOrder->order) }}" class="text-decoration-none">
                                        {{ $stitchingOrder->order->order_number }}
                                    </a>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-5 text-muted">Service Type:</dt>
                                <dd class="col-sm-7"><strong>{{ $stitchingOrder->garment_type }}</strong></dd>

                                <dt class="col-sm-5 text-muted">Order Date:</dt>
                                <dd class="col-sm-7">{{ $stitchingOrder->created_at->format('M d, Y') }}</dd>

                                <dt class="col-sm-5 text-muted">Est. Delivery:</dt>
                                <dd class="col-sm-7">
                                    @if($stitchingOrder->order->delivery_date)
                                    {{ $stitchingOrder->order->delivery_date->format('M d, Y') }}
                                    @else
                                    <span class="text-muted">Not set</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fabric Details -->
            @if($stitchingOrder->fabric_details || $stitchingOrder->design_image)
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-palette me-2"></i>Fabric & Design Details</h5>
                </div>
                <div class="card-body">
                    @if($stitchingOrder->fabric_details)
                    <div class="mb-3">
                        <strong>Fabric Details:</strong>
                        <p class="mb-0">{{ $stitchingOrder->fabric_details }}</p>
                    </div>
                    @endif

                    @if($stitchingOrder->design_image)
                    <div class="mb-3">
                        <strong>Design Image:</strong><br>
                        <img src="{{ $stitchingOrder->design_image_url }}" alt="Design" class="img-thumbnail mt-2" style="max-height: 300px;">
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Measurements -->
            @if($stitchingOrder->measurement)
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-ruler me-2"></i>Measurements</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-6 text-muted">Title:</dt>
                                <dd class="col-sm-6"><strong>{{ $stitchingOrder->measurement->title }}</strong></dd>

                                <dt class="col-sm-6 text-muted">Chest:</dt>
                                <dd class="col-sm-6">{{ $stitchingOrder->measurement->chest }}"</dd>

                                <dt class="col-sm-6 text-muted">Waist:</dt>
                                <dd class="col-sm-6">{{ $stitchingOrder->measurement->waist }}"</dd>

                                <dt class="col-sm-6 text-muted">Hips:</dt>
                                <dd class="col-sm-6">{{ $stitchingOrder->measurement->hips }}"</dd>

                                <dt class="col-sm-6 text-muted">Shoulder:</dt>
                                <dd class="col-sm-6">{{ $stitchingOrder->measurement->shoulder }}"</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-6 text-muted">Sleeve Length:</dt>
                                <dd class="col-sm-6">{{ $stitchingOrder->measurement->sleeve_length }}"</dd>

                                <dt class="col-sm-6 text-muted">Torso Length:</dt>
                                <dd class="col-sm-6">{{ $stitchingOrder->measurement->torso_length }}"</dd>

                                <dt class="col-sm-6 text-muted">Inseam:</dt>
                                <dd class="col-sm-6">{{ $stitchingOrder->measurement->inseam }}"</dd>

                                <dt class="col-sm-6 text-muted">Neck:</dt>
                                <dd class="col-sm-6">{{ $stitchingOrder->measurement->neck }}"</dd>
                            </dl>
                        </div>
                    </div>

                    @if($stitchingOrder->measurement->notes)
                    <div class="alert alert-info mt-3">
                        <strong>Measurement Notes:</strong> {{ $stitchingOrder->measurement->notes }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Special Instructions -->
            @if($stitchingOrder->special_instructions || $stitchingOrder->additional_instructions)
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Special Instructions</h5>
                </div>
                <div class="card-body">
                    @if($stitchingOrder->special_instructions)
                    <div class="mb-3">
                        <strong>Customer Instructions:</strong>
                        <p class="mb-0">{{ $stitchingOrder->special_instructions }}</p>
                    </div>
                    @endif

                    @if($stitchingOrder->additional_instructions)
                    <div class="alert alert-light border-start border-3 border-info">
                        <strong>Additional Notes:</strong><br>
                        {{ $stitchingOrder->additional_instructions }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Tailor Notes -->
            @if($stitchingOrder->tailor_notes)
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Tailor Notes</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $stitchingOrder->tailor_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-timeline me-2"></i>Stitching Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($stitchingOrder->getTimeline() as $event)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <strong>{{ $event['event'] }}</strong><br>
                                <small class="text-muted">{{ $event['date']->format('M d, Y H:i A') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Assigned Tailor Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Assigned Tailor</h5>
                </div>
                <div class="card-body text-center">
                    @if($stitchingOrder->tailor)
                    <h5 class="mb-2">{{ $stitchingOrder->tailor->name }}</h5>
                    <p class="text-muted mb-1">{{ $stitchingOrder->tailor->email }}</p>
                    @if($stitchingOrder->assigned_date)
                    <small class="text-muted">Assigned: {{ $stitchingOrder->assigned_date->format('M d, Y') }}</small>
                    @endif
                    <div class="mt-3">
                        @if($stitchingOrder->canAssignToTailor())
                        @can('assign_stitching_orders')
                        <a href="{{ route('admin.stitching-orders.assign-tailor', $stitchingOrder) }}" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-sync me-1"></i>Change Tailor
                        </a>
                        @endcan
                        @endif
                    </div>
                    @else
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i>No tailor assigned
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status Information -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Status Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-6 text-muted">Status:</dt>
                        <dd class="col-6 text-end">
                            <span class="badge bg-{{ $stitchingOrder->status_badge }}">
                                {{ $stitchingOrder->getStatusTextAttribute() }}
                            </span>
                        </dd>

                        <dt class="col-6 text-muted">Progress:</dt>
                        <dd class="col-6 text-end fw-bold">{{ $stitchingOrder->progress_percentage }}%</dd>

                        <dt class="col-6 text-muted">Est. Cost:</dt>
                        <dd class="col-6 text-end">Rs. {{ number_format($stitchingOrder->estimated_cost ?? 0, 2) }}</dd>
                    </dl>

                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: {{ $stitchingOrder->progress_percentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Dates Information -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Important Dates</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-6 text-muted">Created:</dt>
                        <dd class="col-6 text-end"><small>{{ $stitchingOrder->created_at->format('M d, Y') }}</small></dd>

                        <dt class="col-6 text-muted">Assigned:</dt>
                        <dd class="col-6 text-end">
                            <small>{{ $stitchingOrder->assigned_date?->format('M d, Y') ?? 'Pending' }}</small>
                        </dd>

                        <dt class="col-6 text-muted">Started:</dt>
                        <dd class="col-6 text-end">
                            <small>{{ $stitchingOrder->start_date?->format('M d, Y') ?? 'Not started' }}</small>
                        </dd>

                        <dt class="col-6 text-muted">Fitting:</dt>
                        <dd class="col-6 text-end">
                            <small>{{ $stitchingOrder->fitting_date?->format('M d, Y') ?? 'Not scheduled' }}</small>
                        </dd>

                        <dt class="col-6 text-muted">Completed:</dt>
                        <dd class="col-6 text-end">
                            <small>{{ $stitchingOrder->completion_date?->format('M d, Y') ?? 'In progress' }}</small>
                        </dd>

                        <dt class="col-6 text-muted">Est. Delivery:</dt>
                        <dd class="col-6 text-end">
                            <small>{{ $stitchingOrder->order->delivery_date?->format('M d, Y') ?? 'Not set' }}</small>
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Quick Actions -->
            @can('update_stitching_status')
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-magic me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    @if(!$stitchingOrder->isCompleted())
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="fas fa-sync me-1"></i>Update Status
                    </button>
                    @endif
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Stitching Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.stitching-orders.update-status', $stitchingOrder) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Stitching Status</label>
                        <select name="stitching_status" class="form-select" required>
                            <option value="pending" {{ $stitchingOrder->stitching_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="assigned" {{ $stitchingOrder->stitching_status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="in_progress" {{ $stitchingOrder->stitching_status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="ready_for_fitting" {{ $stitchingOrder->stitching_status === 'ready_for_fitting' ? 'selected' : '' }}>Ready for Fitting</option>
                            <option value="in_fitting" {{ $stitchingOrder->stitching_status === 'in_fitting' ? 'selected' : '' }}>In Fitting</option>
                            <option value="ready" {{ $stitchingOrder->stitching_status === 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ $stitchingOrder->stitching_status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $stitchingOrder->stitching_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Fitting Date</label>
                        <input type="date" name="fitting_date" class="form-control" value="{{ $stitchingOrder->fitting_date?->format('Y-m-d') ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Completion Date</label>
                        <input type="date" name="completion_date" class="form-control" value="{{ $stitchingOrder->completion_date?->format('Y-m-d') ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tailor Notes</label>
                        <textarea name="tailor_notes" class="form-control" rows="3" placeholder="Add notes...">{{ $stitchingOrder->tailor_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
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

.timeline {
    position: relative;
    padding: 0;
}

.timeline-item {
    display: flex;
    margin-bottom: 20px;
    position: relative;
    padding-left: 50px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #dee2e6;
}

.timeline-content {
    flex: 1;
}

.btn-group .btn {
    padding: 6px 12px;
}
</style>
@endsection
