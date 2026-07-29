@extends('admin.layouts.app')

@section('title', 'Add New Product')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.website-management.contact') }}">Website Management</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.website-management.product-sections.index') }}">Product Sections</a></li>
    <li class="breadcrumb-item active">Add New Product</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-plus-circle me-2"></i>Add New Product
                </h1>
                <p class="text-muted">Create a new product and assign it to display sections</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.website-management.product-sections.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.website-management.product-sections.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter product name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Slug <small class="text-muted">(Leave blank to auto-generate)</small></label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="product-slug">
                            <small class="text-muted">URL-friendly version of the name. Will be auto-generated if left empty.</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Product Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Product Brand</label>
                                <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand') }}" placeholder="Enter brand name">
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Regular Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="regular_price" class="form-control @error('regular_price') is-invalid @enderror" value="{{ old('regular_price') }}" placeholder="0.00" required id="regularPrice">
                                @error('regular_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Sale Price (₹)</label>
                                <input type="number" step="0.01" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price') }}" placeholder="0.00" id="salePrice">
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Discount Percentage</label>
                                <input type="text" class="form-control" id="discountPercent" readonly placeholder="0%">
                                <small class="text-muted">Auto-calculated</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descriptions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fas fa-align-left me-2"></i>Descriptions</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Short Description</label>
                            <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="3" placeholder="Brief product description">{{ old('short_description') }}</textarea>
                            <small class="text-muted">This will be shown in product listings.</small>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Description</label>
                            <textarea name="full_description" class="form-control @error('full_description') is-invalid @enderror" rows="6" placeholder="Detailed product description">{{ old('full_description') }}</textarea>
                            <small class="text-muted">This will be shown on the product detail page.</small>
                            @error('full_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Product Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Featured Image <span class="text-danger">*</span></label>
                            <input type="file" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" accept="image/*" id="featuredImageInput">
                            <small class="text-muted">This will be the main product image. Max size: 2MB</small>
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="featuredImagePreview" class="mt-3"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Gallery Images</label>
                            <input type="file" name="gallery_images[]" class="form-control @error('gallery_images.*') is-invalid @enderror" accept="image/*" multiple id="galleryImagesInput">
                            <small class="text-muted">You can select multiple images. Max size: 2MB each</small>
                            @error('gallery_images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="galleryImagesPreview" class="mt-3 row g-3"></div>
                        </div>
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
                            <label class="form-label fw-bold">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', 0) }}" placeholder="0" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Product Status (Active)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Display Sections -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fas fa-th-large me-2"></i>Display Sections</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Select where this product should appear on the website</p>
                        
                        @foreach($sections as $key => $label)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="sections[]" value="{{ $key }}" id="section_{{ $key }}" {{ in_array($key, old('sections', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="section_{{ $key }}">
                                {{ $label }}
                            </label>
                        </div>
                        @endforeach

                        @error('sections')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save me-2"></i>Create Product
                        </button>
                        <a href="{{ route('admin.website-management.product-sections.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Calculate discount percentage
function calculateDiscount() {
    const regularPrice = parseFloat(document.getElementById('regularPrice').value) || 0;
    const salePrice = parseFloat(document.getElementById('salePrice').value) || 0;
    
    if (regularPrice > 0 && salePrice > 0 && salePrice < regularPrice) {
        const discount = ((regularPrice - salePrice) / regularPrice) * 100;
        document.getElementById('discountPercent').value = discount.toFixed(2) + '%';
    } else {
        document.getElementById('discountPercent').value = '0%';
    }
}

document.getElementById('regularPrice').addEventListener('input', calculateDiscount);
document.getElementById('salePrice').addEventListener('input', calculateDiscount);

// Preview featured image
document.getElementById('featuredImageInput').addEventListener('change', function(e) {
    const preview = document.getElementById('featuredImagePreview');
    preview.innerHTML = '';
    
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(event) {
            preview.innerHTML = `
                <div class="position-relative d-inline-block">
                    <img src="${event.target.result}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                </div>
            `;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

// Preview gallery images
document.getElementById('galleryImagesInput').addEventListener('change', function(e) {
    const preview = document.getElementById('galleryImagesPreview');
    preview.innerHTML = '';
    
    if (e.target.files) {
        Array.from(e.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const col = document.createElement('div');
                col.className = 'col-4';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${event.target.result}" class="img-thumbnail w-100" style="height: 120px; object-fit: cover;">
                        <small class="d-block text-center mt-1">Image ${index + 1}</small>
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
@endpush
@endsection
