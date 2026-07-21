@extends('admin.layouts.app')

@section('title', 'About Us Page Management')

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
    <!-- Left Column - Content -->
    <div class="col-lg-8">
        <!-- Company Story -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-book text-accent"></i> Company Story
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Story Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Our Journey" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Story Content</label>
                    <textarea class="form-control" rows="5" placeholder="Tell your company story here..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Featured Image</label>
                    <input type="file" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-label">Image Position</label>
                    <select class="form-select">
                        <option value="left">Left Side</option>
                        <option value="right">Right Side</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-target text-accent"></i> Mission & Vision
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Mission Statement</label>
                    <textarea class="form-control" rows="3" placeholder="Enter your mission"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Vision Statement</label>
                    <textarea class="form-control" rows="3" placeholder="Enter your vision"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Display Style</label>
                    <select class="form-select">
                        <option value="cards">Side by Side Cards</option>
                        <option value="vertical">Vertical Stack</option>
                        <option value="tabs">Tabs</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show mission & vision section
                    </label>
                </div>
            </div>
        </div>

        <!-- Team Members -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-users text-accent"></i> Team Members
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Section Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Meet Our Team" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Number of Team Members to Display</label>
                    <select class="form-select">
                        <option value="4">4 Members</option>
                        <option value="6">6 Members</option>
                        <option value="8">8 Members</option>
                        <option value="12">12 Members</option>
                        <option value="all">All Members</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Grid Layout</label>
                    <select class="form-select">
                        <option value="2">2 Columns</option>
                        <option value="3">3 Columns</option>
                        <option value="4">4 Columns</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show team members section
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show social media links
                    </label>
                </div>
            </div>
        </div>

        <!-- Company Achievements -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-award text-accent"></i> Achievements
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Section Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Our Achievements" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Display Style</label>
                    <select class="form-select">
                        <option value="timeline">Timeline</option>
                        <option value="grid">Grid</option>
                        <option value="list">List</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show achievements section
                    </label>
                </div>
            </div>
        </div>

        <!-- Company Values -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-heart text-accent"></i> Company Values
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Section Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Our Core Values" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Number of Values to Display</label>
                    <select class="form-select">
                        <option value="3">3 Values</option>
                        <option value="4">4 Values</option>
                        <option value="5">5 Values</option>
                        <option value="6">6 Values</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Display Style</label>
                    <select class="form-select">
                        <option value="icons">Icons</option>
                        <option value="cards">Cards</option>
                        <option value="list">List</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show values section
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Info -->
    <div class="col-lg-4">
        <!-- Statistics Card -->
        <div class="card rounded-lg border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar text-accent"></i> Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Show Statistics</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="showStats" checked>
                        <label class="form-check-label" for="showStats">
                            Display company statistics
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Example Statistics:</label>
                    <ul class="small">
                        <li>Years in Business</li>
                        <li>Happy Customers</li>
                        <li>Products Sold</li>
                        <li>Team Members</li>
                    </ul>
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
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> Changes will be reflected on the about us page after you save them.
                </div>
                <button class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-link"></i> View About Us Page
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
                        <strong>Story:</strong> Be authentic and compelling
                    </li>
                    <li class="mb-3">
                        <strong>Team:</strong> Show the faces behind your brand
                    </li>
                    <li class="mb-3">
                        <strong>Values:</strong> Express what matters to you
                    </li>
                    <li>
                        <strong>Achievements:</strong> Highlight your milestones
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
