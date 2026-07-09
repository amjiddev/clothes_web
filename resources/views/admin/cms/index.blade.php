@extends('admin.layouts.app')

@section('title', 'Website CMS')

@section('breadcrumb')
    <li class="breadcrumb-item active">CMS</li>
    <li class="breadcrumb-item active">Manage Content</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-file-alt me-2"></i>Website CMS</h1>
                <p class="text-muted">Manage all website content sections</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.cms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Section
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Error!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Section Tabs -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#slider" data-bs-toggle="tab">
                        <i class="fas fa-images me-2"></i>Slider
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#hero" data-bs-toggle="tab">
                        <i class="fas fa-heading me-2"></i>Hero Text
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about" data-bs-toggle="tab">
                        <i class="fas fa-info-circle me-2"></i>About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#services" data-bs-toggle="tab">
                        <i class="fas fa-cogs me-2"></i>Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#testimonial" data-bs-toggle="tab">
                        <i class="fas fa-comments me-2"></i>Testimonials
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact" data-bs-toggle="tab">
                        <i class="fas fa-envelope me-2"></i>Contact
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#social" data-bs-toggle="tab">
                        <i class="fas fa-share-alt me-2"></i>Social
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#footer" data-bs-toggle="tab">
                        <i class="fas fa-copyright me-2"></i>Footer
                    </a>
                </li>
            </ul>

            <div class="tab-content mt-4">
                <!-- Slider Section -->
                <div class="tab-pane fade show active" id="slider">
                    @include('admin.cms.sections.slider', ['items' => $groupedSections->get('slider', collect())])
                </div>

                <!-- Hero Section -->
                <div class="tab-pane fade" id="hero">
                    @include('admin.cms.sections.hero', ['items' => $groupedSections->get('hero', collect())])
                </div>

                <!-- About Section -->
                <div class="tab-pane fade" id="about">
                    @include('admin.cms.sections.about', ['items' => $groupedSections->get('about', collect())])
                </div>

                <!-- Services Section -->
                <div class="tab-pane fade" id="services">
                    @include('admin.cms.sections.services', ['items' => $groupedSections->get('services', collect())])
                </div>

                <!-- Testimonials Section -->
                <div class="tab-pane fade" id="testimonial">
                    @include('admin.cms.sections.testimonial', ['items' => $groupedSections->get('testimonial', collect())])
                </div>

                <!-- Contact Section -->
                <div class="tab-pane fade" id="contact">
                    @include('admin.cms.sections.contact', ['items' => $groupedSections->get('contact', collect())])
                </div>

                <!-- Social Section -->
                <div class="tab-pane fade" id="social">
                    @include('admin.cms.sections.social', ['items' => $groupedSections->get('social', collect())])
                </div>

                <!-- Footer Section -->
                <div class="tab-pane fade" id="footer">
                    @include('admin.cms.sections.footer', ['items' => $groupedSections->get('footer', collect())])
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    padding: 20px 0;
    border-bottom: 1px solid #e9ecef;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.nav-tabs .nav-link:hover {
    color: #495057;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background: transparent;
}
</style>
@endsection
