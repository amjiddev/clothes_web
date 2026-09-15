@extends('admin.layouts.app')

@section('title', 'View Submission')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.contact-submissions.index', ['type' => $submission->type]) }}">
            {{ $submission->type === 'message' ? 'Contact Messages' : 'Tailoring Service Requests' }}
        </a>
    </li>
    <li class="breadcrumb-item active">View Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas {{ $submission->type === 'message' ? 'fa-envelope' : 'fa-scissors' }} me-2"></i>
                    {{ $submission->type === 'message' ? 'Message Details' : 'Tailoring Request Details' }}
                </h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.contact-submissions.index', ['type' => $submission->type]) }}" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Sender Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-user me-2"></i>Sender Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted">Full Name</p>
                            <p class="mb-3">{{ $submission->full_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted">Email</p>
                            <p class="mb-3">
                                <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted">Phone</p>
                            <p class="mb-3">
                                <a href="tel:{{ $submission->phone }}">{{ $submission->phone }}</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted">Received On</p>
                            <p class="mb-3">{{ $submission->created_at->format('M d, Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            @if($submission->type === 'message')
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-message me-2"></i>Message
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Subject</p>
                    <p class="mb-4"><strong>{{ $submission->subject }}</strong></p>
                    
                    <p class="text-muted">Message</p>
                    <div class="p-3 bg-light rounded" style="border-left: 4px solid #D4AF37;">
                        {!! nl2br(e($submission->message)) !!}
                    </div>
                </div>
            </div>
            @else
            <!-- Tailoring Request Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-scissors me-2"></i>Service Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted">Service Type</p>
                            <p class="mb-3">
                                <span class="badge bg-info">{{ $submission->service_type }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted">Garment Type</p>
                            <p class="mb-3">{{ $submission->garment_type }}</p>
                        </div>
                    </div>

                    @if($submission->measurements)
                    <hr>
                    <p class="text-muted mb-2"><strong>Body Measurements</strong></p>
                    <div class="row">
                        @foreach($submission->measurements as $key => $value)
                        <div class="col-md-6">
                            <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}</small>
                            <p class="mb-2">{{ $value }} cm</p>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($submission->special_instructions)
                    <hr>
                    <p class="text-muted">Special Instructions</p>
                    <div class="p-3 bg-light rounded" style="border-left: 4px solid #D4AF37;">
                        {!! nl2br(e($submission->special_instructions)) !!}
                    </div>
                    @endif

                    @if($submission->design_image)
                    <hr>
                    <p class="text-muted mb-3">Design Image</p>
                    <div>
                        <img src="{{ asset('storage/' . $submission->design_image) }}" 
                             alt="Design Image" class="img-fluid rounded" style="max-width: 300px; max-height: 400px;">
                        <br>
                        <a href="{{ asset('storage/' . $submission->design_image) }}" 
                           target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fas fa-download me-2"></i>Download Image
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.contact-submissions.destroy', $submission->id) }}" 
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this submission?')">
                            <i class="fas fa-trash me-2"></i>Delete Submission
                        </button>
                    </form>
                    <a href="{{ route('admin.contact-submissions.index', ['type' => $submission->type]) }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Close
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <p class="text-muted mb-2">Status</p>
                    @if($submission->is_read)
                        <span class="badge bg-success p-2">
                            <i class="fas fa-check-circle me-1"></i>Read
                        </span>
                        <small class="text-muted d-block mt-2">
                            Read on: {{ $submission->read_at->format('M d, Y H:i') }}
                        </small>
                    @else
                        <span class="badge bg-warning text-dark p-2">
                            <i class="fas fa-exclamation-circle me-1"></i>New/Unread
                        </span>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="mailto:{{ $submission->email }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="fas fa-reply me-2"></i>Reply via Email
                    </a>
                    <a href="tel:{{ $submission->phone }}" class="btn btn-outline-info btn-sm w-100">
                        <i class="fas fa-phone me-2"></i>Call Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
