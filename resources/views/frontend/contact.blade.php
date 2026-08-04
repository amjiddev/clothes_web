@extends('frontend.layouts.app')

@section('title', 'Contact Us - Clothes Store')

@section('content')

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" style="background: #f8f9fa; padding: 15px 0; border-bottom: 1px solid #e0e0e0;">
    <div class="container">
        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: var(--primary-dark); text-decoration: none;"><i class="fas fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: var(--accent-gold); font-weight: 600;">Contact</li>
        </ol>
    </div>
</nav>

<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="row g-5 mb-5">
            <!-- Contact Info -->
            <div class="col-lg-4">
                @if($contactInfo && $contactInfo->data)
                    @if(!empty($contactInfo->data['phone']))
                        <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                            <div class="card-body p-4">
                                <i class="fas fa-phone" style="font-size: 2rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                                <h5 class="fw-bold" style="color: var(--primary-dark);">Phone</h5>
                                <p><a href="tel:{{ $contactInfo->data['phone'] }}" style="text-decoration: none; color: var(--primary-dark);">{{ $contactInfo->data['phone'] }}</a></p>
                            </div>
                        </div>
                    @endif

                    @if(!empty($contactInfo->data['email']))
                        <div class="card border-0 shadow-sm mb-4" style="border-top: 3px solid var(--accent-gold);">
                            <div class="card-body p-4">
                                <i class="fas fa-envelope" style="font-size: 2rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                                <h5 class="fw-bold" style="color: var(--primary-dark);">Email</h5>
                                <p><a href="mailto:{{ $contactInfo->data['email'] }}" style="text-decoration: none; color: var(--primary-dark);">{{ $contactInfo->data['email'] }}</a></p>
                                @if(!empty($contactInfo->data['response_time']))
                                    <p class="text-muted small">Response time: {{ $contactInfo->data['response_time'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(!empty($contactInfo->data['address']))
                        <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                            <div class="card-body p-4">
                                <i class="fas fa-map-marker-alt" style="font-size: 2rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                                <h5 class="fw-bold" style="color: var(--primary-dark);">Address</h5>
                                <p>{!! nl2br(e($contactInfo->data['address'])) !!}</p>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Default Placeholder if no contact info exists -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Contact information will be displayed here once configured by admin.
                    </div>
                @endif
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
        @if($locations && $locations->count() > 0)
            <div class="mt-5">
                <h2 class="text-center fw-bold mb-5" style="color: var(--primary-dark);">Our Locations</h2>
                <div class="row g-4">
                    @foreach($locations as $location)
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm" style="border-top: 3px solid var(--accent-gold);">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">{{ $location->page_title }}</h5>
                                    
                                    @if($location->page_content)
                                        <p class="text-muted mb-2">{{ $location->page_content }}</p>
                                    @endif
                                    
                                    @if(!empty($location->data['address']))
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {!! nl2br(e($location->data['address'])) !!}
                                        </p>
                                    @endif
                                    
                                    @if(!empty($location->data['phone']))
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-phone me-1"></i>
                                            Phone: <a href="tel:{{ $location->data['phone'] }}" style="text-decoration: none; color: inherit;">{{ $location->data['phone'] }}</a>
                                        </p>
                                    @endif
                                    
                                    @if(!empty($location->data['email']))
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-envelope me-1"></i>
                                            Email: <a href="mailto:{{ $location->data['email'] }}" style="text-decoration: none; color: inherit;">{{ $location->data['email'] }}</a>
                                        </p>
                                    @endif
                                    
                                    @if(!empty($location->data['timings']))
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $location->data['timings'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
