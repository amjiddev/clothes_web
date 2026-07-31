@extends('tailor.layouts.app')

@section('title', 'Profile Settings - Tailor Panel')

@section('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 40px;
        border-radius: 14px;
        margin-bottom: 35px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
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

    .content-wrapper {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 30px;
        margin-bottom: 30px;
    }

    .profile-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
    }

    .profile-card-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .profile-card-header i {
        color: #d4af37;
        font-size: 1.4rem;
    }

    .profile-card-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-child {
        margin-bottom: 0;
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

    .profile-image-section {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
        margin-bottom: 20px;
    }

    .profile-image-display {
        flex-shrink: 0;
    }

    .profile-image-current {
        width: 120px;
        height: 120px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 3px solid #d4af37;
    }

    .profile-image-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f5f5f5, #e9e9e9);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #ddd;
        color: #999;
    }

    .profile-image-placeholder i {
        font-size: 2.5rem;
    }

    .profile-image-upload {
        flex: 1;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input-custom {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        border: 2px dashed #d4af37;
        border-radius: 8px;
        background: rgba(212, 175, 55, 0.05);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-input-custom:hover {
        border-color: #c9a227;
        background: rgba(212, 175, 55, 0.1);
    }

    .file-input-custom i {
        color: #d4af37;
        margin-right: 10px;
        font-size: 1.3rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #27ae60, #229954);
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

    .btn-save:hover {
        background: linear-gradient(135deg, #229954, #1e8449);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
    }

    .btn-save:active {
        transform: translateY(0);
    }

    .sidebar-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
        margin-bottom: 20px;
    }

    .sidebar-card-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 1.05rem;
    }

    .sidebar-card-header i {
        color: #d4af37;
        font-size: 1.2rem;
    }

    .sidebar-card-body {
        padding: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 15px;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        color: #666;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        color: #1a1a2e;
        font-size: 1rem;
        font-weight: 600;
    }

    .info-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #d4af37, transparent);
        margin: 12px 0;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .btn-change-password {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
        text-decoration: none;
    }

    .btn-change-password:hover {
        background: linear-gradient(135deg, #2980b9, #1a5276);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        color: white;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-row.full {
        grid-template-columns: 1fr;
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

    .error-message {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    @media (max-width: 1024px) {
        .content-wrapper {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-hero {
            padding: 25px;
        }

        .page-hero h1 {
            font-size: 1.8rem;
        }

        .profile-card-body {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .profile-image-section {
            flex-direction: column;
            align-items: center;
        }

        .profile-image-current,
        .profile-image-placeholder {
            width: 100px;
            height: 100px;
        }
    }

    @media (max-width: 480px) {
        .page-hero {
            padding: 20px;
        }

        .page-hero h1 {
            font-size: 1.5rem;
        }

        .profile-card-body {
            padding: 15px;
        }

        .form-label {
            font-size: 0.85rem;
        }

        .btn-save {
            padding: 12px 20px;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Hero -->
<div class="page-hero">
    <h1>Profile Settings</h1>
    <p>Manage your profile information and account settings</p>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="success-message">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Main Content -->
<div class="content-wrapper">
    <!-- Profile Information Form -->
    <div class="profile-card">
        <div class="profile-card-header">
            <i class="fas fa-user-edit"></i>
            Profile Information
        </div>
        <div class="profile-card-body">
            <form action="{{ route('tailor.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Profile Image Section -->
                <div class="profile-image-section">
                    <div class="profile-image-display">
                        @if($tailor && $tailor->profile_image)
                            <img src="{{ asset('storage/' . $tailor->profile_image) }}" alt="Profile" class="profile-image-current">
                        @else
                            <div class="profile-image-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>

                    <div class="profile-image-upload">
                        <label class="form-label">Profile Image</label>
                        <div class="file-input-wrapper">
                            <label class="file-input-custom">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload image</span>
                                <input type="file" name="profile_image" accept="image/*" style="display: none;">
                            </label>
                        </div>
                        <div class="form-help">Max 2MB. Supported: JPEG, PNG, JPG, GIF</div>
                        @error('profile_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ auth()->user()->name }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ auth()->user()->email }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ $tailor->phone ?? '' }}" placeholder="+1 (555) 123-4567">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Experience (Years)</label>
                        <input type="number" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" value="{{ $tailor->experience_years ?? '' }}" min="0" placeholder="5">
                        @error('experience_years')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="form-group">
                    <label class="form-label">Specialization</label>
                    <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror" placeholder="e.g., Men's Formal Wear, Casual Tailoring" value="{{ $tailor->specialization ?? '' }}">
                    @error('specialization')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-row full">
                    <div>
                        <label class="form-label">About You</label>
                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="5" placeholder="Tell customers about your experience, style, and specialties...">{{ $tailor->bio ?? '' }}</textarea>
                        <div class="form-help">Max 1000 characters</div>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Save Button -->
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Account Information Card -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <i class="fas fa-info-circle"></i>
                Account Info
            </div>
            <div class="sidebar-card-body">
                <div class="info-item">
                    <div class="info-label">User ID</div>
                    <div class="info-value">#{{ auth()->user()->id }}</div>
                </div>

                <div class="info-divider"></div>

                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        @if($tailor && $tailor->status === 'active')
                            <span class="status-badge badge-active">Active</span>
                        @else
                            <span class="status-badge badge-inactive">{{ ucfirst($tailor->status ?? 'Unknown') }}</span>
                        @endif
                    </div>
                </div>

                <div class="info-divider"></div>

                <div class="info-item">
                    <div class="info-label">Member Since</div>
                    <div class="info-value">{{ auth()->user()->created_at->format('M d, Y') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Last Login</div>
                    <div class="info-value">
                        @if(auth()->user()->last_login_at)
                            {{ auth()->user()->last_login_at->format('M d, Y H:i A') }}
                        @else
                            Never
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Card -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <i class="fas fa-lock"></i>
                Security
            </div>
            <div class="sidebar-card-body">
                <a href="{{ route('tailor.profile.change-password') }}" class="btn-change-password">
                    <i class="fas fa-key"></i> Change Password
                </a>
            </div>
        </div>

        <!-- Permissions Card -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <i class="fas fa-shield-alt"></i>
                Permissions
            </div>
            <div class="sidebar-card-body">
                <div class="info-item">
                    <div class="info-label">Role</div>
                    <div class="info-value">Tailor</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Access Level</div>
                    <div class="info-value">View & Edit Own Data</div>
                </div>

                <div class="form-help" style="margin-top: 15px;">
                    <i class="fas fa-info-circle" style="color: #666; margin-right: 6px;"></i>
                    Role and permissions are managed by administrators
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Handle file input visual feedback
    document.querySelectorAll('.file-input-custom').forEach(label => {
        const input = label.querySelector('input[type="file"]');
        if (input) {
            input.addEventListener('change', function() {
                if (this.files.length > 0) {
                    label.innerHTML = '<i class="fas fa-check-circle"></i> File selected: ' + this.files[0].name;
                    label.style.borderColor = '#27ae60';
                    label.style.background = 'rgba(39, 174, 96, 0.05)';
                }
            });
        }
    });
</script>
@endsection
