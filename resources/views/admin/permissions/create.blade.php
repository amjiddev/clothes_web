@extends('admin.layouts.app')

@section('title', 'Create Permission')

@section('breadcrumb')
    <li class="breadcrumb-item active">Roles & Permissions</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.user-management.permissions.index') }}">Permissions</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-plus-circle me-2"></i>Create Permission</h1>
                <p class="text-muted">Add a new permission to the system</p>
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
                    <form action="{{ route('admin.user-management.permissions.store') }}" method="POST">
                        @csrf

                        <!-- Permission Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-key me-2"></i>Permission Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" placeholder="e.g., manage_users, edit_posts" 
                                   value="{{ old('name') }}" required>
                            <small class="text-muted d-block mt-2">
                                Use lowercase with underscores. Format: category_action (e.g., view_products, edit_orders)
                            </small>
                            @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category Selection -->
                        <div class="mb-4">
                            <label for="category" class="form-label fw-bold">
                                <i class="fas fa-tag me-2"></i>Category <span class="text-muted">(Optional)</span>
                            </label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Select Category (suggested)</option>
                                @foreach($categories as $category)
                                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-2">Choose a category prefix to auto-populate the permission name</small>
                        </div>

                        <!-- Display Name -->
                        <div class="mb-4">
                            <label for="display_name" class="form-label fw-bold">
                                <i class="fas fa-heading me-2"></i>Display Name <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                   id="display_name" name="display_name" 
                                   placeholder="e.g., Manage Users (human-readable)" 
                                   value="{{ old('display_name') }}">
                            <small class="text-muted d-block mt-2">
                                Human-readable name for display in UI. Leave blank to auto-generate from permission name.
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
                                      placeholder="Describe what this permission allows...">{{ old('description') }}</textarea>
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
                                <i class="fas fa-save me-2"></i>Create Permission
                            </button>
                            <a href="{{ route('admin.user-management.permissions.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="card border-0 shadow mt-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2 text-info"></i>Permission Name Guidelines
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li>Use lowercase letters and underscores only</li>
                        <li>Format: <code>category_action</code></li>
                        <li>Examples: <code>view_products</code>, <code>create_orders</code>, <code>manage_settings</code></li>
                        <li>Common categories: view, create, edit, delete, manage, assign, export</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Quick Reference -->
        <div class="col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2 text-warning"></i>Permission Format</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="small text-muted d-block mb-2">STRUCTURE:</strong>
                        <code class="bg-light d-block px-2 py-1 rounded">category_action</code>
                    </div>

                    <div class="mb-3">
                        <strong class="small text-muted d-block mb-2">EXAMPLES:</strong>
                        <ul class="list-unstyled small">
                            <li><code class="bg-light px-1">view_products</code></li>
                            <li><code class="bg-light px-1">create_orders</code></li>
                            <li><code class="bg-light px-1">edit_customers</code></li>
                            <li><code class="bg-light px-1">delete_users</code></li>
                            <li><code class="bg-light px-1">manage_settings</code></li>
                            <li><code class="bg-light px-1">assign_tailors</code></li>
                        </ul>
                    </div>

                    <hr>

                    <div class="mb-0">
                        <strong class="small text-muted d-block mb-2">CATEGORIES:</strong>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($categories as $category)
                            <span class="badge bg-light text-dark small">{{ ucfirst($category) }}</span>
                            @endforeach
                        </div>
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

code {
    color: #d63384;
    font-size: 0.85rem;
}
</style>

<script>
document.getElementById('category').addEventListener('change', function() {
    const permissionName = document.getElementById('name');
    if (this.value && !permissionName.value) {
        permissionName.value = this.value + '_';
        permissionName.focus();
        permissionName.setSelectionRange(permissionName.value.length, permissionName.value.length);
    }
});
</script>
@endsection
