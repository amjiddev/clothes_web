@extends('admin.layouts.app')

@section('title', 'Roles Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Roles & Permissions</li>
    <li class="breadcrumb-item active">Roles</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-shield-alt me-2"></i>Roles Management</h1>
                <p class="text-muted">Manage system roles and permissions</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.user-management.roles.create') }}" class="btn btn-dark">
                    <i class="fas fa-plus me-2"></i>Create Role
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

    <!-- Error Message -->
    @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Search Section -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.user-management.roles.index') }}" method="GET" class="row g-3">
                <!-- Search Input -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Search by role name..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="col-md-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Search
                    </button>
                    <a href="{{ route('admin.user-management.roles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Roles Grid -->
    <div class="row">
        @foreach($roles as $role)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <!-- Role Name -->
                    <div class="mb-3">
                        <h5 class="card-title mb-1">
                            <i class="fas fa-shield-alt me-2 text-primary"></i>{{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </h5>
                        <small class="text-muted">{{ $role->name }}</small>
                    </div>

                    <!-- Description -->
                    @if($role->description)
                    <p class="card-text small text-muted mb-3">{{ $role->description }}</p>
                    @endif

                    <!-- Permissions Count -->
                    <div class="mb-3">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-lock me-1"></i>{{ $role->permissions()->count() }} Permissions
                        </span>
                    </div>

                    <!-- Users Count -->
                    <div class="mb-3">
                        <span class="badge bg-info">
                            <i class="fas fa-users me-1"></i>{{ $role->users()->count() }} Users
                        </span>
                    </div>

                    <!-- System Role Badge -->
                    @if(in_array($role->name, $predefinedRoles))
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-lock me-1"></i>System Role
                        </span>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('admin.user-management.roles.show', $role) }}" class="btn btn-sm btn-info flex-grow-1">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                        @if(!in_array($role->name, $predefinedRoles))
                        <a href="{{ route('admin.user-management.roles.edit', $role) }}" class="btn btn-sm btn-warning flex-grow-1">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.user-management.roles.destroy', $role) }}" method="POST" class="flex-grow-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger w-100" 
                                    onclick="return confirm('Delete this role?')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                        @else
                        <button class="btn btn-sm btn-secondary flex-grow-1" disabled title="Cannot edit system roles">
                            <i class="fas fa-lock me-1"></i>System
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            {{ $roles->links() }}
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
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
}
</style>
@endsection
