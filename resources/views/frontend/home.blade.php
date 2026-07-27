@extends('frontend.layouts.app')

@section('title', 'Home - Premium Men\'s Fashion & Custom Tailoring')

@section('content')

<!-- Hero Section with Image Slider -->
    <!-- Slider Container -->
    <div class="hero-slider" style="position: relative; width: 100%; height: 85vh; overflow: hidden;">
        <!-- Slide 1 - Clickable -->
        <div class="hero-slide active" onclick="window.location.href='{{ route('shop') }}';" style="position: absolute; width: 100%; height: 100%; background: url('{{ asset('frontend/images/images.jfif') }}') center/cover no-repeat; background-size: cover; opacity: 1; transition: opacity 0.8s ease-in-out; cursor: pointer;">
        </div>
        
        <!-- Slide 2 - Clickable -->
        <div class="hero-slide" onclick="window.location.href='{{ route('shop') }}';" style="position: absolute; width: 100%; height: 100%; background: url('{{ asset('frontend/images/images (1).jfif') }}') center/cover no-repeat; background-size: cover; opacity: 0; transition: opacity 0.8s ease-in-out; cursor: pointer;">
        </div>
        
        <!-- Slide 3 - Clickable -->
        <div class="hero-slide" onclick="window.location.href='{{ route('shop') }}';" style="position: absolute; width: 100%; height: 100%; background: url('{{ asset('frontend/images/images (2).jfif') }}') center/cover no-repeat; background-size: cover; opacity: 0; transition: opacity 0.8s ease-in-out; cursor: pointer;">
        </div>
        
        <!-- Slide 4 - Clickable -->
        <div class="hero-slide" onclick="window.location.href='{{ route('shop') }}';" style="position: absolute; width: 100%; height: 100%; background: url('{{ asset('frontend/images/images (3).jfif') }}') center/cover no-repeat; background-size: cover; opacity: 0; transition: opacity 0.8s ease-in-out; cursor: pointer;">
        </div>
    </div>

    <!-- Previous Button - Clickable -->
    <button class="hero-nav-btn hero-prev" onclick="prevSlide(event)" style="position: absolute; left: 30px; top: 50%; transform: translateY(-50%); z-index: 20; background: rgba(255,255,255,0.4); border: 2px solid rgba(255,255,255,0.8); color: white; font-size: 28px; width: 60px; height: 60px; border-radius: 50%; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-chevron-left"></i>
    </button>

    <!-- Next Button - Clickable -->
    <button class="hero-nav-btn hero-next" onclick="nextSlide(event)" style="position: absolute; right: 30px; top: 50%; transform: translateY(-50%); z-index: 20; background: rgba(255,255,255,0.4); border: 2px solid rgba(255,255,255,0.8); color: white; font-size: 28px; width: 60px; height: 60px; border-radius: 50%; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-chevron-right"></i>
    </button>

    <!-- Slider Dots - Clickable -->
    <div class="hero-dots" style="position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); z-index: 20; display: flex; gap: 12px;">
        <span class="hero-dot active" onclick="currentSlide(0, event)" style="width: 14px; height: 14px; border-radius: 50%; background: rgba(255,255,255,0.9); cursor: pointer; transition: all 0.3s ease;"></span>
        <span class="hero-dot" onclick="currentSlide(1, event)" style="width: 14px; height: 14px; border-radius: 50%; background: rgba(255,255,255,0.5); cursor: pointer; transition: all 0.3s ease;"></span>
        <span class="hero-dot" onclick="currentSlide(2, event)" style="width: 14px; height: 14px; border-radius: 50%; background: rgba(255,255,255,0.5); cursor: pointer; transition: all 0.3s ease;"></span>
        <span class="hero-dot" onclick="currentSlide(3, event)" style="width: 14px; height: 14px; border-radius: 50%; background: rgba(255,255,255,0.5); cursor: pointer; transition: all 0.3s ease;"></span>
    </div>
</section>

