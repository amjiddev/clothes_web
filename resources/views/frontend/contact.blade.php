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
                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: var(--primary-dark);">Full Name</label>
                                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" style="border-color: var(--accent-gold);" value="{{ old('full_name') }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: var(--primary-dark);">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" style="border-color: var(--accent-gold);" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">Phone</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" style="border-color: var(--accent-gold);" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">Subject</label>
                                <select name="subject" class="form-select @error('subject') is-invalid @enderror" style="border-color: var(--accent-gold);" required>
                                    <option value="">Select Subject</option>
                                    <option value="Product Inquiry" {{ old('subject') === 'Product Inquiry' ? 'selected' : '' }}>Product Inquiry</option>
                                    <option value="Tailoring Service" {{ old('subject') === 'Tailoring Service' ? 'selected' : '' }}>Tailoring Service</option>
                                    <option value="Order Status" {{ old('subject') === 'Order Status' ? 'selected' : '' }}>Order Status</option>
                                    <option value="Feedback" {{ old('subject') === 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                    <option value="Other" {{ old('subject') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--primary-dark);">Message</label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5" style="border-color: var(--accent-gold);" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>Please fix the errors and try again.
                            </div>
                            @endif

                            @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                            @endif

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
