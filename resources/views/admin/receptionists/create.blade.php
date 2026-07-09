@extends('admin.layouts.app')

@section('title', 'Add Receptionist')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.receptionists.index') }}">Receptionists</a></li>
    <li class="breadcrumb-item active">Add Receptionist</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-plus-circle me-2"></i>Add New Receptionist</h1>
                <p class="text-muted">Create a new receptionist account</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.receptionists.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Receptionists
                </a>
            </div>
        </div>
    </div>

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
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>Receptionist Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.receptionists.store') }}" method="POST">
                        @csrf

                        <!-- User Selection Tab -->
                        <ul class="nav nav-tabs mb-4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="existing-user-tab" data-bs-toggle="tab" 
                                        data-bs-target="#existing-user" type="button" role="tab">
                                    <i class="fas fa-user-check me-2"></i>Existing User
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="new-user-tab" data-bs-toggle="tab" 
                                        data-bs-target="#new-user" type="button" role="tab">
                                    <i class="fas fa-user-plus me-2"></i>Create New User
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content mb-4">
                            <!-- Existing User Tab -->
                            <div class="tab-pane fade show active" id="existing-user" role="tabpanel">
                                <div class="mb-4">
                                    <label for="user_id" class="form-label fw-bold">Select User</label>
                                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                        <option value="">Choose a user...</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- New User Tab -->
                            <div class="tab-pane fade" id="new-user" role="tabpanel">
                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-bold">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" placeholder="John Doe">
                                    @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-bold">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" placeholder="john@example.com">
                                    @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-bold">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Minimum 8 characters">
                                    @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
                                </div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="+92 300 1234567">
                            @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="mb-4">
                            <label for="department" class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                            <select name="department" id="department" class="form-select @error('department') is-invalid @enderror" required>
                                <option value="">Select department...</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                                @endforeach
                            </select>
                            @error('department')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Add Receptionist
                            </button>
                            <a href="{{ route('admin.receptionists.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <!-- Permissions Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lock me-2"></i>Receptionist Permissions
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>View customers</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Create customers</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Edit customers</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Create orders</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Edit orders</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Assign tailors</small>
                        </li>
                        <li>
                            <i class="fas fa-times text-danger me-2"></i>
                            <small>No access to system settings</small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Important Notes
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-arrow-right me-2"></i>
                            <small>Choose between existing user or create new</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-arrow-right me-2"></i>
                            <small>Department helps organize workflow</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-arrow-right me-2"></i>
                            <small>Password must be at least 8 characters</small>
                        </li>
                        <li>
                            <i class="fas fa-arrow-right me-2"></i>
                            <small>Set status to Active for immediate use</small>
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

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 3px solid transparent;
}

.nav-tabs .nav-link.active {
    color: #000;
    border-color: #000;
    background-color: transparent;
}
</style>
@endsection
