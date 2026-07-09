@extends('frontend.layouts.app')

@section('title', 'Contact Us - Clothes Store')

@section('content')

<!-- Page Header -->
<div style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white; padding: 60px 0; text-align: center; border-bottom: 3px solid var(--accent-gold);">
    <h1 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif;">Contact Us</h1>
    <p>We'd love to hear from you</p>
</div>

<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="row g-5 mb-5">
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <i class="fas fa-phone" style="font-size: 2rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                        <h5 class="fw-bold" style="color: var(--primary-dark);">Phone</h5>
                        <p><a href="tel:+91-XXXXXXXXXX" style="text-decoration: none; color: var(--primary-dark);">+91-XXXXXXXXXX</a></p>
                        <p class="text-muted small">Mon - Fri: 10 AM - 6 PM</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <i class="fas fa-envelope" style="font-size: 2rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                        <h5 class="fw-bold" style="color: var(--primary-dark);">Email</h5>
                        <p><a href="mailto:info@clothes.com" style="text-decoration: none; color: var(--primary-dark);">info@clothes.com</a></p>
                        <p class="text-muted small">Response time: 2-4 hours</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4">
                        <i class="fas fa-map-marker-alt" style="font-size: 2rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                        <h5 class="fw-bold" style="color: var(--primary-dark);">Address</h5>
                        <p>123 Fashion Street<br>City Center<br>Postal Code 123456</p>
                        <p class="text-muted small">Mon - Sun: 10 AM - 9 PM</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-5">
                        <h4 class="fw-bold mb-4" style="color: var(--primary-dark);">Send us a Message</h4>
                        <form>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: var(--primary-dark);">Full Name</label>
                                    <input type="text" class="form-control" style="border-color: var(--accent-gold);" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: var(--primary-dark);">Email</label>
                                    <input type="email" class="form-control" style="border-color: var(--accent-gold);" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">Phone</label>
                                <input type="tel" class="form-control" style="border-color: var(--accent-gold);" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">Subject</label>
                                <select class="form-select" style="border-color: var(--accent-gold);" required>
                                    <option>Select Subject</option>
                                    <option>Product Inquiry</option>
                                    <option>Tailoring Service</option>
                                    <option>Order Status</option>
                                    <option>Feedback</option>
                                    <option>Other</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">Message</label>
                                <textarea class="form-control" rows="5" style="border-color: var(--accent-gold);" required></textarea>
                            </div>

                            <button type="submit" class="btn-premium" style="width: 100%; padding: 12px;">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store Locations -->
        <div class="mt-5">
            <h2 class="text-center fw-bold mb-5" style="color: var(--primary-dark);">Our Locations</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">Main Store</h5>
                            <p class="text-muted">123 Fashion Street, City Center</p>
                            <p class="text-muted">Phone: +91-XXXXXXXXXX</p>
                            <p class="text-muted">Mon - Sun: 10 AM - 9 PM</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">East Wing</h5>
                            <p class="text-muted">456 Fashion Avenue, East District</p>
                            <p class="text-muted">Phone: +91-YYYYYYYYYY</p>
                            <p class="text-muted">Mon - Sun: 11 AM - 8 PM</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">West Wing</h5>
                            <p class="text-muted">789 Style Boulevard, West Side</p>
                            <p class="text-muted">Phone: +91-ZZZZZZZZZZ</p>
                            <p class="text-muted">Mon - Sun: 10 AM - 9 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
