@extends('admin.layouts.app')

@section('title', 'Edit Receptionist - ' . $receptionist->user->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.receptionists.index') }}">Receptionists</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-user-edit me-2"></i>Edit Receptionist</h1>
                <p class="text-muted">Update receptionist information</p>
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
                        <i class="fas fa-user-circle me-2"></i>Receptionist Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.receptionists.update', $receptionist) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- User Info (Read-only) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Assigned User</label>
                            <div class="alert alert-info mb-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-0">
                                            <i class="fas fa-user me-2"></i>
                                            <strong>{{ $receptionist->user->name }}</strong>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-0">
                                            <i class="fas fa-envelope me-2"></i>
                                            <a href="mailto:{{ $receptionist->user->email }}">{{ $receptionist->user->email }}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $receptionist->phone) }}" placeholder="+92 300 1234567">
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
                                <option value="{{ $dept }}" {{ old('department', $receptionist->department) == $dept ? 'selected' : '' }}>
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
                                <option value="active" {{ old('status', $receptionist->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $receptionist->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
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
            <!-- Status & Timeline Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Status Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Current Status</label>
                        <p class="mb-0">
                            @if($receptionist->status === 'active')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>Inactive
                            </span>
                            @endif
                        </p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label text-muted">Assigned Date</label>
                        <p class="mb-0">
                            @if($receptionist->assigned_date)
                            <strong>{{ $receptionist->assigned_date->format('M d, Y h:i A') }}</strong>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="form-label text-muted">Last Updated</label>
                        <p class="mb-0">
                            @if($receptionist->last_action_date)
                            <strong>{{ $receptionist->last_action_date->diffForHumans() }}</strong>
                            @else
                            <span class="text-muted">Never</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Permissions Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lock me-2"></i>Permissions
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
</style>
@endsection
