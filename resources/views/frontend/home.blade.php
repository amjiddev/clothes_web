@extends('frontend.layouts.app')

@section('title', 'Home - Premium Men\'s Fashion & Custom Tailoring')

@section('content')

<!-- Hero Section with Image Slider -->
    <!-- Slider Container -->
    <div class="hero-slider" style="position: relative; width: 100%; height: 85vh; overflow: hidden;">
        @php
            $heroImages = [];
            if ($homePageContent && isset($homePageContent->data)) {
                for ($i = 1; $i <= 4; $i++) {
                    if (isset($homePageContent->data['hero_image_' . $i])) {
                        $heroImages[] = asset('storage/' . $homePageContent->data['hero_image_' . $i]);
                    }
                }
            }
            // Fallback to default images if none are set
            if (empty($heroImages)) {
                $heroImages = [
                    asset('frontend/images/images.jfif'),
                    asset('frontend/images/images (1).jfif'),
                    asset('frontend/images/images (2).jfif'),
                    asset('frontend/images/images (3).jfif'),
                ];
            }
        @endphp
        
        @foreach($heroImages as $index => $image)
        <!-- Slide {{ $index + 1 }} - Clickable -->
        <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" onclick="window.location.href='{{ route('shop') }}';" style="position: absolute; width: 100%; height: 100%; background: url('{{ $image }}') center/cover no-repeat; background-size: cover; opacity: {{ $index === 0 ? 1 : 0 }}; transition: opacity 0.8s ease-in-out; cursor: pointer;">
        </div>
        @endforeach
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
        @php $dotCount = count($heroImages); @endphp
        @for($i = 0; $i < $dotCount; $i++)
        <span class="hero-dot {{ $i === 0 ? 'active' : '' }}" onclick="currentSlide({{ $i }}, event)" style="width: 14px; height: 14px; border-radius: 50%; background: rgba(255,255,255,{{ $i === 0 ? 0.9 : 0.5 }})!important; cursor: pointer; transition: all 0.3s ease;"></span>
        @endfor
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
                    <div class="product-image" style="position: relative;">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'" style="height: 280px; object-fit: cover;">
                        <span class="product-badge" style="position: absolute; top: 10px; right: 10px; background: #D4AF37; color: #0B0B0B; padding: 5px 12px; border-radius: 4px; font-weight: 700; font-size: 0.85rem; opacity: 0; transition: opacity 0.3s ease; z-index: 10;">{{ $product->stock_status }}</span>
                        @if($product->hasDiscount())
                        <span class="sale-badge" style="position: absolute; top: 10px; left: 10px; background: #D4AF37; color: #0B0B0B; padding: 5px 12px; border-radius: 4px; font-weight: 700; font-size: 0.9rem; z-index: 5;">-{{ $product->discount_percentage }}%</span>
                        @endif
                    </div>
                    <div class="product-info">
                        <h3 class="product-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">{{ $product->name }}</h3>
                        <div class="product-price">
                            <span class="current">Rs. {{ number_format($product->final_price, 0) }}</span>
                            @if($product->hasDiscount())
                            <span class="original">Rs. {{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>
                        <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                            <button onclick="addToCart({{ $product->id }}); event.stopPropagation();" style="flex: 1; background: var(--accent-gold); border: none; color: #0B0B0B; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s ease;">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                            <a href="{{ route('product.detail', $product->slug) }}" style="width: 45px; height: 45px; background: white; border: 2px solid var(--accent-gold); color: var(--accent-gold); border-radius: 4px; cursor: pointer; font-weight: 700; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s ease;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">No featured products available</p>
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
@if($homePageContent && isset($homePageContent->data))
<section class="tailoring-section section-padding">
    <div class="container">
        <div class="section-title" style="color: white; margin-bottom: 60px;">
            <h2 style="color: white;">{{ $homePageContent->data['tailoring_section_title'] ?? $homePageContent->data['tailoring_title_1'] ?? 'Our Tailoring Services' }}</h2>
            <div class="divider"></div>
            <p style="color: rgba(255,255,255,0.8);">{{ $homePageContent->data['tailoring_section_description'] ?? '' }}</p>
        </div>

        <div class="row g-4">
            @php
                $tailoringServices = [];
                if ($homePageContent && isset($homePageContent->data)) {
                    for ($i = 1; $i <= 3; $i++) {
                        if (!empty($homePageContent->data['tailoring_title_' . $i])) {
                            $tailoringServices[] = [
                                'title' => $homePageContent->data['tailoring_title_' . $i] ?? '',
                                'description' => $homePageContent->data['tailoring_description_' . $i] ?? '',
                                'icon' => $homePageContent->data['tailoring_icon_' . $i] ?? 'fas fa-star',
                                'price' => $homePageContent->data['tailoring_price_' . $i] ?? '',
                            ];
                        }
                    }
                }
                // Fallback to default services if none are set
                if (empty($tailoringServices)) {
                    $tailoringServices = [
                        [
                            'title' => 'Cloth + Stitching',
                            'description' => 'Browse our premium fabric collection and get perfect custom stitching. Choose from a variety of designs and get tailored according to your exact measurements.',
                            'icon' => 'fas fa-ruler-combined',
                            'price' => 'Rs. 500',
                        ],
                        [
                            'title' => 'Stitching Only',
                            'description' => 'Got your own fabric? We\'ll stitch it for you! Our expert tailors can create anything from traditional kurtas to modern suits with precision and care.',
                            'icon' => 'fas fa-scissors',
                            'price' => 'Rs. 300',
                        ],
                        [
                            'title' => 'Custom Designs',
                            'description' => 'Have a specific design in mind? Our expert tailors can bring your vision to life. Consultations available to discuss your custom tailoring needs.',
                            'icon' => 'fas fa-pencil-ruler',
                            'price' => 'Contact for Quote',
                        ],
                    ];
                }
            @endphp

            @foreach($tailoringServices as $service)
            <div class="col-lg-4 col-md-6">
                <div class="tailoring-card">
                    <i class="{{ $service['icon'] }}"></i>
                    <h3>{{ $service['title'] }}</h3>
                    <p>
                        {{ $service['description'] }}
                    </p>
                    <p style="margin-top: 1rem;">
                        <strong>{{ $service['price'] }}</strong>
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('tailoring') }}" class="btn-premium">
                <i class="fas fa-calendar-alt me-2"></i>Book Your Tailoring Service
            </a>
        </div>
    </div>
