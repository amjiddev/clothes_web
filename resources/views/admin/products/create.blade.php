@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Add Product</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <h1 class="page-title"><i class="fas fa-plus-circle me-2"></i>Add New Men's Clothing Product</h1>
        <p class="text-muted">Create a new product for your store</p>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-body p-4">
                    <!-- Error Messages -->
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Validation Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h5>

                            <!-- Category & SKU -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            <option value="">Select Product Category</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label fw-bold">SKU <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" placeholder="e.g., MEN-SK-001" value="{{ old('sku') }}" required>
                                        @error('sku')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Product Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="e.g., Premium Shalwar Kameez" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Detailed product description...">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Product Details Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">
                                <i class="fas fa-tshirt me-2"></i>Product Details
                            </h5>

                            <!-- Fabric Type & Color -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fabric_type" class="form-label fw-bold">Fabric Type</label>
                                        <select class="form-select @error('fabric_type') is-invalid @enderror" id="fabric_type" name="fabric_type">
                                            <option value="">Select Fabric Type</option>
                                            @foreach($fabricTypes as $fabric)
                                            <option value="{{ $fabric }}" {{ old('fabric_type') == $fabric ? 'selected' : '' }}>
                                                {{ $fabric }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('fabric_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="color" class="form-label fw-bold">Color</label>
                                        <select class="form-select @error('color') is-invalid @enderror" id="color" name="color">
                                            <option value="">Select Color</option>
                                            @foreach($colors as $color)
                                            <option value="{{ $color }}" {{ old('color') == $color ? 'selected' : '' }}>
                                                {{ $color }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('color')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Available Sizes -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Available Sizes</label>
                                <div class="row">
                                    @foreach($sizes as $size)
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="size_{{ $size }}" name="available_sizes[]" value="{{ $size }}" {{ in_array($size, old('available_sizes', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="size_{{ $size }}">
                                                {{ $size }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('available_sizes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Pricing & Inventory Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">
                                <i class="fas fa-money-bill-wave me-2"></i>Pricing & Inventory
                            </h5>

                            <!-- Price, Discount Price, Stock -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="price" class="form-label fw-bold">Price <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="0.00" value="{{ old('price') }}" required>
                                        </div>
                                        @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="discount_price" class="form-label fw-bold">Discount Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" step="0.01" class="form-control @error('discount_price') is-invalid @enderror" id="discount_price" name="discount_price" placeholder="0.00" value="{{ old('discount_price') }}">
                                        </div>
                                        <small class="text-muted">Leave empty if no discount</small>
                                        @error('discount_price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="stock_quantity" class="form-label fw-bold">Stock Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" placeholder="0" value="{{ old('stock_quantity', 0) }}" required>
                                        @error('stock_quantity')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Images Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">
                                <i class="fas fa-images me-2"></i>Product Images
                            </h5>

                            <!-- Main Product Image -->
                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold">Main Product Image</label>
                                <div class="upload-area border-2 border-dashed rounded p-4 text-center" style="border-color: #dee2e6; background: #f8f9fa; cursor: pointer;">
                                    <input type="file" class="form-control @error('image') is-invalid @enderror d-none" id="image" name="image" accept="image/*">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <p class="mb-1">Drag and drop your image here</p>
                                    <small class="text-muted">or click to select (Max: 2MB, JPG/PNG/GIF)</small>
                                </div>
                                @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <!-- Gallery Images -->
                            <div class="mb-3">
                                <label for="gallery" class="form-label fw-bold">Gallery Images (Multiple)</label>
                                <div class="upload-area border-2 border-dashed rounded p-4 text-center" style="border-color: #dee2e6; background: #f8f9fa; cursor: pointer;">
                                    <input type="file" class="form-control @error('gallery') is-invalid @enderror d-none" id="gallery" name="gallery[]" accept="image/*" multiple>
                                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                    <p class="mb-1">Upload multiple product images</p>
                                    <small class="text-muted">or click to select (Max: 2MB each, JPG/PNG/GIF)</small>
                                </div>
                                @error('gallery')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Status Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">
                                <i class="fas fa-toggle-on me-2"></i>Status
                            </h5>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Product (Visible on store)
                                </label>
                            </div>
                        </div>

                        <hr>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-save me-2"></i>Add Product
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="col-lg-4">
            <div class="card border-0 shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="fas fa-lightbulb me-2"></i>Tips
                    </h5>
                    <ul class="small text-muted">
                        <li class="mb-2">✓ Use clear, descriptive product names</li>
                        <li class="mb-2">✓ Create unique SKU codes for each product</li>
                        <li class="mb-2">✓ Upload high-quality product images</li>
                        <li class="mb-2">✓ Add detailed descriptions</li>
                        <li class="mb-2">✓ Set competitive prices</li>
                        <li class="mb-2">✓ Manage inventory accurately</li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="fas fa-shirt me-2"></i>Product Types
                    </h5>
                    <ul class="small text-muted">
                        <li>Shalwar Kameez</li>
                        <li>Suits</li>
                        <li>Kurta</li>
                        <li>Waistcoat</li>
                        <li>Sherwani</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Drag and drop for main image
const imageArea = document.querySelector('[id="image"]').parentElement;
imageArea.addEventListener('click', () => document.getElementById('image').click());
imageArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    imageArea.style.backgroundColor = '#e9ecef';
});
imageArea.addEventListener('dragleave', () => {
    imageArea.style.backgroundColor = '#f8f9fa';
});
imageArea.addEventListener('drop', (e) => {
    e.preventDefault();
    document.getElementById('image').files = e.dataTransfer.files;
    imageArea.style.backgroundColor = '#f8f9fa';
});

// Drag and drop for gallery
const galleryArea = document.querySelector('[id="gallery"]').parentElement;
galleryArea.addEventListener('click', () => document.getElementById('gallery').click());
galleryArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    galleryArea.style.backgroundColor = '#e9ecef';
});
galleryArea.addEventListener('dragleave', () => {
    galleryArea.style.backgroundColor = '#f8f9fa';
});
galleryArea.addEventListener('drop', (e) => {
    e.preventDefault();
    document.getElementById('gallery').files = e.dataTransfer.files;
    galleryArea.style.backgroundColor = '#f8f9fa';
});
</script>

<style>
.upload-area {
    transition: all 0.3s ease;
}

.upload-area:hover {
    background-color: #e9ecef !important;
    border-color: #495057 !important;
}

.page-header {
    padding: 20px 0;
    border-bottom: 1px solid #e9ecef;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
}
</style>
@endsection
