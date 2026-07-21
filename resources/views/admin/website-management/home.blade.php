@extends('admin.layouts.app')

@section('title', 'Home Page Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ $pageTitle }}</h1>
            <p class="page-subtitle">{{ $pageDescription }}</p>
        </div>
        <a href="#" class="btn btn-accent">
            <i class="fas fa-save"></i> Save Changes
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Left Column - Sections -->
    <div class="col-lg-8">
        <!-- Hero Section -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-image text-accent"></i> Hero Section
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Hero Title</label>
                    <input type="text" class="form-control" placeholder="Enter hero title" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Hero Subtitle</label>
                    <textarea class="form-control" rows="3" placeholder="Enter hero subtitle"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Hero Image</label>
                    <input type="file" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-label">Call to Action Button Text</label>
                    <input type="text" class="form-control" placeholder="e.g., Shop Now" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Button Link</label>
                    <input type="text" class="form-control" placeholder="Enter button link" value="">
                </div>
            </div>
        </div>

        <!-- Featured Collection -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-star text-accent"></i> Featured Collection
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Section Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Featured Products" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Number of Products to Display</label>
                    <select class="form-select">
                        <option value="">Select count</option>
                        <option value="4">4 Products</option>
                        <option value="8">8 Products</option>
                        <option value="12">12 Products</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Enable Featured Section
                    </label>
                </div>
            </div>
        </div>

        <!-- New Arrivals -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle text-accent"></i> New Arrivals
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Section Title</label>
                    <input type="text" class="form-control" placeholder="e.g., New Arrivals" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Show Last X Days</label>
                    <input type="number" class="form-control" placeholder="e.g., 30" value="30">
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Enable New Arrivals Section
                    </label>
                </div>
            </div>
        </div>

        <!-- Best Sellers -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-fire text-accent"></i> Best Sellers
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Section Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Best Sellers" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Number of Products</label>
                    <select class="form-select">
                        <option value="">Select count</option>
                        <option value="4">4 Products</option>
                        <option value="8">8 Products</option>
                        <option value="12">12 Products</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Enable Best Sellers Section
                    </label>
                </div>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-comments text-accent"></i> Testimonials
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Section Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Customer Reviews" value="">
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Enable Testimonials Section
                    </label>
                </div>
            </div>
        </div>

        <!-- Newsletter Section -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-envelope text-accent"></i> Newsletter Section
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Section Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Subscribe to Our Newsletter" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Section Description</label>
                    <textarea class="form-control" rows="2" placeholder="Enter section description"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Enable Newsletter Section
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Preview & Info -->
    <div class="col-lg-4">
        <!-- Preview Card -->
        <div class="card rounded-lg border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-eye text-accent"></i> Preview
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> Changes will be reflected on the home page after you save them.
                </div>
                <button class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-link"></i> View Home Page
                </button>
                <button class="btn btn-outline-secondary w-100">
                    <i class="fas fa-sync"></i> Preview Changes
                </button>
            </div>
        </div>

        <!-- Help Card -->
        <div class="card rounded-lg border-0 shadow-sm mt-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-question-circle text-accent"></i> Quick Tips
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <strong>Hero Section:</strong> This is the first thing visitors see. Make it attractive!
                    </li>
                    <li class="mb-3">
                        <strong>Featured Products:</strong> Showcase your best-selling items.
                    </li>
                    <li class="mb-3">
                        <strong>Testimonials:</strong> Display customer reviews to build trust.
                    </li>
                    <li>
                        <strong>Newsletter:</strong> Collect emails for marketing campaigns.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
