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

        body {
            background-color: #f5f6fa;
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
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

        .sidebar-brand h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--accent-color);
            letter-spacing: 1px;
        }

        .sidebar-nav {
            padding: 15px 0;
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

        .sidebar-nav .nav-submenu {
            display: none;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 0;
        }

        .sidebar-nav .nav-submenu.show {
            display: block;
        }

        .sidebar-nav .nav-submenu .nav-link {
            padding: 10px 20px 10px 50px;
            font-size: 0.9rem;
        }

        .sidebar-nav .nav-toggle::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-nav .nav-toggle.collapsed::after {
            transform: rotate(-90deg);
        }

        /* Main Content */
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
            width: 300px;
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
        .profile-avatar {
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

        .profile-avatar {
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

        /* Content Area */
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

        /* Cards */
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

        /* Tables */
        .data-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0e6ed;
        }

        .data-table thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .data-table thead th {
            padding: 15px;
            font-weight: 600;
            border: none;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .data-table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e6ed;
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background-color: #f5f6fa;
        }

        /* Badges */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-pending {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }

        .badge-active {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .badge-inactive {
            background-color: rgba(149, 165, 166, 0.1);
            color: #95a5a6;
        }

        .badge-success {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .badge-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger);
        }

        .badge-info {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--info);
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--accent-color);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
        }

        .btn-accent {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        .btn-accent:hover {
            background: #e6c200;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Forms */
        .form-control,
        .form-select {
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger);
        }

        .alert-warning {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }

        .alert-info {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--info);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
                width: 280px;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .search-box {
                display: none;
            }

            .topbar-content {
                gap: 10px;
            }

            .topbar-actions {
                gap: 15px;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .content-area {
                padding: 15px;
            }

            .stat-card {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            .profile-info {
                display: none;
            }

            .topbar {
                padding: 12px 15px;
            }
        }

        /* Charts */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            border: 1px solid #e0e6ed;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        /* Loading Spinner */
        .spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 400px;
        }

        .custom-spinner {
            border: 4px solid rgba(212, 175, 55, 0.1);
            border-top: 4px solid var(--accent-color);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Modal */
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
        }

        .modal-title {
            font-weight: 700;
        }

        .btn-close {
            background-color: rgba(255, 255, 255, 0.3);
            opacity: 1;
        }

        .btn-close:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        /* Utility Classes */
        .text-accent {
            color: var(--accent-color);
        }

        .text-muted {
            color: #95a5a6;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .p-3 {
            padding: 1rem;
        }

        .rounded-lg {
            border-radius: 12px;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        @include('admin.components.sidebar')

        <div class="main-content">
            @include('admin.components.topbar')

            <div class="content-area">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h5>Please correct the following errors:</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        // Auto-hide alerts after 2 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');
            
            if (successAlert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(successAlert);
                    bsAlert.close();
                }, 2000);
            }
            
            if (errorAlert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(errorAlert);
                    bsAlert.close();
                }, 2000);
            }
        });

        // Mobile Sidebar Toggle
        document.querySelector('.mobile-toggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking on a link
        document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    document.querySelector('.sidebar').classList.remove('show');
                }
            });
        });

        // Submenu Toggle
        document.querySelectorAll('.nav-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = this.nextElementSibling;
                const parent = this.closest('.nav-item');
                parent.classList.toggle('active');
                submenu?.classList.toggle('show');
            });
        });

        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });

        // Initialize DataTables
        if (document.querySelector('.data-table')) {
            new DataTable('.data-table', {
                responsive: true,
                pageLength: 10,
                language: {
                    search: "Search:",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                }
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
