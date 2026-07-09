<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet" />

    <style>
        :root {
            --primary-color: #1a1a2e;
            --secondary-color: #0f0f1e;
            --tertiary-color: #16213e;
            --accent-color: #d4af37;
            --text-dark: #2c3e50;
            --text-light: #ecf0f1;
            --border-color: #34495e;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --info: #3498db;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            background-color: #f5f6fa;
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .receptionist-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ===================== SIDEBAR STYLING ===================== */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--text-light);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--accent-color);
            border-radius: 3px;
        }

        .sidebar-brand {
            padding: 25px 20px;
            border-bottom: 2px solid rgba(212, 175, 55, 0.3);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent-color), #c9a227);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--primary-color);
            font-weight: bold;
        }

        .sidebar-brand h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--accent-color);
            letter-spacing: 1px;
        }

        .sidebar-brand p {
            margin: 0;
            font-size: 0.75rem;
            color: #95a5a6;
        }

        .sidebar-nav {
            padding: 15px 0;
        }

        .sidebar-nav .nav-section {
            margin-bottom: 10px;
        }

        .sidebar-nav .nav-section-title {
            padding: 10px 20px;
            font-size: 0.75rem;
            font-weight: 700;
            color: rgba(212, 175, 55, 0.7);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-nav .nav-item {
            border-left: 3px solid transparent;
            margin: 0;
            transition: all 0.3s ease;
        }

        .sidebar-nav .nav-item:hover {
            border-left-color: var(--accent-color);
            background-color: rgba(212, 175, 55, 0.1);
        }

        .sidebar-nav .nav-link {
            color: var(--text-light);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
            color: var(--accent-color);
        }

        .sidebar-nav .nav-link.active {
            background-color: rgba(212, 175, 55, 0.2);
            border-left-color: var(--accent-color);
            color: var(--accent-color);
        }

        .sidebar-nav .nav-link:hover {
            color: var(--accent-color);
        }

        /* ===================== MAIN CONTENT ===================== */
        .main-content {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #e0e6ed;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .breadcrumb-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-dark);
            cursor: pointer;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .search-box {
            position: relative;
            width: 280px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #e0e6ed;
            border-radius: 25px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 8px rgba(212, 175, 55, 0.2);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }

        .notification-bell,
        .profile-dropdown {
            cursor: pointer;
            position: relative;
        }

        .notification-bell i {
            font-size: 1.3rem;
            color: var(--text-dark);
            transition: color 0.3s ease;
        }

        .notification-bell:hover i {
            color: var(--accent-color);
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .profile-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-color);
        }

        .profile-info span {
            display: block;
        }

        .profile-info .user-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .profile-info .user-role {
            font-size: 0.8rem;
            color: #95a5a6;
        }

        /* Dropdown */
        .dropdown-menu {
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 180px;
        }

        .dropdown-menu .dropdown-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f5f6fa;
            transition: all 0.3s ease;
        }

        .dropdown-menu .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #f5f6fa;
            color: var(--accent-color);
        }

        /* ===================== CONTENT AREA ===================== */
        .content-area {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: #95a5a6;
            font-size: 0.95rem;
        }

        /* ===================== STAT CARDS ===================== */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            border: 1px solid #e0e6ed;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--accent-color);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #95a5a6;
            font-size: 0.9rem;
        }

        .stat-change {
            font-size: 0.85rem;
            margin-top: 10px;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }

        .stat-change.positive {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .stat-change.negative {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger);
        }

        /* ===================== TABLES ===================== */
        .table-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0e6ed;
        }

        .table-card .table-header {
            padding: 20px;
            border-bottom: 1px solid #e0e6ed;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-card .table-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .table-card table {
            margin-bottom: 0;
        }

        .table-card thead {
            background-color: #f5f6fa;
            border-bottom: 2px solid #e0e6ed;
        }

        .table-card th {
            color: var(--text-dark);
            font-weight: 600;
            padding: 15px;
            border: none;
            font-size: 0.9rem;
        }

        .table-card td {
            padding: 15px;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #e0e6ed;
        }

        .table-card tbody tr:last-child td {
            border-bottom: none;
        }

        .table-card tbody tr:hover {
            background-color: #f5f6fa;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }

        .status-completed {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .status-assigned {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--info);
        }

        .status-in-progress {
            background-color: rgba(149, 165, 166, 0.1);
            color: #34495e;
        }

        .status-ready {
            background-color: rgba(212, 175, 55, 0.1);
            color: var(--accent-color);
        }

        /* ===================== BUTTONS ===================== */
        .btn-custom {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary-custom {
            background-color: var(--accent-color);
            color: var(--primary-color);
        }

        .btn-primary-custom:hover {
            background-color: #c9a227;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .btn-secondary-custom {
            background-color: var(--primary-color);
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
        }

        .btn-secondary-custom:hover {
            background-color: var(--secondary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* ===================== QUICK ACTIONS ===================== */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .quick-action-btn {
            background: white;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            color: var(--text-dark);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .quick-action-btn i {
            font-size: 2rem;
            color: var(--accent-color);
        }

        .quick-action-btn:hover {
            border-color: var(--accent-color);
            background-color: rgba(212, 175, 55, 0.05);
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
            color: var(--accent-color);
        }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                transform: translateX(-250px);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .search-box {
                width: 150px;
            }

            .search-box input {
                padding: 8px 12px 8px 35px;
                font-size: 0.85rem;
            }

            .topbar-actions {
                gap: 15px;
            }

            .content-area {
                padding: 20px;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-card {
                font-size: 0.85rem;
            }

            .table-card th,
            .table-card td {
                padding: 10px;
            }

            .stat-value {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .stat-card {
                padding: 15px;
            }
        }
    </style>

    @yield('extra-css')
</head>

<body>
    <div class="receptionist-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div>
                    <h4>CLOTHES</h4>
                    <p>Receptionist</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <!-- Main Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <div class="nav-item">
                        <a href="{{ route('receptionist.dashboard') }}" class="nav-link {{ request()->routeIs('receptionist.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </div>

                <!-- Operations Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Operations</div>
                    <div class="nav-item">
                        <a href="{{ route('receptionist.customers.index') }}" class="nav-link {{ request()->routeIs('receptionist.customers.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Customers</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('receptionist.orders.create') }}" class="nav-link {{ request()->routeIs('receptionist.orders.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i>
                            <span>Create Order</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('receptionist.orders.index') }}" class="nav-link {{ request()->routeIs('receptionist.orders.index') ? 'active' : '' }}">
                            <i class="fas fa-shopping-bag"></i>
                            <span>Orders</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('receptionist.stitching-orders.index') }}" class="nav-link {{ request()->routeIs('receptionist.stitching-orders.*') ? 'active' : '' }}">
                            <i class="fas fa-needle"></i>
                            <span>Stitching Orders</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('receptionist.measurements.index') }}" class="nav-link {{ request()->routeIs('receptionist.measurements.*') ? 'active' : '' }}">
                            <i class="fas fa-ruler"></i>
                            <span>Measurements</span>
                        </a>
                    </div>
                </div>

                <!-- Management Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Management</div>
                    <div class="nav-item">
                        <a href="{{ route('receptionist.tailors.index') }}" class="nav-link {{ request()->routeIs('receptionist.tailors.*') ? 'active' : '' }}">
                            <i class="fas fa-hammer"></i>
                            <span>Tailors</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('receptionist.payments.index') }}" class="nav-link {{ request()->routeIs('receptionist.payments.*') ? 'active' : '' }}">
                            <i class="fas fa-credit-card"></i>
                            <span>Payments</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('receptionist.invoices.index') }}" class="nav-link {{ request()->routeIs('receptionist.invoices.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice"></i>
                            <span>Invoices</span>
                        </a>
                    </div>
                </div>

                <!-- Reports Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Reports</div>
                    <div class="nav-item">
                        <a href="{{ route('receptionist.reports.index') }}" class="nav-link {{ request()->routeIs('receptionist.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation Bar -->
            <div class="topbar">
                <div class="topbar-content">
                    <div class="breadcrumb-section">
                        <button class="mobile-toggle" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('receptionist.dashboard') }}" style="text-decoration: none; color: var(--accent-color);">Dashboard</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>

                    <div class="topbar-actions">
                        <!-- Search Box -->
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" placeholder="Search...">
                        </div>

                        <!-- Notification Bell -->
                        <div class="notification-bell" id="notificationBell">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="dropdown">
                            <div class="profile-dropdown" id="profileDropdown" data-bs-toggle="dropdown">
                                <img src="https://via.placeholder.com/40" alt="Profile" class="avatar-image">
                                <div class="profile-info">
                                    <span class="user-name">{{ Auth::user()->name }}</span>
                                    <span class="user-role">Receptionist</span>
                                </div>
                                <i class="fas fa-chevron-down" style="font-size: 0.8rem; color: #95a5a6;"></i>
                            </div>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#profile">
                                        <i class="fas fa-user me-2"></i>My Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#settings">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar on link click (mobile)
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    document.getElementById('sidebar').classList.remove('show');
                });
            });
        }

        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                allowClear: true,
            });
        });
    </script>

    @yield('extra-js')
</body>

</html>