<!-- Featured Categories Section -->
<section class="section-padding" style="background: #FFFFFF;">
    <div class="container">
        <div class="section-title">
            <h2>Featured Categories</h2>
            <div class="divider"></div>
            <p>Explore our diverse collection of premium men's fashion</p>
        </div>

        <div class="row g-4">
            <!-- Cotton Category -->
            <div class="col-lg-2 col-md-3 col-sm-6">
                <a href="{{ route('shop') }}?category=cotton" class="text-decoration-none">
                    <div class="category-card">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Cotton</h3>
                        <p>Premium Collection</p>
                    </div>
                </a>
            </div>

            <!-- Wash & Wear Category -->
            <div class="col-lg-2 col-md-3 col-sm-6">
                <a href="{{ route('shop') }}?category=wash-wear" class="text-decoration-none">
                    <div class="category-card">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Wash & Wear</h3>
                        <p>Easy Care Fabrics</p>
                    </div>
                </a>
            </div>

            <!-- Khaddar Category -->
            <div class="col-lg-2 col-md-3 col-sm-6">
                <a href="{{ route('shop') }}?category=khaddar" class="text-decoration-none">
                    <div class="category-card">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Khaddar</h3>
                        <p>Durable Fabric</p>
                    </div>
                </a>
            </div>

            <!-- Linen Category -->
            <div class="col-lg-2 col-md-3 col-sm-6">
                <a href="{{ route('shop') }}?category=linen" class="text-decoration-none">
                    <div class="category-card">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Linen</h3>
                        <p>Lightweight & Breathable</p>
                    </div>
                </a>
            </div>

            <!-- Boski Category -->
            <div class="col-lg-2 col-md-3 col-sm-6">
                <a href="{{ route('shop') }}?category=boski" class="text-decoration-none">
                    <div class="category-card">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Boski</h3>
                        <p>Premium Quality</p>
                    </div>
                </a>
            </div>

            <!-- Dhanakye Category -->
            <div class="col-lg-2 col-md-3 col-sm-6">
                <a href="{{ route('shop') }}?category=dhanakye" class="text-decoration-none">
                    <div class="category-card">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Dhanak</h3>
                        <p>Traditional Style</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="section-padding" style="background: #F8F5EF;">
    <div class="container">
        <div class="section-title">
            <h2>Featured Products</h2>
            <div class="divider"></div>
            <p>Handpicked collection of our finest men's fashion pieces</p>
        </div>

        <div class="row g-4">
            @forelse($featuredProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card">
                    <div class="product-image">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                        @else
                            <img src="https://via.placeholder.com/300x300?text={{ urlencode($product->name) }}" alt="{{ $product->name }}">
                        @endif
                        @if($product->stock_quantity > 0)
                        <span class="product-badge">In Stock</span>
                        @else
                        <span class="product-badge" style="background: #dc3545;">Out of Stock</span>
                        @endif
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-price">
                            <span class="current">Rs. {{ number_format($product->price, 0) }}</span>
                            <span class="original">Rs. {{ number_format($product->price * 1.2, 0) }}</span>
                        </div>
                        @if($product->color)
                        <small class="text-muted d-block mb-2">
                            <strong>Color:</strong> {{ $product->color }}
                        </small>
                        @endif
                        <div class="product-actions">
                            <button class="btn-add-cart" onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-cart-plus"></i> Add
                            </button>
                            <a href="{{ route('product.detail', $product->id) }}" class="btn-view-detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No products available at the moment</p>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('shop') }}" class="btn-premium">
                <i class="fas fa-shopping-bag me-2"></i>View All Products
            </a>
        </div>
    </div>
</section>

<!-- Tailoring Service Section -->
<section class="tailoring-section section-padding">
    <div class="container">
        <div class="section-title" style="color: white; margin-bottom: 60px;">
            <h2 style="color: white;">Our Tailoring Services</h2>
            <div class="divider"></div>
            <p style="color: rgba(255,255,255,0.8);">We provide professional custom stitching services according to your measurements</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="tailoring-card">
                    <i class="fas fa-ruler-combined"></i>
                    <h3>Cloth + Stitching</h3>
                    <p>
                        Browse our premium fabric collection and get perfect custom stitching. Choose from a variety of designs and get tailored according to your exact measurements.
                    </p>
                    <p style="margin-top: 1rem;">
                        <strong>Starting from Rs. 500</strong>
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="tailoring-card">
                    <i class="fas fa-scissors"></i>
                    <h3>Stitching Only</h3>
                    <p>
                        Got your own fabric? We'll stitch it for you! Our expert tailors can create anything from traditional kurtas to modern suits with precision and care.
                    </p>
                    <p style="margin-top: 1rem;">
                        <strong>Starting from Rs. 300</strong>
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="tailoring-card">
                    <i class="fas fa-pencil-ruler"></i>
                    <h3>Custom Designs</h3>
                    <p>
                        Have a specific design in mind? Our expert tailors can bring your vision to life. Consultations available to discuss your custom tailoring needs.
                    </p>
                    <p style="margin-top: 1rem;">
                        <strong>Contact for Quote</strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('tailoring') }}" class="btn-premium">
                <i class="fas fa-calendar-alt me-2"></i>Book Your Tailoring Service
            </a>
        </div>
    </div>
