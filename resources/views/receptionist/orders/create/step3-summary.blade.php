@extends('receptionist.layouts.app')

@section('title', 'Create Order - Step 3')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Create Order</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">
        <i class="fas fa-shopping-cart me-2"></i>Create New Order
    </h1>
    <p class="text-muted">Step 3: Order Summary</p>
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
                <div class="step-indicator active">
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

<!-- Customer Info -->
<div class="alert alert-info border-0" role="alert">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Customer:</strong> {{ $customer->name }} ({{ $customer->email }})
    | <strong>Order Type:</strong> {{ ['ready_made' => 'Cloth Only', 'stitching' => 'Stitching Only', 'combined' => 'Cloth + Stitching'][$orderType] ?? $orderType }}
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Products Section -->
        @if($items['products'])
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>Products
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items['products'] as $product)
                        <tr>
                            <td class="fw-bold">{{ $product['product_name'] }}</td>
                            <td>{{ $product['quantity'] }}</td>
                            <td>Rs. {{ number_format($product['price'], 2) }}</td>
                            <td>Rs. {{ number_format($product['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Stitching Details Section -->
        @if($items['stitching'])
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-pencil-ruler me-2"></i>Stitching Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Fabric Type</strong>
                            <p class="mb-0">{{ $items['stitching']['fabric_type'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Color</strong>
                            <p class="mb-0">{{ $items['stitching']['color'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                @if($items['stitching']['instructions'])
                <div class="mb-3">
                    <strong class="text-muted d-block mb-1">Special Instructions</strong>
                    <p class="mb-0">{{ $items['stitching']['instructions'] }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Summary Sidebar -->
    <div class="col-lg-4">
        <div class="card border-0 shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-receipt me-2"></i>Order Total
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong>Rs. {{ number_format($subtotal, 2) }}</strong>
                    </div>
                </div>

                <div class="mb-3 pb-3 border-bottom">
                    @if($stitchingCharge > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Stitching Charge</span>
                        <span>Rs. {{ number_format($stitchingCharge, 2) }}</span>
                    </div>
                    @endif

                    @if($tax > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax</span>
                        <span>Rs. {{ number_format($tax, 2) }}</span>
                    </div>
                    @endif

                    @if($discount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount</span>
                        <span class="text-success">-Rs. {{ number_format($discount, 2) }}</span>
                    </div>
                    @endif
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <h6>Total Amount</h6>
                        <h5 class="text-primary mb-0">Rs. {{ number_format($total, 2) }}</h5>
                    </div>
                </div>

                <form method="POST" action="{{ route('receptionist.orders.payment') }}">
                    @csrf

                    <input type="hidden" name="customer_id" value="{{ $customerId }}">
                    <input type="hidden" name="order_type" value="{{ $orderType }}">
                    <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                    <input type="hidden" name="stitching_charge" value="{{ $stitchingCharge }}">
                    <input type="hidden" name="discount" value="{{ $discount }}">
                    <input type="hidden" name="tax" value="{{ $tax }}">
                    <input type="hidden" name="total" value="{{ $total }}">

                    <!-- Add all products -->
                    @if($items['products'])
                        @foreach($items['products'] as $index => $product)
                        <input type="hidden" name="product_ids[]" value="{{ $product['product_id'] }}">
                        <input type="hidden" name="quantities[]" value="{{ $product['quantity'] }}">
                        @endforeach
                    @endif

                    <!-- Add stitching details -->
                    @if($items['stitching'])
                        <input type="hidden" name="fabric_type" value="{{ $items['stitching']['fabric_type'] ?? '' }}">
                        <input type="hidden" name="fabric_color" value="{{ $items['stitching']['color'] ?? '' }}">
                        <input type="hidden" name="stitching_instructions" value="{{ $items['stitching']['instructions'] ?? '' }}">
                    @endif

                    <div class="d-flex gap-2">
                        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg w-50">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg w-50">
                            <i class="fas fa-arrow-right me-2"></i>Continue to Payment
                        </button>
                    </div>
                </form>
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

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}
</style>
@endsection
