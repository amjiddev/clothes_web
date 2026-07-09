@extends('admin.layouts.app')

@section('title', 'Edit CMS Section')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
    <li class="breadcrumb-item active">Edit Section</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-edit me-2"></i>Edit CMS Section</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.cms.update', $cms) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- Section Type -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Section Type</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Section Type</label>
                            <input type="text" class="form-control" value="{{ ucfirst($cms->section_type) }}" disabled>
                            <input type="hidden" name="section_type" value="{{ $cms->section_type }}">
                            <small class="text-muted d-block mt-1">Section type cannot be changed</small>
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
                            <input type="text" name="page_title" class="form-control @error('page_title') is-invalid @enderror" value="{{ old('page_title', $cms->page_title) }}" required>
                            @error('page_title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Content</label>
                            <textarea name="page_content" class="form-control @error('page_content') is-invalid @enderror" rows="5">{{ old('page_content', $cms->page_content) }}</textarea>
                            @error('page_content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Description</label>
                                    <input type="text" name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" value="{{ old('meta_description', $cms->meta_description) }}" maxlength="160">
                                    @error('meta_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror" value="{{ old('meta_keywords', $cms->meta_keywords) }}">
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
                        <!-- Current Featured Image -->
                        @if ($cms->featured_image)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Featured Image</label>
                                <div class="mb-2">
                                    <img src="{{ $cms->featured_image_url }}" class="img-fluid rounded" alt="{{ $cms->page_title }}" style="max-height: 300px;">
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Update Featured Image</label>
                            <input type="file" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" accept="image/*">
                            <small class="text-muted d-block mt-1">Leave empty to keep current image. Max 5MB.</small>
                            @error('featured_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Gallery Images -->
                        @if ($cms->gallery_images && count($cms->gallery_images) > 0)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Gallery Images</label>
                                <div class="row">
                                    @foreach ($cms->gallery_images as $image)
                                        <div class="col-md-3 mb-2">
                                            <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Gallery image" style="height: 150px; object-fit: cover; width: 100%;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Add Gallery Images</label>
                            <input type="file" name="gallery_images[]" class="form-control @error('gallery_images.*') is-invalid @enderror" accept="image/*" multiple>
                            <small class="text-muted d-block mt-1">Max 5MB each. New images will be added to existing gallery.</small>
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
                                <input type="text" name="hero_button_text" class="form-control" value="{{ old('hero_button_text', $cms->data['button_text'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Button URL</label>
                                <input type="url" name="hero_button_url" class="form-control" value="{{ old('hero_button_url', $cms->data['button_url'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Subtitle</label>
                                <input type="text" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle', $cms->data['subtitle'] ?? '') }}">
                            </div>
                        </div>

                        <!-- Services Fields -->
                        <div class="services-fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Icon Class (Font Awesome)</label>
                                <input type="text" name="service_icon" class="form-control" value="{{ old('service_icon', $cms->data['icon'] ?? '') }}" placeholder="e.g., fas fa-shopping-cart">
                            </div>
                        </div>

                        <!-- Testimonial Fields -->
                        <div class="testimonial-fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Author Name</label>
                                        <input type="text" name="testimonial_author" class="form-control" value="{{ old('testimonial_author', $cms->data['author'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Author Position</label>
                                        <input type="text" name="testimonial_position" class="form-control" value="{{ old('testimonial_position', $cms->data['position'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rating</label>
                                <select name="testimonial_rating" class="form-select">
                                    <option value="">-- Select Rating --</option>
                                    <option value="1" {{ old('testimonial_rating', $cms->data['rating'] ?? '') === '1' || old('testimonial_rating', $cms->data['rating'] ?? '') === 1 ? 'selected' : '' }}>1 Star</option>
                                    <option value="2" {{ old('testimonial_rating', $cms->data['rating'] ?? '') === '2' || old('testimonial_rating', $cms->data['rating'] ?? '') === 2 ? 'selected' : '' }}>2 Stars</option>
                                    <option value="3" {{ old('testimonial_rating', $cms->data['rating'] ?? '') === '3' || old('testimonial_rating', $cms->data['rating'] ?? '') === 3 ? 'selected' : '' }}>3 Stars</option>
                                    <option value="4" {{ old('testimonial_rating', $cms->data['rating'] ?? '') === '4' || old('testimonial_rating', $cms->data['rating'] ?? '') === 4 ? 'selected' : '' }}>4 Stars</option>
                                    <option value="5" {{ old('testimonial_rating', $cms->data['rating'] ?? '') === '5' || old('testimonial_rating', $cms->data['rating'] ?? '') === 5 ? 'selected' : '' }}>5 Stars</option>
                                </select>
                            </div>
                        </div>

                        <!-- Social Fields -->
                        <div class="social-fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Icon Class (Font Awesome)</label>
                                <input type="text" name="social_icon" class="form-control" value="{{ old('social_icon', $cms->data['icon'] ?? '') }}" placeholder="e.g., fab fa-facebook">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Social Profile URL</label>
                                <input type="url" name="social_url" class="form-control" value="{{ old('social_url', $cms->data['url'] ?? '') }}">
                            </div>
                        </div>

                        <!-- Contact Fields -->
                        <div class="contact-fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $cms->data['email'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="tel" name="contact_phone" class="form-control" value="{{ old('contact_phone', $cms->data['phone'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="contact_address" class="form-control" rows="3">{{ old('contact_address', $cms->data['address'] ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Footer Fields -->
                        <div class="footer-fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Footer Text</label>
                                <textarea name="footer_text" class="form-control" rows="3">{{ old('footer_text', $cms->page_content) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Copyright Text</label>
                                <input type="text" name="footer_copyright" class="form-control" value="{{ old('footer_copyright', $cms->data['copyright'] ?? '') }}" placeholder="© 2024 Company Name. All rights reserved.">
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
                                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $cms->display_order) }}" min="0">
                                    <small class="text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-check">
                                        <input type="checkbox" name="is_published" class="form-check-input" value="1" {{ old('is_published', $cms->is_published) ? 'checked' : '' }}>
                                        <span class="form-check-label fw-bold">Publish This Section</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            @if ($cms->published_at)
                                <i class="fas fa-info-circle me-2"></i>Published on {{ $cms->published_at->format('M d, Y H:i') }}
                            @else
                                <i class="fas fa-info-circle me-2"></i>Not published yet
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Section
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
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Section Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Type:</strong> {{ ucfirst($cms->section_type) }}
                    </div>
                    <div class="mb-3">
                        <strong>Created By:</strong> {{ $cms->creator->name ?? 'Unknown' }}
                    </div>
                    <div class="mb-3">
                        <strong>Created:</strong> {{ $cms->created_at->format('M d, Y H:i') }}
                    </div>
                    @if ($cms->updated_at->ne($cms->created_at))
                        <div class="mb-3">
                            <strong>Last Updated:</strong> {{ $cms->updated_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                    <div class="alert alert-warning small">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Changing publish status will affect website visibility immediately.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Show/hide dynamic fields based on section type
    const sectionType = '{{ $cms->section_type }}';
    const dynamicFieldsDiv = document.getElementById('dynamicFields');

    function toggleDynamicFields() {
        // Hide all
        document.querySelectorAll('[class$="-fields"]').forEach(el => el.style.display = 'none');
        
        // Show selected
        if (sectionType) {
            const fieldsClass = sectionType + '-fields';
            const fields = document.querySelector('.' + fieldsClass);
            if (fields) {
                fields.style.display = 'block';
            }
        }
    }

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
