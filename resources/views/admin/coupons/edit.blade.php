@extends('admin.layouts.app')

@section('title', 'Edit Coupon')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-edit me-2"></i>Edit Coupon</h1>
                <p class="text-muted">Update coupon details</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
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
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Coupon Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Coupon Code -->
                        <div class="mb-3">
                            <label for="code" class="form-label fw-bold">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $coupon->code) }}" placeholder="e.g., SUMMER20" required>
                            @error('code')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Discount Type -->
                        <div class="mb-3">
                            <label for="discount_type" class="form-label fw-bold">Discount Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type" required>
                                <option value="">Select type...</option>
                                <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                            </select>
                            @error('discount_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Discount Value -->
                        <div class="mb-3">
                            <label for="discount_value" class="form-label fw-bold">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('discount_value') is-invalid @enderror" 
                                   id="discount_value" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" 
                                   placeholder="Enter discount value" min="0" step="0.01" required>
                            @error('discount_value')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Valid Until -->
                        <div class="mb-3">
                            <label for="valid_until" class="form-label fw-bold">Valid Until</label>
                            <input type="datetime-local" class="form-control @error('valid_until') is-invalid @enderror" 
                                   id="valid_until" name="valid_until" 
                                   value="{{ old('valid_until', $coupon->valid_until?->format('Y-m-d\TH:i')) }}">
                            @error('valid_until')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Coupon
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Coupon
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Coupon Details</h6>
                </div>
                <div class="card-body">
                    <dl>
                        <dt>Created:</dt>
                        <dd>{{ $coupon->created_at->format('M d, Y H:i') }}</dd>

                        <dt class="mt-2">Last Updated:</dt>
                        <dd>{{ $coupon->updated_at->format('M d, Y H:i') }}</dd>

                        <dt class="mt-2">Status:</dt>
                        <dd>
                            @if($coupon->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card border-0 shadow mt-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Danger Zone</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash me-2"></i>Delete Coupon
                        </button>
                    </form>
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
        margin: 0;
    }

    .card {
        border-radius: 0.5rem;
    }

    dl dt {
        font-weight: 600;
        color: #6c757d;
    }

    dl dd {
        margin-left: 0;
    }
</style>
@endsection
