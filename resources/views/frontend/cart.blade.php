@extends('frontend.layouts.app')

@section('title', 'Shopping Cart')

@section('content')

<!-- Page Header -->
<div style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white; padding: 60px 0; text-align: center; border-bottom: 3px solid var(--accent-gold);">
    <h1 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif; margin: 0;">Shopping Cart</h1>
    <p style="margin: 10px 0 0 0; font-size: 1rem;">Review and manage your items</p>
</div>

<!-- Cart Section -->
<section class="section-padding" style="background: white;">
    <div class="container">
        @if($errors->any())
        <div style="background: #F8D7DA; border-left: 4px solid #721C24; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            <strong style="color: #721C24;">Error:</strong>
            <ul style="margin: 10px 0 0 0; color: #721C24;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div style="background: #D4EDDA; border-left: 4px solid #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            <p style="color: #155724; margin: 0;">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div style="background: #F8D7DA; border-left: 4px solid #721C24; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            <p style="color: #721C24; margin: 0;">{{ session('error') }}</p>
        </div>
        @endif

        @if(empty($cartItems))
        <!-- Empty Cart -->
        <div style="text-align: center; padding: 60px 0;">
            <div style="font-size: 4rem; color: var(--accent-gold); margin-bottom: 20px;">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h2 style="color: var(--primary-dark); font-size: 1.8rem; margin-bottom: 10px;">Your cart is empty</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">Start shopping to add items to your cart</p>
            <a href="{{ route('shop') }}" class="btn" style="background: var(--accent-gold); color: var(--primary-dark); padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: 600;">
                <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
            </a>
        </div>
        @else
        <!-- Cart Items -->
        <div class="row g-4">
            <!-- Cart Items Column -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4" style="color: var(--primary-dark);">
                            <i class="fas fa-box me-2"></i>Cart Items ({{ count($cartItems) }})
                        </h4>

                        @foreach($cartItems as $item)
                        <div style="border-bottom: 1px solid #eee; padding: 20px 0; display: flex; gap: 20px;" class="cart-item">
                            <!-- Product Image -->
                            <div style="flex-shrink: 0;">
                                <img src="{{ $item['product']->image_url }}" 
                                     alt="{{ $item['product']->name }}"
                                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #eee;">
                            </div>

                            <!-- Product Details -->
                            <div style="flex: 1;">
                                <h5 style="color: var(--primary-dark); margin: 0 0 5px 0;">
                                    <a href="{{ route('product.detail', $item['product']->slug) }}" style="text-decoration: none; color: var(--primary-dark);">
                                        {{ $item['product']->name }}
                                    </a>
                                </h5>

                                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 5px 0;">
                                    Category: <strong>{{ $item['product']->category->name ?? 'N/A' }}</strong>
                                </p>

                                @if($item['size'] || $item['color'])
                                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 5px 0;">
                                    @if($item['size'])
                                    Size: <strong>{{ $item['size'] }}</strong>
                                    @endif
                                    @if($item['color'])
                                    | Color: <strong>{{ $item['color'] }}</strong>
                                    @endif
                                </p>
                                @endif

                                <div style="display: flex; align-items: center; gap: 20px; margin-top: 10px;">
                                    <!-- Price -->
                                    <div>
                                        @if($item['product']->hasDiscount())
                                        <span style="text-decoration: line-through; color: var(--text-muted); font-size: 0.9rem;">
                                            ₹{{ number_format($item['product']->price, 2) }}
                                        </span>
                                        @endif
                                        <p style="margin: 0; color: var(--accent-gold); font-weight: 600; font-size: 1.1rem;">
                                            ₹{{ number_format($item['price'], 2) }}
                                        </p>
                                    </div>

                                    <!-- Quantity -->
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <label style="font-size: 0.9rem; color: var(--text-muted);">Qty:</label>
                                        <input type="number" 
                                               class="form-control quantity-input" 
                                               value="{{ $item['quantity'] }}"
                                               min="1"
                                               max="{{ $item['product']->stock_quantity }}"
                                               data-cart-key="{{ $item['cart_key'] }}"
                                               style="width: 80px; border-color: var(--accent-gold); text-align: center;">
                                    </div>

                                    <!-- Line Total -->
                                    <div style="text-align: right; min-width: 100px;">
                                        <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Line Total</p>
                                        <p style="margin: 0; color: var(--primary-dark); font-weight: 600; font-size: 1.1rem;">
                                            ₹{{ number_format($item['line_total'], 2) }}
                                        </p>
                                    </div>

                                    <!-- Remove Button -->
                                    <button class="btn-remove" 
                                            data-cart-key="{{ $item['cart_key'] }}"
                                            style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 1.2rem; padding: 0;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div style="padding-top: 20px; text-align: center;">
                            <a href="{{ route('shop') }}" class="btn" style="background: transparent; color: var(--accent-gold); border: 2px solid var(--accent-gold); padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: 600;">
                                <i class="fas fa-plus me-2"></i>Continue Shopping
                            </a>
                            <button id="clearCartBtn" class="btn" style="background: transparent; color: #dc3545; border: 2px solid #dc3545; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: 600; margin-left: 10px;">
                                <i class="fas fa-times me-2"></i>Clear Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold); position: sticky; top: 20px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4" style="color: var(--primary-dark);">
                            <i class="fas fa-calculator me-2"></i>Order Summary
                        </h4>

                        <div style="border-bottom: 1px solid #eee; padding-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: var(--text-muted);">Subtotal</span>
                                <span style="color: var(--primary-dark); font-weight: 600;">₹{{ number_format($total, 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: var(--text-muted);">Tax (5%)</span>
                                <span style="color: var(--primary-dark); font-weight: 600;">₹{{ number_format($total * 0.05, 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: var(--text-muted);">Shipping</span>
                                <span style="color: var(--primary-dark); font-weight: 600;">Free</span>
                            </div>
                        </div>

                        <div style="padding: 15px 0; margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: var(--primary-dark); font-weight: 700; font-size: 1.2rem;">Total</span>
                                <span style="color: var(--accent-gold); font-weight: 700; font-size: 1.5rem;">
                                    ₹{{ number_format($total + ($total * 0.05), 2) }}
                                </span>
                            </div>
                        </div>

                        @if(auth()->check())
                        <a href="{{ route('checkout') }}" class="btn w-100" style="background: var(--accent-gold); color: var(--primary-dark); padding: 15px; border: none; border-radius: 5px; font-weight: 600; text-decoration: none; display: block; text-align: center; font-size: 1.1rem;">
                            <i class="fas fa-arrow-right me-2"></i>Proceed to Checkout
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="btn w-100" style="background: var(--accent-gold); color: var(--primary-dark); padding: 15px; border: none; border-radius: 5px; font-weight: 600; text-decoration: none; display: block; text-align: center; font-size: 1.1rem;">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Checkout
                        </a>
                        <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-top: 10px;">
                            Don't have an account? <a href="{{ route('register') }}" style="color: var(--accent-gold); font-weight: 600; text-decoration: none;">Register</a>
                        </p>
                        @endif

                        <!-- Promo Code (Future Enhancement) -->
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                            <label style="font-size: 0.9rem; color: var(--text-muted); display: block; margin-bottom: 5px;">Promo Code (Coming Soon)</label>
                            <div style="display: flex; gap: 5px;">
                                <input type="text" class="form-control" placeholder="Enter code" disabled style="border-color: #ccc;">
                                <button class="btn" style="background: #ccc; color: #999; padding: 8px 15px; border: none; border-radius: 5px; cursor: not-allowed;" disabled>Apply</button>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div style="background: #F8F5EF; padding: 15px; border-radius: 8px; margin-top: 20px;">
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">
                                <i class="fas fa-info-circle me-2" style="color: var(--accent-gold);"></i>
                                Free shipping on all orders. Secure checkout with multiple payment options.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<style>
    .cart-item {
        transition: background-color 0.3s ease;
    }

    .cart-item:hover {
        background-color: #f9f9f9;
    }

    .quantity-input {
        text-align: center;
        font-weight: 600;
    }

    .quantity-input:focus {
        border-color: var(--accent-gold) !important;
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
    }

    .btn-remove:hover {
        transform: scale(1.2);
    }

    @media (max-width: 768px) {
        .cart-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .quantity-input {
            font-size: 16px; /* Prevents zoom on iOS */
        }
    }
</style>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update quantity
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const cartKey = this.dataset.cartKey;
            const quantity = parseInt(this.value);

            if (quantity < 1) {
                this.value = 1;
                return;
            }

            fetch(`/cart/update/${cartKey}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                    this.value = this.dataset.previousValue;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating cart');
            });
        });

        input.dataset.previousValue = input.value;
    });

    // Remove item
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this item?')) {
                const cartKey = this.dataset.cartKey;
                
                fetch(`/cart/remove/${cartKey}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error removing item');
                });
            }
        });
    });

    // Clear cart
    const clearBtn = document.getElementById('clearCartBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your entire cart?')) {
                fetch('/cart/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error clearing cart');
                });
            }
        });
    }
});
</script>
@endsection
