@extends('frontend.layouts.app')

@section('title', $product->name . ' - Clothes Store')

@section('content')

<!-- Page Header -->
<div style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white; padding: 40px 0; border-bottom: 3px solid var(--accent-gold);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: none; padding: 0; margin: 0;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: var(--accent-gold);">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" style="color: var(--accent-gold);">Shop</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}?category={{ $product->category->id }}" style="color: var(--accent-gold);">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active" style="color: white;">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="row g-5 mb-5">
            <!-- Product Images -->
            <div class="col-lg-6">
                <!-- Main Image -->
                <div class="product-image" style="height: 500px; border-radius: 10px; overflow: hidden; border: 3px solid var(--accent-gold); margin-bottom: 1rem;">
                    @if($product->image)
                        <img id="mainImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://via.placeholder.com/500x500?text=No+Image'">
                    @else
                        <img id="mainImage" src="https://via.placeholder.com/500x500?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </div>

                <!-- Gallery Images -->
                <div class="row g-2">
                    <div class="col-3">
                        <div style="height: 100px; background: #f0f0f0; border-radius: 5px; overflow: hidden; cursor: pointer; border: 2px solid var(--accent-gold);" onclick="changeImage(this)">
                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150x150' }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                    @if($product->gallery && is_array($product->gallery) && count($product->gallery) > 0)
                        @foreach($product->gallery as $index => $galleryImage)
                        <div class="col-3">
                            <div style="height: 100px; background: #f0f0f0; border-radius: 5px; overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: all 0.3s ease;" onclick="changeImage(this)" onmouseover="this.style.borderColor='var(--accent-gold)'" onmouseout="this.style.borderColor='transparent'">
                                <img src="{{ asset('storage/' . $galleryImage) }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://via.placeholder.com/150x150'">
                            </div>
                        </div>
                        @endforeach
                    @else
                        @for($i = 0; $i < 2; $i++)
                        <div class="col-3">
                            <div style="height: 100px; background: #f0f0f0; border-radius: 5px; overflow: hidden; cursor: pointer;">
                                <img src="https://via.placeholder.com/150x150" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        @endfor
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-6">
                <!-- Badges -->
                <div style="margin-bottom: 1rem; display: flex; gap: 10px;">
                    @if($product->stock_quantity > 0)
                    <span class="badge" style="background: var(--accent-gold); color: var(--primary-dark); padding: 8px 15px; font-size: 0.85rem;">
                        <i class="fas fa-check-circle"></i> In Stock
                    </span>
                    @else
                    <span class="badge" style="background: #dc3545; color: white; padding: 8px 15px; font-size: 0.85rem;">
                        <i class="fas fa-times-circle"></i> Out of Stock
                    </span>
                    @endif

                    @if($product->hasDiscount())
                    <span class="badge" style="background: var(--accent-gold); color: var(--primary-dark); padding: 8px 15px; font-size: 0.85rem;">
                        Save {{ $product->discount_percentage }}%
                    </span>
                    @endif
                </div>

                <!-- Title -->
                <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem; color: var(--primary-dark);">{{ $product->name }}</h1>

                <!-- Rating -->
                <div class="mb-3">
                    <span style="color: var(--accent-gold); font-size: 1.2rem;">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </span>
                    <span class="text-muted ms-2">(125 Reviews)</span>
                </div>

                <!-- Pricing -->
                <div class="row mb-4" style="border-top: 2px solid var(--accent-gold); border-bottom: 2px solid var(--accent-gold); padding: 1.5rem 0;">
                    <div class="col-md-6">
                        <h3 style="color: var(--accent-gold); font-size: 1.8rem; font-weight: 700;">
                            ₹{{ number_format($product->final_price, 0) }}
                        </h3>
                    </div>
                    <div class="col-md-6 text-end">
                        @if($product->hasDiscount())
                        <p style="font-size: 0.9rem; color: var(--text-muted); text-decoration: line-through;">
                            ₹{{ number_format($product->price, 0) }}
                        </p>
                        <span style="color: var(--accent-gold); font-weight: 600;">
                            You Save: ₹{{ number_format($product->price - $product->final_price, 0) }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Product Info Table -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">Product Details</h5>
                    <table style="width: 100%; font-size: 0.95rem;">
                        @if($product->category)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 0.5rem 0; color: var(--text-muted); font-weight: 600; width: 30%;">Category</td>
                            <td style="padding: 0.5rem 0; color: var(--primary-dark);">{{ $product->category->name }}</td>
                        </tr>
                        @endif
                        @if($product->color)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 0.5rem 0; color: var(--text-muted); font-weight: 600;">Color</td>
                            <td style="padding: 0.5rem 0; color: var(--primary-dark);">{{ $product->color }}</td>
                        </tr>
                        @endif
                        @if($product->material)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 0.5rem 0; color: var(--text-muted); font-weight: 600;">Material</td>
                            <td style="padding: 0.5rem 0; color: var(--primary-dark);">{{ $product->material }}</td>
                        </tr>
                        @endif
                        @if($product->fabric_type)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 0.5rem 0; color: var(--text-muted); font-weight: 600;">Fabric Type</td>
                            <td style="padding: 0.5rem 0; color: var(--primary-dark);">{{ $product->fabric_type }}</td>
                        </tr>
                        @endif
                        @if($product->size)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 0.5rem 0; color: var(--text-muted); font-weight: 600;">Size</td>
                            <td style="padding: 0.5rem 0; color: var(--primary-dark);">{{ $product->size }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding: 0.5rem 0; color: var(--text-muted); font-weight: 600;">SKU</td>
                            <td style="padding: 0.5rem 0; color: var(--primary-dark);">{{ $product->sku }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Size Selection -->
                @if($product->available_sizes && is_array($product->available_sizes) && count($product->available_sizes) > 0)
                <div class="mb-4">
                    <label class="form-label fw-bold" style="color: var(--primary-dark); font-size: 1rem;">
                        <i class="fas fa-ruler"></i> Select Size
                    </label>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        @foreach($product->available_sizes as $availableSize)
                        <button 
                            type="button" 
                            class="size-btn"
                            style="width: 60px; height: 50px; border: 2px solid var(--primary-dark); background: white; color: var(--primary-dark); font-weight: 600; border-radius: 5px; cursor: pointer; transition: all 0.3s ease;"
                            onclick="selectSize(this)"
                        >
                            {{ $availableSize }}
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="selectedSize" value="">
                </div>
                @endif

                <!-- Color Selection -->
                @if($product->available_colors && is_array($product->available_colors) && count($product->available_colors) > 0)
                <div class="mb-4">
                    <label class="form-label fw-bold" style="color: var(--primary-dark); font-size: 1rem;">
                        <i class="fas fa-palette"></i> Available Colors
                    </label>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        @foreach($product->available_colors as $availableColor)
                        <div 
                            class="color-option"
                            style="width: 50px; height: 50px; border-radius: 50%; border: 3px solid #ddd; cursor: pointer; transition: all 0.3s ease; background: {{ strtolower($availableColor) }};" 
                            title="{{ $availableColor }}"
                            onclick="selectColor(this, '{{ $availableColor }}')"
                        ></div>
                        @endforeach
                    </div>
                    <input type="hidden" id="selectedColor" value="">
                </div>
                @endif

                <!-- Quantity Selector -->
                <div class="mb-4">
                    <label class="form-label fw-bold" style="color: var(--primary-dark); font-size: 1rem;">
                        <i class="fas fa-cubes"></i> Quantity
                    </label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button class="btn btn-outline-secondary" onclick="decrementQty()" style="border-color: var(--accent-gold); color: var(--primary-dark);">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="form-control text-center" style="width: 80px; border-color: var(--accent-gold);">
                        <button class="btn btn-outline-secondary" onclick="incrementQty()" style="border-color: var(--accent-gold); color: var(--primary-dark);">+</button>
                        <span class="text-muted" style="margin-left: 1rem;">{{ $product->stock_quantity }} available</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 15px; margin-bottom: 2rem;">
                    <button class="btn btn-lg" style="flex: 1; background: var(--accent-gold); color: var(--primary-dark); font-weight: 600; border: none; border-radius: 5px;" onclick="addToCart({{ $product->id }})" @if(!$product->stock_quantity > 0) disabled @endif>
                        <i class="fas fa-shopping-bag me-2"></i>Add to Cart
                    </button>
                    <button class="btn btn-lg" style="flex: 1; background: transparent; color: var(--primary-dark); font-weight: 600; border: 2px solid var(--accent-gold); border-radius: 5px;">
                        <i class="fas fa-heart me-2"></i>Wishlist
                    </button>
                </div>

                <!-- Additional Info -->
                <div class="row g-3" style="background: #F8F5EF; padding: 1.5rem; border-radius: 10px; border-left: 4px solid var(--accent-gold);">
                    <div class="col-md-4 text-center">
                        <i class="fas fa-shipping-fast" style="font-size: 1.5rem; color: var(--accent-gold);"></i>
                        <p class="mt-2" style="color: var(--primary-dark);"><strong>Free Shipping</strong><br><small>On orders above ₹2000</small></p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-undo" style="font-size: 1.5rem; color: var(--accent-gold);"></i>
                        <p class="mt-2" style="color: var(--primary-dark);"><strong>Easy Returns</strong><br><small>30 days return policy</small></p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-lock" style="font-size: 1.5rem; color: var(--accent-gold);"></i>
                        <p class="mt-2" style="color: var(--primary-dark);"><strong>Secure Payment</strong><br><small>100% secure checkout</small></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description & Fabric Information -->
        <div class="row mb-5">
            <div class="col-lg-8">
                <ul class="nav nav-tabs" role="tablist" style="border-color: var(--accent-gold);">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" style="color: var(--primary-dark); font-weight: 600;">
                            <i class="fas fa-align-left me-2"></i>Description
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="fabric-tab" data-bs-toggle="tab" data-bs-target="#fabric" type="button" style="color: var(--primary-dark); font-weight: 600;">
                            <i class="fas fa-layer-group me-2"></i>Fabric Information
                        </button>
                    </li>
                </ul>

                <div class="tab-content" style="padding: 2rem; background: white; border: 1px solid #eee;">
                    <div class="tab-pane fade show active" id="description">
                        @if($product->short_description)
                        <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">Summary</h6>
                        <p style="color: var(--text-muted);">{{ $product->short_description }}</p>
                        @endif

                        @if($product->description)
                        <h6 class="fw-bold mb-3 mt-4" style="color: var(--primary-dark);">Full Description</h6>
                        <p style="color: var(--text-muted); line-height: 1.8;">{{ $product->description }}</p>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="fabric">
                        @if($product->material)
                        <div class="mb-4">
                            <h6 class="fw-bold" style="color: var(--primary-dark);">Material</h6>
                            <p style="color: var(--text-muted);">{{ $product->material }}</p>
                        </div>
                        @endif

                        @if($product->fabric_type)
                        <div class="mb-4">
                            <h6 class="fw-bold" style="color: var(--primary-dark);">Fabric Type</h6>
                            <p style="color: var(--text-muted);">{{ $product->fabric_type }}</p>
                        </div>
                        @endif

                        <div class="mb-4">
                            <h6 class="fw-bold" style="color: var(--primary-dark);">Care Instructions</h6>
                            <ul style="color: var(--text-muted);">
                                <li>Dry clean only for best results</li>
                                <li>Do not bleach</li>
                                <li>Cool iron if needed</li>
                                <li>Store in a cool, dry place</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h3 class="fw-bold mb-4" style="color: var(--primary-dark); border-bottom: 3px solid var(--accent-gold); padding-bottom: 1rem;">
                <i class="fas fa-link me-2"></i>Related Products
            </h3>
            <div class="row g-4">
                @foreach($relatedProducts as $related)
                <div class="col-lg-3 col-md-6">
                    <div class="product-card">
                        <div class="product-image" style="height: 250px; position: relative;">
                            @if($related->image)
                            <img src="{{ $related->image_url }}" alt="{{ $related->name }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                            @else
                            <img src="https://via.placeholder.com/300x300?text={{ urlencode($related->name) }}" alt="{{ $related->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                            @if($related->stock_quantity > 0)
                            <span class="product-badge" style="position: absolute; top: 15px; right: 15px; background: var(--accent-gold); color: var(--primary-dark); padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">In Stock</span>
                            @endif
                        </div>
                        <div class="product-info" style="padding: 1rem;">
                            <p style="font-size: 0.8rem; color: var(--accent-gold); font-weight: 600; margin-bottom: 0.5rem;">{{ $related->category->name }}</p>
                            <h5 style="font-weight: 600; color: var(--primary-dark); margin-bottom: 0.5rem;">{{ $related->name }}</h5>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 1.2rem; font-weight: 700; color: var(--accent-gold);">₹{{ number_format($related->final_price, 0) }}</span>
                                <a href="{{ route('product.detail', $related->slug) }}" class="btn btn-sm" style="background: var(--accent-gold); color: var(--primary-dark); border: none; padding: 5px 10px;">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<style>
    .size-btn:hover,
    .size-btn.active {
        background: var(--accent-gold);
        color: var(--primary-dark);
        border-color: var(--accent-gold);
    }

    .color-option:hover,
    .color-option.active {
        border-color: var(--accent-gold) !important;
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    }

    .nav-tabs .nav-link {
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        border-bottom-color: var(--accent-gold);
        background: none;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: var(--accent-gold);
    }

    @media (max-width: 768px) {
        .product-image {
            height: 350px !important;
        }
    }
</style>

@endsection

@section('scripts')
<script>
function incrementQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max);
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
    }
}

function decrementQty() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function selectSize(btn) {
    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('selectedSize').value = btn.textContent.trim();
}

function selectColor(btn, color) {
    document.querySelectorAll('.color-option').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('selectedColor').value = color;
}

function changeImage(element) {
    const img = element.querySelector('img');
    document.getElementById('mainImage').src = img.src;
}

function addToCart(productId) {
    const qty = parseInt(document.getElementById('quantity').value);
    const size = document.getElementById('selectedSize')?.value || null;
    const color = document.getElementById('selectedColor')?.value || null;
    
    if (qty < 1) {
        alert('Please enter a valid quantity');
        return;
    }

    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: qty,
            size: size,
            color: color
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count in navbar
            updateCartCount();
            
            // Show success message
            const message = document.createElement('div');
            message.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 15px 20px; border-radius: 5px; z-index: 9999; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
            message.textContent = data.message;
            document.body.appendChild(message);
            
            setTimeout(() => message.remove(), 3000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding to cart');
    });
}

function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.cart-badge');
            if (badge) {
                badge.textContent = data.count;
            }
        });
}
</script>
@endsection
