@extends('frontend.layouts.app')

@section('title', $product->name . ' - Clothes Store')

@section('content')

<section style="background: #f5f5f5; padding: 60px 0;">
    <div class="container-fluid" style="max-width: 1400px; padding: 0 40px;">
        <div style="display: grid; grid-template-columns: 110px 1fr 380px; gap: 20px; align-items: start;">
            
            <!-- Left - Thumbnail Gallery with Scroll -->
            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                <!-- Up Arrow -->
                <div style="text-align: center; cursor: pointer;" onclick="scrollGalleryUp()">
                    <i class="fas fa-chevron-up" style="font-size: 1.5rem; color: #333;"></i>
                </div>

                <!-- Thumbnails Container with Scroll - Show Only Images That Exist -->
                @php
                    $allProductImages = [];
                    
                    // Add featured image if exists
                    if ($product->featuredImage) {
                        $allProductImages[] = $product->featuredImage;
                    }
                    
                    // Add gallery images if exist
                    if ($product->galleryImages && count($product->galleryImages) > 0) {
                        $allProductImages = array_merge($allProductImages, $product->galleryImages->all());
                    }
                    
                    $totalImages = count($allProductImages);
                @endphp

                <div style="width: 110px; height: 480px; overflow: hidden; position: relative;">
                    <div id="thumbnailGallery" style="display: flex; flex-direction: column; gap: 10px; transition: transform 0.4s ease; padding: 0;">
                        @forelse($allProductImages as $index => $image)
                        <div 
                            style="width: 110px; height: 150px; background: #e0e0e0; cursor: pointer; overflow: hidden; border: 2px solid #ddd; flex-shrink: 0;" 
                            onclick="selectThumbnail(this, {{ $index }})"
                        >
                            <img src="{{ $image->image_url }}" alt="Product Image {{ $index + 1 }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        @empty
                        <div 
                            style="width: 110px; height: 150px; background: #e0e0e0; cursor: pointer; overflow: hidden; border: 2px solid #ddd; flex-shrink: 0;" 
                            onclick="selectThumbnail(this, 0)"
                        >
                            <img src="https://via.placeholder.com/110x150?text=No+Image" alt="No Image" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Down Arrow -->
                @if($totalImages > 3)
                <div style="text-align: center; cursor: pointer;" onclick="scrollGalleryDown()">
                    <i class="fas fa-chevron-down" style="font-size: 1.5rem; color: #333;"></i>
                </div>
                @endif
            </div>

            <!-- Center - Large Main Image with Zoom -->
            <div style="position: relative; overflow: hidden; cursor: zoom-in;">
                @php
                    $mainImageUrl = '';
                    if ($product->featuredImage) {
                        $mainImageUrl = $product->featuredImage->image_url;
                    } elseif ($product->galleryImages && count($product->galleryImages) > 0) {
                        $mainImageUrl = $product->galleryImages->first()->image_url;
                    } else {
                        $mainImageUrl = 'https://via.placeholder.com/500x650?text=No+Image';
                    }
                @endphp
                
                <img 
                    id="mainImage" 
                    src="{{ $mainImageUrl }}" 
                    alt="{{ $product->name }}" 
                    style="width: 100%; height: 650px; object-fit: cover; background: #e0e0e0; transition: transform 0.1s ease-out; transform-origin: center;"
                    onmousemove="zoomImage(event)"
                    onmouseout="resetZoom()"
                >
            </div>

            <!-- Right - Product Details -->
            <div>
                    <!-- Product Title -->
                    <h1 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1rem; color: #333; line-height: 1.3;">{{ $product->name }}</h1>

                    <!-- Price -->
                    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #ddd;">
                        <div style="font-size: 1.8rem; font-weight: 700; color: #000; margin-bottom: 0.5rem;">Rs. {{ number_format($product->final_price, 0) }}</div>
                        @if($product->hasDiscount())
                        <div style="font-size: 0.9rem; color: #999;">
                            <span style="text-decoration: line-through;">Rs. {{ number_format($product->price, 0) }}</span>
                            <span style="margin-left: 1rem; color: #e74c3c;">Save {{ $product->discount_percentage }}%</span>
                        </div>
                        @endif
                    </div>

                    <!-- Size Selection -->
                    @if($product->available_sizes && is_array($product->available_sizes) && count($product->available_sizes) > 0)
                    <div style="margin-bottom: 1.5rem;">
                        <label style="font-size: 0.85rem; font-weight: 700; color: #333; display: block; margin-bottom: 0.8rem; text-transform: uppercase;">Size</label>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            @foreach($product->available_sizes as $availableSize)
                            <button 
                                type="button" 
                                class="size-option"
                                data-size="{{ $availableSize }}"
                                style="width: 45px; height: 45px; border: 2px solid #ccc; background: white; color: #333; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 0.85rem;"
                                onclick="selectSize(this, '{{ $availableSize }}')"
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
                    <div style="margin-bottom: 1.5rem;">
                        <label style="font-size: 0.85rem; font-weight: 700; color: #333; display: block; margin-bottom: 0.8rem; text-transform: uppercase;">Kurtis Colour</label>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            @foreach($product->available_colors as $availableColor)
                            <div 
                                class="color-option"
                                data-color="{{ $availableColor }}"
                                style="width: 35px; height: 35px; border-radius: 50%; border: 3px solid #ddd; cursor: pointer; transition: all 0.3s; background: {{ strtolower(str_replace(' ', '-', $availableColor)) }};" 
                                title="{{ $availableColor }}"
                                onclick="selectColor(this, '{{ $availableColor }}')"
                            ></div>
                            @endforeach
                        </div>
                        <input type="hidden" id="selectedColor" value="">
                    </div>
                    @endif

                    <!-- Quantity -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="font-size: 0.85rem; font-weight: 700; color: #333; display: block; margin-bottom: 0.8rem; text-transform: uppercase;">Quantity</label>
                        <div style="display: flex; align-items: center; gap: 10px; width: fit-content;">
                            <button style="width: 38px; height: 38px; border: 1px solid #ccc; background: white; cursor: pointer; font-size: 1.1rem; font-weight: 600;" onclick="decrementQty()">−</button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" style="width: 55px; text-align: center; border: 1px solid #ccc; padding: 8px; font-size: 0.95rem; font-weight: 600;">
                            <button style="width: 38px; height: 38px; border: 1px solid #ccc; background: white; cursor: pointer; font-size: 1.1rem; font-weight: 600;" onclick="incrementQty()">+</button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 1.5rem;">
                        <button 
                            onclick="addToCart({{ $product->id }})" 
                            style="width: 100%; background: #000; color: white; border: none; padding: 12px; font-size: 0.9rem; font-weight: 700; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;"
                            @if(!($product->stock_quantity > 0)) disabled @endif
                        >
                            Add to Cart
                        </button>
                        <button 
                            style="width: 100%; background: white; color: #000; border: 1px solid #000; padding: 12px; font-size: 0.9rem; font-weight: 700; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;"
                        >
                            Buy It Now
                        </button>
                    </div>

                    <!-- Rating -->
                    <div style="padding: 1rem; background: #f9f9f9; border-radius: 4px; margin-bottom: 1.5rem;">
                        <div style="display: flex; gap: 8px; margin-bottom: 0.7rem;">
                            <span style="color: #2ecc71; font-size: 0.85rem;">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </span>
                        </div>
                        <p style="font-size: 0.8rem; color: #666; margin: 0;">Testimonials 4.5 | 14,500+ reviews</p>
                        <p style="font-size: 0.8rem; color: #999; margin: 0.5rem 0 0 0;">100 customer care reviews using this product</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 style="font-size: 0.9rem; font-weight: 700; margin-bottom: 0.7rem; color: #333; text-transform: uppercase;">Description</h3>
                        <p style="font-size: 0.8rem; color: #666; line-height: 1.5;">{{ $product->description ?? 'Premium quality product with excellent craftsmanship and attention to detail.' }}</p>
                    </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->count() > 0)
        <div style="margin-top: 60px; padding-top: 60px; border-top: 1px solid #ddd; text-align: center;">
            <h2 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 30px; color: #333; text-transform: uppercase; letter-spacing: 2px;">Related Products</h2>
            
            <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; max-width: 100%;">
                <div style="text-align: center; width: 220px;">
                    <div style="position: relative; margin-bottom: 15px; overflow: hidden; background: #e0e0e0; height: 280px;">
                        <img src="{{ asset('frontend/images/images (1).jfif') }}" alt="Related Product" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h4 style="font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; color: #333; line-height: 1.3;">Premium Cotton Suit</h4>
                    <p style="font-size: 1rem; font-weight: 700; color: #000; margin: 0;">Rs. 8,990</p>
                </div>
                <div style="text-align: center; width: 220px;">
                    <div style="position: relative; margin-bottom: 15px; overflow: hidden; background: #e0e0e0; height: 280px;">
                        <img src="{{ asset('frontend/images/images (2).jfif') }}" alt="Related Product" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h4 style="font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; color: #333; line-height: 1.3;">Wash & Wear Kurta</h4>
                    <p style="font-size: 1rem; font-weight: 700; color: #000; margin: 0;">Rs. 3,590</p>
                </div>
                <div style="text-align: center; width: 220px;">
                    <div style="position: relative; margin-bottom: 15px; overflow: hidden; background: #e0e0e0; height: 280px;">
                        <img src="{{ asset('frontend/images/images (3).jfif') }}" alt="Related Product" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h4 style="font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; color: #333; line-height: 1.3;">Khaddar Formal Suit</h4>
                    <p style="font-size: 1rem; font-weight: 700; color: #000; margin: 0;">Rs. 5,990</p>
                </div>
                <div style="text-align: center; width: 220px;">
                    <div style="position: relative; margin-bottom: 15px; overflow: hidden; background: #e0e0e0; height: 280px;">
                        <img src="{{ asset('frontend/images/images (4).jfif') }}" alt="Related Product" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h4 style="font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; color: #333; line-height: 1.3;">Linen Casual Shirt</h4>
                    <p style="font-size: 1rem; font-weight: 700; color: #000; margin: 0;">Rs. 2,890</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Recently Viewed Products Section -->
        <div style="margin-top: 60px; padding-top: 60px; border-top: 1px solid #ddd; text-align: center;">
            <h2 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 30px; color: #333; text-transform: uppercase; letter-spacing: 2px;">Recently Viewed Products</h2>
            
            <div style="display: flex; justify-content: center;">
                <div style="text-align: center;">
                    <div style="position: relative; margin-bottom: 15px; overflow: hidden; background: #e0e0e0; height: 300px; width: 240px;">
                        <img src="{{ asset('frontend/images/images.jfif') }}" alt="Recently Viewed" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h4 style="font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Black Embroidered Kurta Trouser FD-5688</h4>
                    <p style="font-size: 1rem; font-weight: 700; color: #000; margin: 0;">Rs. 25,995.00</p>
                </div>
            </div>
        </div>

        <!-- Customer Reviews Section -->
        <div style="margin-top: 60px; padding-top: 60px; border-top: 1px solid #ddd; text-align: center;">
            <h2 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 30px; color: #333; text-transform: uppercase; letter-spacing: 2px;">Customer Reviews</h2>
            
            <div style="display: flex; gap: 20px; justify-content: center; align-items: center; margin-bottom: 30px;">
                <div>
                    <div style="color: #2ecc71; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p style="font-size: 0.8rem; color: #666;">Be the first to review</p>
                </div>
            </div>
            
            <button style="background: #2ecc71; color: white; border: none; padding: 10px 30px; font-size: 0.85rem; font-weight: 700; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;">
                Write a review
            </button>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<style>
    .size-option:hover,
    .size-option.active {
        border-color: #000;
        background: #f0f0f0;
    }

    .color-option:hover,
    .color-option.active {
        border-color: #000 !important;
        box-shadow: 0 0 8px rgba(0,0,0,0.2);
    }

    #thumbnailGallery {
        transition: transform 0.4s ease;
    }

    #thumbnailGallery div {
        position: relative;
    }

    #thumbnailGallery div.selected {
        border-color: #000 !important;
        border-width: 3px !important;
    }
