@extends('frontend.layouts.app')

@section('title', 'Custom Tailoring Services - Clothes Store')

@section('content')

<!-- Page Header -->
<div style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%); color: white; padding: 60px 0; text-align: center; border-bottom: 3px solid var(--accent-gold);">
    <h1 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif;">Custom Tailoring Services</h1>
    <p>Professional stitching with expert craftsmanship</p>
</div>

<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Cloth + Stitching -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg h-100" style="border-top: 3px solid var(--accent-gold);">
                    <div style="height: 300px; background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--primary-dark) 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-ruler-combined" style="font-size: 5rem; color: var(--accent-gold);"></i>
                    </div>
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3" style="color: var(--primary-dark);">Cloth + Stitching</h3>
                        <p class="text-muted mb-3">
                            Choose from our premium collection of fabrics and let our expert tailors create a perfectly fitted garment just for you.
                        </p>
                        
                        <h6 class="fw-bold mt-4 mb-2" style="color: var(--primary-dark);">What's Included:</h6>
                        <ul style="margin-bottom: 2rem;">
                            <li>Premium fabric selection</li>
                            <li>Expert design consultation</li>
                            <li>Precise body measurements</li>
                            <li>Custom stitching</li>
                            <li>Multiple fitting rounds</li>
                            <li>Free minor alterations (30 days)</li>
                        </ul>

                        <h6 class="fw-bold mb-2" style="color: var(--primary-dark);">Turnaround Time:</h6>
                        <p class="mb-3">5-7 business days (expedited available)</p>

                        <h6 class="fw-bold mb-2" style="color: var(--primary-dark);">Pricing:</h6>
                        <p class="mb-4" style="font-size: 1.2rem; color: var(--accent-gold); font-weight: 600;">
                            <strong>Starting from Rs. 500</strong> + Fabric Cost
                        </p>

                        <button class="btn w-100" style="background: var(--accent-gold); color: var(--primary-dark); font-weight: 600; padding: 12px; border: none; border-radius: 5px; transition: all 0.3s ease;" onmouseover="this.style.background='white'; this.style.color='var(--accent-gold)'; this.style.border='2px solid var(--accent-gold)';" onmouseout="this.style.background='var(--accent-gold)'; this.style.color='var(--primary-dark)'; this.style.border='none';">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stitching Only -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg h-100" style="border-top: 3px solid var(--accent-gold);">
                    <div style="height: 300px; background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--primary-dark) 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-scissors" style="font-size: 5rem; color: var(--accent-gold);"></i>
                    </div>
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3" style="color: var(--primary-dark);">Stitching Only</h3>
                        <p class="text-muted mb-3">
                            Have your own fabric? Bring it to us and our master tailors will create stunning custom garments with precision.
                        </p>
                        
                        <h6 class="fw-bold mt-4 mb-2" style="color: var(--primary-dark);">What's Included:</h6>
                        <ul style="margin-bottom: 2rem;">
                            <li>Design consultation</li>
                            <li>Custom measurements</li>
                            <li>Expert stitching</li>
                            <li>Fitting adjustments</li>
                            <li>Multiple fittings included</li>
                            <li>Quality guarantee</li>
                        </ul>

                        <h6 class="fw-bold mb-2" style="color: var(--primary-dark);">Turnaround Time:</h6>
                        <p class="mb-3">3-5 business days</p>

                        <h6 class="fw-bold mb-2" style="color: var(--primary-dark);">Pricing:</h6>
                        <p class="mb-4" style="font-size: 1.2rem; color: var(--accent-gold); font-weight: 600;">
                            <strong>Starting from Rs. 300</strong>
                        </p>

                        <button class="btn w-100" style="background: var(--accent-gold); color: var(--primary-dark); font-weight: 600; padding: 12px; border: none; border-radius: 5px; transition: all 0.3s ease;" onmouseover="this.style.background='white'; this.style.color='var(--accent-gold)'; this.style.border='2px solid var(--accent-gold)';" onmouseout="this.style.background='var(--accent-gold)'; this.style.color='var(--primary-dark)'; this.style.border='none';">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Process -->
        <div class="mt-5">
            <h2 class="text-center fw-bold mb-5" style="color: var(--primary-dark);">Our Tailoring Process</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 text-center">
                    <div style="width: 80px; height: 80px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; margin: 0 auto 1rem; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);">1</div>
                    <h5 class="fw-bold" style="color: var(--primary-dark);">Consultation</h5>
                    <p class="text-muted">Discuss your style preferences and design ideas with our experts.</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div style="width: 80px; height: 80px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; margin: 0 auto 1rem; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);">2</div>
                    <h5 class="fw-bold" style="color: var(--primary-dark);">Measurements</h5>
                    <p class="text-muted">Precise body measurements taken with professional accuracy.</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div style="width: 80px; height: 80px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; margin: 0 auto 1rem; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);">3</div>
                    <h5 class="fw-bold" style="color: var(--primary-dark);">Stitching</h5>
                    <p class="text-muted">Expert tailors begin the meticulous stitching process.</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div style="width: 80px; height: 80px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; margin: 0 auto 1rem; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);">4</div>
                    <h5 class="fw-bold" style="color: var(--primary-dark);">Fitting</h5>
                    <p class="text-muted">Multiple fittings and adjustments for perfect fit.</p>
                </div>
            </div>
        </div>

        <!-- Garment Types -->
        <div class="mt-5">
            <h2 class="text-center fw-bold mb-5" style="color: var(--primary-dark);">Garment Types</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 text-center" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <i class="fas fa-shirt" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                            <h5 class="fw-bold" style="color: var(--primary-dark);">Formal Shirts</h5>
                            <p class="text-muted">Custom formal and casual shirts tailored to perfection.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 text-center" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <i class="fas fa-person" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                            <h5 class="fw-bold" style="color: var(--primary-dark);">Trousers & Pants</h5>
                            <p class="text-muted">Perfectly fitted pants from formal to casual styles.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 text-center" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <i class="fas fa-crown" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                            <h5 class="fw-bold" style="color: var(--primary-dark);">Ethnic Wear</h5>
                            <p class="text-muted">Traditional kurtas, sherwanis, and ethnic clothing.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 text-center" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <i class="fas fa-tuxedo" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                            <h5 class="fw-bold" style="color: var(--primary-dark);">Suits & Blazers</h5>
                            <p class="text-muted">Premium suits and blazers for corporate occasions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 text-center" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <i class="fas fa-vest" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                            <h5 class="fw-bold" style="color: var(--primary-dark);">Waistcoats</h5>
                            <p class="text-muted">Elegant waistcoats for formal events.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 text-center" style="border-top: 3px solid var(--accent-gold);">
                        <div class="card-body p-4">
                            <i class="fas fa-star" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                            <h5 class="fw-bold" style="color: var(--primary-dark);">Custom Designs</h5>
                            <p class="text-muted">Bring your unique design ideas to life.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="mt-5">
            <h2 class="text-center fw-bold mb-5" style="color: var(--primary-dark);">Frequently Asked Questions</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item" style="border: 1px solid var(--accent-gold);">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="background: transparent; color: var(--primary-dark); font-weight: 600;">
                            How do I book a tailoring appointment?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can book an appointment by clicking the "Book Now" button or contacting us directly at +91-XXXXXXXXXX or info@clothes.com. We'll schedule a convenient time for your consultation and measurements.
                        </div>
                    </div>
                </div>
                <div class="accordion-item" style="border: 1px solid var(--accent-gold);">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="background: transparent; color: var(--primary-dark); font-weight: 600;">
                            What is the average turnaround time?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Standard orders take 5-7 business days from the time of final measurements. For stitching only, it's 3-5 days. We also offer expedited services for urgent orders.
                        </div>
                    </div>
                </div>
                <div class="accordion-item" style="border: 1px solid var(--accent-gold);">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="background: transparent; color: var(--primary-dark); font-weight: 600;">
                            Can I make alterations after delivery?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! We offer free minor alterations within 30 days of delivery. Major alterations may have additional charges. Contact us for details.
                        </div>
                    </div>
                </div>
                <div class="accordion-item" style="border: 1px solid var(--accent-gold);">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="background: transparent; color: var(--primary-dark); font-weight: 600;">
                            What if I'm not satisfied with the fit?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We have a satisfaction guarantee. If you're not happy with the fit, we'll make adjustments at no additional cost within 30 days.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
