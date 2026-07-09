@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('breadcrumb')
    <li class="breadcrumb-item active">User Management</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.user-management.users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-user-edit me-2"></i>Edit User</h1>
                <p class="text-muted">Update user information and role</p>
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
                    <form action="{{ route('admin.user-management.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" placeholder="Full Name" 
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">
                                <i class="fas fa-envelope me-2"></i>Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" placeholder="user@example.com" 
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="form-label fw-bold">
                                <i class="fas fa-shield-alt me-2"></i>Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                    {{ (old('role') ?? $userRoles[0] ?? null) == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password (Optional) -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-key me-2"></i>New Password <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Leave blank to keep current password">
                            <small class="text-muted d-block mt-2">
                                Leave blank if you don't want to change the password
                            </small>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">
                                <i class="fas fa-check me-2"></i>Confirm Password
                            </label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirm new password">
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.user-management.users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>User Information
                    </h6>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">User ID:</strong>
                        <code class="bg-light px-2 py-1">{{ $user->id }}</code>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">Status:</strong>
                        @if($user->email_verified_at)
                        <span class="badge bg-success">Active</span>
                        @else
                        <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">Joined:</strong>
                        <p class="mb-0">{{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-0">
                        <strong class="text-muted d-block mb-1">Last Updated:</strong>
                        <p class="mb-0">{{ $user->updated_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
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
@endsection
