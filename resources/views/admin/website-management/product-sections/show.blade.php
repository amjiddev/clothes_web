@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.website-management.contact') }}">Website Management</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.website-management.product-sections.index') }}">Product Sections</a></li>
    <li class="breadcrumb-item active">Product Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-eye me-2"></i>Product Details
                </h1>
                <p class="text-muted">{{ $product->name }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.website-management.product-sections.edit', $product) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-2"></i>Edit Product
                </a>
                <a href="{{ route('admin.website-management.product-sections.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Product Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Product Name:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $product->name }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Slug:</strong>
                        </div>
                        <div class="col-md-8">
                            <code>{{ $product->slug }}</code>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Category:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-info-subtle text-info">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Brand:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $product->brand ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>SKU:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $product->sku ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-8">
                            @if($product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-tag me-2"></i>Pricing Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Regular Price:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="fs-5">₹{{ number_format($product->regular_price ?? $product->price, 2) }}</span>
                        </div>
                    </div>
                    @if($product->sale_price)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Sale Price:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="fs-5 text-danger fw-bold">₹{{ number_format($product->sale_price, 2) }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Discount:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-danger-subtle text-danger">{{ $product->discount_percentage }}% OFF</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Descriptions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-align-left me-2"></i>Descriptions</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <strong class="d-block mb-2">Short Description:</strong>
                        <p class="text-muted">{{ $product->short_description ?? 'No short description provided.' }}</p>
                    </div>
                    <div>
                        <strong class="d-block mb-2">Full Description:</strong>
                        <p class="text-muted">{{ $product->full_description ?? 'No full description provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Product Images</h5>
                </div>
                <div class="card-body">
                    @if($product->featuredImage)
                    <div class="mb-4">
                        <strong class="d-block mb-3">Featured Image:</strong>
                        <img src="{{ $product->featuredImage->image_url }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 300px;">
                    </div>
                    @endif

                    @if($product->galleryImages->count() > 0)
                    <div>
                        <strong class="d-block mb-3">Gallery Images:</strong>
                        <div class="row g-3">
                            @foreach($product->galleryImages as $image)
                            <div class="col-md-3">
                                <img src="{{ $image->image_url }}" alt="{{ $product->name }}" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <p class="text-muted">No gallery images available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Inventory -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Stock Quantity:</strong>
                        <div class="mt-2">
                            <h3 class="mb-0">{{ $product->stock_quantity }} <small class="text-muted">units</small></h3>
                        </div>
                    </div>
                    <div>
                        <strong>Stock Status:</strong>
                        <div class="mt-2">
                            <span class="badge bg-{{ $product->stock_status_badge }}-subtle text-{{ $product->stock_status_badge }}">
                                {{ $product->stock_status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display Sections -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-th-large me-2"></i>Display Sections</h5>
                </div>
                <div class="card-body">
                    @if($product->displaySections->count() > 0)
                        <p class="text-muted small mb-3">This product appears on the following pages:</p>
                        <div class="d-flex flex-column gap-2">
                            @foreach($product->displaySections as $section)
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="badge bg-primary-subtle text-primary">{{ $section->section_name }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">This product is not assigned to any display section.</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <a href="{{ route('admin.website-management.product-sections.edit', $product) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit Product
                    </a>
                    <button type="button" class="btn btn-danger w-100" onclick="deleteProduct({{ $product->id }})">
                        <i class="fas fa-trash me-2"></i>Delete Product
                    </button>
                    
                    <form id="delete-form-{{ $product->id }}" action="{{ route('admin.website-management.product-sections.destroy', $product) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
