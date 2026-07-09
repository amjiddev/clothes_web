<!-- Website Colors Form -->
<form action="{{ route('admin.settings.updateColors') }}" method="POST" class="needs-validation" novalidate>
    @csrf

    <!-- Brand Colors -->
    <div class="setting-group">
        <h5><i class="fas fa-paint-brush me-2"></i>Brand Colors</h5>

        <p class="text-muted mb-4">Customize the primary colors used throughout your website</p>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">Primary Color</label>
                    <div class="color-picker">
                        <input type="color" name="primary_color" class="form-control @error('primary_color') is-invalid @enderror"
                               value="{{ old('primary_color', $settings->get('primary_color')->value ?? '#0d6efd') }}" required>
                        <code id="primary_color_value">{{ old('primary_color', $settings->get('primary_color')->value ?? '#0d6efd') }}</code>
                    </div>
                    <small class="text-muted d-block mt-2">Used for buttons, links, and highlights</small>
                    @error('primary_color')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">Secondary Color</label>
                    <div class="color-picker">
                        <input type="color" name="secondary_color" class="form-control @error('secondary_color') is-invalid @enderror"
                               value="{{ old('secondary_color', $settings->get('secondary_color')->value ?? '#6c757d') }}" required>
                        <code id="secondary_color_value">{{ old('secondary_color', $settings->get('secondary_color')->value ?? '#6c757d') }}</code>
                    </div>
                    <small class="text-muted d-block mt-2">Used for secondary buttons and elements</small>
                    @error('secondary_color')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Colors -->
    <div class="setting-group">
        <h5><i class="fas fa-palette me-2"></i>Additional Colors</h5>

        <p class="text-muted mb-4">Fine-tune other colors for your website</p>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">Accent Color</label>
                    <div class="color-picker">
                        <input type="color" name="accent_color" class="form-control @error('accent_color') is-invalid @enderror"
                               value="{{ old('accent_color', $settings->get('accent_color')->value ?? '#28a745') }}">
                        <code id="accent_color_value">{{ old('accent_color', $settings->get('accent_color')->value ?? '#28a745') }}</code>
                    </div>
                    <small class="text-muted d-block mt-2">Success color for confirmations</small>
                    @error('accent_color')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">Text Color</label>
                    <div class="color-picker">
                        <input type="color" name="text_color" class="form-control @error('text_color') is-invalid @enderror"
                               value="{{ old('text_color', $settings->get('text_color')->value ?? '#1a1a1a') }}">
                        <code id="text_color_value">{{ old('text_color', $settings->get('text_color')->value ?? '#1a1a1a') }}</code>
                    </div>
                    <small class="text-muted d-block mt-2">Main text color</small>
                    @error('text_color')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">Background Color</label>
                    <div class="color-picker">
                        <input type="color" name="background_color" class="form-control @error('background_color') is-invalid @enderror"
                               value="{{ old('background_color', $settings->get('background_color')->value ?? '#ffffff') }}">
                        <code id="background_color_value">{{ old('background_color', $settings->get('background_color')->value ?? '#ffffff') }}</code>
                    </div>
                    <small class="text-muted d-block mt-2">Main background color</small>
                    @error('background_color')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Color Preview -->
    <div class="setting-group">
        <h5><i class="fas fa-eye me-2"></i>Preview</h5>

        <div class="row">
            <div class="col-md-6">
                <div class="card border-0" style="background-color: var(--primary-color, #0d6efd);">
                    <div class="card-body">
                        <p class="text-white mb-0">Primary Color Preview</p>
                        <small class="text-white-50">Buttons and highlights</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0" style="background-color: var(--secondary-color, #6c757d);">
                    <div class="card-body">
                        <p class="text-white mb-0">Secondary Color Preview</p>
                        <small class="text-white-50">Secondary elements</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card border-0" style="background-color: var(--accent-color, #28a745);">
                    <div class="card-body">
                        <p class="text-white mb-0">Accent Color Preview</p>
                        <small class="text-white-50">Success states</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0" style="background-color: var(--background-color, #ffffff); border: 1px solid #ddd !important;">
                    <div class="card-body">
                        <p style="color: var(--text-color, #1a1a1a); margin-bottom: 0;">Background & Text Preview</p>
                        <small style="color: var(--text-color, #1a1a1a); opacity: 0.7;">Text on background</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Save Color Settings
        </button>
    </div>
</form>

<script>
// Update color values display
document.addEventListener('DOMContentLoaded', function() {
    const colorInputs = document.querySelectorAll('input[type="color"]');
    
    colorInputs.forEach(input => {
        input.addEventListener('change', function() {
            const id = this.name + '_value';
            const valueElement = document.getElementById(id);
            if (valueElement) {
                valueElement.textContent = this.value.toUpperCase();
            }
        });

        // Also update on input for real-time preview
        input.addEventListener('input', function() {
            const id = this.name + '_value';
            const valueElement = document.getElementById(id);
            if (valueElement) {
                valueElement.textContent = this.value.toUpperCase();
            }
        });
    });
});
</script>
