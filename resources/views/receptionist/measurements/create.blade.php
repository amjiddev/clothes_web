@extends('receptionist.layouts.app')

@section('title', 'Add Measurement')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.measurements.index') }}">Measurements</a></li>
    <li class="breadcrumb-item active">Add Measurement</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-ruler-combined me-2"></i>Add Customer Measurement
            </h1>
            <p class="text-muted">Create a new measurement profile</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.measurements.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<!-- Error Messages -->
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>Please fix the errors below:</strong>
    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form method="POST" action="{{ route('receptionist.measurements.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Basic Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label fw-bold">
                            <i class="fas fa-user me-2"></i>Customer <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg @error('customer_id') is-invalid @enderror" 
                                id="customer_id" name="customer_id" required>
                            <option value="">-- Select Customer --</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="profile_name" class="form-label fw-bold">
                            <i class="fas fa-tag me-2"></i>Measurement Profile Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg @error('profile_name') is-invalid @enderror" 
                               id="profile_name" name="profile_name" placeholder="e.g., Wedding Suit Measurement"
                               value="{{ old('profile_name') }}" required>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-lightbulb me-1"></i>Give this profile a descriptive name
                        </small>
                        @error('profile_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Upper Body Measurements -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-person me-2"></i>Upper Body Measurements
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="chest" class="form-label fw-bold">Chest (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('chest') is-invalid @enderror" 
                                   id="chest" name="chest" placeholder="0.00" value="{{ old('chest') }}">
                            @error('chest')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="shoulder" class="form-label fw-bold">Shoulder (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('shoulder') is-invalid @enderror" 
                                   id="shoulder" name="shoulder" placeholder="0.00" value="{{ old('shoulder') }}">
                            @error('shoulder')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sleeve_length" class="form-label fw-bold">Sleeve Length (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('sleeve_length') is-invalid @enderror" 
                                   id="sleeve_length" name="sleeve_length" placeholder="0.00" value="{{ old('sleeve_length') }}">
                            @error('sleeve_length')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="shirt_length" class="form-label fw-bold">Shirt Length (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('shirt_length') is-invalid @enderror" 
                                   id="shirt_length" name="shirt_length" placeholder="0.00" value="{{ old('shirt_length') }}">
                            @error('shirt_length')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="neck" class="form-label fw-bold">Neck (cm)</label>
                        <input type="number" step="0.01" class="form-control @error('neck') is-invalid @enderror" 
                               id="neck" name="neck" placeholder="0.00" value="{{ old('neck') }}">
                        @error('neck')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Lower Body Measurements -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-person me-2"></i>Lower Body Measurements
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="waist" class="form-label fw-bold">Waist (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('waist') is-invalid @enderror" 
                                   id="waist" name="waist" placeholder="0.00" value="{{ old('waist') }}">
                            @error('waist')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trouser_length" class="form-label fw-bold">Trouser Length (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('trouser_length') is-invalid @enderror" 
                                   id="trouser_length" name="trouser_length" placeholder="0.00" value="{{ old('trouser_length') }}">
                            @error('trouser_length')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bottom" class="form-label fw-bold">Bottom/Hip (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('bottom') is-invalid @enderror" 
                                   id="bottom" name="bottom" placeholder="0.00" value="{{ old('bottom') }}">
                            @error('bottom')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="thigh" class="form-label fw-bold">Thigh (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('thigh') is-invalid @enderror" 
                                   id="thigh" name="thigh" placeholder="0.00" value="{{ old('thigh') }}">
                            @error('thigh')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cuff_size" class="form-label fw-bold">Cuff Size (cm)</label>
                        <input type="number" step="0.01" class="form-control @error('cuff_size') is-invalid @enderror" 
                               id="cuff_size" name="cuff_size" placeholder="0.00" value="{{ old('cuff_size') }}">
                        @error('cuff_size')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-plus-circle me-2"></i>Additional Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="design_image" class="form-label fw-bold">
                            <i class="fas fa-image me-2"></i>Design Image
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('design_image') is-invalid @enderror" 
                                   id="design_image" name="design_image" accept="image/*">
                            <small class="form-text text-muted d-block mt-1">
                                Accepted: JPEG, PNG, GIF (Max 2MB)
                            </small>
                        </div>
                        @error('design_image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="special_instructions" class="form-label fw-bold">
                            <i class="fas fa-comment me-2"></i>Special Instructions
                        </label>
                        <textarea class="form-control @error('special_instructions') is-invalid @enderror" 
                                  id="special_instructions" name="special_instructions" rows="3"
                                  placeholder="Any special notes for this measurement...">{{ old('special_instructions') }}</textarea>
                        <small class="text-muted d-block mt-1">
                            Special tailoring instructions or fitting notes (max 500 chars)
                        </small>
                        @error('special_instructions')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-bold">
                            <i class="fas fa-sticky-note me-2"></i>Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3"
                                  placeholder="Additional notes about this profile...">{{ old('notes') }}</textarea>
                        <small class="text-muted d-block mt-1">
                            Internal notes about this measurement profile (max 500 chars)
                        </small>
                        @error('notes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default" 
                                   value="1" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">
                                <strong>Set as default profile for this customer</strong>
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1">
                            This profile will be pre-selected when creating orders for this customer
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Info -->
            <div class="card border-0 shadow mb-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Quick Info
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>All measurements in centimeters</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Use 2 decimal places for accuracy</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Optional fields can be left blank</small>
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Upload design images for reference</small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Measurement Guide -->
            <div class="card border-0 shadow mb-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-ruler me-2"></i>Measurement Tips
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2"><strong>Chest:</strong> Around the fullest part of the chest</p>
                    <p class="small mb-2"><strong>Shoulder:</strong> From shoulder to shoulder</p>
                    <p class="small mb-2"><strong>Sleeve:</strong> From shoulder point to wrist</p>
                    <p class="small mb-2"><strong>Waist:</strong> Around the natural waist</p>
                    <p class="small mb-2"><strong>Trouser Length:</strong> From waist to ankle</p>
                    <p class="small"><strong>Thigh:</strong> Around the fullest part of thigh</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card border-0 shadow">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-save me-2"></i>Save Measurement
                    </button>
                    <a href="{{ route('receptionist.measurements.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}
</style>
@endsection
