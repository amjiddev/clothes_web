<div class="topbar">
    <div class="topbar-content">
        <div class="breadcrumb-section">
            <button class="mobile-toggle" type="button">
                <i class="fas fa-bars"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    @if (trim($__env->yieldContent('breadcrumb')))
                        @yield('breadcrumb')
                    @else
                        <li class="breadcrumb-item active">Dashboard</li>
                    @endif
                </ol>
            </nav>
        </div>

        <div class="topbar-actions">
            <!-- Search Box -->
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search..." class="form-control">
            </div>

            <!-- Notifications -->
            <div class="notification-bell" id="notificationBell" style="display: none;">
                <i class="fas fa-bell"></i>
                @php
                    $unreadNotifications = 0; // Disabled: notifications table doesn't exist yet
                @endphp
                @if ($unreadNotifications > 0)
                    <span class="notification-badge">{{ $unreadNotifications }}</span>
                @endif
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <div class="profile-avatar" data-bs-toggle="dropdown">
                    @if (auth()->user()->profile_photo_path)
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"
                            class="avatar-image">
                    @else
                        <div class="avatar-image" style="background: linear-gradient(135deg, #d4af37, #9d8f3a); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="profile-info">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role">{{ auth()->user()->roles->first()?->name ?? 'User' }}</span>
                    </div>
                </div>

                <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#adminProfileModal">
                            <i class="fas fa-user me-2"></i> Profile
                        </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="adminProfileModal" tabindex="-1" aria-labelledby="adminProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminProfileModalLabel">Super Admin Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" id="adminProfileForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="profile_form" value="1">
                <div class="modal-body text-center">
                    @if (auth()->user()->profile_photo_path)
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle mb-3" style="width: 88px; height: 88px; object-fit: cover;">
                    @else
                        <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 88px; height: 88px; background: linear-gradient(135deg, #d4af37, #9d8f3a); color: white; font-size: 2rem; font-weight: 700;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="text-start">
                        <label class="form-label">Name</label>
                        <input class="form-control profile-field mb-3" name="name" value="{{ auth()->user()->name }}" readonly>
                        <label class="form-label">Old Email</label>
                        <input class="form-control mb-3" type="email" value="{{ auth()->user()->email }}" readonly>
                        <div id="newAdminEmailField" class="d-none">
                            <label class="form-label">New Email</label>
                            <input class="form-control profile-field mb-1" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" readonly>
                            @error('email')
                                <div class="text-danger small mb-3">{{ $message }}</div>
                            @else
                                <div class="mb-3"></div>
                            @enderror
                        </div>
                        <label class="form-label">Contact Number</label>
                        <input class="form-control profile-field" name="contact_number" value="{{ auth()->user()->contact_number }}" placeholder="Add contact number" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="editAdminProfile">Edit</button>
                    <button type="submit" class="btn btn-success d-none" id="saveAdminProfile">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButton = document.getElementById('editAdminProfile');
        const saveButton = document.getElementById('saveAdminProfile');
        const newEmailField = document.getElementById('newAdminEmailField');
        const fields = document.querySelectorAll('#adminProfileForm .profile-field');

        if (editButton) {
            editButton.addEventListener('click', function () {
                fields.forEach(field => field.removeAttribute('readonly'));
                newEmailField.classList.remove('d-none');
                editButton.classList.add('d-none');
                saveButton.classList.remove('d-none');
                fields[0].focus();
            });
        }

        @if ($errors->has('email') && old('profile_form'))
            new bootstrap.Modal(document.getElementById('adminProfileModal')).show();
            newEmailField.classList.remove('d-none');
            editButton.classList.add('d-none');
            saveButton.classList.remove('d-none');
            fields.forEach(field => field.removeAttribute('readonly'));
        @endif
    });
</script>
