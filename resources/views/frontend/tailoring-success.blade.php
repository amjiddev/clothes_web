@extends('frontend.layouts.app')

@section('title', 'Tailoring Request Submitted - Thank You')

@section('content')

<!-- Success Section -->
<section class="section-padding" style="background: linear-gradient(135deg, #F8F5EF 0%, white 100%); min-height: 500px; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <!-- Success Card -->
                <div class="card border-0 shadow-lg" style="border-top: 5px solid var(--accent-gold); text-align: center;">
                    <div class="card-body p-5">
                        <!-- Success Icon -->
                        <div style="width: 80px; height: 80px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 2rem;">
                            <i class="fas fa-check"></i>
                        </div>

                        <!-- Success Message -->
                        <h2 class="fw-bold mb-3" style="color: var(--primary-dark); font-family: 'Playfair Display', serif; font-size: 2rem;">
                            Request Submitted Successfully!
                        </h2>

                        <p class="text-muted mb-4" style="font-size: 1.1rem;">
                            Thank you for choosing our tailoring service. Your custom stitching request has been received and will be processed shortly.
                        </p>

                        <!-- Details Box -->
                        <div style="background: #F8F5EF; padding: 20px; border-radius: 10px; border-left: 4px solid var(--accent-gold); text-align: left; margin-bottom: 2rem;">
                            <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">
                                <i class="fas fa-info-circle me-2"></i>What Happens Next:
                            </h6>
                            <ol style="color: var(--text-muted); margin-bottom: 0;">
                                <li>Our team will review your measurements and specifications</li>
                                <li>You'll receive a confirmation email with your order details</li>
                                <li>We'll contact you within 24 hours to confirm fabric selection</li>
                                <li>Stitching will begin once fabric is finalized</li>
                                <li>We'll schedule fitting appointments as needed</li>
                                <li>Your garment will be ready in 5-7 business days</li>
                            </ol>
                        </div>

                        <!-- Timeline -->
                        <div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 2rem; border: 2px solid var(--accent-gold);">
                            <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">
                                <i class="fas fa-clock me-2"></i>Estimated Timeline:
                            </h6>
                            <div style="display: flex; justify-content: space-around; align-items: center;">
                                <div style="text-align: center;">
                                    <div style="width: 50px; height: 50px; background: var(--accent-gold); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; margin: 0 auto 10px;">1</div>
                                    <small style="color: var(--text-muted);">Consultation</small>
                                </div>
                                <div style="flex-grow: 1; height: 3px; background: var(--accent-gold); margin: 0 10px;"></div>
                                <div style="text-align: center;">
                                    <div style="width: 50px; height: 50px; background: var(--accent-gold); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; margin: 0 auto 10px;">2-3</div>
                                    <small style="color: var(--text-muted);">Days</small>
                                </div>
                                <div style="flex-grow: 1; height: 3px; background: var(--accent-gold); margin: 0 10px;"></div>
                                <div style="text-align: center;">
                                    <div style="width: 50px; height: 50px; background: var(--accent-gold); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; margin: 0 auto 10px;">5-7</div>
                                    <small style="color: var(--text-muted);">Ready</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 10px; flex-direction: column;">
                            <a href="{{ route('home') }}" class="btn w-100" style="background: var(--accent-gold); color: var(--primary-dark); font-weight: 600; padding: 15px; text-decoration: none; border-radius: 5px;">
                                <i class="fas fa-home me-2"></i>Back to Home
                            </a>
                            <a href="{{ route('shop') }}" class="btn w-100" style="background: transparent; color: var(--primary-dark); font-weight: 600; padding: 15px; text-decoration: none; border: 2px solid var(--primary-dark); border-radius: 5px;">
                                <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                            </a>
                        </div>

                        <!-- Contact Info -->
                        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #ddd;">
                            <p class="text-muted mb-2">
                                <strong>Need help?</strong> Contact our tailoring team
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-phone me-2" style="color: var(--accent-gold);"></i>
                                <a href="tel:+91XXXXXXXXXX" style="color: var(--accent-gold); text-decoration: none; font-weight: 600;">+91-XXXXXXXXXX</a>
                            </p>
                            <p>
                                <i class="fas fa-envelope me-2" style="color: var(--accent-gold);"></i>
                                <a href="mailto:tailoring@clothes.com" style="color: var(--accent-gold); text-decoration: none; font-weight: 600;">tailoring@clothes.com</a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="mt-5">
                    <h4 class="fw-bold mb-4" style="color: var(--primary-dark); text-align: center; border-bottom: 3px solid var(--accent-gold); padding-bottom: 15px;">
                        <i class="fas fa-question-circle me-2"></i>Frequently Asked Questions
                    </h4>

                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item" style="border: 1px solid var(--accent-gold);">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="background: transparent; color: var(--primary-dark); font-weight: 600;">
                                    How will I be contacted?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: var(--text-muted);">
                                    We'll contact you via the phone number and email provided in your request. You can also check your account for updates on your tailoring order status.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item" style="border: 1px solid var(--accent-gold);">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="background: transparent; color: var(--primary-dark); font-weight: 600;">
                                    Can I modify my measurements?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: var(--text-muted);">
                                    Yes, you can modify your measurements or special instructions within 24 hours of submitting your request. Please contact us as soon as possible with any changes.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item" style="border: 1px solid var(--accent-gold);">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="background: transparent; color: var(--primary-dark); font-weight: 600;">
                                    What if I'm not satisfied with the fit?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: var(--text-muted);">
                                    We offer free alterations within 30 days of delivery. If you're not happy with the fit, we'll make adjustments at no additional cost until you're satisfied.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item" style="border: 1px solid var(--accent-gold);">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="background: transparent; color: var(--primary-dark); font-weight: 600;">
                                    Can I schedule multiple fittings?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: var(--text-muted);">
                                    Absolutely! We typically schedule 1-2 fittings depending on the complexity of your garment. You can request additional fittings if needed.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Next Steps Section -->
<section class="section-padding" style="background: white;">
    <div class="container">
        <h3 class="fw-bold mb-5 text-center" style="color: var(--primary-dark); border-bottom: 3px solid var(--accent-gold); padding-bottom: 15px;">
            <i class="fas fa-tasks me-2"></i>Your Next Steps
        </h3>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4 text-center">
                        <div style="width: 60px; height: 60px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.5rem; margin: 0 auto 1rem;">1</div>
                        <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">Check Your Email</h5>
                        <p class="text-muted">Look for our confirmation email with your order details and next steps.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4 text-center">
                        <div style="width: 60px; height: 60px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.5rem; margin: 0 auto 1rem;">2</div>
                        <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">Confirm Fabric</h5>
                        <p class="text-muted">Our team will contact you to finalize fabric selection and color preferences.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                    <div class="card-body p-4 text-center">
                        <div style="width: 60px; height: 60px; background: var(--accent-gold); color: var(--primary-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.5rem; margin: 0 auto 1rem;">3</div>
                        <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">Schedule Fittings</h5>
                        <p class="text-muted">We'll schedule fitting appointments convenient for you during the process.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
