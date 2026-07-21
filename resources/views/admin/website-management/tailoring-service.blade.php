@extends('admin.layouts.app')

@section('title', 'Tailoring Service Page Management')

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
    <!-- Left Column - Services -->
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
                    <input type="text" class="form-control" placeholder="Enter page title" value="Tailoring Services">
                </div>
                <div class="form-group">
                    <label class="form-label">Page Subtitle</label>
                    <textarea class="form-control" rows="2" placeholder="Enter page subtitle"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Hero Image</label>
                    <input type="file" class="form-control" accept="image/*">
                </div>
            </div>
        </div>

        <!-- Service Type 1 - Cloth Only -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-shirt text-accent"></i> Service 1: Cloth Only
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Service Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Ready-Made Collection" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Service Description</label>
                    <textarea class="form-control" rows="3" placeholder="Describe this service"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Price Range</label>
                    <div class="input-group">
                        <span class="input-group-text">Rs.</span>
                        <input type="number" class="form-control" placeholder="From">
                        <span class="input-group-text">-</span>
                        <input type="number" class="form-control" placeholder="To">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Service Image</label>
                    <input type="file" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Enable this service
                    </label>
                </div>
            </div>
        </div>

        <!-- Service Type 2 - Cloth + Stitching -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-needle text-accent"></i> Service 2: Cloth + Stitching
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Service Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Fabric + Custom Stitching" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Service Description</label>
                    <textarea class="form-control" rows="3" placeholder="Describe this service"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Base Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Rs.</span>
                        <input type="number" class="form-control" placeholder="Enter price">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Stitching Charges (Additional)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rs.</span>
                        <input type="number" class="form-control" placeholder="Enter stitching charges">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Estimated Turnaround Time</label>
                    <input type="text" class="form-control" placeholder="e.g., 7-10 days">
                </div>
                <div class="form-group">
                    <label class="form-label">Service Image</label>
                    <input type="file" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Enable this service
                    </label>
                </div>
            </div>
        </div>

        <!-- Service Type 3 - Stitching Only -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-cutting text-accent"></i> Service 3: Stitching Only
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Service Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Custom Stitching Service" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Service Description</label>
                    <textarea class="form-control" rows="3" placeholder="Describe this service"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Starting Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Rs.</span>
                        <input type="number" class="form-control" placeholder="Enter starting price">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Estimated Turnaround Time</label>
                    <input type="text" class="form-control" placeholder="e.g., 10-14 days">
                </div>
                <div class="form-group">
                    <label class="form-label">Service Image</label>
                    <input type="file" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Enable this service
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Process & Additional -->
    <div class="col-lg-4">
        <!-- Pricing Table -->
        <div class="card rounded-lg border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-list-ul text-accent"></i> Pricing Table
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Show Pricing Table</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="showPricing" checked>
                        <label class="form-check-label" for="showPricing">
                            Display pricing comparison
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Table Layout</label>
                    <select class="form-select">
                        <option value="horizontal">Horizontal</option>
                        <option value="vertical">Vertical</option>
                        <option value="cards">Cards</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Process Timeline -->
        <div class="card rounded-lg border-0 shadow-sm mt-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-timeline text-accent"></i> Process Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Show Process Steps</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="showTimeline" checked>
                        <label class="form-check-label" for="showTimeline">
                            Display process timeline
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Timeline Style</label>
                    <select class="form-select">
                        <option value="vertical">Vertical</option>
                        <option value="horizontal">Horizontal</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="card rounded-lg border-0 shadow-sm mt-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-comments text-accent"></i> Testimonials
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show customer testimonials
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-label">Number of Testimonials</label>
                    <select class="form-select">
                        <option value="3">3 Testimonials</option>
                        <option value="5">5 Testimonials</option>
                        <option value="8">8 Testimonials</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card rounded-lg border-0 shadow-sm mt-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-eye text-accent"></i> Preview
                </h5>
            </div>
            <div class="card-body">
                <button class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-link"></i> View Services Page
                </button>
                <button class="btn btn-outline-secondary w-100">
                    <i class="fas fa-sync"></i> Preview Changes
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
