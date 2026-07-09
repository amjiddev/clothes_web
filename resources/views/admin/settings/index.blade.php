@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('breadcrumb')
    <li class="breadcrumb-item active">Settings</li>
    <li class="breadcrumb-item active">System Configuration</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-cog me-2"></i>System Settings</h1>
                <p class="text-muted">Manage your shop configuration and preferences</p>
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

    <!-- Settings Tabs Navigation -->
    <div class="card border-0 shadow">
        <div class="card-header bg-white border-bottom">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#shop" data-bs-toggle="tab">
                        <i class="fas fa-store me-2"></i>Shop Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#payment" data-bs-toggle="tab">
                        <i class="fas fa-credit-card me-2"></i>Payment Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#email" data-bs-toggle="tab">
                        <i class="fas fa-envelope me-2"></i>Email Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#colors" data-bs-toggle="tab">
                        <i class="fas fa-palette me-2"></i>Website Colors
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">
                <!-- Shop Settings Tab -->
                <div class="tab-pane fade show active" id="shop">
                    @include('admin.settings.tabs.shop', ['settings' => $settings['shop']])
                </div>

                <!-- Payment Settings Tab -->
                <div class="tab-pane fade" id="payment">
                    @include('admin.settings.tabs.payment', ['settings' => $settings['payment']])
                </div>

                <!-- Email Settings Tab -->
                <div class="tab-pane fade" id="email">
                    @include('admin.settings.tabs.email', ['settings' => $settings['email']])
                </div>

                <!-- Colors Settings Tab -->
                <div class="tab-pane fade" id="colors">
                    @include('admin.settings.tabs.colors', ['settings' => $settings['website']])
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

.tab-content {
    padding: 30px 0;
}

.setting-group {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e9ecef;
}

.setting-group:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.setting-group h5 {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.setting-group .text-muted {
    font-size: 13px;
}

.color-picker {
    display: flex;
    align-items: center;
    gap: 10px;
}

.color-picker input[type="color"] {
    width: 60px;
    height: 40px;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
}

.image-preview {
    position: relative;
    margin: 10px 0;
}

.image-preview img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.image-preview .remove-btn {
    position: absolute;
    top: -10px;
    right: -10px;
}
</style>
@endsection
