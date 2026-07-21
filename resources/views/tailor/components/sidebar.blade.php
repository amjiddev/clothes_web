<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="fas fa-needle"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 1.1rem;">Tailor Pro</h4>
            <small style="color: rgba(212, 175, 55, 0.7); font-size: 0.75rem;">Professional Dashboard</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <div class="nav-item">
            <a href="{{ route('tailor.dashboard') }}" class="nav-link {{ request()->routeIs('tailor.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Main Section -->
        <div class="nav-section-title">Work Management</div>

        <!-- Assigned Stitching Orders -->
        <div class="nav-item">
            <a href="{{ route('tailor.stitching-orders.index') }}" class="nav-link {{ request()->routeIs('tailor.stitching-orders.*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
                <span>Assigned Orders</span>
            </a>
        </div>

        <!-- Measurement Details -->
        <div class="nav-item">
            <a href="{{ route('tailor.measurements.index') }}" class="nav-link {{ request()->routeIs('tailor.measurements.*') ? 'active' : '' }}">
                <i class="fas fa-ruler-combined"></i>
                <span>Measurements</span>
            </a>
        </div>

        <!-- Stitching Status -->
        <div class="nav-item">
            <a href="{{ route('tailor.status.index') }}" class="nav-link {{ request()->routeIs('tailor.status.*') ? 'active' : '' }}">
                <i class="fas fa-spinner"></i>
                <span>Stitching Status</span>
            </a>
        </div>

        <!-- Resources Section -->
        <div class="nav-section-title">Resources</div>

        <!-- Design Gallery -->
        <div class="nav-item">
            <a href="{{ route('tailor.designs.gallery') }}" class="nav-link {{ request()->routeIs('tailor.designs.*') ? 'active' : '' }}">
                <i class="fas fa-image"></i>
                <span>Designs</span>
            </a>
        </div>

        <!-- Completed Orders -->
        <div class="nav-item">
            <a href="{{ route('tailor.completed-orders.index') }}" class="nav-link {{ request()->routeIs('tailor.completed-orders.*') ? 'active' : '' }}">
                <i class="fas fa-check-double"></i>
                <span>Completed Work</span>
            </a>
        </div>

        <!-- Account Section -->
        <div class="nav-section-title">Account</div>

        <!-- Profile Settings -->
        <div class="nav-item">
            <a href="{{ route('tailor.profile.edit') }}" class="nav-link {{ request()->routeIs('tailor.profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i>
                <span>Profile Settings</span>
            </a>
        </div>

        <!-- Divider -->
        <div style="margin: 20px 0; border-top: 1px solid rgba(255, 255, 255, 0.15);"></div>

        <!-- Logout -->
        <div class="nav-item">
            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                @csrf
                <button type="submit" class="nav-link w-100 text-start" style="background: none; border: none; cursor: pointer; color: rgba(255, 255, 255, 0.7);">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>
