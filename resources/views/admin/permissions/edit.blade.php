@extends('admin.layouts.app')

@section('title', 'Edit Permission')

@section('breadcrumb')
    <li class="breadcrumb-item active">Roles & Permissions</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.user-management.permissions.index') }}">Permissions</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-edit me-2"></i>Edit Permission</h1>
                <p class="text-muted">Update permission details</p>
            </div>
        </div>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Validation Error:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <form action="{{ route('admin.user-management.permissions.update', $permission) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Permission Name (Read-only) -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-key me-2"></i>Permission Name
                            </label>
                            <input type="text" class="form-control" id="name" 
                                   value="{{ $permission->name }}" readonly>
                            <small class="text-muted d-block mt-2">
                                Permission names cannot be changed after creation. Delete and recreate if needed.
                            </small>
                        </div>

                        <!-- Display Name -->
                        <div class="mb-4">
                            <label for="display_name" class="form-label fw-bold">
                                <i class="fas fa-heading me-2"></i>Display Name <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                   id="display_name" name="display_name" 
                                   placeholder="e.g., Manage Users (human-readable)" 
                                   value="{{ old('display_name', $permission->display_name ?? ucfirst(str_replace('_', ' ', $permission->name))) }}">
                            <small class="text-muted d-block mt-2">
                                Human-readable name for display in UI
                            </small>
                            @error('display_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-2"></i>Description <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Describe what this permission allows...">{{ old('description', $permission->description ?? '') }}</textarea>
                            <small class="text-muted d-block mt-2">
                                Provide a clear description of what this permission grants
                            </small>
                            @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.user-management.permissions.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Permission Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2 text-info"></i>Permission Information</h6>
                </div>
                <div class="card-body">
                    <!-- Permission ID -->
                    <div class="mb-3">
                        <strong class="small text-muted d-block mb-1">ID:</strong>
                        <code class="bg-light px-2 py-1">{{ $permission->id }}</code>
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <strong class="small text-muted d-block mb-1">Category:</strong>
                        @php
                            $parts = explode('_', $permission->name);
                            $category = $parts[0] ?? 'other';
                        @endphp
                        <span class="badge bg-light text-dark">{{ ucfirst($category) }}</span>
                    </div>

                    <!-- Guard Name -->
                    <div class="mb-3">
                        <strong class="small text-muted d-block mb-1">Guard:</strong>
                        <code class="bg-light px-2 py-1">{{ $permission->guard_name ?? 'web' }}</code>
                    </div>

                    <hr>

                    <!-- Assigned Roles -->
                    <div class="mb-0">
                        <strong class="small text-muted d-block mb-2">Assigned to Roles:</strong>
                        @php $roles = $permission->roles; @endphp
                        @if($roles->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($roles as $role)
                            <span class="badge bg-primary">
                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                            </span>
                            @endforeach
                        </div>
                        @else
                        <p class="small text-muted mb-0">No roles assigned yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Created/Updated Info -->
            <div class="card border-0 shadow mt-4 bg-light">
                <div class="card-body small text-muted">
                    <p class="mb-1">
                        <strong>Created:</strong><br>
                        {{ $permission->created_at?->format('M d, Y H:i') ?? 'N/A' }}
                    </p>
                    <p class="mb-0">
                        <strong>Updated:</strong><br>
                        {{ $permission->updated_at?->format('M d, Y H:i') ?? 'N/A' }}
                    </p>
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
