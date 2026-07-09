@extends('frontend.layouts.app')

@section('title', 'About Us - Clothes Store')

@section('content')

<!-- Page Header -->
<div style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white; padding: 60px 0; text-align: center; border-bottom: 3px solid var(--accent-gold);">
    <h1 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif;">About Us</h1>
    <p>Your trusted partner in premium men's fashion</p>
</div>

<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="row g-5 align-items-center mb-5">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1556821552-7c82fb011277?w=500&h=500&fit=crop" alt="Our Store" class="img-fluid rounded" style="box-shadow: 0 20px 60px rgba(212, 175, 55, 0.4); border: 3px solid var(--accent-gold);">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--primary-dark);">Welcome to CLOTHES STORE</h2>
                <p class="text-muted mb-3">
                    Established in 2010, CLOTHES STORE has been the premier destination for premium men's fashion and custom tailoring for over a decade. What started as a small tailoring shop has grown into a full-service fashion powerhouse.
                </p>
                <p class="text-muted mb-3">
                    Our mission is simple: to provide exceptional quality clothing and tailoring services that help our customers look and feel their best. We believe that style is a reflection of personality, and every customer deserves garments that are tailored perfectly to their unique preferences and body shape.
                </p>
                <p class="text-muted">
                    With a team of master tailors, fashion experts, and dedicated customer service professionals, we're committed to delivering excellence in every aspect of our business.
                </p>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-4 mb-5" style="background: linear-gradient(135deg, #F8F5EF 0%, white 100%); padding: 50px; border-radius: 10px; border: 2px solid var(--accent-gold);">
            <div class="col-md-3 text-center">
                <h3 class="fw-bold" style="color: var(--accent-gold); font-size: 2.5rem;">10,000+</h3>
                <p class="text-muted" style="color: var(--primary-dark);">Satisfied Customers</p>
            </div>
            <div class="col-md-3 text-center">
                <h3 class="fw-bold" style="color: var(--accent-gold); font-size: 2.5rem;">50+</h3>
                <p class="text-muted" style="color: var(--primary-dark);">Expert Tailors</p>
            </div>
            <div class="col-md-3 text-center">
                <h3 class="fw-bold" style="color: var(--accent-gold); font-size: 2.5rem;">100%</h3>
                <p class="text-muted" style="color: var(--primary-dark);">Quality Guaranteed</p>
            </div>
            <div class="col-md-3 text-center">
                <h3 class="fw-bold" style="color: var(--accent-gold); font-size: 2.5rem;">15+</h3>
                <p class="text-muted" style="color: var(--primary-dark);">Years Experience</p>
            </div>
        </div>

        <!-- Values -->
        <div class="mt-5">
            <h2 class="text-center fw-bold mb-5" style="color: var(--primary-dark);">Our Values</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-heart" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                        <h5 class="fw-bold" style="color: var(--primary-dark);">Quality</h5>
                        <p class="text-muted">We never compromise on quality. Only the finest materials and craftsmanship.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-handshake" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                        <h5 class="fw-bold" style="color: var(--primary-dark);">Integrity</h5>
                        <p class="text-muted">Honest dealings, transparent pricing, and genuine customer relationships.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-star" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                        <h5 class="fw-bold" style="color: var(--primary-dark);">Excellence</h5>
                        <p class="text-muted">Striving for perfection in every garment and every customer interaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