</style>

<script>
let currentImageIndex = 0;
const imageHeight = 150;
const gap = 10;
const imagesPerView = 3;
let totalUniqueImages = {{ $totalImages }};
let allImages = [];
const zoomLevel = 2; // 200% zoom

function initGallery() {
    // Get all image elements
    const gallery = document.getElementById('thumbnailGallery');
    const originalImages = Array.from(gallery.querySelectorAll('div'));
    
    // Store original images for infinite loop
    allImages = originalImages;
    totalUniqueImages = originalImages.length;
    
    // Duplicate images for infinite scroll effect
    if (totalUniqueImages > 0) {
        originalImages.forEach(img => {
            const clone = img.cloneNode(true);
            clone.onclick = function() {
                selectThumbnail(this, originalImages.indexOf(img));
            };
            gallery.appendChild(clone);
        });
    }
    
    // Select first image
    if (allImages.length > 0) {
        selectThumbnail(allImages[0], 0);
    }
}

function zoomImage(event) {
    const image = document.getElementById('mainImage');
    const container = image.parentElement;
    
    // Get container dimensions
    const containerRect = container.getBoundingClientRect();
    const containerWidth = containerRect.width;
    const containerHeight = containerRect.height;
    
    // Get mouse position relative to container
    const mouseX = event.clientX - containerRect.left;
    const mouseY = event.clientY - containerRect.top;
    
    // Calculate percentage position (0 to 1)
    const xPercent = (mouseX / containerWidth) * 100;
    const yPercent = (mouseY / containerHeight) * 100;
    
    // Apply zoom and position
    image.style.transform = `scale(${zoomLevel})`;
    image.style.transformOrigin = `${xPercent}% ${yPercent}%`;
}

