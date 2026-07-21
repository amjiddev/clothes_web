@extends('admin.layouts.app')

@section('title', 'Shop Page Management')

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
        <!-- Page Header Settings -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-heading text-accent"></i> Page Header
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Page Title</label>
                    <input type="text" class="form-control" placeholder="Enter page title" value="Shop">
                </div>
                <div class="form-group">
                    <label class="form-label">Page Description</label>
                    <textarea class="form-control" rows="3" placeholder="Enter page description"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Header Image/Banner</label>
                    <input type="file" class="form-control" accept="image/*">
                </div>
            </div>
        </div>

        <!-- Filter Settings -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-filter text-accent"></i> Filters Section
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Enable Filters</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="filterCategory" checked>
                        <label class="form-check-label" for="filterCategory">
                            Category Filter
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="filterPrice" checked>
                        <label class="form-check-label" for="filterPrice">
                            Price Range Filter
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="filterSize" checked>
                        <label class="form-check-label" for="filterSize">
                            Size Filter
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="filterColor" checked>
                        <label class="form-check-label" for="filterColor">
                            Color Filter
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="filterFabric" checked>
                        <label class="form-check-label" for="filterFabric">
                            Fabric Type Filter
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid Settings -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-th text-accent"></i> Products Grid
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Products Per Page</label>
                    <select class="form-select">
                        <option value="12">12 Products</option>
                        <option value="24">24 Products</option>
                        <option value="36">36 Products</option>
                        <option value="48">48 Products</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Grid Layout</label>
                    <select class="form-select">
                        <option value="3">3 Columns</option>
                        <option value="4">4 Columns</option>
                        <option value="5">5 Columns</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Default Sort By</label>
                    <select class="form-select">
                        <option value="newest">Newest</option>
                        <option value="popular">Most Popular</option>
                        <option value="low-to-high">Price: Low to High</option>
                        <option value="high-to-low">Price: High to Low</option>
                        <option value="best-selling">Best Selling</option>
                        <option value="rating">Top Rated</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pagination Settings -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-list-ol text-accent"></i> Pagination
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Pagination Style</label>
                    <select class="form-select">
                        <option value="numbers">Numbers</option>
                        <option value="previous-next">Previous/Next</option>
                        <option value="load-more">Load More Button</option>
                        <option value="infinite-scroll">Infinite Scroll</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show Total Products Count
                    </label>
                </div>
            </div>
        </div>

        <!-- Sidebar Settings -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-bars text-accent"></i> Sidebar
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Sidebar Position</label>
                    <select class="form-select">
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show Search Bar
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show Featured Products
                    </label>
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
                    <strong>Note:</strong> Changes will be reflected on the shop page after you save them.
                </div>
                <button class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-link"></i> View Shop Page
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
                        <strong>Filters:</strong> Make it easy for customers to find products.
                    </li>
                    <li class="mb-3">
                        <strong>Grid Layout:</strong> 4 columns works best for most screens.
                    </li>
                    <li class="mb-3">
                        <strong>Pagination:</strong> Infinite scroll provides better UX.
                    </li>
                    <li>
                        <strong>Sidebar:</strong> Keep it organized and easy to scan.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