</section>
@else
<section class="tailoring-section section-padding">
    <div class="container">
        <div class="section-title" style="color: white; margin-bottom: 60px;">
            <h2 style="color: white;">Our Tailoring Services</h2>
            <div class="divider"></div>
            <p style="color: rgba(255,255,255,0.8);">Premium tailoring solutions for every occasion</p>
        </div>

        <div class="row g-4">
            @php
                $tailoringServices = [
                    [
                        'title' => 'Cloth + Stitching',
                        'description' => 'Browse our premium fabric collection and get perfect custom stitching. Choose from a variety of designs and get tailored according to your exact measurements.',
                        'icon' => 'fas fa-ruler-combined',
                        'price' => 'Rs. 500',
                    ],
                    [
                        'title' => 'Stitching Only',
                        'description' => 'Got your own fabric? We\'ll stitch it for you! Our expert tailors can create anything from traditional kurtas to modern suits with precision and care.',
                        'icon' => 'fas fa-scissors',
                        'price' => 'Rs. 300',
                    ],
                    [
                        'title' => 'Custom Designs',
                        'description' => 'Have a specific design in mind? Our expert tailors can bring your vision to life. Consultations available to discuss your custom tailoring needs.',
                        'icon' => 'fas fa-pencil-ruler',
                        'price' => 'Contact for Quote',
                    ],
                ];
            @endphp

            @foreach($tailoringServices as $service)
            <div class="col-lg-4 col-md-6">
                <div class="tailoring-card">
                    <i class="{{ $service['icon'] }}"></i>
                    <h3>{{ $service['title'] }}</h3>
                    <p>
                        {{ $service['description'] }}
                    </p>
                    <p style="margin-top: 1rem;">
                        <strong>{{ $service['price'] }}</strong>
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('tailoring') }}" class="btn-premium">
                <i class="fas fa-calendar-alt me-2"></i>Book Your Tailoring Service
            </a>
        </div>
    </div>
</section>
@endif

<!-- Men's Collection Section -->
<section class="section-padding" style="background: #F8F5EF;">
    <div class="container">
        <div class="section-title">
            <h2>Shalwar Kameez Collection</h2>
            <div class="divider"></div>
            <p>Explore our premium range of men's fashion</p>
        </div>

        <div class="row g-4">
            @php
                $collectionProducts = [];
                if ($homePageContent && isset($homePageContent->data['collection_products'])) {
                    $collectionProducts = $homePageContent->data['collection_products'];
                    if (is_string($collectionProducts)) {
                        $collectionProducts = json_decode($collectionProducts, true) ?? [];
                    }
                }
            @endphp

            @forelse($collectionProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div style="cursor: pointer; text-align: center;">
                    <div class="card-image-hover" style="width: 100%; height: 300px; background: #e9e9e9; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; margin-bottom: 0.5rem;">
                        @if($product['discount_percentage'] > 0)
                        <span class="sale-badge" style="position: absolute; top: 10px; left: 10px; background: #D4AF37; color: #0B0B0B; padding: 5px 12px; border-radius: 4px; font-weight: 700; font-size: 0.9rem; z-index: 5;">-{{ $product['discount_percentage'] }}%</span>
                        @endif
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                            <i class="fas fa-image" style="font-size: 3rem; color: #ccc;"></i>
                        </div>
                    </div>
                    <h3 style="font-size: 0.95rem; font-weight: 600; color: #0B0B0B; margin-bottom: 0.2rem; line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product['name'] }}</h3>
                    <p style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">{{ $product['category'] }}</p>
                    <div style="display: flex; gap: 8px; justify-content: center; align-items: center; margin-bottom: 1rem;">
                        <p style="font-size: 1.1rem; font-weight: 700; color: #D4AF37; margin: 0;">Rs. {{ number_format($product['sale_price'] > 0 ? $product['sale_price'] : $product['regular_price'], 0) }}</p>
                        @if($product['sale_price'] > 0 && $product['regular_price'] > $product['sale_price'])
                        <p style="font-size: 0.9rem; color: #999; text-decoration: line-through; margin: 0;">Rs. {{ number_format($product['regular_price'], 0) }}</p>
                        @endif
                    </div>
                    <button style="width: 100%; background: #D4AF37; border: none; color: #0B0B0B; padding: 10px; border-radius: 4px; cursor: pointer; font-weight: 700; font-size: 0.9rem; transition: all 0.3s ease;">
                        <i class="fas fa-shopping-cart me-2"></i> View Product
                    </button>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted py-5">No products in Shalwar Kameez Collection</p>
            </div>
            @endforelse
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

    /* Featured Products - Show In Stock badge on hover */
    .product-image:hover .product-badge {
        opacity: 1 !important;
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
