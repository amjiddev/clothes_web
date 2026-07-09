@extends('admin.layouts.app')

@section('title', 'Categories')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Categories</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-list me-2"></i>Categories</h1>
                <p class="text-muted">Manage product categories</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Category
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Categories Table -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light border-bottom">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="mb-0">All Categories</h6>
                </div>
                <div class="col-auto">
                    <input type="search" class="form-control form-control-sm" placeholder="Search categories..." id="searchInput">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>
                                <strong>{{ $category->name }}</strong>
                            </td>
                            <td>
                                <code>{{ $category->slug }}</code>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $category->products_count }}</span>
                            </td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $category->sort_order }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">No categories found</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>Create First Category
                </a>
            </div>
            @endif
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
        margin: 0;
    }

    .card {
        border-radius: 0.5rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('table tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endsection
