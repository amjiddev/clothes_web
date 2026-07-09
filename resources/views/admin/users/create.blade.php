@extends('admin.layouts.app')

@section('title', 'Create User')

@section('breadcrumb')
    <li class="breadcrumb-item active">User Management</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.user-management.users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-user-plus me-2"></i>Create User</h1>
                <p class="text-muted">Add a new user to the system</p>
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
                    <form action="{{ route('admin.user-management.users.store') }}" method="POST">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" placeholder="Full Name" 
                                   value="{{ old('name') }}" required>
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
                                   value="{{ old('email') }}" required>
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
                                <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-key me-2"></i>Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Minimum 8 characters" required>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">
                                <i class="fas fa-check me-2"></i>Confirm Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirm password" required>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create User
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
            <div class="card border-0 shadow bg-light">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2 text-info"></i>User Guidelines
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li>Name should be the user's full name</li>
                        <li>Email must be unique in the system</li>
                        <li>Password must be at least 8 characters</li>
                        <li>Role determines user permissions</li>
                        <li>User email will be marked as verified</li>
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
</style>
@endsection
