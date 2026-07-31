@extends('receptionist.layouts.app')

@section('title', 'Tailors')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tailors</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-scissors me-2"></i>Tailors Management
            </h1>
            <p class="text-muted">View and manage tailor assignments</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.tailors.assign-form') }}" class="btn btn-primary">
                <i class="fas fa-tasks me-2"></i>Assign Order
            </a>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form action="{{ route('receptionist.tailors.index') }}" method="GET" class="row g-3">
            <!-- Search Input -->
            <div class="col-md-5">
                <label class="form-label fw-bold">
                    <i class="fas fa-search me-2"></i>Search
                </label>
                <input type="text" name="search" class="form-control" placeholder="Name or phone..." value="{{ request('search') }}">
            </div>

            <!-- Specialization Filter -->
            <div class="col-md-5">
                <label class="form-label fw-bold">
                    <i class="fas fa-filter me-2"></i>Specialization
                </label>
                <select name="specialization" class="form-select">
                    <option value="">All Specializations</option>
                    @foreach($specializations as $spec)
                    <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>
                        {{ $spec }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('receptionist.tailors.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tailors Table -->
<div class="card border-0 shadow">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Name</th>
                    <th>Phone</th>
                    <th>Specialization</th>
                    <th>Active Orders</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tailors as $tailor)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold">{{ $tailor->user->name }}</div>
                        <small class="text-muted">ID: #{{ $tailor->user_id }}</small>
                    </td>
                    <td>
                        <a href="tel:{{ $tailor->phone }}">{{ $tailor->phone }}</a>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">
                            {{ $tailor->specialization ?? 'General' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary">{{ $tailor->getActiveOrders() }}</span>
                            <small class="text-muted">/ {{ $tailor->getTotalAssignedOrders() }}</small>
                        </div>
                    </td>
                    <td>
                        @if($tailor->status === 'active')
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Active
                        </span>
                        @elseif($tailor->status === 'on_leave')
                        <span class="badge bg-warning">
                            <i class="fas fa-calendar-times me-1"></i>On Leave
                        </span>
                        @else
                        <span class="badge bg-danger">
                            <i class="fas fa-times-circle me-1"></i>Inactive
                        </span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('receptionist.tailors.show', $tailor) }}" 
                               class="btn btn-outline-info" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('receptionist.tailors.tailor-orders', $tailor) }}" 
                               class="btn btn-outline-primary" title="View Orders">
                                <i class="fas fa-tasks"></i>
                            </a>
                            <a href="{{ route('receptionist.tailors.view-dashboard', $tailor) }}" 
                               class="btn btn-outline-secondary" title="View Dashboard">
                                <i class="fas fa-chart-line"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted">No active tailors found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($tailors->hasPages())
    <div class="card-footer border-top">
        {{ $tailors->links() }}
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
