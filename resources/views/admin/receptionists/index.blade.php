@extends('admin.layouts.app')

@section('title', 'Receptionists Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Receptionists</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-headset me-2"></i>Receptionists Management</h1>
                <p class="text-muted">Manage shop receptionists and their permissions</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.receptionists.create') }}" class="btn btn-dark">
                    <i class="fas fa-plus me-2"></i>Add Receptionist
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
            <form action="{{ route('admin.receptionists.index') }}" method="GET" class="row g-3">
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

                <!-- Department Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Department</label>
                    <select name="department" class="form-select">
                        <option value="">All</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                            {{ $dept }}
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
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.receptionists.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Receptionists Table -->
    <div class="card border-0 shadow">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="fw-bold">
                            <i class="fas fa-user me-2"></i>Name
                        </th>
                        <th class="fw-bold">Email</th>
                        <th class="fw-bold">Phone</th>
                        <th class="fw-bold">Department</th>
                        <th class="fw-bold text-center">Status</th>
                        <th class="fw-bold text-center">Assigned Date</th>
                        <th class="fw-bold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receptionists as $receptionist)
                    <tr>
                        <!-- Name -->
                        <td>
                            <strong>{{ $receptionist->user->name }}</strong>
                        </td>

                        <!-- Email -->
                        <td>
                            <a href="mailto:{{ $receptionist->user->email }}">{{ $receptionist->user->email }}</a>
                        </td>

                        <!-- Phone -->
                        <td>
                            @if($receptionist->phone)
                            <a href="tel:{{ $receptionist->phone }}">{{ $receptionist->phone }}</a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Department -->
                        <td>
                            <span class="badge bg-light text-dark">{{ $receptionist->department }}</span>
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            @if($receptionist->status === 'active')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>Inactive
                            </span>
                            @endif
                        </td>

                        <!-- Assigned Date -->
                        <td class="text-center">
                            @if($receptionist->assigned_date)
                            <small class="text-muted">{{ $receptionist->assigned_date->format('M d, Y') }}</small>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.receptionists.show', $receptionist) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.receptionists.edit', $receptionist) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($receptionist->status === 'active')
                                <form action="{{ route('admin.receptionists.deactivate', $receptionist) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary" title="Deactivate" 
                                            onclick="return confirm('Deactivate this receptionist?')">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.receptionists.activate', $receptionist) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Activate">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.receptionists.destroy', $receptionist) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" 
                                            onclick="return confirm('Are you sure you want to delete this receptionist?')">
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
                                <p>No receptionists found.</p>
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
                        Showing {{ $receptionists->firstItem() ?? 0 }} to {{ $receptionists->lastItem() ?? 0 }} of {{ $receptionists->total() }} receptionists
                    </small>
                </div>
                <div class="col-auto">
                    {{ $receptionists->links() }}
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
