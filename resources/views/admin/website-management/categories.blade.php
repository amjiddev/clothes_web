@extends('admin.layouts.app')

@section('title', 'Categories Page Management')

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
    <!-- Left Column - Settings -->
    <div class="col-lg-8">
        <!-- Page Header -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-heading text-accent"></i> Page Header
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Page Title</label>
                    <input type="text" class="form-control" placeholder="Enter page title" value="Men's Categories">
                </div>
                <div class="form-group">
                    <label class="form-label">Page Description</label>
                    <textarea class="form-control" rows="3" placeholder="Enter page description"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Header Image</label>
                    <input type="file" class="form-control" accept="image/*">
                </div>
            </div>
        </div>

        <!-- Categories Grid Settings -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-th text-accent"></i> Categories Grid
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Categories Per Row</label>
                    <select class="form-select">
                        <option value="2">2 Columns</option>
                        <option value="3">3 Columns</option>
                        <option value="4">4 Columns</option>
                        <option value="5">5 Columns</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Display Style</label>
                    <select class="form-select">
                        <option value="card">Card Style</option>
                        <option value="image">Image Only</option>
                        <option value="with-count">With Product Count</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show Category Images
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show Product Count
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show Category Descriptions
                    </label>
                </div>
            </div>
        </div>

        <!-- Featured Categories -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-star text-accent"></i> Featured Categories
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Number of Featured Categories</label>
                    <select class="form-select">
                        <option value="3">3 Categories</option>
                        <option value="6">6 Categories</option>
                        <option value="9">9 Categories</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Featured Section Position</label>
                    <select class="form-select">
                        <option value="top">Top of Page</option>
                        <option value="bottom">Bottom of Page</option>
                        <option value="both">Both Top and Bottom</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Enable Featured Categories Section
                    </label>
                </div>
            </div>
        </div>

        <!-- Category Descriptions -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-align-left text-accent"></i> Category Descriptions
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Description Length</label>
                    <select class="form-select">
                        <option value="short">Short (50 characters)</option>
                        <option value="medium">Medium (100 characters)</option>
                        <option value="long">Long (200 characters)</option>
                        <option value="full">Full Description</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Description Position</label>
                    <select class="form-select">
                        <option value="below">Below Category Image</option>
                        <option value="overlay">Overlay on Image</option>
                        <option value="hover">Show on Hover</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show Description
                    </label>
                </div>
            </div>
        </div>

        <!-- Subcategories -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-sitemap text-accent"></i> Subcategories
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show Subcategories
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-label">Subcategories Display</label>
                    <select class="form-select">
                        <option value="dropdown">In Dropdown</option>
                        <option value="list">As List</option>
                        <option value="accordion">Accordion Style</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Info -->
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
                    <strong>Note:</strong> Changes will be reflected on the categories page after you save them.
                </div>
                <button class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-link"></i> View Categories Page
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
                    <i class="fas fa-question-circle text-accent"></i> Tips
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <strong>Grid Layout:</strong> 3-4 columns work best for desktop.
                    </li>
                    <li class="mb-3">
                        <strong>Images:</strong> Use high-quality category images for better appeal.
                    </li>
                    <li class="mb-3">
                        <strong>Descriptions:</strong> Keep them concise and informative.
                    </li>
                    <li>
                        <strong>Featured:</strong> Highlight your top-selling categories.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
