@extends('admin.layouts.app')

@section('title', 'Contact Page Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Contact Page</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-address-book me-2"></i>Contact Page Management
                </h1>
                <p class="text-muted">Update contact page information directly from this form</p>
            </div>
        </div>
    </div>

    <form action="{{ $contactInfo ? route('admin.website-management.contact.update', $contactInfo->id) : route('admin.website-management.contact.store') }}" method="POST">
        @csrf
        @if($contactInfo)
            @method('PUT')
        @endif
        <input type="hidden" name="section_type" value="contact">

        <div class="row">
            <!-- Main Contact Information -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Main Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Page Title <span class="text-danger">*</span></label>
                            <input type="text" name="page_title" class="form-control @error('page_title') is-invalid @enderror" value="{{ old('page_title', $contactInfo->page_title ?? 'Contact Us') }}" required>
                            @error('page_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Page Description</label>
                            <textarea name="page_content" class="form-control @error('page_content') is-invalid @enderror" rows="2" placeholder="We'd love to hear from you!">{{ old('page_content', $contactInfo->page_content ?? '') }}</textarea>
                            @error('page_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">This will appear below the page title</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $contactInfo->data['email'] ?? '') }}" placeholder="info@example.com">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $contactInfo->data['phone'] ?? '') }}" placeholder="+91-XXXXXXXXXX">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Address</label>
                            <textarea name="contact_address" class="form-control @error('contact_address') is-invalid @enderror" rows="3" placeholder="Street, City, State, Pincode">{{ old('contact_address', $contactInfo->data['address'] ?? '') }}</textarea>
                            @error('contact_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Business Hours / Timings</label>
                            <input type="text" name="contact_timings" class="form-control @error('contact_timings') is-invalid @enderror" value="{{ old('contact_timings', $contactInfo->data['timings'] ?? '') }}" placeholder="Mon - Fri: 10 AM - 6 PM">
                            @error('contact_timings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Google Maps URL</label>
                            <input type="url" name="contact_map_url" class="form-control @error('contact_map_url') is-invalid @enderror" value="{{ old('contact_map_url', $contactInfo->data['map_url'] ?? '') }}" placeholder="https://maps.google.com/...">
                            @error('contact_map_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional: Link to your location on Google Maps</small>
                        </div>
                    </div>
                </div>

                <!-- Store Locations -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Store Locations</h5>
                        <button type="button" class="btn btn-sm btn-success" onclick="addLocation()">
                            <i class="fas fa-plus me-1"></i>Add Location
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="locations-container">
                            @if($locations && $locations->count() > 0)
                                @foreach($locations as $index => $location)
                                    <div class="location-item border rounded p-3 mb-3 position-relative">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeLocation({{ $location->id }}, this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <input type="hidden" name="locations[{{ $index }}][id]" value="{{ $location->id }}">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-bold">Location Name</label>
                                                <input type="text" name="locations[{{ $index }}][title]" class="form-control form-control-sm" value="{{ $location->page_title }}" placeholder="Main Store" required>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-bold">Phone</label>
                                                <input type="text" name="locations[{{ $index }}][phone]" class="form-control form-control-sm" value="{{ $location->data['phone'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-bold">Email</label>
                                                <input type="email" name="locations[{{ $index }}][email]" class="form-control form-control-sm" value="{{ $location->data['email'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-bold">Timings</label>
                                                <input type="text" name="locations[{{ $index }}][timings]" class="form-control form-control-sm" value="{{ $location->data['timings'] ?? '' }}">
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label class="form-label small fw-bold">Address</label>
                                                <textarea name="locations[{{ $index }}][address]" class="form-control form-control-sm" rows="2">{{ $location->data['address'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center py-3">No locations added yet. Click "Add Location" to add store locations.</p>
                            @endif
                        </div>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> After adding or editing locations, click the "Save Contact Page" button below to save all changes.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Publishing Options -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Publishing</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="is_published" class="form-check-input" id="is_published" value="1" {{ old('is_published', $contactInfo->is_published ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_published">Publish Contact Page</label>
                        </div>
                        @if($contactInfo && $contactInfo->published_at)
                            <small class="text-muted d-block">
                                <i class="fas fa-calendar me-1"></i>Published on {{ $contactInfo->published_at->format('M d, Y H:i') }}
                            </small>
                        @endif
                    </div>
                </div>

                <!-- Save Button -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="fas fa-save me-2"></i>{{ $contactInfo ? 'Update' : 'Save' }} Contact Page
                        </button>
                        <a href="{{ route('contact') }}" class="btn btn-outline-secondary w-100" target="_blank">
                            <i class="fas fa-eye me-2"></i>Preview Contact Page
                        </a>
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> Click this button to save all contact information and locations together.
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Help</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold">Tips:</h6>
                        <ul class="small ps-3 mb-0">
                            <li>Fill in main contact info that appears in sidebar</li>
                            <li>Add multiple store locations if needed</li>
                            <li>Use Google Maps share link for map URL</li>
                            <li>Check "Publish" to make changes live</li>
                            <li>Click "Preview" to see before publishing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let locationIndex = {{ $locations ? $locations->count() : 0 }};
let deletedLocations = [];

function addLocation() {
    const container = document.getElementById('locations-container');
    
    // Remove "no locations" message if exists
    const noLocationsMsg = container.querySelector('p.text-muted');
    if (noLocationsMsg) {
        noLocationsMsg.remove();
    }
    
    const locationHtml = `
        <div class="location-item border rounded p-3 mb-3 position-relative" data-location-index="${locationIndex}">
            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeNewLocation(this)">
                <i class="fas fa-times"></i>
            </button>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label small fw-bold">Location Name</label>
                    <input type="text" name="new_locations[${locationIndex}][title]" class="form-control form-control-sm" placeholder="Main Store" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label small fw-bold">Phone</label>
                    <input type="text" name="new_locations[${locationIndex}][phone]" class="form-control form-control-sm">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="new_locations[${locationIndex}][email]" class="form-control form-control-sm">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label small fw-bold">Timings</label>
                    <input type="text" name="new_locations[${locationIndex}][timings]" class="form-control form-control-sm">
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label small fw-bold">Address</label>
                    <textarea name="new_locations[${locationIndex}][address]" class="form-control form-control-sm" rows="2"></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', locationHtml);
    locationIndex++;
}

function removeNewLocation(button) {
    if (confirm('Remove this location?')) {
        button.closest('.location-item').remove();
        
        // Check if container is empty
        const container = document.getElementById('locations-container');
        if (container.children.length === 0) {
            container.innerHTML = '<p class="text-muted text-center py-3">No locations added yet. Click "Add Location" to add store locations.</p>';
        }
    }
}

function removeLocation(locationId, button) {
    if (confirm('Are you sure you want to delete this location? This will be permanent after saving.')) {
        // Add to deleted list
        deletedLocations.push(locationId);
        
        // Add hidden input to track deletion
        const form = button.closest('form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_locations[]';
        input.value = locationId;
        form.appendChild(input);
        
        // Remove the location item
        button.closest('.location-item').remove();
        
        // Check if container is empty
        const container = document.getElementById('locations-container');
        if (container.children.length === 0) {
            container.innerHTML = '<p class="text-muted text-center py-3">No locations added yet. Click "Add Location" to add store locations.</p>';
        }
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

.location-item {
    background: #f8f9fa;
    transition: all 0.2s;
}

.location-item:hover {
    background: #e9ecef;
}

.card {
    transition: box-shadow 0.2s;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endsection
