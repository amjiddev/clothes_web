@extends('admin.layouts.app')

@section('title', 'Users Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">User Management</li>
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-users me-2"></i>Users Management</h1>
                <p class="text-muted">Manage system users and their roles</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.user-management.users.create') }}" class="btn btn-dark">
                    <i class="fas fa-plus me-2"></i>Create User
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

    <!-- Search & Filter Section -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.user-management.users.index') }}" method="GET" class="row g-3">
                <!-- Search Input -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Role Filter -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Role</label>
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="col-md-12 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.user-management.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4"><i class="fas fa-user me-2"></i>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4 fw-bold">
                            <i class="fas fa-user-circle me-2 text-primary"></i>{{ $user->name }}
                        </td>
                        <td>
                            <code class="bg-light px-2 py-1">{{ $user->email }}</code>
                        </td>
                        <td>
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                @endforeach
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @if($user->email_verified_at)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.user-management.users.show', $user) }}" 
                                   class="btn btn-sm btn-info" title="View User">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.user-management.users.edit', $user) }}" 
                                   class="btn btn-sm btn-warning" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$user->hasRole('super_admin'))
                                <form action="{{ route('admin.user-management.users.destroy', $user) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete User"
                                            onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            {{ $users->links() }}
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

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
@endsection
