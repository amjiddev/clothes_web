@extends('receptionist.layouts.app')

@section('title', 'Create Order - Step 1')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Create Order</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">
        <i class="fas fa-shopping-cart me-2"></i>Create New Order
    </h1>
    <p class="text-muted">Step 1: Select Customer</p>
</div>

<!-- Progress Bar -->
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <div class="step-indicator active">
                    <div class="step-circle">1</div>
                    <div class="step-label">Select Customer</div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-indicator">
                    <div class="step-circle">2</div>
                    <div class="step-label">Order Type</div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-indicator">
                    <div class="step-circle">3</div>
                    <div class="step-label">Summary</div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-indicator">
                    <div class="step-circle">4</div>
                    <div class="step-label">Payment</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card border-0 shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Select Customer for Order
                </h5>
            </div>
            <div class="card-body">
                <!-- Option 1: Existing Customer -->
                <div class="mb-4">
                    <h6 class="mb-3 fw-bold">
                        <i class="fas fa-check-circle text-success me-2"></i>Select Existing Customer
                    </h6>
                    <form method="GET" action="{{ route('receptionist.orders.select-type') }}" id="existingCustomerForm">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label fw-bold">
                                <i class="fas fa-users me-2"></i>Customer <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg" id="customer_id" name="customer_id" required>
                                <option value="">-- Select a Customer --</option>
                                @forelse($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                                @empty
                                <option value="" disabled>No customers available</option>
                                @endforelse
                            </select>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Select an existing customer to create an order
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-arrow-right me-2"></i>Continue with Existing Customer
                        </button>
                    </form>
                </div>

                <hr class="my-4">

                <!-- Option 2: Create New Customer -->
                <div>
                    <h6 class="mb-3 fw-bold">
                        <i class="fas fa-plus-circle text-info me-2"></i>Create New Customer
                    </h6>
                    <form method="POST" action="{{ route('receptionist.customers.store') }}" id="newCustomerForm">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fas fa-user me-2"></i>Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       id="name" name="name" placeholder="Enter customer name" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-2"></i>Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" name="email" placeholder="Enter email address" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold">
                                    <i class="fas fa-phone me-2"></i>Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" placeholder="Enter phone number" 
                                       value="{{ old('phone') }}" required>
                                @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label fw-bold">
                                    <i class="fas fa-city me-2"></i>City
                                </label>
                                <input type="text" class="form-control form-control-lg @error('city') is-invalid @enderror" 
                                       id="city" name="city" placeholder="Enter city" 
                                       value="{{ old('city') }}">
                                @error('city')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt me-2"></i>Address
                            </label>
                            <textarea class="form-control form-control-lg @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2" 
                                      placeholder="Enter address">{{ old('address') }}</textarea>
                            @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hidden input to redirect to step 2 after customer creation -->
                        <input type="hidden" name="redirect_to" value="order">

                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-user-plus me-2"></i>Create Customer & Continue
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card border-0 shadow mt-4 bg-light">
            <div class="card-body">
                <h6 class="mb-3 fw-bold">
                    <i class="fas fa-info-circle me-2 text-info"></i>Tips
                </h6>
                <ul class="small mb-0">
                    <li>Select an existing customer to save time</li>
                    <li>Or create a new customer account and create order</li>
                    <li>All customer information will be saved for future orders</li>
                    <li>You can view customer history after creating the order</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.step-indicator {
    padding: 20px 10px;
}

.step-circle {
    width: 50px;
    height: 50px;
    margin: 0 auto 10px;
    background-color: #f0f0f0;
    border: 3px solid #ddd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 20px;
    transition: all 0.3s ease;
}

.step-indicator.active .step-circle {
    background-color: #d4af37;
    border-color: #d4af37;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
}

.step-label {
    font-size: 14px;
    font-weight: 600;
    color: #666;
}

.step-indicator.active .step-label {
    color: #d4af37;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}
</style>
@endsection
