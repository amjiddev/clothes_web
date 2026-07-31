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
                    <!-- Profile Edit - Route not defined, removed -->
                    <!-- My Profile and Settings links removed - profile.edit route not available -->
                    
                    <li><a class="dropdown-item" href="{{ route('change-password') }}">
                            <i class="fas fa-lock me-2"></i> Change Password
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
