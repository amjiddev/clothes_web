@extends('admin.layouts.app')

@section('title', 'Permissions Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Roles & Permissions</li>
    <li class="breadcrumb-item active">Permissions</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-key me-2"></i>Permissions Management</h1>
                <p class="text-muted">Manage application permissions</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.user-management.permissions.create') }}" class="btn btn-dark">
                    <i class="fas fa-plus me-2"></i>Create Permission
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
            <form action="{{ route('admin.user-management.permissions.index') }}" method="GET" class="row g-3">
                <!-- Search Input -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Search by permission name..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                            {{ ucfirst($category) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="col-md-12 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.user-management.permissions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Permissions Table -->
    <div class="card border-0 shadow">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4"><i class="fas fa-key me-2"></i>Permission Name</th>
                        <th>Display Name</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th class="text-center">Assigned Roles</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                    <tr>
                        <td class="ps-4 fw-bold">
                            <code class="bg-light px-2 py-1">{{ $permission->name }}</code>
                        </td>
                        <td>
                            {{ $permission->display_name ?? ucfirst(str_replace('_', ' ', $permission->name)) }}
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $permission->description ?? '-' }}
                            </small>
                        </td>
                        <td>
                            @php
                                $parts = explode('_', $permission->name);
                                $category = $parts[0] ?? 'other';
                            @endphp
                            <span class="badge bg-light text-dark">{{ ucfirst($category) }}</span>
                        </td>
                        <td class="text-center">
                            @php $roleCount = $permission->roles()->count(); @endphp
                            @if($roleCount > 0)
                                <span class="badge bg-info">{{ $roleCount }}</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.user-management.permissions.show', $permission) }}" 
                                   class="btn btn-sm btn-info" title="View Permission">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.user-management.permissions.edit', $permission) }}" 
                                   class="btn btn-sm btn-warning" title="Edit Permission">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.user-management.permissions.destroy', $permission) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete Permission"
                                            onclick="return confirm('Are you sure? This permission is assigned to {{ $permission->roles()->count() }} role(s).')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted">No permissions found</p>
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
            {{ $permissions->links() }}
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

code {
    color: #d63384;
    font-size: 0.85rem;
}
</style>
@endsection
