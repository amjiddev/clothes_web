@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.website-management.contact') }}">Website Management</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.website-management.product-sections.index') }}">Product Sections</a></li>
    <li class="breadcrumb-item active">Edit Product</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Product
                </h1>
                <p class="text-muted">Update product information and display sections</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.website-management.product-sections.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.website-management.product-sections.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
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
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" placeholder="Enter product name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Slug <small class="text-muted">(Leave blank to auto-generate)</small></label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug) }}" placeholder="product-slug">
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
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                    <option value="">-- Select Brand --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
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
                                <input type="number" step="0.01" name="regular_price" class="form-control @error('regular_price') is-invalid @enderror" value="{{ old('regular_price', $product->regular_price ?? $product->price) }}" placeholder="0.00" required id="regularPrice">
                                @error('regular_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Sale Price (₹)</label>
                                <input type="number" step="0.01" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price', $product->sale_price) }}" placeholder="0.00" id="salePrice">
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Discount Percentage</label>
                                <input type="text" class="form-control" id="discountPercent" readonly placeholder="0%" value="{{ $product->discount_percentage }}%">
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
                            <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="3" placeholder="Brief product description">{{ old('short_description', $product->short_description) }}</textarea>
                            <small class="text-muted">This will be shown in product listings.</small>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Description</label>
                            <textarea name="full_description" class="form-control @error('full_description') is-invalid @enderror" rows="6" placeholder="Detailed product description">{{ old('full_description', $product->full_description) }}</textarea>
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
                        <!-- Upload Featured Image -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload Featured Image</label>
                            <input type="file" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" accept="image/*" id="featuredImageInput">
                            <small class="text-muted">Upload a new image for featured image. Max size: 2MB</small>
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="featuredImagePreview" class="mt-3"></div>
                        </div>

                        <!-- Current Featured Image -->
                        @php
                            $hasFeaturedImage = $product->featuredImage;
                            $hasOldImage = $product->image;
                            $featuredImageUrl = null;
                            
                            if ($hasFeaturedImage) {
                                $featuredImageUrl = $product->featuredImage->image_url;
                            } elseif ($hasOldImage) {
                                $featuredImageUrl = asset('storage/' . $product->image);
                            }
                        @endphp
                        
                        @if($featuredImageUrl)
                        <div class="mb-4">
                            <label class="form-label fw-bold">Current Featured Image</label>
                            <div class="position-relative d-inline-block">
                                <img src="{{ $featuredImageUrl }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/200x200?text=Image+Not+Found'" data-featured-image-id="{{ $hasFeaturedImage ? $product->featuredImage->id : '' }}">
                                @if($hasFeaturedImage)
                                <!-- Red Delete Icon for Featured Image -->
                                <button type="button" class="btn btn-danger btn-sm rounded-circle delete-featured-image-btn" style="position: absolute; top: -10px; right: -10px; width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center; border: none;" data-image-id="{{ $product->featuredImage->id }}" title="Delete this featured image">
                                    <i class="fas fa-times" style="font-size: 18px; color: white;"></i>
                                </button>
                                @endif
                            </div>
                            @if($hasOldImage && !$hasFeaturedImage)
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> This is using the old image system. Upload a new image to migrate it.
                            </small>
                            @endif
                        </div>
                        @else
                        <div class="mb-4">
                            <label class="form-label fw-bold">Current Featured Image</label>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>No featured image uploaded yet.
                            </div>
                        </div>
                        @endif

                        <!-- Add More Gallery Images -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Add More Gallery Images</label>
                            <input type="file" name="gallery_images[]" class="form-control @error('gallery_images.*') is-invalid @enderror" accept="image/*" multiple id="galleryImagesInput">
                            <small class="text-muted">You can select multiple images to add. Max size: 2MB each</small>
                            @error('gallery_images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="galleryImagesPreview" class="mt-3 row g-3"></div>
                        </div>

                        <!-- Current Gallery Images -->
                        @php
                            $hasGalleryImages = $product->galleryImages && $product->galleryImages->count() > 0;
                            $hasOldGallery = $product->gallery && is_array($product->gallery) && count($product->gallery) > 0;
                        @endphp
                        
                        @if($hasGalleryImages)
                        <div class="mb-4">
                            <label class="form-label fw-bold">Current Gallery Images</label>
                            <div class="row g-3">
                                @foreach($product->galleryImages as $image)
                                <div class="col-md-3">
                                    <div class="position-relative">
                                        <img src="{{ $image->image_url }}" class="img-thumbnail w-100" style="height: 120px; object-fit: cover;" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/120x120?text=Image+Not+Found'" data-image-id="{{ $image->id }}">
                                        <!-- Red Cross Icon for Delete -->
                                        <button type="button" class="btn btn-danger btn-sm rounded-circle delete-image-btn" style="position: absolute; top: -10px; right: -10px; width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center; border: none;" data-image-id="{{ $image->id }}" title="Delete this image">
                                            <i class="fas fa-times" style="font-size: 18px; color: white;"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @elseif($hasOldGallery)
                        <div class="mb-4">
                            <label class="form-label fw-bold">Current Gallery Images (Old System)</label>
                            <div class="row g-3">
                                @foreach($product->gallery as $oldImage)
                                <div class="col-md-3">
                                    <img src="{{ asset('storage/' . $oldImage) }}" class="img-thumbnail w-100" style="height: 120px; object-fit: cover;" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/120x120?text=Image+Not+Found'">
                                </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> These are using the old image system. They cannot be deleted individually. Upload new gallery images to migrate.
                            </small>
                        </div>
                        @else
                        <div class="mb-4">
                            <label class="form-label fw-bold">Current Gallery Images</label>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>No gallery images uploaded yet.
                            </div>
                        </div>
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
                            <label class="form-label fw-bold">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', $product->stock_quantity) }}" placeholder="0" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
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
                            <input class="form-check-input" type="checkbox" name="sections[]" value="{{ $key }}" id="section_{{ $key }}" {{ in_array($key, old('sections', $selectedSections)) ? 'checked' : '' }}>
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
                            <i class="fas fa-save me-2"></i>Update Product
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
                    <button type="button" class="btn btn-danger btn-sm rounded-circle remove-featured-preview-btn" style="position: absolute; top: -10px; right: -10px; width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer;" title="Remove this image">
                        <i class="fas fa-times" style="font-size: 18px; color: white;"></i>
                    </button>
                    <div class="text-muted small mt-2">New featured image preview</div>
                </div>
            `;
            
            // Add click handler to remove button
            document.querySelector('.remove-featured-preview-btn').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                document.getElementById('featuredImageInput').value = '';
                preview.innerHTML = '';
            });
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
                col.className = 'col-md-3';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${event.target.result}" class="img-thumbnail w-100" style="height: 120px; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm rounded-circle remove-preview-btn" style="position: absolute; top: -10px; right: -10px; width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer;" title="Remove this image">
                            <i class="fas fa-times" style="font-size: 18px; color: white;"></i>
                        </button>
                        <small class="d-block text-center mt-1">New Image ${index + 1}</small>
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }
});

// Event delegation for remove preview buttons
document.getElementById('galleryImagesPreview').addEventListener('click', function(e) {
    const removeBtn = e.target.closest('.remove-preview-btn');
    if (removeBtn) {
        e.preventDefault();
        e.stopPropagation();
        removeBtn.closest('.col-md-3').remove();
    }
});

// Delete image via AJAX
document.querySelectorAll('.delete-image-btn, .delete-featured-image-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const imageId = this.getAttribute('data-image-id');
        const productId = '{{ $product->id }}';
        const btn = this;
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const isFeaturedImage = this.classList.contains('delete-featured-image-btn');
        
        if (!csrfToken) {
            alert('Security token not found');
            return;
        }
        
        if (!confirm('Are you sure you want to delete this image?')) {
            return;
        }
        
        // Add loading state
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // AJAX request
        fetch(`/admin/website-management/product-sections/{{ $product->id }}/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (isFeaturedImage) {
                    // Remove the featured image container
                    btn.closest('.position-relative').remove();
                    
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>Featured image deleted successfully
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    const parentDiv = btn.closest('.mb-4').parentElement;
                    parentDiv.insertBefore(alertDiv, parentDiv.firstChild);
                } else {
                    // Remove the gallery image container
                    btn.closest('.col-md-3').remove();
                    
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>Image deleted successfully
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.card-body').firstChild);
                }
            } else {
                alert('Error: ' + (data.error || 'Image could not be deleted'));
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-times" style="font-size: 18px; color: white;"></i>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: Image could not be deleted');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-times" style="font-size: 18px; color: white;"></i>';
        });
    });
});
</script>
@endpush
@endsection