function resetZoom() {
    const image = document.getElementById('mainImage');
    image.style.transform = 'scale(1)';
    image.style.transformOrigin = 'center';
}

function scrollGalleryUp() {
    currentImageIndex--;
    
    // Loop back to end if at beginning
    if (currentImageIndex < 0) {
        currentImageIndex = totalUniqueImages - 1;
    }
    
    updateGalleryPosition();
}

function scrollGalleryDown() {
    currentImageIndex++;
    
    // Loop to beginning if at end
    if (currentImageIndex >= totalUniqueImages) {
        currentImageIndex = 0;
    }
    
    updateGalleryPosition();
}

function updateGalleryPosition() {
    const gallery = document.getElementById('thumbnailGallery');
    const scrollAmount = currentImageIndex * (imageHeight + gap);
    gallery.style.transform = `translateY(-${scrollAmount}px)`;
}

function selectThumbnail(element, index) {
    // Get the image src from the clicked thumbnail
    const imgSrc = element.querySelector('img').src;
    document.getElementById('mainImage').src = imgSrc;
    
    // Reset zoom when changing image
    resetZoom();
    
    // Update selected state - only highlight original images, not duplicates
    const gallery = document.getElementById('thumbnailGallery');
    const allGalleryItems = Array.from(gallery.querySelectorAll('div'));
    
    allGalleryItems.forEach(el => {
        el.style.borderColor = '#ddd';
        el.style.borderWidth = '2px';
    });
    
    // Highlight both original and duplicate
    element.style.borderColor = '#000';
    element.style.borderWidth = '3px';
    
    // Set current index
    currentImageIndex = index;
    updateGalleryPosition();
}

function changeImage(element) {
    const img = element.querySelector('img');
    document.getElementById('mainImage').src = img.src;
    resetZoom();
}

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

function selectSize(btn, size) {
    document.querySelectorAll('.size-option').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('selectedSize').value = size;
}

function selectColor(btn, color) {
    document.querySelectorAll('.color-option').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('selectedColor').value = color;
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
            updateCartCount();
            
            const message = document.createElement('div');
            message.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #2ecc71; color: white; padding: 15px 20px; border-radius: 5px; z-index: 9999; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
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

// Initialize gallery when page loads
document.addEventListener('DOMContentLoaded', function() {
    initGallery();
});
</script>
@endsection
