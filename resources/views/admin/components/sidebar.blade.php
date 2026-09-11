<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-crown"></i>
        <h4>Admin</h4>
    </div>

    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Products Management -->
        <div class="nav-item">
            <a href="#" class="nav-link nav-toggle {{ request()->routeIs('admin.products.*', 'admin.categories.*', 'admin.inventory.*') ? 'active' : '' }} collapsed">
                <i class="fas fa-shirt"></i>
                <span>Products</span>
            </a>
            <div class="nav-submenu {{ request()->routeIs('admin.products.*', 'admin.categories.*', 'admin.inventory.*') ? 'show' : '' }}">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>Product List</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tag"></i>
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory</span>
                </a>
            </div>
        </div>

        <!-- Orders Management -->
        <div class="nav-item">
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Orders</span>
            </a>
        </div>

        <!-- Stitching Management -->
        <div class="nav-item">
            <a href="#" class="nav-link nav-toggle {{ request()->routeIs('admin.stitching.*', 'admin.tailors.*') ? 'active' : '' }} collapsed">
                <i class="fas fa-needle"></i>
                <span>Stitching</span>
            </a>
            <div class="nav-submenu {{ request()->routeIs('admin.stitching.*', 'admin.tailors.*') ? 'show' : '' }}">
                <a href="{{ route('admin.stitching-orders.index') }}" class="nav-link {{ request()->routeIs('admin.stitching-orders.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>Stitching Orders</span>
                </a>
                <a href="{{ route('admin.tailors.index') }}" class="nav-link {{ request()->routeIs('admin.tailors.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Tailors</span>
                </a>
            </div>
        </div>

        <!-- Customers -->
        <div class="nav-item">
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Customers</span>
            </a>
        </div>

        <!-- Receptionists -->
        <div class="nav-item">
            <a href="{{ route('admin.receptionists.index') }}" class="nav-link {{ request()->routeIs('admin.receptionists.*') ? 'active' : '' }}">
                <i class="fas fa-headset"></i>
                <span>Receptionists</span>
            </a>
        </div>

        <!-- Payments -->
        <div class="nav-item">
            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>
                <span>Payments</span>
            </a>
        </div>

        <!-- Coupons & Discounts -->
        <div class="nav-item">
            <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i>
                <span>Coupons & Discounts</span>
            </a>
        </div>

        <!-- Reports -->
        <div class="nav-item">
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
        </div>

        <!-- Website Management -->
        <div class="nav-item">
            <a href="#" class="nav-link nav-toggle {{ request()->routeIs('admin.website-management.*') ? 'active' : '' }} collapsed">
                <i class="fas fa-globe"></i>
                <span>Website Management</span>
            </a>
            <div class="nav-submenu {{ request()->routeIs('admin.website-management.*') ? 'show' : '' }}">
                <a href="{{ route('admin.website-management.home-page') }}" class="nav-link {{ request()->routeIs('admin.website-management.home-page') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Home Page</span>
                </a>
                <a href="{{ route('admin.website-management.contact') }}" class="nav-link {{ request()->routeIs('admin.website-management.contact') ? 'active' : '' }}">
                    <i class="fas fa-address-book"></i>
                    <span>Contact Page</span>
                </a>
                <a href="{{ route('admin.website-management.collections.index') }}" class="nav-link {{ request()->routeIs('admin.website-management.collections.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i>
                    <span>Collections Page</span>
                </a>
                <a href="{{ route('admin.website-management.product-sections.index') }}" class="nav-link {{ request()->routeIs('admin.website-management.product-sections.*') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Product Sections</span>
                </a>
            </div>
        </div>

        <!-- User Management -->
        <div class="nav-item">
            <a href="#" class="nav-link nav-toggle {{ request()->routeIs('admin.user-management.*') ? 'active' : '' }} collapsed">
                <i class="fas fa-sliders-h"></i>
                <span>User Management</span>
            </a>
            <div class="nav-submenu {{ request()->routeIs('admin.user-management.*') ? 'show' : '' }}">
                <a href="{{ route('admin.user-management.users.index') }}" class="nav-link {{ request()->routeIs('admin.user-management.users.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Users</span>
                </a>
                <a href="{{ route('admin.user-management.roles.index') }}" class="nav-link {{ request()->routeIs('admin.user-management.roles.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Roles</span>
                </a>
                <a href="{{ route('admin.user-management.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.user-management.permissions.*') ? 'active' : '' }}">
                    <i class="fas fa-lock"></i>
                    <span>Permissions</span>
                </a>
            </div>
        </div>

        <!-- Settings -->
        <div class="nav-item">
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>

        <!-- Divider -->
        <div style="border-bottom: 1px solid rgba(212, 175, 55, 0.2); margin: 20px 0;"></div>

        <!-- View Site -->
        <div class="nav-item">
            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                <i class="fas fa-external-link-alt"></i>
                <span>View Website</span>
            </a>
        </div>
    </nav>
</aside>