</section>

<!-- Men's Collection Section -->
<section class="section-padding" style="background: #F8F5EF;">
    <div class="container">
        <div class="section-title">
            <h2>Shalwar Kameez Collection</h2>
            <div class="divider"></div>
            <p>Explore our premium range of men's fashion</p>
        </div>

        <div class="row g-4">
            <!-- Card 1 - COTTON -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="cursor: pointer; text-align: center;">
                    <div class="card-image-hover" style="width: 100%; height: 400px; background: #e9e9e9; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; margin-bottom: 0.5rem;">
                        <img class="img-primary" src="{{ asset('frontend/images/images.jfif') }}" alt="Premium Cotton Suit" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 1;">
                        <img class="img-secondary" src="{{ asset('frontend/images/images (1).jfif') }}" alt="Premium Cotton Suit" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0;">
                        <div class="card-action-buttons" style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); display: flex; gap: 1rem; opacity: 0; transition: opacity 0.3s ease;">
                            <button onclick="toggleWishlist(1, this); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="far fa-heart"></i>
                            </button>
                            <button onclick="addToCart(1); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button onclick="window.location.href='{{ route('product.detail', 1) }}'; event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.2rem; line-height: 1.4;">Premium Cotton Suit</h3>
                    <p style="font-size: 1.1rem; font-weight: 700; color: #D4AF37; margin: 0;">Rs. 8,990</p>
                </div>
            </div>

            <!-- Card 2 - COTTON -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="cursor: pointer; text-align: center;">
                    <div class="card-image-hover" style="width: 100%; height: 400px; background: #e9e9e9; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; margin-bottom: 0.5rem;">
                        <img class="img-primary" src="{{ asset('frontend/images/images (1).jfif') }}" alt="Cotton Formal Shirt" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 1;">
                        <img class="img-secondary" src="{{ asset('frontend/images/images (2).jfif') }}" alt="Cotton Formal Shirt" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0;">
                        <div class="card-action-buttons" style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); display: flex; gap: 1rem; opacity: 0; transition: opacity 0.3s ease;">
                            <button onclick="toggleWishlist(2, this); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="far fa-heart"></i>
                            </button>
                            <button onclick="addToCart(2); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button onclick="window.location.href='{{ route('product.detail', 2) }}'; event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.2rem; line-height: 1.4;">Cotton Formal Shirt</h3>
                    <p style="font-size: 1.1rem; font-weight: 700; color: #D4AF37; margin: 0;">Rs. 2,490</p>
                </div>
            </div>

            <!-- Card 3 - WASH & WEAR -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="cursor: pointer; text-align: center;">
                    <div class="card-image-hover" style="width: 100%; height: 400px; background: #e9e9e9; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; margin-bottom: 0.5rem;">
                        <img class="img-primary" src="{{ asset('frontend/images/images (2).jfif') }}" alt="Wash & Wear Kurta" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 1;">
                        <img class="img-secondary" src="{{ asset('frontend/images/images (3).jfif') }}" alt="Wash & Wear Kurta" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0;">
                        <div class="card-action-buttons" style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); display: flex; gap: 1rem; opacity: 0; transition: opacity 0.3s ease;">
                            <button onclick="toggleWishlist(3, this); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="far fa-heart"></i>
                            </button>
                            <button onclick="addToCart(3); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button onclick="window.location.href='{{ route('product.detail', 3) }}'; event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.2rem; line-height: 1.4;">Wash & Wear Kurta</h3>
                    <p style="font-size: 1.1rem; font-weight: 700; color: #D4AF37; margin: 0;">Rs. 3,590</p>
                </div>
            </div>

            <!-- Card 4 - KHADDAR -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="cursor: pointer; text-align: center;">
                    <div class="card-image-hover" style="width: 100%; height: 400px; background: #e9e9e9; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; margin-bottom: 0.5rem;">
                        <img class="img-primary" src="{{ asset('frontend/images/images (3).jfif') }}" alt="Khaddar Formal Suit" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 1;">
                        <img class="img-secondary" src="{{ asset('frontend/images/images (4).jfif') }}" alt="Khaddar Formal Suit" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0;">
                        <div class="card-action-buttons" style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); display: flex; gap: 1rem; opacity: 0; transition: opacity 0.3s ease;">
                            <button onclick="toggleWishlist(4, this); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="far fa-heart"></i>
                            </button>
                            <button onclick="addToCart(4); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button onclick="window.location.href='{{ route('product.detail', 4) }}'; event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.2rem; line-height: 1.4;">Khaddar Formal Suit</h3>
                    <p style="font-size: 1.1rem; font-weight: 700; color: #D4AF37; margin: 0;">Rs. 5,990</p>
                </div>
            </div>

            <!-- Card 5 - WEDDING WEAR -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="cursor: pointer; text-align: center;">
                    <div class="card-image-hover" style="width: 100%; height: 400px; background: #e9e9e9; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; margin-bottom: 0.5rem;">
                        <img class="img-primary" src="{{ asset('frontend/images/images (4).jfif') }}" alt="Wedding Sherwani" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 1;">
                        <img class="img-secondary" src="{{ asset('frontend/images/images (5).jfif') }}" alt="Wedding Sherwani" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0;">
                        <div class="card-action-buttons" style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); display: flex; gap: 1rem; opacity: 0; transition: opacity 0.3s ease;">
                            <button onclick="toggleWishlist(5, this); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="far fa-heart"></i>
                            </button>
                            <button onclick="addToCart(5); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button onclick="window.location.href='{{ route('product.detail', 5) }}'; event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.2rem; line-height: 1.4;">Wedding Sherwani</h3>
                    <p style="font-size: 1.1rem; font-weight: 700; color: #D4AF37; margin: 0;">Rs. 7,490</p>
                </div>
            </div>

            <!-- Card 6 - EID WEAR -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="cursor: pointer; text-align: center;">
                    <div class="card-image-hover" style="width: 100%; height: 400px; background: #e9e9e9; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; margin-bottom: 0.5rem;">
                        <img class="img-primary" src="{{ asset('frontend/images/images (5).jfif') }}" alt="Eid Pyjama" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 1;">
                        <img class="img-secondary" src="{{ asset('frontend/images/images.jfif') }}" alt="Eid Pyjama" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0;">
                        <div class="card-action-buttons" style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); display: flex; gap: 1rem; opacity: 0; transition: opacity 0.3s ease;">
                            <button onclick="toggleWishlist(6, this); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="far fa-heart"></i>
                            </button>
                            <button onclick="addToCart(6); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button onclick="window.location.href='{{ route('product.detail', 6) }}'; event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.2rem; line-height: 1.4;">Eid Pyjama</h3>
                    <p style="font-size: 1.1rem; font-weight: 700; color: #D4AF37; margin: 0;">Rs. 1,290</p>
                </div>
            </div>

            <!-- Card 7 - COTTON -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="cursor: pointer; text-align: center;">
                    <div class="card-image-hover" style="width: 100%; height: 400px; background: #e9e9e9; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; margin-bottom: 0.5rem;">
                        <img class="img-primary" src="{{ asset('frontend/images/images.jfif') }}" alt="Cotton Casual Shirt" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 1;">
                        <img class="img-secondary" src="{{ asset('frontend/images/images (1).jfif') }}" alt="Cotton Casual Shirt" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0;">
                        <div class="card-action-buttons" style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); display: flex; gap: 1rem; opacity: 0; transition: opacity 0.3s ease;">
                            <button onclick="toggleWishlist(7, this); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="far fa-heart"></i>
                            </button>
                            <button onclick="addToCart(7); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button onclick="window.location.href='{{ route('product.detail', 7) }}'; event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.2rem; line-height: 1.4;">Cotton Casual Shirt</h3>
                    <p style="font-size: 1.1rem; font-weight: 700; color: #D4AF37; margin: 0;">Rs. 2,890</p>
                </div>
            </div>

            <!-- Card 8 - KHADDAR -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="cursor: pointer; text-align: center;">
                    <div class="card-image-hover" style="width: 100%; height: 400px; background: #e9e9e9; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; margin-bottom: 0.5rem;">
                        <img class="img-primary" src="{{ asset('frontend/images/images (1).jfif') }}" alt="Khaddar Designer Kurta" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 1;">
                        <img class="img-secondary" src="{{ asset('frontend/images/images (2).jfif') }}" alt="Khaddar Designer Kurta" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0;">
                        <div class="card-action-buttons" style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); display: flex; gap: 1rem; opacity: 0; transition: opacity 0.3s ease;">
                            <button onclick="toggleWishlist(8, this); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="far fa-heart"></i>
                            </button>
                            <button onclick="addToCart(8); event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button onclick="window.location.href='{{ route('product.detail', 8) }}'; event.stopPropagation();" style="background: #D4AF37; border: none; color: #0B0B0B; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; font-size: 1.1rem;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.2rem; line-height: 1.4;">Khaddar Designer Kurta</h3>
                    <p style="font-size: 1.1rem; font-weight: 700; color: #D4AF37; margin: 0;">Rs. 4,690</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews Section -->
