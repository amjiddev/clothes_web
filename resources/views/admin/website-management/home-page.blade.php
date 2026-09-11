@extends('admin.layouts.app')

@section('title', 'Home Page Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Home Page</li>
@endsection

@php
    // Ensure $homePageContent is accessible even if null
    $homePageContent = $homePageContent ?? null;
@endphp

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-home me-2"></i>Home Page Management
                </h1>
                <p class="text-muted">Manage your home page content, hero section, and featured content</p>
            </div>
        </div>
    </div>

    <form id="homePageForm" action="{{ route('admin.website-management.home-page.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4 border-0" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero-section" type="button" role="tab">
                    <i class="fas fa-image me-2"></i>Hero Images
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tailoring-tab" data-bs-toggle="tab" data-bs-target="#tailoring-section" type="button" role="tab">
                    <i class="fas fa-scissors me-2"></i>Tailoring Services
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="collection-tab" data-bs-toggle="tab" data-bs-target="#collection-section" type="button" role="tab">
                    <i class="fas fa-shopping-bag me-2"></i>Shalwar Kameez
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-section" type="button" role="tab">
                    <i class="fas fa-cog me-2"></i>General Settings
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Hero Images Section -->
            <div class="tab-pane fade show active" id="hero-section" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <!-- Hero Images -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0"><i class="fas fa-images me-2"></i>Hero Slider Images (4 Images)</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-4">Upload 4 hero images for the homepage slider. Images should be 1920x1080px for best results.</p>
                                
                                <div class="hero-images-container" id="heroImagesContainer">
                                    <div class="row g-3">
                                        @for($i = 1; $i <= 4; $i++)
                                        <div class="col-lg-3 col-md-6">
                                            <div class="hero-image-card position-relative" data-slot="{{ $i }}" style="border: 2px solid #dee2e6; border-radius: 8px; padding: 12px; background: #f8f9fa; height: 280px; display: flex; flex-direction: column;">
                                                
                                                <!-- Delete Button -->
                                                <button 
                                                    type="button" 
                                                    class="btn btn-sm btn-danger delete-image-btn position-absolute" 
                                                    style="top: 5px; right: 5px; width: 32px; height: 32px; padding: 0; display: none; z-index: 10;"
                                                    data-slot="{{ $i }}"
                                                    title="Delete image">
                                                    <i class="fas fa-times"></i>
                                                </button>

                                                <!-- Badge -->
                                                <div class="mb-2">
                                                    <span class="badge bg-primary">Image {{ $i }}</span>
                                                </div>

                                                <!-- Image Preview -->
                                                <div class="image-preview-box mb-3" style="flex-grow: 1; border: 1px solid #e0e0e0; border-radius: 4px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: white; min-height: 150px;">
                                                    @if($homePageContent?->data && isset($homePageContent->data['hero_image_' . $i]) && $homePageContent->data['hero_image_' . $i])
                                                        <img src="{{ asset('storage/' . $homePageContent->data['hero_image_' . $i]) }}" alt="Hero Image {{ $i }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                    @else
                                                        <div class="text-center" style="width: 100%; padding: 15px;">
                                                            <i class="fas fa-image" style="font-size: 2rem; color: #ccc; display: block; margin-bottom: 5px;"></i>
                                                            <small class="text-muted">No image</small>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- File Input -->
                                                <input 
                                                    type="file" 
                                                    name="hero_image_{{ $i }}" 
                                                    class="form-control form-control-sm hero-image-input"
                                                    accept="image/*"
                                                    style="font-size: 0.85rem; padding: 4px 6px; border: 1px solid #e0e0e0;">
                                                <small class="form-text text-muted d-block mt-2" style="font-size: 0.75rem;">📷 JPEG, PNG, GIF</small>
                                            </div>
                                        </div>
                                        @endfor
                                    </div>
                                </div>

                                <div class="alert alert-info mt-4 mb-0">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    <strong>Tip:</strong> Click the <i class="fas fa-times" style="color: #dc3545;"></i> icon to remove an image, or upload a new one to replace it.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tailoring Services Section -->
            <div class="tab-pane fade" id="tailoring-section" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0"><i class="fas fa-scissors me-2"></i>Tailoring Services</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">Manage your tailoring service offerings (up to 3 services). Customize the title, description, icon, and pricing for each service.</p>
                                
                                <!-- Tailoring Section Title -->
                                <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-heading me-2"></i>Tailoring Section Header</h6>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tailoring Section Title</label>
                                        <input type="text" name="tailoring_section_title" class="form-control" value="{{ old('tailoring_section_title', $homePageContent?->data['tailoring_section_title'] ?? '') }}" placeholder="e.g., Our Tailoring Services">
                                        <small class="form-text text-muted">Main heading for the tailoring services section</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tailoring Section Description</label>
                                        <textarea name="tailoring_section_description" class="form-control" rows="2" placeholder="">{{ old('tailoring_section_description', $homePageContent?->data['tailoring_section_description'] ?? '') }}</textarea>
                                        <small class="form-text text-muted">Optional description for the section</small>
                                    </div>
                                </div>

                                <!-- Individual Services -->
                                @for($i = 1; $i <= 3; $i++)
                                <div class="card bg-light mb-4 border">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="mb-0">
                                            <i class="fas fa-cog me-2"></i>Service {{ $i }}
                                            @if(!empty($homePageContent?->data['tailoring_title_' . $i]))
                                                <span class="badge bg-success float-end">Active</span>
                                            @else
                                                <span class="badge bg-secondary float-end">Not set</span>
                                            @endif
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Service Title *</label>
                                                    <input type="text" name="tailoring_title_{{ $i }}" class="form-control" value="{{ old('tailoring_title_' . $i, $homePageContent?->data['tailoring_title_' . $i] ?? '') }}" placeholder="e.g., Cloth + Stitching">
                                                    <small class="form-text text-muted">The name of the service</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Service Icon (Font Awesome) *</label>
                                                    <input type="text" name="tailoring_icon_{{ $i }}" class="form-control" value="{{ old('tailoring_icon_' . $i, $homePageContent?->data['tailoring_icon_' . $i] ?? '') }}" placeholder="e.g., fas fa-ruler-combined">
                                                    <small class="form-text text-muted">
                                                        <a href="https://fontawesome.com/icons" target="_blank">Font Awesome Icons</a>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Service Description *</label>
                                            <textarea name="tailoring_description_{{ $i }}" class="form-control" rows="4" placeholder="Describe the service in detail...">{{ old('tailoring_description_' . $i, $homePageContent?->data['tailoring_description_' . $i] ?? '') }}</textarea>
                                            <small class="form-text text-muted">Detailed description of what this service includes</small>
                                        </div>

                                        <div class="mb-0">
                                            <label class="form-label fw-bold">Starting Price *</label>
                                            <input type="text" name="tailoring_price_{{ $i }}" class="form-control" value="{{ old('tailoring_price_' . $i, $homePageContent?->data['tailoring_price_' . $i] ?? '') }}" placeholder="e.g., Rs. 500 or Contact for Quote">
                                            <small class="form-text text-muted">Display price or pricing info (e.g., "Rs. 500", "Starting from Rs. 1000", "Contact for Quote")</small>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shalwar Kameez Collection Section -->
            <div class="tab-pane fade" id="collection-section" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <!-- Add New Product Button -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body text-center">
                                <button type="button" class="btn btn-primary btn-lg" id="showProductFormBtn">
                                    <i class="fas fa-plus me-2"></i>Add New Product
                                </button>
                            </div>
                        </div>

                        <!-- Products Table (Always Visible) -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Collection Products</h5>
                            </div>
                            <div class="card-body">
                                <div id="productsTableContainer" style="overflow-x: auto;">
                                    <table class="table table-hover mb-0" id="productsTable">
                                        <thead style="background: #f8f9fa;">
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Category</th>
                                                <th>Regular Price</th>
                                                <th>Sale Price</th>
                                                <th>Discount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productsTableBody">
                                        </tbody>
                                    </table>
                                    <div id="emptyProductsMsg" class="text-center text-muted py-4">
                                        No products added yet
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Form (Hidden by Default) -->
                        <div id="productFormContainer" style="display: none;">
                            <!-- Basic Information -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Product Name *</label>
                                        <input type="text" name="collection_product_name" class="form-control" value="" placeholder="Enter product name">
                                        <small class="form-text text-muted">The name of the Shalwar Kameez product</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Product Slug</label>
                                        <input type="text" name="collection_product_slug" class="form-control" value="" placeholder="product-slug">
                                        <small class="form-text text-muted">URL-friendly version of the name. Leave blank to auto-generate.</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Product Category *</label>
                                                <select name="collection_product_category" class="form-control">
                                                    <option value="">-- Select Category --</option>
                                                    <option value="shalwar-kameez">Shalwar Kameez</option>
                                                    <option value="kurta">Kurta</option>
                                                    <option value="khaddar">Khaddar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Product Brand</label>
                                                <input type="text" name="collection_product_brand" class="form-control" value="" placeholder="e.g., XYZ Brand">
                                            </div>
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
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Regular Price (₹) *</label>
                                                <input type="number" name="collection_regular_price" class="form-control" value="" placeholder="0.00" step="0.01">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Sale Price (₹)</label>
                                                <input type="number" name="collection_sale_price" class="form-control" value="" placeholder="0.00" step="0.01">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Discount %</label>
                                                <input type="number" name="collection_discount_percentage" class="form-control" value="" placeholder="0" min="0" max="100">
                                                <small class="text-muted">Auto-calculated</small>
                                            </div>
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
                                        <textarea name="collection_short_description" class="form-control" rows="3" placeholder="Brief product description"></textarea>
                                        <small class="form-text text-muted">This will be shown in product listings.</small>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-bold">Full Description</label>
                                        <textarea name="collection_full_description" class="form-control" rows="5" placeholder="Detailed product description"></textarea>
                                        <small class="form-text text-muted">This will be shown on the product detail page.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Images -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom">
                                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Product Images</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Featured Image -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Featured Image *</label>
                                        <div class="mb-3" style="border: 2px dashed #dee2e6; border-radius: 8px; padding: 20px; text-align: center; background: #f8f9fa;">
                                            @if($homePageContent?->data && isset($homePageContent->data['collection_featured_image']) && $homePageContent->data['collection_featured_image'])
                                                <img src="{{ asset('storage/' . $homePageContent->data['collection_featured_image']) }}" alt="Featured Image" style="max-width: 100%; max-height: 250px; object-fit: contain;">
                                            @else
                                                <i class="fas fa-image" style="font-size: 2rem; color: #ccc; display: block; margin-bottom: 10px;"></i>
                                                <small class="text-muted">No image selected</small>
                                            @endif
                                        </div>
                                        <input type="file" name="collection_featured_image" class="form-control" accept="image/*">
                                        <small class="form-text text-muted">This will be the main product image. Max size: 2MB</small>
                                    </div>

                                    <!-- Gallery Images -->
                                    <div class="mb-0">
                                        <label class="form-label fw-bold">Gallery Images</label>
                                        <div class="mb-3" style="border: 2px dashed #dee2e6; border-radius: 8px; padding: 20px; text-align: center; background: #f8f9fa; min-height: 100px;">
                                            <i class="fas fa-images" style="font-size: 2rem; color: #ccc; display: block; margin-bottom: 10px;"></i>
                                            <small class="text-muted">Gallery images will appear here</small>
                                        </div>
                                        <input type="file" name="collection_gallery_images[]" class="form-control" accept="image/*" multiple>
                                        <small class="form-text text-muted">You can select multiple images. Max size: 2MB each</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Action Buttons -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-success btn-lg" id="addProductBtn">
                                            <i class="fas fa-plus me-2"></i>Add Product to Collection
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-lg" id="cancelProductFormBtn">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden input to store products JSON -->
            <input type="hidden" id="collection_products" name="collection_products" value="">
        

            <!-- General Settings Section -->
            <div class="tab-pane fade" id="general-section" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <!-- Hero Section -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0"><i class="fas fa-heading me-2"></i>Hero Section Text</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hero Title</label>
                                    <input type="text" name="hero_title" class="form-control @error('hero_title') is-invalid @enderror" value="{{ old('hero_title', $homePageContent?->data['hero_title'] ?? '') }}" placeholder="">
                                    @error('hero_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hero Subtitle</label>
                                    <textarea name="hero_subtitle" class="form-control @error('hero_subtitle') is-invalid @enderror" rows="2" placeholder="">{{ old('hero_subtitle', $homePageContent?->data['hero_subtitle'] ?? '') }}</textarea>
                                    @error('hero_subtitle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Page Content -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Page Content</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Page Title</label>
                                    <input type="text" name="page_title" class="form-control @error('page_title') is-invalid @enderror" value="{{ old('page_title', $homePageContent?->page_title ?? '') }}" placeholder="Home Page">
                                    @error('page_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Page Content</label>
                                    <textarea name="page_content" class="form-control @error('page_content') is-invalid @enderror" rows="6" placeholder="">{{ old('page_content', $homePageContent?->page_content ?? '') }}</textarea>
                                    @error('page_content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">You can add HTML content here</small>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Information -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0"><i class="fas fa-search me-2"></i>SEO Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Description</label>
                                    <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="3" placeholder="">{{ old('meta_description', $homePageContent?->meta_description ?? '') }}</textarea>
                                    <small class="form-text text-muted">Max 160 characters</small>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror" value="{{ old('meta_keywords', $homePageContent?->meta_keywords ?? '') }}" placeholder="">
                                    <small class="form-text text-muted">Comma-separated keywords</small>
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="is_published" class="form-check-input" id="is_published" value="1" {{ old('is_published', $homePageContent?->is_published ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_published">Publish Home Page</label>
                        </div>
                        @if($homePageContent?->published_at)
                            <small class="text-muted d-block mb-3">
                                <i class="fas fa-calendar me-1"></i>Published on {{ $homePageContent->published_at->format('M d, Y H:i') }}
                            </small>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>{{ $homePageContent ? 'Update' : 'Save' }} Home Page
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg" target="_blank">
                                <i class="fas fa-eye me-2"></i>Preview Home Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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

.card {
    transition: box-shadow 0.2s;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.nav-tabs .nav-link {
    color: #666;
    border: none;
    border-bottom: 3px solid transparent;
    font-weight: 500;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #ddd;
}

.nav-tabs .nav-link.active {
    color: #0066cc;
    border-bottom-color: #0066cc;
    background: none;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.stat-item:last-child {
    border-bottom: none;
}

/* Hero Image Grid Styling */
.hero-image-card {
    transition: all 0.3s ease;
    position: relative;
}

.hero-image-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-color: #0066cc !important;
}

.hero-image-card .image-preview-box {
    background: white;
}

.hero-image-card .delete-image-btn {
    border-radius: 50%;
    font-size: 0.9rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.hero-image-card .delete-image-btn:hover {
    background-color: #c82333;
    transform: scale(1.1);
}

.hero-image-card .form-control-sm {
    border: 1px solid #e0e0e0;
}

@media (max-width: 768px) {
    .hero-image-card {
        min-height: 250px;
    }
}

@media (max-width: 576px) {
    .hero-image-card {
        min-height: 200px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============ Hero Image Manager ============
    const container = document.getElementById('heroImagesContainer');
    const MAX_IMAGES = 4;
    
    // Initialize - show delete buttons for existing images
    function initializeHeroImages() {
        for (let i = 1; i <= MAX_IMAGES; i++) {
            const card = container.querySelector(`[data-slot="${i}"]`);
            if (!card) continue;
            
            const previewBox = card.querySelector('.image-preview-box');
            const deleteBtn = card.querySelector('.delete-image-btn');
            
            if (previewBox && previewBox.querySelector('img')) {
                if (deleteBtn) {
                    deleteBtn.style.display = 'block';
                }
            }
        }
    }
    
    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('hero-image-input')) {
            const file = e.target.files[0];
            const card = e.target.closest('.hero-image-card');
            const slotNumber = card.getAttribute('data-slot');
            const previewBox = card.querySelector('.image-preview-box');
            const deleteBtn = card.querySelector('.delete-image-btn');
            
            if (file) {
                if (!validateImage(file)) {
                    alert('Invalid file. Please use JPEG, PNG, or GIF with max size 2MB');
                    e.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewBox.innerHTML = `<img src="${evt.target.result}" alt="Hero Image ${slotNumber}" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                    
                    if (deleteBtn) {
                        deleteBtn.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    });
    
    container.addEventListener('click', function(e) {
        if (e.target.closest('.delete-image-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.delete-image-btn');
            const card = btn.closest('.hero-image-card');
            const previewBox = card.querySelector('.image-preview-box');
            const fileInput = card.querySelector('input[type="file"]');
            
            if (fileInput) {
                fileInput.value = '';
            }
            
            previewBox.innerHTML = `
                <div class="text-center" style="width: 100%; padding: 15px;">
                    <i class="fas fa-image" style="font-size: 2rem; color: #ccc; display: block; margin-bottom: 5px;"></i>
                    <small class="text-muted">No image</small>
                </div>
            `;
            
            btn.style.display = 'none';
        }
    });
    
    function validateImage(file) {
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        const maxSize = 2 * 1024 * 1024;
        
        if (!validTypes.includes(file.type)) {
            return false;
        }
        if (file.size > maxSize) {
            return false;
        }
        return true;
    }
    
    initializeHeroImages();

    // ============ Shalwar Kameez Collection Products Manager ============
    let collectionProducts = [];
    
    // Load existing products if available
    function loadExistingProducts() {
        @if($homePageContent?->data && isset($homePageContent->data['collection_products']))
            try {
                collectionProducts = JSON.parse('{!! json_encode($homePageContent->data['collection_products']) !!}');
                if (!Array.isArray(collectionProducts)) {
                    collectionProducts = [];
                }
            } catch(e) {
                collectionProducts = [];
            }
        @endif
        renderProductsTable();
    }
    
    // Add product button click
    document.getElementById('addProductBtn').addEventListener('click', function() {
        const product = {
            id: Date.now(),
            name: document.querySelector('input[name="collection_product_name"]').value.trim(),
            slug: document.querySelector('input[name="collection_product_slug"]').value.trim(),
            category: document.querySelector('select[name="collection_product_category"]').value.trim(),
            brand: document.querySelector('input[name="collection_product_brand"]').value.trim(),
            regular_price: parseFloat(document.querySelector('input[name="collection_regular_price"]').value) || 0,
            sale_price: parseFloat(document.querySelector('input[name="collection_sale_price"]').value) || 0,
            discount_percentage: parseInt(document.querySelector('input[name="collection_discount_percentage"]').value) || 0,
            short_description: document.querySelector('textarea[name="collection_short_description"]').value.trim(),
            full_description: document.querySelector('textarea[name="collection_full_description"]').value.trim(),
        };
        
        // Validate
        if (!product.name || !product.category) {
            alert('Please fill in Product Name and Category');
            return;
        }
        
        // Add to array
        collectionProducts.push(product);
        
        // Clear form fields completely
        document.querySelector('input[name="collection_product_name"]').value = '';
        document.querySelector('input[name="collection_product_slug"]').value = '';
        document.querySelector('select[name="collection_product_category"]').value = '';
        document.querySelector('input[name="collection_product_brand"]').value = '';
        document.querySelector('input[name="collection_regular_price"]').value = '';
        document.querySelector('input[name="collection_sale_price"]').value = '';
        document.querySelector('input[name="collection_discount_percentage"]').value = '';
        document.querySelector('textarea[name="collection_short_description"]').value = '';
        document.querySelector('textarea[name="collection_full_description"]').value = '';
        
        // Focus on product name field for next entry
        document.querySelector('input[name="collection_product_name"]').focus();
        
        renderProductsTable();
        updateHiddenInput();
        
        alert('Product added successfully! Add another product or click Save Home Page.');
    });
    
    // Render products table
    function renderProductsTable() {
        const tbody = document.getElementById('productsTableBody');
        const emptyMsg = document.getElementById('emptyProductsMsg');
        
        tbody.innerHTML = '';
        
        if (collectionProducts.length === 0) {
            emptyMsg.style.display = 'block';
            return;
        }
        
        emptyMsg.style.display = 'none';
        
        collectionProducts.forEach((product, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${product.name}</strong></td>
                <td><span class="badge bg-info">${product.category}</span></td>
                <td>Rs. ${parseInt(product.regular_price).toLocaleString('en-IN')}</td>
                <td>Rs. ${parseInt(product.sale_price).toLocaleString('en-IN')}</td>
                <td>${product.discount_percentage}%</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct(${index})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
    
    // Delete product
    window.deleteProduct = function(index) {
        if (confirm('Are you sure you want to delete this product?')) {
            collectionProducts.splice(index, 1);
            renderProductsTable();
            updateHiddenInput();
        }
    };
    
    // Update hidden input with JSON
    function updateHiddenInput() {
        document.getElementById('collection_products').value = JSON.stringify(collectionProducts);
    }
    
    // Handle form submission - serialize products
    const form = document.getElementById('homePageForm');
    form.addEventListener('submit', function() {
        updateHiddenInput();
    });
    
    // Initialize
    loadExistingProducts();
});
</script>
@endsection
