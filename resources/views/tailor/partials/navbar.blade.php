<!-- Example Tailor Navbar with Notifications -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <!-- Navbar Brand -->
        <a class="navbar-brand" href="{{ route('tailor.dashboard') }}">
            <i class="fas fa-scissors"></i> Tailor Dashboard
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Orders -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tailor.stitching-orders.index') }}">
                        <i class="fas fa-list"></i> Orders
                    </a>
                </li>

                <!-- Measurements -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tailor.measurements.index') }}">
                        <i class="fas fa-ruler"></i> Measurements
                    </a>
                </li>

                <!-- Designs -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tailor.designs.gallery') }}">
                        <i class="fas fa-image"></i> Designs
                    </a>
                </li>

                <!-- Notification Bell Dropdown -->
                <li class="nav-item">
                    @include('tailor.components.notification-dropdown')
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('tailor.profile.edit') }}">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
    /* Custom navbar styles */
    .navbar {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.7) !important;
        transition: color 0.2s;
    }

    .nav-link:hover {
        color: rgba(255, 255, 255, 1) !important;
    }

    .nav-link.active {
        color: rgba(255, 255, 255, 1) !important;
        border-bottom: 2px solid #007bff;
    }

    .dropdown-menu {
        animation: slideDown 0.2s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
