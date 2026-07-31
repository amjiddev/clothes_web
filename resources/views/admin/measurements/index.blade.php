@extends('layout.app')

@section('title', 'Measurements')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark">Measurements</h2>
        </div>
        <div class="col-md-6 text-end">
            @can('create_measurements')
            <a href="{{ route('measurements.create') }}" class="btn btn-dark">
                <i class="fas fa-plus me-2"></i>Add Measurement
            </a>
            @endcan
        </div>
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="fw-bold">Customer</th>
                        <th class="fw-bold">Title</th>
                        <th class="fw-bold">Chest</th>
                        <th class="fw-bold">Waist</th>
                        <th class="fw-bold">Default</th>
                        <th class="fw-bold">Date</th>
                        <th class="fw-bold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($measurements as $measurement)
                    <tr>
                        <td>{{ $measurement->user->name }}</td>
                        <td>{{ $measurement->title ?? 'Standard' }}</td>
                        <td>{{ $measurement->chest ?? '-' }}</td>
                        <td>{{ $measurement->waist ?? '-' }}</td>
                        <td>
                            @if($measurement->is_default)
                            <span class="badge bg-success">Yes</span>
                            @else
                            <span class="badge bg-light text-dark">No</span>
                            @endif
                        </td>
                        <td>{{ $measurement->created_at->format('M d, Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('measurements.show', $measurement) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('edit_measurements')
                            <a href="{{ route('measurements.edit', $measurement) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            @can('delete_measurements')
                            <form action="{{ route('measurements.destroy', $measurement) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No measurements found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            {{ $measurements->links() }}
        </div>
    </div>
</div>
@endsection
