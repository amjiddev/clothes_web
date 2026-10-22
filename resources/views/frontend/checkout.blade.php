@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')

<!-- Page Header -->
<div style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white; padding: 60px 0; text-align: center; border-bottom: 3px solid var(--accent-gold);">
    <h1 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif; margin: 0;">Checkout</h1>
    <p style="margin: 10px 0 0 0; font-size: 1rem;">Complete your purchase</p>
</div>

<!-- Checkout Section -->
<section class="section-padding" style="background: white;">
    <div class="container">
        @if($errors->any())
        <div style="background: #F8D7DA; border-left: 4px solid #721C24; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            <strong style="color: #721C24;">Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 0; color: #721C24;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('order.store') }}" method="POST" id="checkoutForm">
            @csrf
            
            <div class="row g-4">
                <!-- Left Column - Checkout Form -->
                <div class="col-lg-8">
                    <!-- Order Type Selection -->
                    <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4" style="color: var(--primary-dark);">
                                <i class="fas fa-shopping-bag me-2"></i>Order Type
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="order-type-option" style="border: 2px solid #ddd; padding: 15px; border-radius: 8px; cursor: pointer; text-align: center; transition: all 0.3s;" onclick="selectOrderType('cloth_only', this)">
                                        <input type="radio" name="order_type" value="cloth_only" id="orderType1" class="order-type-input" style="display: none;">
                                        <p style="margin: 0 0 10px 0; color: var(--primary-dark); font-weight: 600;">Cloth Only</p>
                                        <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">Just purchase the fabric</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="order-type-option" style="border: 2px solid #ddd; padding: 15px; border-radius: 8px; cursor: pointer; text-align: center; transition: all 0.3s;" onclick="selectOrderType('cloth_stitching', this)">
                                        <input type="radio" name="order_type" value="cloth_stitching" id="orderType2" class="order-type-input" style="display: none;" checked>
                                        <p style="margin: 0 0 10px 0; color: var(--primary-dark); font-weight: 600;">Cloth + Stitching</p>
                                        <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">Fabric + custom tailoring</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4" style="color: var(--primary-dark);">
                                <i class="fas fa-user me-2"></i>Customer Information
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: var(--primary-dark);">
                                        Full Name <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="text" name="customer_name" class="form-control" 
                                           value="{{ old('customer_name', auth()->user()->name) }}"
                                           style="border-color: var(--accent-gold);" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: var(--primary-dark);">
                                        Phone Number <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="tel" name="customer_phone" class="form-control" 
                                           value="{{ old('customer_phone') }}"
                                           placeholder="10-digit phone number"
                                           style="border-color: var(--accent-gold);" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold" style="color: var(--primary-dark);">
                                        Delivery Address <span style="color: #dc3545;">*</span>
                                    </label>
                                    <textarea name="customer_address" class="form-control" rows="3"
                                              placeholder="Enter complete delivery address"
                                              style="border-color: var(--accent-gold);" required>{{ old('customer_address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Measurement Section (Conditional) -->
                    <div id="measurementSection" class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold); display: none;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4" style="color: var(--primary-dark);">
                                <i class="fas fa-ruler me-2"></i>Tailoring Measurements
                            </h5>

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">
                                    Use Saved Measurement (Optional)
                                </label>
                                <select name="measurement_id" class="form-select" id="savedMeasurements" style="border-color: var(--accent-gold);">
                                    <option value="" selected>-- Or enter new measurements below --</option>
                                    @foreach($measurements as $m)
                                    <option value="{{ $m->id }}" data-chest="{{ $m->chest }}" data-shoulder="{{ $m->shoulder }}" data-sleeve="{{ $m->sleeve_length }}" data-shirt="{{ $m->torso_length }}" data-neck="{{ $m->neck }}" data-waist="{{ $m->waist }}" data-trouser="{{ $m->inseam }}" data-bottom="{{ $m->bottom ?? '' }}">
                                        {{ $m->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="background: #F8F5EF; padding: 20px; border-radius: 10px; border: 2px solid var(--accent-gold);">
                                <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">
                                    Or Enter New Measurements (in cm)
                                </h6>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.9rem;">Chest</label>
                                        <input type="number" name="chest" class="form-control" step="0.5" style="border-color: var(--accent-gold);">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.9rem;">Shoulder</label>
                                        <input type="number" name="shoulder" class="form-control" step="0.5" style="border-color: var(--accent-gold);">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.9rem;">Sleeve Length</label>
                                        <input type="number" name="sleeve_length" class="form-control" step="0.5" style="border-color: var(--accent-gold);">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.9rem;">Shirt Length</label>
                                        <input type="number" name="shirt_length" class="form-control" step="0.5" style="border-color: var(--accent-gold);">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.9rem;">Neck</label>
                                        <input type="number" name="neck" class="form-control" step="0.5" style="border-color: var(--accent-gold);">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.9rem;">Waist</label>
                                        <input type="number" name="waist" class="form-control" step="0.5" style="border-color: var(--accent-gold);">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.9rem;">Trouser Length</label>
                                        <input type="number" name="trouser_length" class="form-control" step="0.5" style="border-color: var(--accent-gold);">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.9rem;">Bottom</label>
                                        <input type="number" name="bottom" class="form-control" step="0.5" style="border-color: var(--accent-gold);">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">
                                    Save Measurements As (Optional)
                                </label>
                                <input type="text" name="measurement_title" class="form-control" 
                                       placeholder="e.g., My Standard Measurements"
                                       style="border-color: var(--accent-gold);">
                            </div>
                        </div>
                    </div>


                    <!-- Special Notes -->
                    <div class="card border-0 shadow-sm mt-4" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4" style="color: var(--primary-dark);">
                                <i class="fas fa-sticky-note me-2"></i>Additional Notes (Optional)
                            </h5>

                            <textarea name="notes" class="form-control" rows="3"
                                      placeholder="Any special instructions or notes for your order..."
                                      style="border-color: var(--accent-gold);">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold); position: sticky; top: 20px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4" style="color: var(--primary-dark);">
                                <i class="fas fa-receipt me-2"></i>Order Summary
                            </h5>

                            <!-- Cart Items -->
                            <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                                @foreach($cartItems as $item)
                                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; font-size: 0.95rem;">
                                    <div>
                                        <p style="margin: 0; color: var(--primary-dark); font-weight: 500;">
                                            {{ $item['product']->name }}
                                        </p>
                                        <p style="margin: 3px 0 0 0; color: var(--text-muted); font-size: 0.85rem;">
                                            Qty: {{ $item['quantity'] }}
                                        </p>
                                    </div>
                                    <p style="margin: 0; color: var(--accent-gold); font-weight: 600;">
                                        Rs. {{ number_format($item['line_total'], 2) }}
                                    </p>
                                </div>
                                @endforeach
                            </div>

                            <!-- Pricing Summary -->
                            <div style="background: #F8F5EF; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span style="color: var(--text-muted);">Subtotal</span>
                                    <span style="font-weight: 600;">Rs. {{ number_format($subtotal, 2) }}</span>
                                </div>

                                <div id="stitchingChargeRow" style="display: none; margin-bottom: 10px;">
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: var(--text-muted);">Stitching Charge</span>
                                        <span id="stitchingAmount" style="font-weight: 600;">Rs. 0</span>
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span style="color: var(--text-muted);">Tax (5%)</span>
                                    <span style="font-weight: 600;">Rs. {{ number_format($tax, 2) }}</span>
                                </div>

                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: var(--text-muted);">Shipping</span>
                                    <span style="font-weight: 600;">Free</span>
                                </div>
                            </div>

                            <!-- Total -->
                            <div style="padding: 15px; border-top: 2px solid var(--accent-gold); border-bottom: 2px solid var(--accent-gold); margin-bottom: 20px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: var(--primary-dark); font-weight: 700;">Total Amount</span>
                                    <span id="totalAmount" style="color: var(--accent-gold); font-weight: 700; font-size: 1.3rem;">
                                        Rs. {{ number_format($total, 2) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100" style="background: var(--accent-gold); color: var(--primary-dark); padding: 15px; border: none; border-radius: 5px; font-weight: 600; font-size: 1.1rem; cursor: pointer;">
                                <i class="fas fa-check me-2"></i>Place Order
                            </button>

                            <!-- Terms -->
                            <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-top: 15px;">
                                By placing order, you agree to our
                                <a href="#" style="color: var(--accent-gold); text-decoration: none;">Terms & Conditions</a>
                            </p>

                            <!-- Info Box -->
                            <div style="background: #E8F4F8; padding: 12px; border-radius: 5px; margin-top: 15px; border-left: 4px solid #0288D1;">
                                <p style="color: #01579B; font-size: 0.85rem; margin: 0;">
                                    <i class="fas fa-shield-alt me-2"></i>Your order is secure and encrypted
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
    .order-type-option {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .order-type-option:hover {
        background-color: #f9f9f9;
    }

    .order-type-option.active {
        border-color: var(--accent-gold) !important;
        background-color: #FFF8E1;
    }

    .payment-option {
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--accent-gold);
        border-color: var(--accent-gold);
    }

    .form-check-input:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
    }

    @media (max-width: 768px) {
        .form-control {
            font-size: 16px; /* Prevents zoom on iOS */
        }
    }
</style>

@endsection

@section('scripts')
<script>
// Handle order type selection - MUST be outside DOMContentLoaded
function selectOrderType(type, element) {
    const orderType1 = document.getElementById('orderType1');
    const orderType2 = document.getElementById('orderType2');
    
    if (!orderType1 || !orderType2) return;
    
    orderType1.checked = false;
    orderType2.checked = false;

    if (type === 'cloth_only') {
        orderType1.checked = true;
    } else if (type === 'cloth_stitching') {
        orderType2.checked = true;
    }

    // Update order type options styling
    document.querySelectorAll('.order-type-option').forEach(opt => {
        opt.style.borderColor = '#ddd';
        opt.style.backgroundColor = 'white';
    });
    
    if (element) {
        element.style.borderColor = 'var(--accent-gold)';
        element.style.backgroundColor = '#fffbf0';
    }

    // Show/hide measurement section
    const measurementSection = document.getElementById('measurementSection');
    if (measurementSection && type !== 'cloth_only') {
        measurementSection.style.display = 'block';
    } else if (measurementSection) {
        measurementSection.style.display = 'none';
    }

    // Update total
    updateTotal(type);
}

// Update total based on order type
function updateTotal(orderType) {
    const subtotal = {{ $subtotal }};
    const tax = {{ $tax }};
    let stitchingCharge = 0;

    if (orderType === 'cloth_stitching') {
        stitchingCharge = 1500;
    }

    const total = subtotal + stitchingCharge + (orderType === 'cloth_only' ? tax : Math.round((subtotal + stitchingCharge) * 0.05));

    // Update display
    const stitchingRow = document.getElementById('stitchingChargeRow');
    const stitchingAmount = document.getElementById('stitchingAmount');
    const totalAmount = document.getElementById('totalAmount');

    if (stitchingCharge > 0 && stitchingRow) {
        stitchingRow.style.display = 'block';
        stitchingAmount.textContent = 'Rs. ' + stitchingCharge.toLocaleString('en-IN');
    } else if (stitchingRow) {
        stitchingRow.style.display = 'none';
    }

    if (totalAmount) {
        totalAmount.textContent = 'Rs. ' + total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Handle form inputs
    document.querySelectorAll('input[name="order_type"]').forEach(input => {
        input.addEventListener('change', function() {
            const options = document.querySelectorAll('.order-type-option');
            const index = Array.from(document.querySelectorAll('.order-type-input')).indexOf(this);
            selectOrderType(this.value, options[index]);
        });
    });

    // Handle saved measurement selection
    const savedMeasurementsSelect = document.getElementById('savedMeasurements');
    if (savedMeasurementsSelect) {
        savedMeasurementsSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                // Get measurement data from data attributes
                const measurements = {
                    chest: selectedOption.dataset.chest,
                    shoulder: selectedOption.dataset.shoulder,
                    sleeve_length: selectedOption.dataset.sleeve,
                    shirt_length: selectedOption.dataset.shirt,
                    neck: selectedOption.dataset.neck,
                    waist: selectedOption.dataset.waist,
                    trouser_length: selectedOption.dataset.trouser,
                    bottom: selectedOption.dataset.bottom
                };
                
                // Populate and disable input fields
                Object.entries(measurements).forEach(([name, value]) => {
                    const input = document.querySelector(`input[name="${name}"]`);
                    if (input) {
                        input.value = value || '';
                        input.disabled = true;
                        input.style.backgroundColor = '#e8e8e8';
                    }
                });
            } else {
                // Clear and enable new measurement inputs
                document.querySelectorAll('input[name="chest"], input[name="shoulder"], input[name="sleeve_length"], input[name="shirt_length"], input[name="neck"], input[name="waist"], input[name="trouser_length"], input[name="bottom"]').forEach(input => {
                    input.disabled = false;
                    input.value = '';
                    input.style.backgroundColor = '';
                });
            }
        });
    }

    // Initialize with Cloth + Stitching selected
    const options = document.querySelectorAll('.order-type-option');
    if (options.length > 1) {
        selectOrderType('cloth_stitching', options[1]);
    }

    // Form validation
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const orderType = document.querySelector('input[name="order_type"]:checked');
            if (!orderType) {
                e.preventDefault();
                alert('Please select an order type');
                return false;
            }
            
            // Allow form submission - measurements can be added to order later
            return true;
        });
    }
});
</script>
@endsection
