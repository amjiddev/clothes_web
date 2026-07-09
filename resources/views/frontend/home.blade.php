@extends('frontend.layouts.app')

@section('title', 'Home - Premium Men\'s Fashion & Custom Tailoring')

@section('content')

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6 hero-content">
                <h1>Premium Men's Fashion & Custom Tailoring</h1>
                <h2>Buy Ready Made Suits or Get Your Perfect Custom Stitching</h2>
                <p style="font-size: 1.1rem; margin-bottom: 2rem; line-height: 1.6;">
                    Discover our exclusive collection of premium men's clothing and professional tailoring services. Whether you're looking for elegant ready-made pieces or custom stitched designs, we have everything to elevate your style.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('shop') }}" class="btn-premium">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Collection
                    </a>
                    <a href="{{ route('tailoring') }}" class="btn-outline-premium">
                        <i class="fas fa-scissors me-2"></i>Book Stitching
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=500&h=600&fit=crop" alt="Premium Suit" class="img-fluid rounded" style="box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            </div>
        </div>
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
            @forelse($categoriesWithCount as $category)
            <div class="col-lg-2 col-md-3 col-sm-6">
                <a href="{{ route('shop') }}?category={{ $category->id }}" class="text-decoration-none">
                    <div class="category-card">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>{{ $category->name }}</h3>
                        <p>{{ $category->products_count }} items</p>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No categories available at the moment</p>
            </div>
            @endforelse
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
                            <span class="current">₹{{ number_format($product->price, 0) }}</span>
                            <span class="original">₹{{ number_format($product->price * 1.2, 0) }}</span>
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
                        <strong>Starting from ₹500</strong>
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
                        <strong>Starting from ₹300</strong>
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

<!-- Why Choose Us Section -->
<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="section-title">
            <h2>Why Choose Us</h2>
            <div class="divider"></div>
            <p>What makes us the preferred choice for men's fashion</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="why-choose-us-card">
                    <i class="fas fa-gem"></i>
                    <h3>Premium Quality</h3>
                    <p>Only the finest fabrics and materials. Each piece crafted with attention to detail and superior quality standards.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="why-choose-us-card">
                    <i class="fas fa-users"></i>
                    <h3>Expert Tailors</h3>
                    <p>20+ years of combined experience. Our tailors are skilled craftspeople dedicated to perfection in every stitch.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="why-choose-us-card">
                    <i class="fas fa-ruler"></i>
                    <h3>Perfect Fit</h3>
                    <p>Custom measurements and multiple fittings. We ensure your garments fit perfectly and flatter your physique.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="why-choose-us-card">
                    <i class="fas fa-truck"></i>
                    <h3>Fast Delivery</h3>
                    <p>Quick turnaround time without compromising quality. Standard orders delivered within 5-7 business days.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="why-choose-us-card">
                    <i class="fas fa-headset"></i>
                    <h3>Expert Support</h3>
                    <p>Dedicated customer service team ready to assist. We're here to answer questions and ensure satisfaction.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="why-choose-us-card">
                    <i class="fas fa-lock"></i>
                    <h3>Secure Payments</h3>
                    <p>Safe and secure payment options. Your transactions are protected with latest encryption technology.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="why-choose-us-card">
                    <i class="fas fa-sync-alt"></i>
                    <h3>Easy Alterations</h3>
                    <p>Need adjustments? We offer free minor alterations within 30 days of delivery to ensure perfection.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="why-choose-us-card">
                    <i class="fas fa-star"></i>
                    <h3>Satisfaction Guaranteed</h3>
                    <p>Not satisfied? We offer hassle-free returns and refunds. Your satisfaction is our top priority.</p>
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
<script>
function addToCart(productId) {
    // TODO: Implement add to cart functionality
    alert('Product added to cart! (Demo)');
    // Update cart badge
    document.querySelector('.cart-badge').textContent = parseInt(document.querySelector('.cart-badge').textContent) + 1;
}
</script>
@endsection
