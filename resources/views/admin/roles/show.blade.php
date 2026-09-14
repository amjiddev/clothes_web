@extends('admin.layouts.app')

@section('title', 'Role - ' . $role->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.user-management.roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active">{{ $role->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-shield-alt me-2"></i>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</h1>
                <p class="text-muted">{{ $role->description ?? 'No description' }}</p>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    @if($role->name !== 'super_admin')
                    <a href="{{ route('admin.user-management.roles.edit', $role) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Role
                    </a>
                    @endif
                    <a href="{{ route('admin.user-management.roles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Permissions -->
        <div class="col-lg-8">
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lock me-2"></i>Assigned Permissions
                    </h5>
                </div>
                <div class="card-body">
                    @if($permissions->count() > 0)
                        @foreach($permissionGroups as $category => $categoryPermissions)
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-check me-2"></i>{{ $category }}
                            </h6>
                            <div class="row">
                                @foreach($categoryPermissions as $permission)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>{{ $permission->display_name ?? ucfirst(str_replace('_', ' ', $permission->name)) }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                    <p class="text-muted">No permissions assigned to this role</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Statistics -->
        <div class="col-lg-4">
            <!-- Role Statistics -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-primary">{{ $permissions->count() }}</h2>
                        <p class="text-muted mb-0">Total Permissions</p>
                    </div>
                    <hr>
                    <div class="text-center mb-4">
                        <h2 class="text-info">{{ $role->users()->count() }}</h2>
                        <p class="text-muted mb-0">Assigned Users</p>
                    </div>
                    <hr>
                    <div class="text-center">
                        <p class="text-muted mb-1">Role Type</p>
                        <span class="badge bg-light text-dark">{{ $role->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Role Information -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Role Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <p class="mb-0"><code>{{ $role->name }}</code></p>
                    </div>
                    @if($role->display_name)
                    <div class="mb-3">
                        <label class="form-label text-muted">Display Name</label>
                        <p class="mb-0"><strong>{{ $role->display_name }}</strong></p>
                    </div>
                    @endif
                    @if($role->description)
                    <div>
                        <label class="form-label text-muted">Description</label>
                        <p class="mb-0">{{ $role->description }}</p>
                    </div>
                    @endif
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
