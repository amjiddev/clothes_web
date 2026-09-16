@extends('admin.layouts.app')

@section('title', $type === 'message' ? 'Contact Messages' : 'Tailoring Service Requests')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">
        {{ $type === 'message' ? 'Contact Messages' : 'Tailoring Service Requests' }}
    </li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas {{ $type === 'message' ? 'fa-envelope' : 'fa-scissors' }} me-2"></i>
                    {{ $type === 'message' ? 'Contact Messages' : 'Tailoring Service Requests' }}
                </h1>
                <p class="text-muted">
                    {{ $type === 'message' ? 'Manage customer messages from contact page' : 'Manage tailoring service requests' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Type Tabs -->
    <div class="mb-4">
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <a href="{{ route('admin.contact-submissions.index', ['type' => 'message']) }}" 
                   class="nav-link {{ $type === 'message' ? 'active' : '' }}">
                    <i class="fas fa-envelope me-2"></i>Contact Messages
                    <span class="badge bg-secondary ms-2">
                        {{ \App\Models\ContactSubmission::where('type', 'message')->count() }}
                    </span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Submissions Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-bottom">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="mb-0">
                        {{ $type === 'message' ? 'All Messages' : 'All Tailoring Requests' }}
                    </h6>
                </div>
                <div class="col-auto">
                    <span class="badge bg-info">{{ $submissions->total() }} Total</span>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($submissions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">Status</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>{{ $type === 'message' ? 'Subject' : 'Service Type' }}</th>
                            <th>Received</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                        <tr>
                            <td>
                                @if($submission->is_read)
                                    <span class="badge bg-success">Read</span>
                                @else
                                    <span class="badge bg-warning text-dark">New</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $submission->full_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $submission->phone }}</small>
                            </td>
                            <td>
                                <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
                            </td>
                            <td>
                                @if($type === 'message')
                                    {{ $submission->subject ?? 'N/A' }}
                                @else
                                    <span class="badge bg-info">{{ $submission->service_type }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $submission->created_at->format('M d, Y H:i') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.contact-submissions.show', $submission->id) }}" 
                                       class="btn btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.contact-submissions.destroy', $submission->id) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" 
                                                onclick="return confirm('Are you sure?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No submissions found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-light">
                {{ $submissions->links() }}
            </div>
            @else
            <div class="p-5 text-center">
                <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                <p class="text-muted">No submissions yet</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
