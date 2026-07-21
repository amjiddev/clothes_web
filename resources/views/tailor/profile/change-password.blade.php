@extends('tailor.layouts.app')

@section('title', 'Change Password - Tailor Panel')

@section('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 40px;
        border-radius: 14px;
        margin-bottom: 35px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-hero h1 {
        margin: 0;
        font-size: 2.2rem;
        font-weight: 800;
    }

    .page-hero p {
        color: rgba(255, 255, 255, 0.85);
        margin: 10px 0 0 0;
        font-size: 1rem;
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 2px solid white;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .back-btn:hover {
        background: white;
        color: #1a1a2e;
    }

    .content-wrapper {
        max-width: 600px;
        margin: 0 auto;
    }

    .password-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
        margin-bottom: 25px;
    }

    .password-card-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .password-card-header i {
        color: #d4af37;
        font-size: 1.4rem;
    }

    .password-card-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-of-type {
        margin-bottom: 30px;
    }

    .form-label {
        display: block;
        color: #1a1a2e;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-control:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 4px;
        display: block;
    }

    .form-help {
        color: #666;
        font-size: 0.85rem;
        margin-top: 4px;
    }

    .password-requirements {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
        border-left: 4px solid #d4af37;
    }

    .requirements-header {
        background: linear-gradient(135deg, #f8f9fa, #f0f0f0);
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .requirements-header i {
        color: #d4af37;
        font-size: 1.3rem;
    }

    .requirements-header h6 {
        margin: 0;
        color: #1a1a2e;
        font-weight: 700;
        font-size: 1rem;
    }

    .requirements-list {
        padding: 20px;
        margin: 0;
        list-style: none;
    }

    .requirements-list li {
        color: #666;
        font-size: 0.95rem;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-left: 0;
    }

    .requirements-list li:last-child {
        margin-bottom: 0;
    }

    .requirements-list i {
        color: #27ae60;
        font-size: 1rem;
    }

    .btn-update {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 14px 32px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
    }

    .btn-update:hover {
        background: linear-gradient(135deg, #2980b9, #1a5276);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .btn-update:active {
        transform: translateY(0);
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .success-message i {
        color: #28a745;
        font-size: 1.2rem;
    }

    .password-strength {
        margin-top: 12px;
        display: flex;
        gap: 4px;
        align-items: center;
    }

    .strength-bar {
        flex: 1;
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        background: #dc3545;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .strength-text {
        font-size: 0.8rem;
        font-weight: 600;
        color: #666;
        min-width: 80px;
    }

    @media (max-width: 768px) {
        .page-hero {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .page-hero h1 {
            font-size: 1.8rem;
        }

        .password-card-body {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .page-hero {
            padding: 20px;
            gap: 15px;
        }

        .page-hero h1 {
            font-size: 1.5rem;
        }

        .password-card-body {
            padding: 15px;
        }

        .back-btn {
            padding: 8px 15px;
            font-size: 0.9rem;
        }

        .requirements-list li {
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Hero with Back Button -->
<div class="page-hero">
    <div>
        <h1>Change Password</h1>
        <p>Update your account password</p>
    </div>
    <a href="{{ route('tailor.profile.edit') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="content-wrapper">
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

<!-- Main Content -->
<div class="content-wrapper">
    <!-- Change Password Form -->
    <div class="password-card">
        <div class="password-card-header">
            <i class="fas fa-lock"></i>
            Update Your Password
        </div>
        <div class="password-card-body">
            <form action="{{ route('tailor.profile.change-password.store') }}" method="POST" id="passwordForm">
                @csrf

                <!-- Current Password -->
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Enter your current password" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" id="newPassword" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password" required>
                    <div class="form-help">Minimum 8 characters</div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm new password" required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Update Button -->
                <button type="submit" class="btn-update">
                    <i class="fas fa-save"></i> Update Password
                </button>
            </form>
        </div>
    </div>

    <!-- Password Requirements -->
    <div class="password-requirements">
        <div class="requirements-header">
            <i class="fas fa-shield-alt"></i>
            <h6>Password Requirements</h6>
        </div>
        <ul class="requirements-list">
            <li>
                <i class="fas fa-circle"></i>
                <span>At least 8 characters long</span>
            </li>
            <li>
                <i class="fas fa-circle"></i>
                <span>Mix of uppercase and lowercase letters (A-Z, a-z)</span>
            </li>
            <li>
                <i class="fas fa-circle"></i>
                <span>At least one number (0-9)</span>
            </li>
            <li>
                <i class="fas fa-circle"></i>
                <span>At least one special character (!@#$%^&*)</span>
            </li>
        </ul>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Password strength indicator (optional)
    const passwordInput = document.getElementById('newPassword');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            let strength = 0;
            const password = this.value;

            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[!@#$%^&*]/.test(password)) strength++;

            // You can add visual feedback here if desired
        });
    }
</script>
@endsection
