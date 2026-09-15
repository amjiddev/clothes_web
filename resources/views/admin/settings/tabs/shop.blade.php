<!-- Shop Settings Form -->
<form action="{{ route('admin.settings.updateShop') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf

    <!-- Basic Information -->
    <div class="setting-group">
        <h5><i class="fas fa-info-circle me-2"></i>Basic Information</h5>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Shop Name</label>
                    <input type="text" name="shop_name" class="form-control @error('shop_name') is-invalid @enderror" 
                           value="{{ old('shop_name', $settings->get('shop_name')->value ?? 'Clothes Store') }}" required>
                    @error('shop_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Address</label>
            <textarea name="shop_address" class="form-control @error('shop_address') is-invalid @enderror" rows="3" required>{{ old('shop_address', $settings->get('shop_address')->value ?? '123 Fashion Street, City, State 12345') }}</textarea>
            @error('shop_address')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Logo & Favicon -->
    <div class="setting-group">
        <h5><i class="fas fa-image me-2"></i>Logo & Favicon</h5>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Shop Logo</label>
                    <input type="file" name="shop_logo" class="form-control @error('shop_logo') is-invalid @enderror" accept="image/*">
                    <small class="text-muted d-block mt-1">Max 5MB. Formats: JPEG, PNG, GIF, WebP</small>
                    @error('shop_logo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    @if ($settings->get('shop_logo') && $settings->get('shop_logo')->value)
                        <div class="image-preview mt-3">
                            <img src="{{ asset('storage/' . $settings->get('shop_logo')->value) }}" alt="Shop Logo">
                            <small class="d-block mt-2">Current logo</small>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Favicon</label>
                    <input type="file" name="shop_favicon" class="form-control @error('shop_favicon') is-invalid @enderror" accept="image/*">
                    <small class="text-muted d-block mt-1">Max 1MB. Formats: JPEG, PNG, GIF, ICO</small>
                    @error('shop_favicon')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    @if ($settings->get('shop_favicon') && $settings->get('shop_favicon')->value)
                        <div class="image-preview mt-3">
                            <img src="{{ asset('storage/' . $settings->get('shop_favicon')->value) }}" alt="Favicon" style="max-width: 100px;">
                            <small class="d-block mt-2">Current favicon</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Save Shop Settings
        </button>
    </div>
</form>
