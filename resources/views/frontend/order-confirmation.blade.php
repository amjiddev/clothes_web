@extends('frontend.layouts.app')

@section('title', 'Order Confirmation - ' . $order->order_number)

@section('content')

<!-- Page Header -->
<div style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white; padding: 60px 0; text-align: center; border-bottom: 3px solid var(--accent-gold);">
    <h1 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif; margin: 0;">Order Confirmed</h1>
    <p style="margin: 10px 0 0 0; font-size: 1rem;">Your order has been successfully placed</p>
</div>

<!-- Confirmation Section -->
<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Message -->
                <div style="text-align: center; margin-bottom: 40px;">
                    <div style="width: 80px; height: 80px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px;">
                        <i class="fas fa-check"></i>
                    </div>
                    <h2 style="color: var(--primary-dark); font-size: 2rem; margin-bottom: 10px;">Thank You!</h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin: 0;">Your order has been successfully placed</p>
                </div>

                <!-- Order Number Card -->
                <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4" style="text-align: center; background: #F8F5EF;">
                        <p style="color: var(--text-muted); margin: 0; font-size: 0.95rem;">Order Number</p>
                        <h3 style="color: var(--accent-gold); margin: 10px 0; font-family: 'Courier New', monospace; letter-spacing: 2px;">
                            {{ $order->order_number }}
                        </h3>
                        <p style="color: var(--text-muted); margin: 0; font-size: 0.9rem;">
                            Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                        </p>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: var(--primary-dark);">
                            <i class="fas fa-box me-2"></i>Order Items
                        </h5>

                        @foreach($order->orderItems as $item)
                        <div style="display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div>
                                <p style="margin: 0; color: var(--primary-dark); font-weight: 500;">
                                    {{ $item->product->name }}
                                </p>
                                <p style="margin: 5px 0 0 0; color: var(--text-muted); font-size: 0.9rem;">
                                    {{ $item->notes }}
                                </p>
                                <p style="margin: 3px 0 0 0; color: var(--text-muted); font-size: 0.85rem;">
                                    Quantity: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}
                                </p>
                            </div>
                            <div style="text-align: right;">
                                <p style="margin: 0; color: var(--accent-gold); font-weight: 600; font-size: 1.1rem;">
                                    ₹{{ number_format($item->total, 2) }}
                                </p>
                            </div>
                        </div>
                        @endforeach

                        @if($order->stitchingOrder)
                        <div style="display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; background: #F8F5EF; padding: 15px;">
                            <div>
                                <p style="margin: 0; color: var(--primary-dark); font-weight: 500;">
                                    <i class="fas fa-scissors me-2"></i>Stitching Service
                                </p>
                                <p style="margin: 5px 0 0 0; color: var(--text-muted); font-size: 0.9rem;">
                                    {{ $order->type === 'cloth_stitching' ? 'Cloth + Stitching' : ($order->type === 'stitching_only' ? 'Stitching Only' : 'Cloth Only') }}
                                </p>
                            </div>
                            <div style="text-align: right;">
                                <p style="margin: 0; color: var(--accent-gold); font-weight: 600; font-size: 1.1rem;">
                                    ₹{{ number_format($order->stitching_charge, 2) }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Pricing Summary -->
                <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: var(--primary-dark);">
                            <i class="fas fa-calculator me-2"></i>Price Summary
                        </h5>

                        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                            <span style="color: var(--text-muted);">Subtotal</span>
                            <span style="font-weight: 600;">₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>

                        @if($order->stitching_charge > 0)
                        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                            <span style="color: var(--text-muted);">Stitching Charge</span>
                            <span style="font-weight: 600;">₹{{ number_format($order->stitching_charge, 2) }}</span>
                        </div>
                        @endif

                        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                            <span style="color: var(--text-muted);">Tax (5%)</span>
                            <span style="font-weight: 600;">₹{{ number_format($order->tax, 2) }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; padding: 15px 0; font-size: 1.2rem;">
                            <span style="color: var(--primary-dark); font-weight: 700;">Total Amount</span>
                            <span style="color: var(--accent-gold); font-weight: 700;">₹{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Status & Next Steps -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">
                                    <i class="fas fa-info-circle me-2"></i>Order Status
                                </h6>
                                <p style="margin: 0; color: var(--primary-dark); font-weight: 600;">
                                    <span class="badge" style="background: #FFC107; color: #000;">{{ strtoupper(str_replace('_', ' ', $order->status)) }}</span>
                                </p>
                                <p style="margin: 10px 0 0 0; color: var(--text-muted); font-size: 0.9rem;">
                                    We're preparing your order
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">
                                    <i class="fas fa-credit-card me-2"></i>Payment Status
                                </h6>
                                <p style="margin: 0; color: var(--primary-dark); font-weight: 600;">
                                    <span class="badge" style="background: #FFC107; color: #000;">{{ strtoupper($order->payment_method) }}</span>
                                </p>
                                <p style="margin: 10px 0 0 0; color: var(--text-muted); font-size: 0.9rem;">
                                    Payment will be collected on delivery
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- What Happens Next -->
                <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: var(--primary-dark);">
                            <i class="fas fa-tasks me-2"></i>What Happens Next
                        </h5>

                        <div>
                            <div style="display: flex; margin-bottom: 20px;">
                                <div style="width: 50px; height: 50px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; margin-right: 15px; flex-shrink: 0;">1</div>
                                <div>
                                    <h6 style="margin: 0; color: var(--primary-dark); font-weight: 600;">Order Confirmation</h6>
                                    <p style="margin: 5px 0 0 0; color: var(--text-muted); font-size: 0.9rem;">You'll receive a confirmation email shortly</p>
                                </div>
                            </div>

                            <div style="display: flex; margin-bottom: 20px;">
                                <div style="width: 50px; height: 50px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; margin-right: 15px; flex-shrink: 0;">2</div>
                                <div>
                                    <h6 style="margin: 0; color: var(--primary-dark); font-weight: 600;">Packing & Dispatch</h6>
                                    <p style="margin: 5px 0 0 0; color: var(--text-muted); font-size: 0.9rem;">We'll prepare and pack your order (1-2 business days)</p>
                                </div>
                            </div>

                            <div style="display: flex; margin-bottom: 20px;">
                                <div style="width: 50px; height: 50px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; margin-right: 15px; flex-shrink: 0;">3</div>
                                <div>
                                    <h6 style="margin: 0; color: var(--primary-dark); font-weight: 600;">Stitching (if applicable)</h6>
                                    <p style="margin: 5px 0 0 0; color: var(--text-muted); font-size: 0.9rem;">Custom tailoring will begin after fabric confirmation</p>
                                </div>
                            </div>

                            <div style="display: flex;">
                                <div style="width: 50px; height: 50px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; margin-right: 15px; flex-shrink: 0;">4</div>
                                <div>
                                    <h6 style="margin: 0; color: var(--primary-dark); font-weight: 600;">Delivery</h6>
                                    <p style="margin: 5px 0 0 0; color: var(--text-muted); font-size: 0.9rem;">Your order will be delivered within 3-5 business days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">
                            <i class="fas fa-headset me-2"></i>Need Help?
                        </h5>
                        <p style="margin: 0; color: var(--text-muted);">
                            Contact our support team at <strong style="color: var(--accent-gold);">support@clothes.com</strong>
                        </p>
                        <p style="margin: 5px 0 0 0; color: var(--text-muted);">
                            Call us: <strong style="color: var(--accent-gold);">+91-XXXXXXXXXX</strong> (9 AM - 6 PM, Mon-Sat)
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 10px; flex-direction: column;">
                    <a href="{{ route('home') }}" class="btn w-100" style="background: var(--accent-gold); color: var(--primary-dark); padding: 15px; text-decoration: none; border-radius: 5px; font-weight: 600; text-align: center; border: none;">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                    <a href="{{ route('shop') }}" class="btn w-100" style="background: transparent; color: var(--primary-dark); padding: 15px; text-decoration: none; border-radius: 5px; font-weight: 600; text-align: center; border: 2px solid var(--primary-dark);">
                        <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .badge {
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        h2 {
            font-size: 1.5rem;
        }
    }
</style>

@endsection
