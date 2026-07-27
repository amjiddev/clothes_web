@extends('receptionist.layouts.app')

@section('title', 'Create Order - Step 4')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Create Order</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">
        <i class="fas fa-shopping-cart me-2"></i>Create New Order
    </h1>
    <p class="text-muted">Step 4: Payment & Generate Order</p>
</div>

<!-- Progress Bar -->
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <div class="step-indicator completed">
                    <div class="step-circle"><i class="fas fa-check"></i></div>
                    <div class="step-label">Select Customer</div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-indicator completed">
                    <div class="step-circle"><i class="fas fa-check"></i></div>
                    <div class="step-label">Order Type</div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-indicator completed">
                    <div class="step-circle"><i class="fas fa-check"></i></div>
                    <div class="step-label">Summary</div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-indicator active">
                    <div class="step-circle">4</div>
                    <div class="step-label">Payment</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Info -->
<div class="alert alert-info border-0" role="alert">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Customer:</strong> {{ $customer->name }} ({{ $customer->email }})
    | <strong>Total Amount:</strong> Rs. {{ number_format($total, 2) }}
</div>

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('receptionist.orders.store') }}" id="paymentForm">
            @csrf

            <!-- Payment Section -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Payment Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="payment_method" class="form-label fw-bold">
                            <i class="fas fa-money-check me-2"></i>Payment Method <span class="text-danger">*</span>
                        </label>
                        <div class="payment-methods">
                            <div class="payment-method-card">
                                <input type="radio" name="payment_method" value="cash" id="cash" class="payment-radio" checked onchange="updatePaymentMethod()">
                                <label for="cash" class="payment-label">
                                    <div class="method-icon">
                                        <i class="fas fa-money-bill"></i>
                                    </div>
                                    <div class="method-text">
                                        <h6>Cash</h6>
                                        <p>Pay at store or delivery</p>
                                    </div>
                                    <div class="method-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>

                            <div class="payment-method-card">
                                <input type="radio" name="payment_method" value="card" id="card" class="payment-radio" onchange="updatePaymentMethod()">
                                <label for="card" class="payment-label">
                                    <div class="method-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="method-text">
                                        <h6>Card</h6>
                                        <p>Debit or Credit Card</p>
                                    </div>
                                    <div class="method-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>

                            <div class="payment-method-card">
                                <input type="radio" name="payment_method" value="bank_transfer" id="bank" class="payment-radio" onchange="updatePaymentMethod()">
                                <label for="bank" class="payment-label">
                                    <div class="method-icon">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div class="method-text">
                                        <h6>Bank Transfer</h6>
                                        <p>Direct bank transfer</p>
                                    </div>
                                    <div class="method-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>

                            <div class="payment-method-card">
                                <input type="radio" name="payment_method" value="online" id="online" class="payment-radio" onchange="updatePaymentMethod()">
                                <label for="online" class="payment-label">
                                    <div class="method-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div class="method-text">
                                        <h6>Online Payment</h6>
                                        <p>UPI, GPay, PayTM, etc.</p>
                                    </div>
                                    <div class="method-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="paid_amount" class="form-label fw-bold">
                                <i class="fas fa-rupee-sign me-2"></i>Paid Amount (Rs.) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control form-control-lg" id="paid_amount" name="paid_amount" 
                                   value="{{ $total }}" min="0" step="0.01" required onchange="calculateRemaining()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="remaining" class="form-label fw-bold">
                                Remaining Amount (Rs.)
                            </label>
                            <input type="number" class="form-control form-control-lg bg-light" id="remaining" 
                                   readonly value="0.00">
                        </div>
                    </div>

                    <!-- Delivery Date -->
                    <div class="mb-3">
                        <label for="delivery_date" class="form-label fw-bold">
                            <i class="fas fa-calendar me-2"></i>Delivery Date
                        </label>
                        <input type="date" class="form-control form-control-lg" id="delivery_date" name="delivery_date">
                    </div>

                    <!-- Order Notes -->
                    <div class="mb-3">
                        <label for="order_notes" class="form-label fw-bold">
                            <i class="fas fa-sticky-note me-2"></i>Order Notes
                        </label>
                        <textarea class="form-control" id="order_notes" name="notes" rows="3" 
                                  placeholder="Any additional notes about this order..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Hidden Inputs -->
            <input type="hidden" name="customer_id" value="{{ $customerId }}">
            <input type="hidden" id="order_type" name="order_type" value="{{ request('order_type') }}">
            <input type="hidden" id="subtotal_input" name="subtotal" value="{{ request('subtotal') }}">
            <input type="hidden" id="stitching_input" name="stitching_charge" value="{{ request('stitching_charge', 0) }}">
            <input type="hidden" id="tax_input" name="tax" value="{{ request('tax', 0) }}">
            <input type="hidden" id="discount_input" name="discount" value="{{ request('discount', 0) }}">
            <input type="hidden" id="total_input" name="total" value="{{ $total }}">

            <!-- Products -->
            @if(request('product_ids'))
                @foreach(request('product_ids', []) as $index => $productId)
                <input type="hidden" name="product_ids[]" value="{{ $productId }}">
                <input type="hidden" name="quantities[]" value="{{ request('quantities.' . $index) }}">
                @endforeach
            @endif

            <!-- Stitching Details -->
            @if(request('fabric_type'))
                <input type="hidden" name="fabric_type" value="{{ request('fabric_type') }}">
                <input type="hidden" name="fabric_color" value="{{ request('fabric_color', '') }}">
                <input type="hidden" name="stitching_instructions" value="{{ request('stitching_instructions', '') }}">
            @endif

            <!-- Action Buttons -->
            <div class="d-flex gap-2 mb-4">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-check me-2"></i>Generate Order
                </button>
            </div>
        </form>
    </div>

    <!-- Payment Summary -->
    <div class="col-lg-4">
        <div class="card border-0 shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-receipt me-2"></i>Payment Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Amount</span>
                        <strong id="totalAmount" class="text-primary">Rs. {{ number_format($total, 2) }}</strong>
                    </div>
                </div>

                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Paid Amount</span>
                        <strong id="paidDisplay" class="text-success">Rs. {{ number_format($total, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Remaining</span>
                        <strong id="remainingDisplay" class="text-danger">Rs. 0.00</strong>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold mb-2">Payment Status</label>
                    <div id="paymentStatus">
                        <span class="badge bg-success w-100">
                            <i class="fas fa-check me-1"></i>Fully Paid
                        </span>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-light p-3 rounded">
                    <h6 class="fw-bold mb-3">Order Details</h6>
                    <ul class="small mb-0">
                        <li><strong>Customer:</strong> {{ $customer->name }}</li>
                        <li><strong>Email:</strong> {{ $customer->email }}</li>
                        <li><strong>Payment Method:</strong> <span id="methodDisplay">Cash</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Print After Order -->
        <div class="alert alert-info mt-4" role="alert">
            <i class="fas fa-print me-2"></i>
            <strong>After creating the order:</strong>
            <p class="mb-0 mt-2">You'll have the option to print the invoice immediately</p>
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
}

.step-indicator.active .step-circle {
    background-color: #d4af37;
    border-color: #d4af37;
    color: white;
}

.step-indicator.completed .step-circle {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.payment-method-card {
    position: relative;
}

.payment-radio {
    display: none;
}

.payment-label {
    display: block;
    padding: 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 0;
    height: 100%;
}

.payment-radio:checked + .payment-label {
    border-color: #28a745;
    background-color: rgba(40, 167, 69, 0.05);
}

.payment-label:hover {
    border-color: #28a745;
}

.method-icon {
    font-size: 24px;
    color: #28a745;
    margin-bottom: 8px;
}

.method-text h6 {
    font-weight: 600;
    margin-bottom: 3px;
    font-size: 14px;
}

.method-text p {
    font-size: 11px;
    color: #999;
    margin: 0;
}

.method-check {
    position: absolute;
    top: 5px;
    right: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: #28a745;
}

.payment-radio:checked + .payment-label .method-check {
    opacity: 1;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

@media (max-width: 576px) {
    .payment-methods {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function calculateRemaining() {
    const total = {{ $total }};
    const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
    const remaining = Math.max(0, total - paid);

    document.getElementById('remaining').value = remaining.toFixed(2);
    document.getElementById('remainingDisplay').textContent = 'Rs. ' + remaining.toFixed(2);
    document.getElementById('paidDisplay').textContent = 'Rs. ' + paid.toFixed(2);

    // Update payment status
    const statusDiv = document.getElementById('paymentStatus');
    if (remaining === 0) {
        statusDiv.innerHTML = '<span class="badge bg-success w-100"><i class="fas fa-check me-1"></i>Fully Paid</span>';
    } else if (paid > 0) {
        statusDiv.innerHTML = '<span class="badge bg-warning text-dark w-100"><i class="fas fa-hourglass-half me-1"></i>Partial Payment</span>';
    } else {
        statusDiv.innerHTML = '<span class="badge bg-danger w-100"><i class="fas fa-times me-1"></i>Not Paid</span>';
    }
}

function updatePaymentMethod() {
    const method = document.querySelector('input[name="payment_method"]:checked').value;
    const methodLabels = {
        'cash': 'Cash',
        'card': 'Card',
        'bank_transfer': 'Bank Transfer',
        'online': 'Online Payment'
    };
    document.getElementById('methodDisplay').textContent = methodLabels[method] || method;
}

// Initialize
document.getElementById('paid_amount').addEventListener('input', calculateRemaining);
calculateRemaining();
</script>
@endsection