<section class="section-padding" style="background: #F8F5EF;">
    <div class="container">
        <div class="section-title">
            <h2>What Our Customers Say</h2>
            <div class="divider"></div>
            <p>Testimonials from satisfied customers</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="review-card">
                    <div class="review-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">
                        "Absolutely impressed with the quality and fitting. The tailors really understood what I wanted and delivered perfectly. Highly recommended!"
                    </p>
                    <p class="review-author">Rajesh Kumar</p>
                    <p class="review-title">Business Executive</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="review-card">
                    <div class="review-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">
                        "Fast service, excellent customer support, and the fit is impeccable. Best tailoring experience I've had. Worth every penny!"
                    </p>
                    <p class="review-author">Arjun Patel</p>
                    <p class="review-title">Software Engineer</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="review-card">
                    <div class="review-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">
                        "The product quality is fantastic and the tailoring is flawless. My wedding suit was absolutely stunning. Thank you so much!"
                    </p>
                    <p class="review-author">Vikram Singh</p>
                    <p class="review-title">Entrepreneur</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="review-card">
                    <div class="review-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">
                        "Premium quality at reasonable prices. The staff is very helpful and patient. Perfect place for all your formal wear needs!"
                    </p>
                    <p class="review-author">Amit Sharma</p>
                    <p class="review-title">Consultant</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="review-card">
                    <div class="review-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">
                        "Custom stitching experience was outstanding. They listened to every detail and created exactly what I envisioned. 5 stars!"
                    </p>
                    <p class="review-author">Nikhil Desai</p>
                    <p class="review-title">Fashion Blogger</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="review-card">
                    <div class="review-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">
                        "Great collection, exceptional tailoring, and amazing customer service. This is my go-to place for all formal occasions!"
                    </p>
                    <p class="review-author">Rohit Gupta</p>
                    <p class="review-title">Lawyer</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding" style="background: linear-gradient(135deg, #0B0B0B 0%, #0F172A 100%); color: white; border-top: 3px solid #D4AF37;">
    <div class="container text-center">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; font-family: 'Playfair Display', serif;">Ready to Elevate Your Style?</h2>
        <p style="font-size: 1.1rem; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
            Join thousands of satisfied customers and experience premium men's fashion with our exclusive collection and expert tailoring services.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('shop') }}" class="btn-premium">Start Shopping</a>
            <a href="{{ route('tailoring') }}" class="btn-outline-premium">Book Tailoring</a>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<style>
    .hero-nav-btn:hover {
        background: rgba(255,255,255,0.7) !important;
        transform: translateY(-50%) scale(1.1) !important;
    }

    .hero-dot {
        transition: all 0.3s ease;
    }

    .hero-dot:hover {
        background: rgba(255,255,255,0.8) !important;
        transform: scale(1.2);
    }

    .hero-dot.active {
        width: 30px !important;
        background: rgba(255,255,255,0.95) !important;
    }

    /* Men's Collection Card Hover Effect */
    .card-image-hover:hover .img-primary {
        opacity: 0 !important;
    }

    .card-image-hover:hover .img-secondary {
        opacity: 1 !important;
    }

    .img-primary, .img-secondary {
        transition: opacity 0.3s ease;
    }

    .card-image-hover:hover .card-action-buttons {
        opacity: 1 !important;
    }

    .card-action-buttons button:hover {
        transform: scale(1.05);
    }
</style>

<script>
let currentSlideIndex = 0;
let autoSlideTimer;

function showSlide(index) {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const totalSlides = slides.length;

    // Ensure index is within bounds
    if (index >= totalSlides) {
        currentSlideIndex = 0;
    } else if (index < 0) {
        currentSlideIndex = totalSlides - 1;
    } else {
        currentSlideIndex = index;
    }

    // Hide all slides
    slides.forEach(slide => {
        slide.style.opacity = '0';
    });

    // Show current slide
    slides[currentSlideIndex].style.opacity = '1';

    // Update dots
    dots.forEach((dot, i) => {
        dot.classList.remove('active');
        if (i === currentSlideIndex) {
            dot.classList.add('active');
        }
    });
}

function nextSlide(event) {
    if (event) {
        event.stopPropagation();
    }
    clearInterval(autoSlideTimer);
    showSlide(currentSlideIndex + 1);
    startAutoSlide();
}

function prevSlide(event) {
    if (event) {
        event.stopPropagation();
    }
    clearInterval(autoSlideTimer);
    showSlide(currentSlideIndex - 1);
    startAutoSlide();
}

function currentSlide(index, event) {
    if (event) {
        event.stopPropagation();
    }
    clearInterval(autoSlideTimer);
    showSlide(index);
    startAutoSlide();
}

function startAutoSlide() {
    autoSlideTimer = setInterval(() => {
        showSlide(currentSlideIndex + 1);
    }, 4000); // Change slide every 4 seconds
}

// Initialize slider when page loads
document.addEventListener('DOMContentLoaded', () => {
    showSlide(0);
    startAutoSlide();
});

function addToCart(productId) {
    // TODO: Implement add to cart functionality
    alert('Product added to cart! (Demo)');
    // Update cart badge
    document.querySelector('.cart-badge').textContent = parseInt(document.querySelector('.cart-badge').textContent) + 1;
}

function toggleWishlist(productId, button) {
    // Toggle between outlined and filled heart
    const icon = button.querySelector('i');
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        button.style.backgroundColor = '#D4AF37';
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        button.style.backgroundColor = '#D4AF37';
    }
}
</script>
@endsection
