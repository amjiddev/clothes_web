@extends('admin.layouts.app')

@section('title', 'Receptionist Profile - ' . $receptionist->user->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.receptionists.index') }}">Receptionists</a></li>
    <li class="breadcrumb-item active">{{ $receptionist->user->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-headset text-muted fa-2x"></i>
                    </div>
                    <div>
                        <h1 class="page-title mb-0">{{ $receptionist->user->name }}</h1>
                        <p class="text-muted mb-0">{{ $receptionist->department }} Department</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.receptionists.edit', $receptionist) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    @if($receptionist->status === 'active')
                    <form action="{{ route('admin.receptionists.deactivate', $receptionist) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Deactivate this receptionist?')">
                            <i class="fas fa-ban me-2"></i>Deactivate
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.receptionists.activate', $receptionist) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Activate
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('admin.receptionists.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Profile Info -->
        <div class="col-lg-4">
            <!-- User Information -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i>User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <p class="mb-0"><strong>{{ $receptionist->user->name }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="mb-0"><a href="mailto:{{ $receptionist->user->email }}">{{ $receptionist->user->email }}</a></p>
                    </div>
                    @if($receptionist->phone)
                    <div class="mb-3">
                        <label class="form-label text-muted">Phone</label>
                        <p class="mb-0"><a href="tel:{{ $receptionist->phone }}">{{ $receptionist->phone }}</a></p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label text-muted">Department</label>
                        <p class="mb-0"><span class="badge bg-light text-dark">{{ $receptionist->department }}</span></p>
                    </div>
                    <div>
                        <label class="form-label text-muted">Status</label>
                        <p class="mb-0">
                            @if($receptionist->status === 'active')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Active
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

            <!-- Timeline Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Assigned Date</label>
                        <p class="mb-0">
                            @if($receptionist->assigned_date)
                            <strong>{{ $receptionist->assigned_date->format('M d, Y') }}</strong><br>
                            <small class="text-muted">{{ $receptionist->assigned_date->format('h:i A') }}</small>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>
                    <hr>
                    <div>
                        <label class="form-label text-muted">Last Updated</label>
                        <p class="mb-0">
                            @if($receptionist->last_action_date)
                            <strong>{{ $receptionist->last_action_date->diffForHumans() }}</strong><br>
                            <small class="text-muted">{{ $receptionist->last_action_date->format('M d, Y h:i A') }}</small>
                            @else
                            <span class="text-muted">Never updated</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Permissions & Details -->
        <div class="col-lg-8">
            <!-- Permissions Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lock me-2"></i>Assigned Permissions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Allowed Permissions -->
                        <div class="col-lg-6">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-check-circle me-2"></i>Allowed Actions
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-users text-success me-3 mt-1"></i>
                                        <div>
                                            <strong>Customer Management</strong>
                                            <p class="text-muted small mb-0">View, create, and edit customer profiles</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-shopping-cart text-success me-3 mt-1"></i>
                                        <div>
                                            <strong>Order Creation</strong>
                                            <p class="text-muted small mb-0">Create and manage customer orders</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-sewing-machine text-success me-3 mt-1"></i>
                                        <div>
                                            <strong>Tailor Assignment</strong>
                                            <p class="text-muted small mb-0">Assign stitching orders to tailors</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Restricted Permissions -->
                        <div class="col-lg-6">
                            <h6 class="text-danger mb-3">
                                <i class="fas fa-lock me-2"></i>Restricted Access
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-ban text-danger me-3 mt-1"></i>
                                        <div>
                                            <strong>System Settings</strong>
                                            <p class="text-muted small mb-0">Cannot access system configuration</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-ban text-danger me-3 mt-1"></i>
                                        <div>
                                            <strong>User Management</strong>
                                            <p class="text-muted small mb-0">Cannot create or manage users</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-ban text-danger me-3 mt-1"></i>
                                        <div>
                                            <strong>Reports</strong>
                                            <p class="text-muted small mb-0">Cannot generate system reports</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permission Details Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Detailed Permissions List
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Permission</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>view_customers</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>create_customers</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>edit_customers</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>view_all_orders</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>create_orders</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>edit_orders</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>assign_tailors</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>view_stitching_orders</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
</style>
@endsection
