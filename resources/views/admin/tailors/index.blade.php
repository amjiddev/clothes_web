@extends('admin.layouts.app')

@section('title', 'Tailors Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tailors</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-needle me-2"></i>Tailors Management</h1>
                <p class="text-muted">Manage all shop tailors</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.tailors.create') }}" class="btn btn-dark">
                    <i class="fas fa-plus me-2"></i>Add Tailor
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

    <!-- Search & Filter Section -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.tailors.index') }}" method="GET" class="row g-3">
                <!-- Search Input -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Name or email..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Specialization Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Specialization</label>
                    <select name="specialization" class="form-select">
                        <option value="">All</option>
                        @foreach($specializations as $spec)
                        <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>
                            {{ $spec }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.tailors.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tailors Table -->
    <div class="card border-0 shadow">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="fw-bold">
                            <i class="fas fa-user me-2"></i>Name
                        </th>
                        <th class="fw-bold">Phone</th>
                        <th class="fw-bold">Specialization</th>
                        <th class="fw-bold text-center">Experience</th>
                        <th class="fw-bold text-center">Active Orders</th>
                        <th class="fw-bold text-center">Status</th>
                        <th class="fw-bold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tailors as $tailor)
                    <tr>
                        <!-- Name with Avatar -->
                        <td>
                            <div class="d-flex align-items-center">
                                @if($tailor->profile_image)
                                <img src="{{ $tailor->profile_image_url }}" alt="{{ $tailor->user->name }}" 
                                     class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                                @endif
                                <strong>{{ $tailor->user->name }}</strong>
                            </div>
                        </td>

                        <!-- Phone -->
                        <td>
                            @if($tailor->phone)
                            <a href="tel:{{ $tailor->phone }}">{{ $tailor->phone }}</a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Specialization -->
                        <td>
                            @if($tailor->specialization)
                            <span class="badge bg-light text-dark">{{ $tailor->specialization }}</span>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        <!-- Experience -->
                        <td class="text-center">
                            @if($tailor->experience_years)
                            <span class="badge bg-info">{{ $tailor->experience_years }} yrs</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Active Orders -->
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $tailor->active ?? 0 }}</span>
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            @if($tailor->status === 'active')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                            @elseif($tailor->status === 'on_leave')
                            <span class="badge bg-warning">
                                <i class="fas fa-clock me-1"></i>On Leave
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>Inactive
                            </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.tailors.show', $tailor) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.tailors.edit', $tailor) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.tailors.destroy', $tailor) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" 
                                            onclick="return confirm('Are you sure you want to delete this tailor?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No tailors found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted">
                        Showing {{ $tailors->firstItem() ?? 0 }} to {{ $tailors->lastItem() ?? 0 }} of {{ $tailors->total() }} tailors
                    </small>
                </div>
                <div class="col-auto">
                    {{ $tailors->links() }}
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
    color: #1a1a1a;
    margin: 0;
}

.btn-group .btn {
    padding: 6px 12px;
}
</style>
@endsection
