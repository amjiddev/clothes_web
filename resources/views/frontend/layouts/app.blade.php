<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Premium Men\'s Fashion & Tailoring') - Clothes Store</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-dark: #0B0B0B;
            --secondary-dark: #0F172A;
            --accent-gold: #D4AF37;
            --text-light: #FFFFFF;
            --text-cream: #F8F5EF;
            --text-muted: #666666;
            --text-dark: #0B0B0B;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        html, body {
            height: 100%;
            scroll-behavior: smooth;
        }

        body {
            background-color: #FFFFFF;
            color: var(--text-dark);
            padding-top: 70px;
        }

        /* Navbar Styles */
        .navbar-premium {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 2px solid var(--accent-gold);
            transition: transform 0.3s ease-in-out;
        }

        .navbar-premium.navbar-hidden {
            transform: translateY(-100%);
        }

        .navbar-premium .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-gold) !important;
            letter-spacing: 2px;
            font-family: 'Playfair Display', serif;
        }

        .navbar-premium .nav-link {
            color: var(--text-light) !important;
            margin: 0 1rem;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
        }

        .navbar-premium .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-gold);
            transition: width 0.3s ease;
        }

        .navbar-premium .nav-link:hover::after,
        .navbar-premium .nav-link.active::after {
            width: 100%;
        }

        .navbar-premium .nav-link:hover,
        .navbar-premium .nav-link.active {
            color: var(--accent-gold) !important;
        }

        .cart-icon {
            position: relative;
            color: var(--accent-gold);
            font-size: 1.3rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cart-icon:hover {
            transform: scale(1.1);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent-gold);
            color: var(--primary-dark);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
            color: white;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
            border-bottom: 3px solid var(--accent-gold);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, var(--accent-gold) 0%, transparent 70%);
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(20px); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
            line-height: 1.2;
        }

        .hero-section h2 {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            color: var(--accent-gold);
            font-weight: 300;
        }

        .btn-premium {
            background: var(--accent-gold);
            color: var(--primary-dark);
            padding: 12px 30px;
            border: 2px solid var(--accent-gold);
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-premium:hover {
            background: transparent;
            color: var(--accent-gold);
        }

        .btn-outline-premium {
            background: transparent;
            color: var(--accent-gold);
            padding: 12px 30px;
            border: 2px solid var(--accent-gold);
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-premium:hover {
            background: var(--accent-gold);
            color: var(--primary-dark);
        }

        /* Section Spacing */
        .section-padding {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .section-title p {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 300;
        }

        .divider {
            width: 80px;
            height: 3px;
            background: var(--accent-gold);
            margin: 10px auto;
        }

        /* Category Card */
        .category-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
            border: 2px solid transparent;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
            border-color: var(--accent-gold);
        }

        .category-card i {
            font-size: 3rem;
            color: var(--accent-gold);
            margin-bottom: 1rem;
        }

        .category-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .category-card p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Product Card */
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(212, 175, 55, 0.2);
            border-color: var(--accent-gold);
        }

        .product-image {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent-gold);
            color: var(--primary-dark);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .product-price {
            display: flex;
            gap: 10px;
            margin-bottom: 1rem;
            align-items: center;
        }

        .product-price .current {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--accent-gold);
        }

        .product-price .original {
            font-size: 0.9rem;
            color: var(--text-muted);
            text-decoration: line-through;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .product-actions a,
        .product-actions button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-add-cart {
            background: var(--accent-gold);
            color: var(--primary-dark);
        }

        .btn-add-cart:hover {
            background: var(--primary-dark);
            color: var(--accent-gold);
        }

        .btn-view-detail {
            background: transparent;
            color: var(--primary-dark);
            border: 2px solid var(--primary-dark);
        }

        .btn-view-detail:hover {
            background: var(--primary-dark);
            color: white;
        }

        /* Tailoring Section */
        .tailoring-section {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
            color: white;
            border-bottom: 3px solid var(--accent-gold);
        }

        .tailoring-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 10px;
            border: 2px solid var(--accent-gold);
            text-align: center;
            transition: all 0.3s ease;
        }

        .tailoring-card:hover {
            background: rgba(212, 175, 55, 0.15);
            transform: translateY(-5px);
            border-color: #FFE066;
        }

        .tailoring-card i {
            font-size: 2.5rem;
            color: var(--accent-gold);
            margin-bottom: 1rem;
        }

        .tailoring-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Why Choose Us */
        .why-choose-us-card {
            text-align: center;
            padding: 2rem;
        }

        .why-choose-us-card i {
            font-size: 3rem;
            color: var(--accent-gold);
            margin-bottom: 1rem;
        }

        .why-choose-us-card h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1rem;
        }

        /* Review Card */
        .review-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .review-stars {
            color: var(--accent-gold);
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .review-text {
            color: var(--text-muted);
            margin-bottom: 1rem;
            font-style: italic;
        }

        .review-author {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.3rem;
        }

        .review-title {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
            color: var(--text-light);
            padding: 60px 0 20px;
            margin-top: 80px;
            border-top: 3px solid var(--accent-gold);
        }

        .footer-section h4 {
            color: var(--accent-gold);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 0.8rem;
        }

        .footer-section ul li a {
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: var(--accent-gold);
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: var(--accent-gold);
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .social-links a:hover {
            background: transparent;
            color: var(--accent-gold);
            border: 2px solid var(--accent-gold);
        }

        .footer-bottom {
            border-top: 1px solid rgba(212, 175, 55, 0.3);
            margin-top: 3rem;
            padding-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Shopping Cart Sidebar */
        .cart-sidebar {
            position: fixed;
            right: -400px;
            top: 0;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 20px rgba(0, 0, 0, 0.3);
            z-index: 1001;
            transition: right 0.4s ease-in-out;
            display: flex;
            flex-direction: column;
        }

        .cart-sidebar.active {
            right: 0;
        }

        .cart-header {
            padding: 1.5rem;
            border-bottom: 2px solid var(--accent-gold);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--primary-dark);
            color: white;
        }

        .cart-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.3rem;
        }

        .cart-header .btn-close {
            filter: invert(1);
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .cart-header .btn-close:hover {
            opacity: 1;
        }

        .cart-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .empty-cart-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--text-muted);
            text-align: center;
        }

        .empty-cart-message i {
            font-size: 3rem;
            color: var(--accent-gold);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .cart-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
            align-items: flex-start;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            background: #f5f5f5;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.3rem;
            font-size: 0.95rem;
        }

        .cart-item-price {
            color: var(--accent-gold);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        .cart-item-quantity button {
            width: 24px;
            height: 24px;
            padding: 0;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .cart-item-quantity button:hover {
            background: var(--accent-gold);
            color: white;
            border-color: var(--accent-gold);
        }

        .cart-item-remove {
            cursor: pointer;
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }

        .cart-item-remove:hover {
            color: #c82333;
        }

        .cart-footer {
            padding: 1.5rem;
            border-top: 2px solid #e0e0e0;
            background: #f8f9fa;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-dark);
        }

        .btn-checkout {
            background: var(--accent-gold);
            color: var(--primary-dark);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 5px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-checkout:hover {
            background: var(--primary-dark);
            color: var(--accent-gold);
        }

        .btn-view-cart {
            background: transparent;
            color: var(--primary-dark);
            border: 2px solid var(--primary-dark);
            padding: 10px;
            font-weight: 600;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view-cart:hover {
            background: var(--primary-dark);
            color: white;
        }

        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .cart-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .login-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.65);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        .login-modal-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .login-modal-dialog {
            position: relative;
            width: min(560px, 100%);
            height: min(460px, calc(100vh - 2rem));
            overflow: hidden;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        .login-modal-dialog.forgot-active {
            height: min(520px, calc(100vh - 2rem));
        }

        .login-modal-dialog iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        .login-modal-dialog iframe.auth-frame-hidden {
            display: none;
        }

        .login-modal-dialog iframe.auth-frame-loading {
            visibility: hidden;
        }

        .login-modal-close {
            position: absolute;
            top: 12px;
            right: 14px;
            z-index: 2;
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 50%;
            background: rgba(11, 11, 11, 0.75);
            color: #fff;
            font-size: 1.8rem;
            line-height: 1;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }

            .hero-section h2 {
                font-size: 1rem;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }

            .navbar-premium .nav-link {
                margin: 0.5rem 0;
            }

            .product-image {
                height: 200px;
            }

            .cart-sidebar {
                width: 100%;
                right: -100%;
            }

            body {
                padding-top: 60px;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-premium">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-crown"></i> CLOTHES
            </a>
            <button class="navbar-toggler btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop') }}">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('new-in') }}">NEW IN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('brands-page') }}">BRANDS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('collections') }}">Collections</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tailoring.service') }}">Tailoring Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                    <li class="nav-item ms-3">
                        @if(auth()->check())
                            <span class="nav-link">{{ auth()->user()->name }}</span>
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        @else
                            <a class="nav-link auth-login-trigger" href="{{ route('login') }}" data-login-url="{{ route('login') }}">Login</a>
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        @endif
                    </li>
                    <li class="nav-item ms-3">
                        <a href="javascript:void(0)" class="cart-icon" id="cartIcon">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="cart-badge">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Shopping Cart Sidebar Modal -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h5>Shopping Cart</h5>
            <button type="button" class="btn-close" id="closeCartSidebar"></button>
        </div>
        <div class="cart-body" id="cartItems">
            <div class="empty-cart-message">
                <i class="fas fa-shopping-bag"></i>
                <p>Your cart is empty</p>
            </div>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span id="cartTotal">$0.00</span>
            </div>
            <button class="btn btn-checkout w-100" id="checkoutBtn">Checkout</button>
            <a href="{{ route('cart') }}" class="btn btn-view-cart w-100 mt-2">View Full Cart</a>
        </div>
    </div>

    <!-- Cart Overlay -->
    <div class="cart-overlay" id="cartOverlay"></div>

    <div class="login-modal-backdrop" id="loginModal" aria-hidden="true">
        <div class="login-modal-dialog" role="dialog" aria-modal="true" aria-label="Login">
            <button type="button" class="login-modal-close" id="closeLoginModal" aria-label="Close">&times;</button>
            <iframe id="loginModalFrame" class="auth-frame-loading" src="{{ route('login') }}" title="Login form"></iframe>
            <iframe id="forgotPasswordModalFrame" class="auth-frame-hidden auth-frame-loading" src="{{ route('password.request') }}" title="Forgot password form"></iframe>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 footer-section">
                    <h4><i class="fas fa-crown"></i> CLOTHES STORE</h4>
                    <p>Premium men's fashion with custom tailoring services. Your style, our expertise.</p>
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="fab fa-tiktok"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('shop') }}">Shop</a></li>
                        <li><a href="{{ route('collections') }}">Collections</a></li>
                        <li><a href="{{ route('tailoring') }}">Tailoring</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-section">
                    <h4>Customer Service</h4>
                    <ul>
                        <li><a href="{{ route('disclaimer') }}">Disclaimer</a></li>
                        <li><a href="{{ route('return-exchange') }}">Return & Exchange</a></li>
                        <li><a href="{{ route('shipping-policy') }}">Shipping Policy</a></li>
                        <li><a href="{{ route('track-order') }}">Track Your Order</a></li>
                        <li><a href="{{ route('feedback-survey') }}">Take our feedback survey</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-section">
                    <h4>Contact Info</h4>
                    <ul>
                        @php
                            $contactInfo = \App\Models\WebsiteCms::where('section_type', 'contact')
                                                                  ->where('is_published', true)
                                                                  ->first();
                        @endphp
                        
                        @if($contactInfo && $contactInfo->data)
                            @if(isset($contactInfo->data['phone']) && $contactInfo->data['phone'])
                                <li>
                                    <i class="fas fa-phone"></i>
                                    <a href="tel:{{ $contactInfo->data['phone'] }}">{{ $contactInfo->data['phone'] }}</a>
                                </li>
                            @endif
                            
                            @if(isset($contactInfo->data['email']) && $contactInfo->data['email'])
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <a href="mailto:{{ $contactInfo->data['email'] }}">{{ $contactInfo->data['email'] }}</a>
                                </li>
                            @endif
                            
                            @if(isset($contactInfo->data['address']) && $contactInfo->data['address'])
                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $contactInfo->data['address'] }}
                                </li>
                            @endif

                            @if(isset($contactInfo->data['response_time']) && $contactInfo->data['response_time'])
                                <li>
                                    <i class="fas fa-clock"></i>
                                    {{ $contactInfo->data['response_time'] }}
                                </li>
                            @endif
                        @else
                            <!-- Default/Fallback contact info if nothing is configured -->
                            <li>
                                <i class="fas fa-phone"></i>
                                <a href="tel:+91-XXXXXXXXXX">+91-XXXXXXXXXX</a>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:info@clothes.com">info@clothes.com</a>
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                123 Fashion Street, City Center
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 CLOTHES STORE. All rights reserved. | <a href="{{ route('privacy-policy') }}" style="color: var(--accent-gold);">Privacy Policy</a> | <a href="{{ route('terms-of-service') }}" style="color: var(--accent-gold);">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cart Management
        const STORAGE_KEY = 'shopping_cart';
        const cartIcon = document.getElementById('cartIcon');
        const cartSidebar = document.getElementById('cartSidebar');
        const cartOverlay = document.getElementById('cartOverlay');
        const closeCartBtn = document.getElementById('closeCartSidebar');
        const cartItemsContainer = document.getElementById('cartItems');
        const cartBadge = document.querySelector('.cart-badge');

        // Load cart from localStorage
        function loadCart() {
            const cart = localStorage.getItem(STORAGE_KEY);
            return cart ? JSON.parse(cart) : [];
        }

        // Save cart to localStorage
        function saveCart(cart) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
            updateCartUI();
        }

        // Open cart sidebar
        function openCart() {
            cartSidebar.classList.add('active');
            cartOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close cart sidebar
        function closeCart() {
            cartSidebar.classList.remove('active');
            cartOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Update cart UI
        function updateCartUI() {
            const cart = loadCart();
            cartBadge.textContent = cart.length;

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = `
                    <div class="empty-cart-message">
                        <i class="fas fa-shopping-bag"></i>
                        <p>Your cart is empty</p>
                    </div>
                `;
                document.querySelector('.cart-total').style.display = 'none';
                return;
            }

            document.querySelector('.cart-total').style.display = 'flex';

            let cartHTML = '';
            let total = 0;

            cart.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                cartHTML += `
                    <div class="cart-item">
                        <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">$${item.price.toFixed(2)}</div>
                            <div class="cart-item-quantity">
                                <button onclick="updateQuantity(${index}, -1)">−</button>
                                <span>${item.quantity}</span>
                                <button onclick="updateQuantity(${index}, 1)">+</button>
                            </div>
                            <div class="cart-item-remove" onclick="removeFromCart(${index})">
                                <i class="fas fa-trash"></i> Remove
                            </div>
                        </div>
                    </div>
                `;
            });

            cartItemsContainer.innerHTML = cartHTML;
            document.getElementById('cartTotal').textContent = '$' + total.toFixed(2);
        }

        // Add to cart
        function addToCart(product) {
            const cart = loadCart();
            const existingItem = cart.find(item => item.id === product.id);

            if (existingItem) {
                existingItem.quantity += product.quantity || 1;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.image,
                    quantity: product.quantity || 1
                });
            }

            saveCart(cart);
            openCart();
        }

        // Update quantity
        function updateQuantity(index, change) {
            const cart = loadCart();
            cart[index].quantity += change;

            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }

            saveCart(cart);
        }

        // Remove from cart
        function removeFromCart(index) {
            const cart = loadCart();
            cart.splice(index, 1);
            saveCart(cart);
        }

        // Event listeners
        cartIcon.addEventListener('click', openCart);
        closeCartBtn.addEventListener('click', closeCart);
        cartOverlay.addEventListener('click', closeCart);

        // Navbar hide/show on scroll
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar-premium');
        const scrollThreshold = 50; // Hide navbar after scrolling 50px

        window.addEventListener('scroll', () => {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > scrollThreshold) {
                // Scrolling down
                if (currentScroll > lastScrollTop) {
                    navbar.classList.add('navbar-hidden');
                }
                // Scrolling up
                else {
                    navbar.classList.remove('navbar-hidden');
                }
            } else {
                // At the top of the page
                navbar.classList.remove('navbar-hidden');
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        });

        // Update cart count
        function updateCartCount() {
            updateCartUI();
        }

        updateCartCount();

        const loginModal = document.getElementById('loginModal');
        const loginModalFrame = document.getElementById('loginModalFrame');
        const forgotPasswordModalFrame = document.getElementById('forgotPasswordModalFrame');
        const closeLoginModal = document.getElementById('closeLoginModal');

        function showLoginFrame() {
            document.querySelector('.login-modal-dialog').classList.remove('forgot-active');
            loginModalFrame.classList.remove('auth-frame-hidden');
            forgotPasswordModalFrame.classList.add('auth-frame-hidden');
        }

        function showForgotPasswordFrame(email = '') {
            document.querySelector('.login-modal-dialog').classList.add('forgot-active');
            loginModalFrame.classList.add('auth-frame-hidden');
            forgotPasswordModalFrame.classList.remove('auth-frame-hidden');

            const forgotEmail = forgotPasswordModalFrame.contentDocument?.querySelector('[name="email"]');
            if (forgotEmail && email) {
                forgotEmail.value = email.trim();
            }
        }

        function bindForgotPasswordLink() {
            const forgotLink = loginModalFrame.contentDocument?.getElementById('forgot-password-link');

            if (forgotLink && !forgotLink.dataset.modalBound) {
                forgotLink.dataset.modalBound = 'true';
                forgotLink.addEventListener('click', function (event) {
                    event.preventDefault();
                    const email = loginModalFrame.contentDocument?.querySelector('[name="email"]')?.value || '';
                    showForgotPasswordFrame(email);
                });
            }

            const cancelLink = forgotPasswordModalFrame.contentDocument?.querySelector('a[href*="/login"]');
            if (cancelLink && !cancelLink.dataset.modalBound) {
                cancelLink.dataset.modalBound = 'true';
                cancelLink.addEventListener('click', function (event) {
                    event.preventDefault();
                    showLoginFrame();
                });
            }
        }

        loginModalFrame.addEventListener('load', function () {
            loginModalFrame.classList.remove('auth-frame-loading');
            bindForgotPasswordLink();
        });
        forgotPasswordModalFrame.addEventListener('load', function () {
            forgotPasswordModalFrame.classList.remove('auth-frame-loading');
            bindForgotPasswordLink();
        });

        function openLoginModal(url) {
            showLoginFrame();
            bindForgotPasswordLink();
            if (!loginModalFrame.src || loginModalFrame.src === 'about:blank') {
                loginModalFrame.src = url;
            }
            loginModal.classList.add('active');
            loginModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeLoginModalWindow() {
            loginModal.classList.remove('active');
            loginModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.auth-login-trigger').forEach(trigger => {
            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                openLoginModal(this.dataset.loginUrl);
            });
        });

        closeLoginModal.addEventListener('click', closeLoginModalWindow);
        loginModal.addEventListener('click', function (event) {
            if (event.target === loginModal) closeLoginModalWindow();
        });

        // Set active nav link based on current page
        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.pathname;
            const navLinks = document.querySelectorAll('.navbar-premium .nav-link');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                
                // Check if the link's href matches the current URL
                if (href && (
                    (currentUrl === '/' && href === '{{ route("home") }}') ||
                    (currentUrl.includes('/shop') && href === '{{ route("shop") }}') ||
                    (currentUrl.includes('/new-in') && href === '{{ route("new-in") }}') ||
                    (currentUrl.includes('/brands-page') && href === '{{ route("brands-page") }}') ||
                    (currentUrl.includes('/collections') && href === '{{ route("collections") }}') ||
                    (currentUrl.includes('/tailoring') && href === '{{ route("tailoring.service") }}') ||
                    (currentUrl.includes('/contact') && href === '{{ route("contact") }}')
                )) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
