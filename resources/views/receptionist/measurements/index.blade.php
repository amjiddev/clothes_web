@extends('receptionist.layouts.app')

@section('title', 'Measurements')

@section('breadcrumb')
    <li class="breadcrumb-item active">Measurements</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-ruler me-2"></i>Customer Measurements
            </h1>
            <p class="text-muted">Manage customer measurement profiles</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.measurements.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Measurement
            </a>
        </div>
    </div>
</div>

<!-- Success Message -->
@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Error Messages -->
@if ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Search & Filter Section -->
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form action="{{ route('receptionist.measurements.index') }}" method="GET" class="row g-3">
            <!-- Search Input -->
            <div class="col-md-5">
                <label class="form-label fw-bold">
                    <i class="fas fa-search me-2"></i>Search
                </label>
                <input type="text" name="search" class="form-control" placeholder="Profile name or customer..." value="{{ request('search') }}">
            </div>

            <!-- Customer Filter -->
            <div class="col-md-5">
                <label class="form-label fw-bold">
                    <i class="fas fa-user me-2"></i>Customer
                </label>
                <select name="customer_id" class="form-select">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }} ({{ $customer->email }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('receptionist.measurements.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Measurements Table -->
<div class="card border-0 shadow">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Customer</th>
                    <th>Profile Name</th>
                    <th>Upper (Chest)</th>
                    <th>Lower (Waist)</th>
                    <th>Created Date</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($measurements as $measurement)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold">{{ $measurement->user->name }}</div>
                        <small class="text-muted">{{ $measurement->user->email }}</small>
                    </td>
                    <td>
                        <span class="d-flex align-items-center gap-2">
                            @if($measurement->is_default)
                            <span class="badge bg-success">Default</span>
                            @endif
                            {{ $measurement->profile_name }}
                        </span>
                    </td>
                    <td>
                        @if($measurement->chest)
                        <span class="badge bg-light text-dark">
                            {{ $measurement->chest }} cm
                        </span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($measurement->waist)
                        <span class="badge bg-light text-dark">
                            {{ $measurement->waist }} cm
                        </span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <small>{{ $measurement->created_at->format('M d, Y') }}</small><br>
                        <small class="text-muted">{{ $measurement->created_at->format('h:i A') }}</small>
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('receptionist.measurements.show', $measurement) }}" 
                               class="btn btn-outline-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('receptionist.measurements.edit', $measurement) }}" 
                               class="btn btn-outline-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('receptionist.measurements.duplicate', $measurement) }}" 
                               class="btn btn-outline-primary" title="Duplicate">
                                <i class="fas fa-copy"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $measurement->id }}"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal{{ $measurement->id }}" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content border-danger">
                            <div class="modal-header bg-danger text-white">
                                <h6 class="modal-title">Delete Measurement</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('receptionist.measurements.destroy', $measurement) }}">
                                @csrf
                                @method('DELETE')
                                <div class="modal-body">
                                    <p class="mb-0">
                                        Are you sure you want to delete the measurement profile 
                                        <strong>{{ $measurement->profile_name }}</strong> for 
                                        <strong>{{ $measurement->user->name }}</strong>?
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

                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted">No measurement profiles found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($measurements->hasPages())
    <div class="card-footer border-top">
        {{ $measurements->links() }}
    </div>
    @endif
</div>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

.btn-group-sm .btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endsection
