@extends('admin.layouts.app')

@section('title', 'Edit Role - ' . $role->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.user-management.roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-edit me-2"></i>Edit Role</h1>
                <p class="text-muted">{{ $role->display_name ?? ucfirst(str_replace('_', ' ', $role->name)) }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.user-management.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Roles
                </a>
            </div>
        </div>
    </div>

    @if($isSystemRole)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>System Role:</strong> This is a system-defined role. You can view its permissions but cannot modify the role name or delete it.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Role Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user-management.roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Role Name (Read-only) -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Role Name</label>
                            <input type="text" class="form-control" id="name" value="{{ $role->name }}" disabled>
                            <small class="text-muted">Role name cannot be changed</small>
                        </div>

                        <!-- Display Name -->
                        <div class="mb-4">
                            <label for="display_name" class="form-label fw-bold">Display Name</label>
                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                   id="display_name" name="display_name" value="{{ old('display_name', $role->display_name) }}" 
                                   placeholder="Human-readable role name">
                            @error('display_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3" placeholder="Describe the purpose of this role...">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Permissions Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">Permissions</label>

                            @foreach($permissionGroups as $category => $categoryPermissions)
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-lock me-2"></i>{{ $category }}
                                </h6>
                                <div class="row">
                                    @foreach($categoryPermissions as $permission)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="permissions[]" value="{{ $permission->id }}" 
                                                   id="perm_{{ $permission->id }}"
                                                   {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->display_name ?? ucfirst(str_replace('_', ' ', $permission->name)) }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach

                            @error('permissions')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.user-management.roles.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Role Statistics -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Total Permissions</label>
                        <p class="mb-0"><h4 class="text-primary">{{ $role->permissions()->count() }}</h4></p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label text-muted">Assigned Users</label>
                        <p class="mb-0"><h4 class="text-info">{{ $role->users()->count() }}</h4></p>
                    </div>
                    <hr>
                    <div>
                        <label class="form-label text-muted">Role Type</label>
                        <p class="mb-0">
                            @if($isSystemRole)
                            <span class="badge bg-warning text-dark">System Role</span>
                            @else
                            <span class="badge bg-success">Custom Role</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Information
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Edit display name and description</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Assign or revoke permissions</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Changes apply immediately to users</small>
                        </li>
                        <li>
                            <i class="fas fa-info text-info me-2"></i>
                            <small>{{ $isSystemRole ? 'Cannot edit system roles' : 'You can delete this role if it has no users' }}</small>
                        </li>
                    </ul>
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
