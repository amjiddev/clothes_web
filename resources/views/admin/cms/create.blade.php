@extends('admin.layouts.app')

@section('title', 'Create CMS Section')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
    <li class="breadcrumb-item active">Create Section</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-plus me-2"></i>Create CMS Section</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.cms.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <!-- Section Type -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Section Type</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Section Type</label>
                            <select name="section_type" class="form-select @error('section_type') is-invalid @enderror" required>
                                <option value="">-- Choose Section Type --</option>
                                <option value="slider" {{ $sectionType === 'slider' ? 'selected' : '' }}>Homepage Slider</option>
                                <option value="hero" {{ $sectionType === 'hero' ? 'selected' : '' }}>Hero Text</option>
                                <option value="about" {{ $sectionType === 'about' ? 'selected' : '' }}>About Section</option>
                                <option value="services" {{ $sectionType === 'services' ? 'selected' : '' }}>Services</option>
                                <option value="testimonial" {{ $sectionType === 'testimonial' ? 'selected' : '' }}>Testimonials</option>
                                <option value="contact" {{ $sectionType === 'contact' ? 'selected' : '' }}>Contact Information</option>
                                <option value="social" {{ $sectionType === 'social' ? 'selected' : '' }}>Social Links</option>
                                <option value="footer" {{ $sectionType === 'footer' ? 'selected' : '' }}>Footer Content</option>
                            </select>
                            @error('section_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" name="page_title" class="form-control @error('page_title') is-invalid @enderror" value="{{ old('page_title') }}" required>
                            @error('page_title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Content</label>
                            <textarea name="page_content" class="form-control @error('page_content') is-invalid @enderror" rows="5">{{ old('page_content') }}</textarea>
                            @error('page_content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Description</label>
                                    <input type="text" name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" value="{{ old('meta_description') }}" maxlength="160">
                                    @error('meta_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror" value="{{ old('meta_keywords') }}">
                                    @error('meta_keywords')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Media</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Featured Image</label>
                            <input type="file" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" accept="image/*">
                            <small class="text-muted d-block mt-1">Max 5MB. Supported: JPEG, PNG, GIF, WebP</small>
                            @error('featured_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Gallery Images</label>
                            <input type="file" name="gallery_images[]" class="form-control @error('gallery_images.*') is-invalid @enderror" accept="image/*" multiple>
                            <small class="text-muted d-block mt-1">Max 5MB each. Supported: JPEG, PNG, GIF, WebP</small>
                            @error('gallery_images.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dynamic Fields -->
                <div class="card border-0 shadow mb-4" id="dynamicFields">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Additional Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Hero Fields -->
                        <div class="hero-fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Button Text</label>
                                <input type="text" name="hero_button_text" class="form-control" value="{{ old('hero_button_text') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Button URL</label>
                                <input type="url" name="hero_button_url" class="form-control" value="{{ old('hero_button_url') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Subtitle</label>
                                <input type="text" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle') }}">
                            </div>
                        </div>

                        <!-- Services Fields -->
                        <div class="services-fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Icon Class (Font Awesome)</label>
                                <input type="text" name="service_icon" class="form-control" value="{{ old('service_icon') }}" placeholder="e.g., fas fa-shopping-cart">
                            </div>
                        </div>

                        <!-- Testimonial Fields -->
                        <div class="testimonial-fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Author Name</label>
                                        <input type="text" name="testimonial_author" class="form-control" value="{{ old('testimonial_author') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Author Position</label>
                                        <input type="text" name="testimonial_position" class="form-control" value="{{ old('testimonial_position') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rating</label>
                                <select name="testimonial_rating" class="form-select">
                                    <option value="">-- Select Rating --</option>
                                    <option value="1" {{ old('testimonial_rating') === '1' ? 'selected' : '' }}>1 Star</option>
                                    <option value="2" {{ old('testimonial_rating') === '2' ? 'selected' : '' }}>2 Stars</option>
                                    <option value="3" {{ old('testimonial_rating') === '3' ? 'selected' : '' }}>3 Stars</option>
                                    <option value="4" {{ old('testimonial_rating') === '4' ? 'selected' : '' }}>4 Stars</option>
                                    <option value="5" {{ old('testimonial_rating') === '5' ? 'selected' : '' }}>5 Stars</option>
                                </select>
                            </div>
                        </div>

                        <!-- Social Fields -->
                        <div class="social-fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Icon Class (Font Awesome)</label>
                                <input type="text" name="social_icon" class="form-control" value="{{ old('social_icon') }}" placeholder="e.g., fab fa-facebook">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Social Profile URL</label>
                                <input type="url" name="social_url" class="form-control" value="{{ old('social_url') }}">
                            </div>
                        </div>

                        <!-- Contact Fields -->
                        <div class="contact-fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="tel" name="contact_phone" class="form-control" value="{{ old('contact_phone') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="contact_address" class="form-control" rows="3">{{ old('contact_address') }}</textarea>
                            </div>
                        </div>

                        <!-- Footer Fields -->
                        <div class="footer-fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Footer Text</label>
                                <textarea name="footer_text" class="form-control" rows="3">{{ old('footer_text') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Copyright Text</label>
                                <input type="text" name="footer_copyright" class="form-control" value="{{ old('footer_copyright') }}" placeholder="© 2024 Company Name. All rights reserved.">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Publishing Options -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Publishing</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Display Order</label>
                                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', 0) }}" min="0">
                                    <small class="text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-check">
                                        <input type="checkbox" name="is_published" class="form-check-input" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                        <span class="form-check-label fw-bold">Publish This Section</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Section
                    </button>
                    <a href="{{ route('admin.cms.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Form Help -->
        <div class="col-lg-4">
            <div class="card border-0 shadow sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Help</h5>
                </div>
                <div class="card-body">
                    <div class="help-section mb-4">
                        <h6 class="fw-bold">Section Types</h6>
                        <p class="small text-muted">
                            <strong>Slider:</strong> Homepage carousel images<br>
                            <strong>Hero:</strong> Main banner heading<br>
                            <strong>About:</strong> Company story<br>
                            <strong>Services:</strong> Services offered<br>
                            <strong>Testimonials:</strong> Customer reviews<br>
                            <strong>Contact:</strong> Contact details<br>
                            <strong>Social:</strong> Social media links<br>
                            <strong>Footer:</strong> Footer content
                        </p>
                    </div>

                    <div class="help-section">
                        <h6 class="fw-bold">Tips</h6>
                        <ul class="small text-muted ps-3 mb-0">
                            <li>Keep titles short and descriptive</li>
                            <li>Optimize images before uploading</li>
                            <li>Use Font Awesome icons for services and social</li>
                            <li>Check publish status before saving</li>
                            <li>Set display order for proper sequencing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Show/hide dynamic fields based on section type
    const sectionTypeSelect = document.querySelector('select[name="section_type"]');
    const dynamicFieldsDiv = document.getElementById('dynamicFields');

    function toggleDynamicFields() {
        const selectedType = sectionTypeSelect.value;
        
        // Hide all
        document.querySelectorAll('[class$="-fields"]').forEach(el => el.style.display = 'none');
        
        // Show selected
        if (selectedType) {
            const fieldsClass = selectedType + '-fields';
            const fields = document.querySelector('.' + fieldsClass);
            if (fields) {
                fields.style.display = 'block';
            }
        }
    }

    sectionTypeSelect.addEventListener('change', toggleDynamicFields);
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', toggleDynamicFields);
</script>

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
</style>
@endsection
