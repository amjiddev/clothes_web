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
                            <label class="form-label fw-bold">Response Time</label>
                            <input type="text" name="contact_response_time" class="form-control @error('contact_response_time') is-invalid @enderror" value="{{ old('contact_response_time', $contactInfo->data['response_time'] ?? '') }}" placeholder="2-4 hours">
                            @error('contact_response_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                    </div>
                </div>
            </div>
        </div>
    </form>
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
    transition: box-shadow 0.2s;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endsection
