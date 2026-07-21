@extends('admin.layouts.app')

@section('title', 'Contact Page Management')

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
                    <input type="text" class="form-control" placeholder="Enter page title" value="Contact Us">
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

        <!-- Contact Information -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle text-accent"></i> Contact Information
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Company Name</label>
                    <input type="text" class="form-control" placeholder="Enter company name">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" placeholder="Enter phone number">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" placeholder="Enter email address">
                </div>
                <div class="form-group">
                    <label class="form-label">Address Line 1</label>
                    <input type="text" class="form-control" placeholder="Enter address">
                </div>
                <div class="form-group">
                    <label class="form-label">Address Line 2</label>
                    <input type="text" class="form-control" placeholder="Enter address line 2 (optional)">
                </div>
                <div class="form-group">
                    <label class="form-label">City, State, Country</label>
                    <input type="text" class="form-control" placeholder="e.g., Lahore, Punjab, Pakistan">
                </div>
                <div class="form-group">
                    <label class="form-label">Postal Code</label>
                    <input type="text" class="form-control" placeholder="Enter postal code">
                </div>
                <div class="form-group">
                    <label class="form-label">Business Hours</label>
                    <textarea class="form-control" rows="3" placeholder="e.g., Monday - Friday: 9:00 AM - 6:00 PM&#10;Saturday: 10:00 AM - 4:00 PM&#10;Sunday: Closed"></textarea>
                </div>
            </div>
        </div>

        <!-- Contact Form Settings -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-envelope text-accent"></i> Contact Form
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Form Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Send us a Message" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Form Description</label>
                    <textarea class="form-control" rows="2" placeholder="Enter form description"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Submit Button Text</label>
                    <input type="text" class="form-control" placeholder="e.g., Send Message" value="Send Message">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Recipient (for contact form submissions)</label>
                    <input type="email" class="form-control" placeholder="Enter recipient email">
                </div>
                <div class="form-group">
                    <label class="form-label">Form Fields</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="field1" checked>
                        <label class="form-check-label" for="field1">Name</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="field2" checked>
                        <label class="form-check-label" for="field2">Email</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="field3" checked>
                        <label class="form-check-label" for="field3">Phone (Optional)</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="field4" checked>
                        <label class="form-check-label" for="field4">Subject</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="field5" checked>
                        <label class="form-check-label" for="field5">Message</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show contact form
                    </label>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-map text-accent"></i> Location Map
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Show Map</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="showMap" checked>
                        <label class="form-check-label" for="showMap">
                            Display location map
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Map Type</label>
                    <select class="form-select">
                        <option value="google">Google Maps</option>
                        <option value="embed">Embedded Map</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Map Latitude</label>
                    <input type="text" class="form-control" placeholder="e.g., 31.5497">
                </div>
                <div class="form-group">
                    <label class="form-label">Map Longitude</label>
                    <input type="text" class="form-control" placeholder="e.g., 74.3436">
                </div>
                <div class="form-group">
                    <label class="form-label">Map Zoom Level</label>
                    <input type="number" class="form-control" placeholder="Enter zoom level (1-20)" value="15">
                </div>
                <div class="form-group">
                    <label class="form-label">Embed Map Code</label>
                    <textarea class="form-control" rows="2" placeholder="Paste Google Map embed code here"></textarea>
                </div>
            </div>
        </div>

        <!-- Social Links -->
        <div class="card rounded-lg border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-share-alt text-accent"></i> Social Media Links
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Facebook URL</label>
                    <input type="url" class="form-control" placeholder="Enter Facebook page URL">
                </div>
                <div class="form-group">
                    <label class="form-label">Twitter URL</label>
                    <input type="url" class="form-control" placeholder="Enter Twitter profile URL">
                </div>
                <div class="form-group">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" class="form-control" placeholder="Enter Instagram profile URL">
                </div>
                <div class="form-group">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="url" class="form-control" placeholder="Enter LinkedIn profile URL">
                </div>
                <div class="form-group">
                    <label class="form-label">WhatsApp Number</label>
                    <input type="tel" class="form-control" placeholder="Enter WhatsApp number (with country code)">
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" checked>
                        Show social media links
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Info -->
    <div class="col-lg-4">
        <!-- Quick Preview -->
        <div class="card rounded-lg border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-eye text-accent"></i> Preview
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> Changes will be reflected on the contact page after you save them.
                </div>
                <button class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-link"></i> View Contact Page
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
                        <strong>Contact Info:</strong> Make it easy to find
                    </li>
                    <li class="mb-3">
                        <strong>Contact Form:</strong> Keep it simple and short
                    </li>
                    <li class="mb-3">
                        <strong>Map:</strong> Include your business location
                    </li>
                    <li>
                        <strong>Social Links:</strong> Link to active accounts
                    </li>
                </ul>
            </div>
        </div>

        <!-- Support Card -->
        <div class="card rounded-lg border-0 shadow-sm mt-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-headset text-accent"></i> Support
                </h5>
            </div>
            <div class="card-body">
                <p class="small">Need help with contact page setup? Check our documentation or contact support.</p>
                <button class="btn btn-sm btn-outline-primary w-100">
                    <i class="fas fa-book"></i> View Documentation
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
