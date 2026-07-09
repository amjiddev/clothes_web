@extends('admin.layouts.app')

@section('title', $category->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">{{ $category->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">{{ $category->name }}</h1>
                <p class="text-muted">Category details and products</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Category Info -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Category Information</h6>
                </div>
                <div class="card-body">
                    @if($category->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid rounded">
                    </div>
                    @endif

                    <dl>
                        <dt>Name:</dt>
                        <dd>{{ $category->name }}</dd>

                        <dt class="mt-2">Slug:</dt>
                        <dd><code>{{ $category->slug }}</code></dd>

                        <dt class="mt-2">Status:</dt>
                        <dd>
                            @if($category->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </dd>

                        <dt class="mt-2">Sort Order:</dt>
                        <dd>{{ $category->sort_order }}</dd>

                        <dt class="mt-2">Created:</dt>
                        <dd>{{ $category->created_at->format('M d, Y') }}</dd>

                        <dt class="mt-2">Updated:</dt>
                        <dd>{{ $category->updated_at->format('M d, Y') }}</dd>
                    </dl>

                    @if($category->description)
                    <hr>
                    <h6 class="mb-2">Description</h6>
                    <p class="text-muted">{{ $category->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Products in this Category</h6>
                </div>
                <div class="card-body p-0">
                    @if($category->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>₹{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No products in this category yet</p>
                    </div>
                    @endif
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
        margin: 0;
    }

    .card {
        border-radius: 0.5rem;
    }

    dl dt {
        font-weight: 600;
        color: #6c757d;
    }

    dl dd {
        margin-left: 0;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
