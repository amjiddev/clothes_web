@extends('receptionist.layouts.app')

@section('title', 'Edit Customer')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item"><a href="{{ route('receptionist.customers.show', $customer) }}">{{ $customer->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">
        <i class="fas fa-user-edit me-2"></i>Edit Customer
    </h1>
    <p class="text-muted">Update customer information</p>
</div>

<!-- Validation Errors -->
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
    <div class="col-lg-8">
        <div class="card border-0 shadow">
            <div class="card-body">
                <form action="{{ route('receptionist.customers.update', $customer) }}" method="POST" class="needs-validation">
                    @csrf
                    @method('PUT')

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">
                            <i class="fas fa-user me-2"></i>Full Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" placeholder="Enter full name" 
                               value="{{ old('name', $customer->name) }}" required>
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
                               id="email" name="email" placeholder="Enter email address" 
                               value="{{ old('email', $customer->email) }}" required>
                        @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-4">
                        <label for="phone" class="form-label fw-bold">
                            <i class="fas fa-phone me-2"></i>Phone Number
                        </label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" placeholder="Enter phone number" 
                               value="{{ old('phone') }}">
                        @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="mb-4">
                        <label for="gender" class="form-label fw-bold">
                            <i class="fas fa-venus-mars me-2"></i>Gender
                        </label>
                        <select class="form-select @error('gender') is-invalid @enderror" 
                                id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- City -->
                    <div class="mb-4">
                        <label for="city" class="form-label fw-bold">
                            <i class="fas fa-city me-2"></i>City
                        </label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                               id="city" name="city" placeholder="Enter city name" 
                               value="{{ old('city', $customer->addresses()->first()?->city) }}">
                        @error('city')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="form-label fw-bold">
                            <i class="fas fa-map-marker-alt me-2"></i>Address
                        </label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" 
                                  placeholder="Enter full address">{{ old('address', $customer->addresses()->first()?->address) }}</textarea>
                        @error('address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label fw-bold">
                            <i class="fas fa-sticky-note me-2"></i>Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Any additional notes about the customer">{{ old('notes') }}</textarea>
                        @error('notes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Customer
                        </button>
                        <a href="{{ route('receptionist.customers.show', $customer) }}" class="btn btn-outline-secondary">
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
                    <i class="fas fa-info-circle me-2 text-info"></i>Customer Info
                </h6>
                <div class="mb-3">
                    <small class="text-muted d-block">Customer ID</small>
                    <code>{{ $customer->id }}</code>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Created</small>
                    <p class="mb-0">{{ $customer->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Total Orders</small>
                    <p class="mb-0">{{ $customer->orders()->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}
</style>
@endsection
