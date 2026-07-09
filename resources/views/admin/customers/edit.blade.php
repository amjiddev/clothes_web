@extends('admin.layouts.app')

@section('title', 'Edit Customer - ' . $customer->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.show', $customer) }}">{{ $customer->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-user-edit me-2"></i>Edit Customer</h1>
                <p class="text-muted">Update customer information</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Profile
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

    <!-- Success Message -->
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Left Column: Customer Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.customers.update', $customer) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Profile Photo -->
                        <div class="mb-4">
                            <label for="profile_photo_path" class="form-label fw-bold">Profile Photo</label>
                            <div class="row align-items-start">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="file" class="form-control @error('profile_photo_path') is-invalid @enderror" 
                                               id="profile_photo_path" name="profile_photo_path" accept="image/*" 
                                               onchange="previewImage(event)">
                                    </div>
                                    <small class="form-text text-muted d-block mt-2">
                                        Allowed formats: JPEG, PNG, JPG, GIF (Max 2MB)
                                    </small>
                                    @error('profile_photo_path')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <!-- Current Photo -->
                                    @if($customer->profile_photo_path)
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Current Photo</label>
                                        <img src="{{ $customer->profile_photo_url }}" alt="{{ $customer->name }}" 
                                             class="img-thumbnail rounded" width="150" height="150" style="object-fit: cover;">
                                    </div>
                                    @endif
                                    <!-- Preview -->
                                    <div id="photoPreview" class="mb-3" style="display: none;">
                                        <label class="form-label text-muted">New Photo Preview</label>
                                        <img id="previewImg" src="" alt="Preview" 
                                             class="img-thumbnail rounded" width="150" height="150" style="object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Customer Info Sidebar -->
        <div class="col-lg-4">
            <!-- Customer Summary Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Customer Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Member Since</label>
                        <p class="mb-0"><strong>{{ $customer->created_at->format('M d, Y') }}</strong></p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email Verified</label>
                        <p class="mb-0">
                            @if($customer->email_verified_at)
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Verified
                            </span>
                            @else
                            <span class="badge bg-warning">
                                <i class="fas fa-clock me-1"></i>Not Verified
                            </span>
                            @endif
                        </p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label text-muted">Account Status</label>
                        <p class="mb-0">
                            @if($customer->is_blocked)
                            <span class="badge bg-danger">
                                <i class="fas fa-ban me-1"></i>Blocked
                            </span>
                            @else
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                            @endif
                        </p>
                    </div>
                    <hr>
                    <div>
                        <label class="form-label text-muted">Last Login</label>
                        <p class="mb-0">
                            @if($customer->last_login_at)
                            <strong>{{ $customer->last_login_at->diffForHumans() }}</strong>
                            @else
                            <span class="text-muted">Never</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h3 class="text-primary mb-1">{{ $customer->orders_count }}</h3>
                        <p class="text-muted mb-0">Total Orders</p>
                    </div>
                    <hr>
                    <div class="text-center mb-3">
                        <h3 class="text-info mb-1">{{ $customer->measurements_count ?? 0 }}</h3>
                        <p class="text-muted mb-0">Saved Measurements</p>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h3 class="text-success mb-1">{{ $customer->addresses_count ?? 0 }}</h3>
                        <p class="text-muted mb-0">Addresses</p>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card border-0 shadow mt-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-eye me-2"></i>View Profile
                    </a>
                    @can('delete_customers')
                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline w-100">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" 
                                onclick="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                            <i class="fas fa-trash me-2"></i>Delete Customer
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photoPreview');
    const previewImg = document.getElementById('previewImg');

    if (file) {
        const reader = new FileReader();
        reader.onload = function() {
            previewImg.src = reader.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
</script>

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
