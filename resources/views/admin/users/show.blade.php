@extends('admin.layouts.app')

@section('title', 'User Details')

@section('breadcrumb')
    <li class="breadcrumb-item active">User Management</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.user-management.users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-user-circle me-2"></i>{{ $user->name }}
                </h1>
                <p class="text-muted">View user information and permissions</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.user-management.users.edit', $user) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                @if(!$user->hasRole('super_admin'))
                <form action="{{ route('admin.user-management.users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Delete this user?')">
                        <i class="fas fa-trash me-2"></i>Delete
                    </button>
                </form>
                @endif
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
            <!-- User Info Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>User Information
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Name & Email -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Full Name</strong>
                            <p class="mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Email</strong>
                            <code class="bg-light px-3 py-2 d-block rounded">{{ $user->email }}</code>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Status</strong>
                            @if($user->email_verified_at)
                            <span class="badge bg-success px-3 py-2">Active</span>
                            @else
                            <span class="badge bg-warning text-dark px-3 py-2">Pending</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Verified At</strong>
                            <p class="mb-0">
                                {{ $user->email_verified_at?->format('M d, Y H:i') ?? 'Not verified' }}
                            </p>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Created</strong>
                            <p class="mb-0">{{ $user->created_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Updated</strong>
                            <p class="mb-0">{{ $user->updated_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Assigned Roles
                    </h5>
                </div>
                <div class="card-body">
                    @if($userRoles->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($userRoles as $role)
                        <span class="badge bg-info px-3 py-2">
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </span>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted mb-0">No roles assigned</p>
                    @endif
                </div>
            </div>

            <!-- Permissions Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>Direct Permissions
                        <span class="badge bg-success ms-2">{{ $permissions->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($permissions->count() > 0)
                    <div class="row">
                        @foreach($permissions as $permission)
                        <div class="col-md-6 mb-2">
                            <span class="badge bg-light text-dark px-3 py-2">
                                {{ $permission->display_name ?? ucfirst(str_replace('_', ' ', $permission->name)) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted mb-0">
                        Permissions are inherited from assigned roles
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.user-management.users.index') }}" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Users
                    </a>
                    <a href="{{ route('admin.user-management.users.edit', $user) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit User
                    </a>
                    <a href="{{ route('admin.user-management.roles.index') }}" class="btn btn-outline-info btn-sm w-100">
                        <i class="fas fa-shield-alt me-2"></i>Manage Roles
                    </a>
                </div>
            </div>

            <!-- ID Card -->
            <div class="card border-0 shadow bg-light">
                <div class="card-body small">
                    <strong class="text-muted d-block mb-1">User ID</strong>
                    <code class="bg-white px-2 py-1 d-block rounded">{{ $user->id }}</code>
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
