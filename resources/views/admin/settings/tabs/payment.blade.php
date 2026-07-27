<!-- Payment Settings Form -->
<form action="{{ route('admin.settings.updatePayment') }}" method="POST" class="needs-validation" novalidate>
    @csrf

    <!-- Stripe Settings -->
    <div class="setting-group">
        <h5><i class="fab fa-cc-stripe me-2"></i>Stripe Payment</h5>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Enter your Stripe API keys from your Stripe Dashboard. Keep secret key secure!
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Stripe Public Key</label>
            <input type="text" name="payment_stripe_key" class="form-control @error('payment_stripe_key') is-invalid @enderror" 
                   value="{{ old('payment_stripe_key', $settings->get('payment_stripe_key')->value ?? '') }}"
                   placeholder="pk_live_...">
            @error('payment_stripe_key')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Stripe Secret Key</label>
            <input type="password" name="payment_stripe_secret" class="form-control @error('payment_stripe_secret') is-invalid @enderror" 
                   value="{{ old('payment_stripe_secret', $settings->get('payment_stripe_secret')->value ?? '') }}"
                   placeholder="sk_live_...">
            <small class="text-muted d-block mt-1">This key is sensitive - handle with care</small>
            @error('payment_stripe_secret')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- PayPal Settings -->
    <div class="setting-group">
        <h5><i class="fab fa-cc-paypal me-2"></i>PayPal Payment</h5>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Configure your PayPal merchant account credentials here.
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">PayPal Client ID</label>
            <input type="text" name="payment_paypal_client_id" class="form-control @error('payment_paypal_client_id') is-invalid @enderror" 
                   value="{{ old('payment_paypal_client_id', $settings->get('payment_paypal_client_id')->value ?? '') }}"
                   placeholder="AZvXXXXXXXXXXX...">
            @error('payment_paypal_client_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">PayPal Secret</label>
            <input type="password" name="payment_paypal_secret" class="form-control @error('payment_paypal_secret') is-invalid @enderror" 
                   value="{{ old('payment_paypal_secret', $settings->get('payment_paypal_secret')->value ?? '') }}"
                   placeholder="XXXXX...">
            <small class="text-muted d-block mt-1">Keep your secret key confidential</small>
            @error('payment_paypal_secret')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Transaction Settings -->
    <div class="setting-group">
        <h5><i class="fas fa-exchange-alt me-2"></i>Transaction Settings</h5>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Currency</label>
                    <select name="payment_currency" class="form-select @error('payment_currency') is-invalid @enderror">
                        <option value="USD" {{ old('payment_currency', $settings->get('payment_currency')->value ?? 'USD') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ old('payment_currency', $settings->get('payment_currency')->value ?? 'USD') === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="GBP" {{ old('payment_currency', $settings->get('payment_currency')->value ?? 'USD') === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                        <option value="AUD" {{ old('payment_currency', $settings->get('payment_currency')->value ?? 'USD') === 'AUD' ? 'selected' : '' }}>AUD (A$)</option>
                        <option value="CAD" {{ old('payment_currency', $settings->get('payment_currency')->value ?? 'USD') === 'CAD' ? 'selected' : '' }}>CAD (C$)</option>
                        <option value="INR" {{ old('payment_currency', $settings->get('payment_currency')->value ?? 'USD') === 'INR' ? 'selected' : '' }}>INR (Rs.)</option>
                    </select>
                    @error('payment_currency')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tax Percentage (%)</label>
                    <input type="number" name="payment_tax_percentage" class="form-control @error('payment_tax_percentage') is-invalid @enderror" 
                           value="{{ old('payment_tax_percentage', $settings->get('payment_tax_percentage')->value ?? '0') }}"
                           min="0" max="100" step="0.01">
                    <small class="text-muted">Applied to all orders</small>
                    @error('payment_tax_percentage')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Save Payment Settings
        </button>
    </div>
</form>
