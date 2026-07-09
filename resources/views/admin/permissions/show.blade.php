@extends('admin.layouts.app')

@section('title', 'Permission Details')

@section('breadcrumb')
    <li class="breadcrumb-item active">Roles & Permissions</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.user-management.permissions.index') }}">Permissions</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-key me-2"></i>Permission Details</h1>
                <p class="text-muted">View permission information and assigned roles</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.user-management.permissions.edit', $permission) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                <form action="{{ route('admin.user-management.permissions.destroy', $permission) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Delete this permission? It will be removed from all roles.')">
                        <i class="fas fa-trash me-2"></i>Delete
                    </button>
                </form>
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
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Permission Info Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Permission Information
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Permission Name -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Permission Name</strong>
                            <code class="bg-light px-3 py-2 d-block rounded">{{ $permission->name }}</code>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Display Name</strong>
                            <p class="mb-0">
                                {{ $permission->display_name ?? ucfirst(str_replace('_', ' ', $permission->name)) }}
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <strong class="text-muted d-block mb-2">Description</strong>
                        <p class="mb-0">
                            {{ $permission->description ?? 'No description provided' }}
                        </p>
                    </div>

                    <!-- Guard Name -->
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Guard Name</strong>
                            <code class="bg-light px-3 py-2 d-block rounded">{{ $permission->guard_name ?? 'web' }}</code>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Category</strong>
                            @php
                                $parts = explode('_', $permission->name);
                                $category = $parts[0] ?? 'other';
                            @endphp
                            <span class="badge bg-light text-dark px-3 py-2">{{ ucfirst($category) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Roles Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Assigned to Roles 
                        <span class="badge bg-info ms-2">{{ $rolesWithPermission->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($rolesWithPermission->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Role Name</th>
                                    <th>Users Count</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rolesWithPermission as $role)
                                <tr>
                                    <td>
                                        <strong>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $role->name }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $role->users()->count() }} users
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.user-management.roles.show', $role) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-arrow-right"></i> View Role
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        This permission is not assigned to any roles yet.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Timestamps Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Timestamps
                    </h6>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">Created</strong>
                        <p class="mb-0">
                            {{ $permission->created_at?->format('M d, Y \a\t h:i A') ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="mb-0">
                        <strong class="text-muted d-block mb-1">Last Updated</strong>
                        <p class="mb-0">
                            {{ $permission->updated_at?->format('M d, Y \a\t h:i A') ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.user-management.permissions.index') }}" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Permissions
                    </a>
                    <a href="{{ route('admin.user-management.roles.index') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="fas fa-shield-alt me-2"></i>View Roles
                    </a>
                    <a href="{{ route('admin.user-management.users.index') }}" class="btn btn-outline-info btn-sm w-100">
                        <i class="fas fa-users me-2"></i>View Users
                    </a>
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

code {
    color: #d63384;
    font-size: 0.85rem;
}
</style>
@endsection
