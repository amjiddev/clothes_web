<!-- Email Settings Form -->
<form action="{{ route('admin.settings.updateEmail') }}" method="POST" class="needs-validation" novalidate>
    @csrf

    <!-- Basic Email Settings -->
    <div class="setting-group">
        <h5><i class="fas fa-envelope me-2"></i>Email Configuration</h5>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">From Email Address</label>
                    <input type="email" name="email_from_address" class="form-control @error('email_from_address') is-invalid @enderror" 
                           value="{{ old('email_from_address', $settings->get('email_from_address')->value ?? 'noreply@clothesstore.com') }}" required>
                    @error('email_from_address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">From Name</label>
                    <input type="text" name="email_from_name" class="form-control @error('email_from_name') is-invalid @enderror" 
                           value="{{ old('email_from_name', $settings->get('email_from_name')->value ?? 'Clothes Store') }}" required>
                    @error('email_from_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- SMTP Settings -->
    <div class="setting-group">
        <h5><i class="fas fa-server me-2"></i>SMTP Configuration</h5>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Configure SMTP settings for sending emails. Leave empty to use default mail driver.
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">SMTP Host</label>
                    <input type="text" name="email_smtp_host" class="form-control @error('email_smtp_host') is-invalid @enderror" 
                           value="{{ old('email_smtp_host', $settings->get('email_smtp_host')->value ?? '') }}"
                           placeholder="smtp.gmail.com">
                    @error('email_smtp_host')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">SMTP Port</label>
                    <input type="number" name="email_smtp_port" class="form-control @error('email_smtp_port') is-invalid @enderror" 
                           value="{{ old('email_smtp_port', $settings->get('email_smtp_port')->value ?? '587') }}"
                           placeholder="587">
                    @error('email_smtp_port')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">SMTP Username</label>
                    <input type="text" name="email_smtp_username" class="form-control @error('email_smtp_username') is-invalid @enderror" 
                           value="{{ old('email_smtp_username', $settings->get('email_smtp_username')->value ?? '') }}"
                           placeholder="your-email@gmail.com">
                    @error('email_smtp_username')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">SMTP Password</label>
                    <input type="password" name="email_smtp_password" class="form-control @error('email_smtp_password') is-invalid @enderror" 
                           value="{{ old('email_smtp_password', $settings->get('email_smtp_password')->value ?? '') }}"
                           placeholder="your-app-password">
                    @error('email_smtp_password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Encryption</label>
            <select name="email_smtp_encryption" class="form-select @error('email_smtp_encryption') is-invalid @enderror">
                <option value="tls" {{ old('email_smtp_encryption', $settings->get('email_smtp_encryption')->value ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                <option value="ssl" {{ old('email_smtp_encryption', $settings->get('email_smtp_encryption')->value ?? 'tls') === 'ssl' ? 'selected' : '' }}>SSL</option>
            </select>
            @error('email_smtp_encryption')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="setting-group">
        <h5><i class="fas fa-bell me-2"></i>Notification Settings</h5>

        <div class="form-check mb-3">
            <input type="checkbox" name="email_notifications_enabled" class="form-check-input" value="1"
                   {{ old('email_notifications_enabled', $settings->get('email_notifications_enabled')->value ?? false) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold">
                Enable Email Notifications
            </label>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="email_order_notifications" class="form-check-input" value="1"
                   {{ old('email_order_notifications', $settings->get('email_order_notifications')->value ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">
                Send order confirmation emails
            </label>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="email_user_notifications" class="form-check-input" value="1"
                   {{ old('email_user_notifications', $settings->get('email_user_notifications')->value ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">
                Send user registration emails
            </label>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Save Email Settings
        </button>
    </div>
</form>
