@extends('admin.layouts.app')

@section('title', $pageTitle)

@php
    // Initialize section variable for create form
    if (!isset($section)) {
        $section = null;
    }
@endphp

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="page-header d-print-none mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pageTitle }}</h2>
                <p class="text-muted">{{ $pageDescription }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.website-management.collections.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <form action="{{ isset($section) ? route('admin.website-management.collections.update', $section) : route('admin.website-management.collections.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($section))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Section Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="section_name">Section Name *</label>
                                    <input type="text" class="form-control @error('section_name') is-invalid @enderror" id="section_name" name="section_name" value="{{ old('section_name', $section->section_name ?? '') }}" placeholder="e.g., Best Sellers" required>
                                    @error('section_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="section_key">Section Key *</label>
                                    <input type="text" class="form-control @error('section_key') is-invalid @enderror" id="section_key" name="section_key" value="{{ old('section_key', $section->section_key ?? '') }}" placeholder="e.g., best_sellers" required>
                                    <small class="form-hint">Unique identifier (lowercase, underscores)</small>
                                    @error('section_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="badge_text">Badge Text</label>
                                    <input type="text" class="form-control @error('badge_text') is-invalid @enderror" id="badge_text" name="badge_text" value="{{ old('badge_text', $section->badge_text ?? '') }}" placeholder="e.g., TRENDING NOW">
                                    @error('badge_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="badge_bg_color">Badge Color *</label>
                                    <select class="form-control @error('badge_bg_color') is-invalid @enderror" id="badge_bg_color" name="badge_bg_color" required>
                                        <option value="gold" {{ (old('badge_bg_color', $section->badge_bg_color ?? 'gold') === 'gold') ? 'selected' : '' }}>Gold</option>
                                        <option value="dark" {{ (old('badge_bg_color', $section->badge_bg_color ?? '') === 'dark') ? 'selected' : '' }}>Dark</option>
                                    </select>
                                    @error('badge_bg_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="title">Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $section->title ?? '') }}" placeholder="e.g., Best Sellers" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="description">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Enter section description" required>{{ old('description', $section->description ?? '') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="button_text">Select Brand *</label>
                                    <select class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" required>
                                        <option value="">-- Select Brand --</option>
                                        @if(isset($brands) && $brands->count() > 0)
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->name }}" data-slug="{{ $brand->slug }}" {{ (old('button_text', $section->button_text ?? '') === $brand->name) ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="form-hint">When users click the button, they will be directed to this brand's page</small>
                                    @if(isset($brands) && $brands->count() === 0)
                                        <div class="text-warning small mt-1">
                                            <i class="fas fa-exclamation-triangle"></i> No active brands found. Please add brands first.
                                        </div>
                                    @endif
                                    @error('button_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Hidden field for button link that gets set automatically -->
                        <input type="hidden" name="button_link" id="button_link" value="{{ old('button_link', $section->button_link ?? '') }}">

                        <div class="mb-3">
                            <label class="form-label" for="features">Features (comma-separated)</label>
                            <textarea class="form-control @error('features') is-invalid @enderror" id="features" name="features" rows="2" placeholder="Breathable Fabrics, Modern Cuts, Vibrant Colors">{{ old('features', isset($section) && $section->features ? implode(', ', $section->features) : '') }}</textarea>
                            <small class="form-hint">Enter features separated by commas</small>
                            @error('features')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="display_order">Display Order</label>
                                    <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', $section->display_order ?? 0) }}" min="0">
                                    <small class="form-hint">Lower numbers appear first</small>
                                    @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_published" value="1" {{ (old('is_published', $section->is_published ?? false)) ? 'checked' : '' }}>
                                        <span class="form-check-label">Publish Section</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Preview -->
                <div class="card mb-3 sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Preview</h5>
                    </div>
                    <div class="card-body">
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center;">
                            @if(isset($section) && $section?->badge_text)
                                <div style="display: inline-block; background: {{ $section->badge_bg_color === 'gold' ? '#d4af37' : '#1a1a1a' }}; color: {{ $section->badge_bg_color === 'gold' ? '#000' : '#fff' }}; padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-bottom: 10px;">
                                    {{ $section->badge_text }}
                                </div>
                            @endif
                            <h4 id="title-preview">{{ isset($section) ? $section->title : 'Section Title' }}</h4>
                            <p id="description-preview" style="font-size: 0.9rem; color: #666;">
                                @if(isset($section) && $section->description)
                                    {{ strlen($section->description) > 100 ? substr($section->description, 0, 100) . '...' : $section->description }}
                                @else
                                    Section description appears here
                                @endif
                            </p>
                        </div>
                        <div class="mt-3 text-center">
                            <small class="text-muted">Images: <strong id="images-count">0</strong>/4</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Images Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Section Images</h5>
                <small class="form-hint">Upload exactly 4 images for this section</small>
            </div>
            <div class="card-body">
                @if(isset($section) && $section->images->count() > 0)
                    <div class="mb-4">
                        <h6>Existing Images</h6>
                        <div class="row" id="existing-images">
                            @foreach($section->images as $image)
                                <div class="col-md-3 mb-3">
                                    <div class="card position-relative">
                                        <div style="aspect-ratio: 1/1; overflow: hidden; border-radius: 4px 4px 0 0;">
                                            <img src="{{ $image->image_url }}" alt="{{ $image->image_alt_text }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div class="card-body p-2 small">
                                            <div class="text-truncate" title="{{ $image->product_name }}">{{ $image->product_name ?? 'Product' }}</div>
                                            <div class="text-muted small">{{ $image->product_price ?? 'Price' }}</div>
                                        </div>
                                        <label class="position-absolute top-2 right-2">
                                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                                            <span class="badge badge-danger">Delete</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($section->images->count() < 4)
                            <p class="text-muted small">You can add {{ 4 - $section->images->count() }} more image(s)</p>
                        @endif
                    </div>
                @endif

                <!-- New Images Upload -->
                <div>
                    <h6>{{ isset($section) ? 'Add Additional Images' : 'Upload Images' }}</h6>
                    <div class="row" id="new-images-container">
                        @php
                            $fieldName = isset($section) ? 'new_images' : 'images';
                        @endphp
                        @for($i = 0; $i < 4; $i++)
                            <div class="col-md-6 mb-3">
                                <div class="card border-dashed text-center p-4 image-upload-card" data-index="{{ $i }}">
                                    <div class="image-preview mb-2" style="display: none; height: 200px; border-radius: 4px; overflow: hidden;">
                                        <img class="preview-img" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="image-input-wrapper">
                                        <input type="file" class="form-control d-none image-input" name="{{ $fieldName }}[{{ $i }}][image]" accept="image/*">
                                        <button type="button" class="btn btn-sm btn-outline-secondary upload-btn">
                                            <i class="fas fa-cloud-upload-alt"></i> Choose Image
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm mt-2" name="{{ $fieldName }}[{{ $i }}][alt_text]" placeholder="Alt Text">
                                    <input type="text" class="form-control form-control-sm mt-2" name="{{ $fieldName }}[{{ $i }}][product_name]" placeholder="Product Name">
                                    <input type="text" class="form-control form-control-sm mt-2" name="{{ $fieldName }}[{{ $i }}][product_price]" placeholder="Price">
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-image-btn" style="display: none;">Remove</button>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer mt-4">
            <a href="{{ route('admin.website-management.collections.index') }}" class="btn btn-link">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ isset($section) ? 'Update Section' : 'Create Section' }}
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle brand selection and auto-set button link
    const brandSelect = document.getElementById('button_text');
    const buttonLinkInput = document.getElementById('button_link');

    brandSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value && selectedOption.dataset.slug) {
            // Set the button link to the brand detail route
            buttonLinkInput.value = '/brands/' + selectedOption.dataset.slug;
        } else {
            buttonLinkInput.value = '';
        }
    });

    // Set button link on page load if brand is already selected
    if (brandSelect.value) {
        const selectedOption = brandSelect.options[brandSelect.selectedIndex];
        if (selectedOption.dataset.slug) {
            buttonLinkInput.value = '/brands/' + selectedOption.dataset.slug;
        }
    }

    // Handle image uploads
    document.querySelectorAll('.image-upload-card').forEach(card => {
        const fileInput = card.querySelector('.image-input');
        const uploadBtn = card.querySelector('.upload-btn');
        const preview = card.querySelector('.image-preview');
        const previewImg = card.querySelector('.preview-img');
        const removeBtn = card.querySelector('.remove-image-btn');

        uploadBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                    card.querySelector('.image-input-wrapper').style.display = 'none';
                    removeBtn.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
                updateImageCount();
            }
        });

        removeBtn.addEventListener('click', () => {
            fileInput.value = '';
            preview.style.display = 'none';
            card.querySelector('.image-input-wrapper').style.display = 'block';
            removeBtn.style.display = 'none';
            updateImageCount();
        });
    });

    function updateImageCount() {
        const count = document.querySelectorAll('.image-upload-card .preview-img').length +
                      document.querySelectorAll('#existing-images .card').length;
        document.getElementById('images-count').textContent = count;
    }

    // Update preview on title change
    document.getElementById('title').addEventListener('input', function() {
        document.getElementById('title-preview').textContent = this.value || 'Section Title';
    });

    // Update preview on description change
    document.getElementById('description').addEventListener('input', function() {
        const desc = this.value ? this.value.substring(0, 100) + (this.value.length > 100 ? '...' : '') : 'Section description appears here';
        document.getElementById('description-preview').textContent = desc;
    });

    // Initialize counts
    updateImageCount();
});
</script>

<style>
    .border-dashed {
        border: 2px dashed #dee2e6;
    }
    
    .image-upload-card {
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .image-upload-card:hover {
        background-color: #f8f9fa;
        border-color: #007bff;
    }
    
    .sticky-top {
        position: sticky;
    }
    
    .right-2 {
        right: 8px;
    }
</style>
@endsection
