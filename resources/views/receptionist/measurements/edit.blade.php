@extends('receptionist.layouts.app')

@section('title', 'Edit Measurement - ' . $measurement->profile_name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.measurements.index') }}">Measurements</a></li>
    <li class="breadcrumb-item"><a href="{{ route('receptionist.measurements.show', $measurement) }}">{{ $measurement->profile_name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-ruler-combined me-2"></i>Edit Measurement
            </h1>
            <p class="text-muted">{{ $measurement->profile_name }} - {{ $measurement->user->name }}</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.measurements.show', $measurement) }}" class="btn btn-outline-secondary">
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

<form method="POST" action="{{ route('receptionist.measurements.update', $measurement) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                            <option value="{{ $customer->id }}" {{ old('customer_id', $measurement->user_id) == $customer->id ? 'selected' : '' }}>
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
                               value="{{ old('profile_name', $measurement->profile_name) }}" required>
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
                                   id="chest" name="chest" placeholder="0.00" value="{{ old('chest', $measurement->chest) }}">
                            @error('chest')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="shoulder" class="form-label fw-bold">Shoulder (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('shoulder') is-invalid @enderror" 
                                   id="shoulder" name="shoulder" placeholder="0.00" value="{{ old('shoulder', $measurement->shoulder) }}">
                            @error('shoulder')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sleeve_length" class="form-label fw-bold">Sleeve Length (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('sleeve_length') is-invalid @enderror" 
                                   id="sleeve_length" name="sleeve_length" placeholder="0.00" value="{{ old('sleeve_length', $measurement->sleeve_length) }}">
                            @error('sleeve_length')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="shirt_length" class="form-label fw-bold">Shirt Length (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('shirt_length') is-invalid @enderror" 
                                   id="shirt_length" name="shirt_length" placeholder="0.00" value="{{ old('shirt_length', $measurement->shirt_length) }}">
                            @error('shirt_length')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="neck" class="form-label fw-bold">Neck (cm)</label>
                        <input type="number" step="0.01" class="form-control @error('neck') is-invalid @enderror" 
                               id="neck" name="neck" placeholder="0.00" value="{{ old('neck', $measurement->neck) }}">
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
                                   id="waist" name="waist" placeholder="0.00" value="{{ old('waist', $measurement->waist) }}">
                            @error('waist')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trouser_length" class="form-label fw-bold">Trouser Length (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('trouser_length') is-invalid @enderror" 
                                   id="trouser_length" name="trouser_length" placeholder="0.00" value="{{ old('trouser_length', $measurement->trouser_length) }}">
                            @error('trouser_length')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bottom" class="form-label fw-bold">Bottom/Hip (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('bottom') is-invalid @enderror" 
                                   id="bottom" name="bottom" placeholder="0.00" value="{{ old('bottom', $measurement->bottom) }}">
                            @error('bottom')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="thigh" class="form-label fw-bold">Thigh (cm)</label>
                            <input type="number" step="0.01" class="form-control @error('thigh') is-invalid @enderror" 
                                   id="thigh" name="thigh" placeholder="0.00" value="{{ old('thigh', $measurement->thigh) }}">
                            @error('thigh')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cuff_size" class="form-label fw-bold">Cuff Size (cm)</label>
                        <input type="number" step="0.01" class="form-control @error('cuff_size') is-invalid @enderror" 
                               id="cuff_size" name="cuff_size" placeholder="0.00" value="{{ old('cuff_size', $measurement->cuff_size) }}">
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
                        @if($measurement->design_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $measurement->design_image) }}" alt="Design" class="img-thumbnail" style="max-width: 200px;">
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()">
                                    <i class="fas fa-trash me-1"></i>Remove Image
                                </button>
                                <input type="hidden" id="remove_image" name="remove_image" value="0">
                            </div>
                        </div>
                        @endif
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
                                  placeholder="Any special notes for this measurement...">{{ old('special_instructions', $measurement->special_instructions) }}</textarea>
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
                                  placeholder="Additional notes about this profile...">{{ old('notes', $measurement->notes) }}</textarea>
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
                                   value="1" {{ old('is_default', $measurement->is_default) ? 'checked' : '' }}>
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
                        <i class="fas fa-info-circle me-2"></i>Profile Information
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <small class="text-muted d-block">Created Date</small>
                        <strong>{{ $measurement->created_at->format('M d, Y') }}</strong>
                    </p>
                    <p class="mb-2">
                        <small class="text-muted d-block">Last Updated</small>
                        <strong>{{ $measurement->updated_at->format('M d, Y h:i A') }}</strong>
                    </p>
                    <p>
                        <small class="text-muted d-block">Used in Orders</small>
                        <strong>{{ $measurement->stitchingOrders->count() }} order(s)</strong>
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card border-0 shadow mb-3">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <a href="{{ route('receptionist.measurements.duplicate', $measurement) }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-copy me-2"></i>Duplicate
                    </a>
                    <a href="{{ route('receptionist.measurements.show', $measurement) }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-danger border-2 shadow">
                <div class="card-header bg-danger text-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        Permanently delete this measurement profile. This cannot be undone.
                    </p>
                    <button type="button" class="btn btn-danger w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i>Delete Profile
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title">Delete Measurement Profile</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('receptionist.measurements.destroy', $measurement) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="mb-0">
                        Are you sure you want to delete <strong>{{ $measurement->profile_name }}</strong>?<br>
                        This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function removeImage() {
    document.getElementById('remove_image').value = '1';
    document.querySelector('img[alt="Design"]').parentElement.style.display = 'none';
}
</script>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}
</style>
@endsection
