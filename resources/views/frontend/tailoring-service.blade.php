@extends('frontend.layouts.app')

@section('title', 'Tailoring Service - Custom Stitching Request')

@section('content')

<!-- Page Header -->
<div style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white; padding: 60px 0; text-align: center; border-bottom: 3px solid var(--accent-gold);">
    <h1 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif;">Custom Tailoring Service</h1>
    <p>Professional Men's Custom Stitching Service</p>
</div>

<section class="section-padding" style="background: white;">
    <div class="container">
        <!-- Authentication Check -->
        @if(!auth()->check())
        <div style="background: #FFF3CD; border-left: 4px solid var(--accent-gold); padding: 20px; margin-bottom: 30px; border-radius: 5px;">
            <p style="color: #856404; margin: 0;">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Please login to request tailoring service.</strong> 
                <a href="{{ route('login') }}" style="color: var(--accent-gold); font-weight: 600;">Login here</a>
            </p>
        </div>
        @endif

        <div class="row">
            <!-- Service Options -->
            <div class="col-lg-4 mb-5">
                <h3 class="fw-bold mb-4" style="color: var(--primary-dark); border-bottom: 3px solid var(--accent-gold); padding-bottom: 15px;">
                    <i class="fas fa-check-circle me-2"></i>Service Options
                </h3>

                <!-- Option 1: Cloth Purchase Only -->
                <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold); cursor: pointer; transition: all 0.3s ease;" onclick="selectOption('cloth_only', this)">
                    <div class="card-body p-4">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <h5 class="fw-bold" style="color: var(--primary-dark);">Cloth Only</h5>
                            <input type="radio" name="service_option" value="cloth_only" style="margin-top: 5px;">
                        </div>
                        <p class="text-muted mb-3">Browse our premium fabric collection</p>
                        <ul style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0;">
                            <li>Premium fabrics</li>
                            <li>Expert consultation</li>
                            <li>Delivery included</li>
                            <li><strong style="color: var(--accent-gold);">Starting from Rs. 500</strong></li>
                        </ul>
                    </div>
                </div>

                <!-- Option 2: Cloth + Stitching -->
                <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold); cursor: pointer; transition: all 0.3s ease; background: #F8F5EF;" onclick="selectOption('cloth_stitching', this)">
                    <div class="card-body p-4">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <h5 class="fw-bold" style="color: var(--primary-dark);">Cloth + Stitching</h5>
                            <input type="radio" name="service_option" value="cloth_stitching" style="margin-top: 5px;" checked>
                        </div>
                        <p class="text-muted mb-3">Perfect for complete custom garments</p>
                        <ul style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0;">
                            <li>Fabric selection</li>
                            <li>Custom stitching</li>
                            <li>Multiple fittings</li>
                            <li><strong style="color: var(--accent-gold);">Starting from Rs. 1,500</strong></li>
                        </ul>
                    </div>
                </div>

                <!-- Option 3: Stitching Only -->
                <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold); cursor: pointer; transition: all 0.3s ease;" onclick="selectOption('stitching_only', this)">
                    <div class="card-body p-4">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <h5 class="fw-bold" style="color: var(--primary-dark);">Stitching Only</h5>
                            <input type="radio" name="service_option" value="stitching_only" style="margin-top: 5px;">
                        </div>
                        <p class="text-muted mb-3">Have your own fabric? We'll stitch it!</p>
                        <ul style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0;">
                            <li>Expert stitching</li>
                            <li>Precise measurements</li>
                            <li>Quality guarantee</li>
                            <li><strong style="color: var(--accent-gold);">Starting from Rs. 300</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Measurement Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-5">
                        <h4 class="fw-bold mb-4" style="color: var(--primary-dark);">
                            <i class="fas fa-ruler me-2"></i>Body Measurements
                        </h4>

                        @if ($errors->any())
                        <div style="background: #F8D7DA; border-left: 4px solid #721C24; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                            <strong style="color: #721C24;">Please fix the following errors:</strong>
                            <ul style="margin: 10px 0 0 0; color: #721C24;">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('tailoring.store') }}" method="POST" enctype="multipart/form-data" id="tailoringForm">
                            @csrf

                            <!-- Service Option (Hidden) -->
                            <input type="hidden" name="service_option" id="serviceOption" value="cloth_stitching">

                            <!-- Measurement Title -->
                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">
                                    Save Measurements As
                                </label>
                                <input 
                                    type="text" 
                                    name="measurement_title" 
                                    class="form-control" 
                                    placeholder="e.g., My Standard Shirt"
                                    style="border-color: var(--accent-gold);"
                                >
                            </div>

                            <!-- Load Saved Measurements -->
                            @if($userMeasurements->count() > 0)
                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">
                                    <i class="fas fa-history me-2"></i>Load Saved Measurements
                                </label>
                                <select class="form-select" id="savedMeasurements" style="border-color: var(--accent-gold);">
                                    <option value="">Select a saved measurement...</option>
                                    @foreach($userMeasurements as $m)
                                    <option value="{{ $m->id }}">{{ $m->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Garment Type -->
                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">
                                    Garment Type <span style="color: #dc3545;">*</span>
                                </label>
                                <select 
                                    name="garment_type" 
                                    class="form-select"
                                    style="border-color: var(--accent-gold);"
                                    required
                                >
                                    <option value="">Select garment type...</option>
                                    <option value="Shirt">Formal Shirt</option>
                                    <option value="Kurta">Kurta</option>
                                    <option value="Trousers">Trousers / Pants</option>
                                    <option value="Suit">3-Piece Suit</option>
                                    <option value="Blazer">Blazer</option>
                                    <option value="Waistcoat">Waistcoat</option>
                                    <option value="Sherwani">Sherwani</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Measurements Section -->
                            <div style="background: #F8F5EF; padding: 20px; border-radius: 10px; margin-bottom: 20px; border: 2px solid var(--accent-gold);">
                                <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">
                                    <i class="fas fa-measure-tape me-2"></i>Enter Your Measurements (in cm)
                                </h6>

                                <div class="row g-3">
                                    <!-- Chest -->
                                    <div class="col-md-6">
                                        <label class="form-label" style="color: var(--primary-dark); font-size: 0.9rem;">
                                            Chest <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="chest" 
                                            class="form-control"
                                            placeholder="Chest circumference"
                                            value="{{ old('chest') }}"
                                            step="0.5"
                                            style="border-color: var(--accent-gold);"
                                            required
                                        >
                                    </div>

                                    <!-- Shoulder -->
                                    <div class="col-md-6">
                                        <label class="form-label" style="color: var(--primary-dark); font-size: 0.9rem;">
                                            Shoulder <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="shoulder" 
                                            class="form-control"
                                            placeholder="Shoulder width"
                                            value="{{ old('shoulder') }}"
                                            step="0.5"
                                            style="border-color: var(--accent-gold);"
                                            required
                                        >
                                    </div>

                                    <!-- Sleeve Length -->
                                    <div class="col-md-6">
                                        <label class="form-label" style="color: var(--primary-dark); font-size: 0.9rem;">
                                            Sleeve Length <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="sleeve_length" 
                                            class="form-control"
                                            placeholder="Length from shoulder to wrist"
                                            value="{{ old('sleeve_length') }}"
                                            step="0.5"
                                            style="border-color: var(--accent-gold);"
                                            required
                                        >
                                    </div>

                                    <!-- Shirt Length -->
                                    <div class="col-md-6">
                                        <label class="form-label" style="color: var(--primary-dark); font-size: 0.9rem;">
                                            Shirt Length <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="shirt_length" 
                                            class="form-control"
                                            placeholder="Length from shoulder to hem"
                                            value="{{ old('shirt_length') }}"
                                            step="0.5"
                                            style="border-color: var(--accent-gold);"
                                            required
                                        >
                                    </div>

                                    <!-- Neck -->
                                    <div class="col-md-6">
                                        <label class="form-label" style="color: var(--primary-dark); font-size: 0.9rem;">
                                            Neck <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="neck" 
                                            class="form-control"
                                            placeholder="Neck circumference"
                                            value="{{ old('neck') }}"
                                            step="0.5"
                                            style="border-color: var(--accent-gold);"
                                            required
                                        >
                                    </div>

                                    <!-- Waist -->
                                    <div class="col-md-6">
                                        <label class="form-label" style="color: var(--primary-dark); font-size: 0.9rem;">
                                            Waist <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="waist" 
                                            class="form-control"
                                            placeholder="Waist circumference"
                                            value="{{ old('waist') }}"
                                            step="0.5"
                                            style="border-color: var(--accent-gold);"
                                            required
                                        >
                                    </div>

                                    <!-- Trouser Length -->
                                    <div class="col-md-6">
                                        <label class="form-label" style="color: var(--primary-dark); font-size: 0.9rem;">
                                            Trouser Length <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="trouser_length" 
                                            class="form-control"
                                            placeholder="Inseam length"
                                            value="{{ old('trouser_length') }}"
                                            step="0.5"
                                            style="border-color: var(--accent-gold);"
                                            required
                                        >
                                    </div>

                                    <!-- Bottom -->
                                    <div class="col-md-6">
                                        <label class="form-label" style="color: var(--primary-dark); font-size: 0.9rem;">
                                            Bottom (Pant Width) <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="bottom" 
                                            class="form-control"
                                            placeholder="Bottom opening width"
                                            value="{{ old('bottom') }}"
                                            step="0.5"
                                            style="border-color: var(--accent-gold);"
                                            required
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Special Instructions -->
                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">
                                    Special Instructions
                                </label>
                                <textarea 
                                    name="special_instructions" 
                                    class="form-control" 
                                    rows="3"
                                    placeholder="e.g., Loose fit, button style, fabric preferences, etc."
                                    style="border-color: var(--accent-gold);"
                                >{{ old('special_instructions') }}</textarea>
                            </div>

                            <!-- Design Image Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">
                                    <i class="fas fa-image me-2"></i>Design Image (Optional)
                                </label>
                                <input 
                                    type="file" 
                                    name="design_image" 
                                    class="form-control"
                                    accept="image/*"
                                    style="border-color: var(--accent-gold);"
                                >
                                <small class="text-muted" style="display: block; margin-top: 5px;">
                                    Upload a design reference image (JPEG, PNG, max 5MB)
                                </small>
                            </div>

                            @if(!auth()->check())
                            <div style="background: #D1ECF1; border-left: 4px solid #0C5460; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                                <p style="color: #0C5460; margin: 0;">
                                    <i class="fas fa-lock me-2"></i>
                                    <strong>Please log in to submit your tailoring request.</strong>
                                </p>
                            </div>
                            @endif

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="btn w-100"
                                style="background: var(--accent-gold); color: var(--primary-dark); font-weight: 600; padding: 15px; border: none; border-radius: 5px; font-size: 1.1rem;"
                                @if(!auth()->check()) disabled @endif
                            >
                                <i class="fas fa-check me-2"></i>Submit Tailoring Request
                            </button>

                            @if(!auth()->check())
                            <p class="text-center text-muted mt-3">
                                <a href="{{ route('login') }}" style="color: var(--accent-gold); font-weight: 600;">Login</a> or 
                                <a href="{{ route('register') }}" style="color: var(--accent-gold); font-weight: 600;">Register</a> to continue
                            </p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="row mt-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-left: 4px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">
                            <i class="fas fa-box me-2"></i>Cloth + Stitching
                        </h6>
                        <ul style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0;">
                            <li>✓ Premium fabric selection</li>
                            <li>✓ Custom stitching</li>
                            <li>✓ Multiple fittings</li>
                            <li>✓ Free alterations (30 days)</li>
                            <li style="margin-top: 10px; border-top: 1px solid #ddd; padding-top: 10px; font-weight: 600; color: var(--accent-gold);">
                                Starting from Rs. 1,500
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-left: 4px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">
                            <i class="fas fa-scissors me-2"></i>Stitching Only
                        </h6>
                        <ul style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0;">
                            <li>✓ Bring your fabric</li>
                            <li>✓ Expert stitching</li>
                            <li>✓ Precise fitting</li>
                            <li>✓ Quality guarantee</li>
                            <li style="margin-top: 10px; border-top: 1px solid #ddd; padding-top: 10px; font-weight: 600; color: var(--accent-gold);">
                                Starting from Rs. 300
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-left: 4px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">
                            <i class="fas fa-info-circle me-2"></i>Process Timeline
                        </h6>
                        <ul style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0;">
                            <li><strong>Day 1:</strong> Consultation</li>
                            <li><strong>Day 2-3:</strong> Measurements</li>
                            <li><strong>Day 5-7:</strong> Stitching</li>
                            <li><strong>Day 8:</strong> First fitting</li>
                            <li style="margin-top: 10px; border-top: 1px solid #ddd; padding-top: 10px; font-weight: 600; color: var(--accent-gold);">
                                5-7 Business Days
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .card:hover {
        border-color: var(--accent-gold);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.2) !important;
    }

    input[type="radio"]:checked + span,
    .card:has(> input[type="radio"]:checked) {
        border-color: var(--accent-gold);
    }

    @media (max-width: 768px) {
        .form-control, .form-select {
            font-size: 16px; /* Prevents zoom on iOS */
        }
    }
</style>

@endsection

@section('scripts')
<script>
function selectOption(option, element) {
    // Update hidden input
    document.getElementById('serviceOption').value = option;
    
    // Update radio button
    document.querySelector(`input[value="${option}"]`).checked = true;
    
    // Update card styling
    document.querySelectorAll('[onclick^="selectOption"]').forEach(el => {
        el.style.background = '';
        el.style.borderColor = 'var(--accent-gold)';
    });
    
    // Highlight selected
    element.style.background = '#F8F5EF';
    element.style.borderColor = 'var(--accent-gold)';
}

// Load saved measurement
document.addEventListener('DOMContentLoaded', function() {
    const savedMeasurementsSelect = document.getElementById('savedMeasurements');
    
    if (savedMeasurementsSelect) {
        savedMeasurementsSelect.addEventListener('change', function() {
            if (this.value) {
                fetch(`/api/measurement/${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('input[name="chest"]').value = data.chest || '';
                        document.querySelector('input[name="shoulder"]').value = data.shoulder || '';
                        document.querySelector('input[name="sleeve_length"]').value = data.sleeve_length || '';
                        document.querySelector('input[name="shirt_length"]').value = data.shirt_length || '';
                        document.querySelector('input[name="neck"]').value = data.neck || '';
                        document.querySelector('input[name="waist"]').value = data.waist || '';
                        document.querySelector('input[name="trouser_length"]').value = data.trouser_length || '';
                        document.querySelector('input[name="bottom"]').value = data.bottom || '';
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    }

    // Set initial selection
    const serviceOption = document.querySelector('input[name="service_option"]:checked');
    if (serviceOption) {
        const card = serviceOption.closest('.card');
        if (card) {
            card.style.background = '#F8F5EF';
        }
    }
});

// Form validation
document.getElementById('tailoringForm').addEventListener('submit', function(e) {
    const serviceOption = document.getElementById('serviceOption').value;
    if (!serviceOption) {
        e.preventDefault();
        alert('Please select a service option');
    }
});
</script>
@endsection
