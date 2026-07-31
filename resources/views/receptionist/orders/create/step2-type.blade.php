@extends('receptionist.layouts.app')

@section('title', 'Create Order - Step 2')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Create Order</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">
        <i class="fas fa-shopping-cart me-2"></i>Create New Order
    </h1>
    <p class="text-muted">Step 2: Select Order Type & Items</p>
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
                <div class="step-indicator active">
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

<!-- Customer Info -->
<div class="alert alert-info border-0" role="alert">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Customer:</strong> {{ $customer->name }} ({{ $customer->email }})
</div>

<form method="POST" id="orderForm" action="{{ route('receptionist.orders.summary') }}">
    @csrf

    <input type="hidden" name="customer_id" value="{{ $customerId }}">

    <div class="row">
        <div class="col-lg-8">
            <!-- Step 1: Select Order Type -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-cube me-2"></i>Select Order Type
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Cloth Buy Only -->
                        <div class="col-md-6">
                            <div class="order-type-card">
                                <input type="radio" name="order_type" value="ready_made" id="clothOnly" 
                                       class="order-type-radio" onchange="updateOrderType('cloth')">
                                <label for="clothOnly" class="order-type-label">
                                    <div class="type-icon">
                                        <i class="fas fa-shirt"></i>
                                    </div>
                                    <div class="type-content">
                                        <h6>Cloth Buy Only</h6>
                                        <p>Purchase ready-made garments</p>
                                    </div>
                                    <div class="type-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Stitching Only -->
                        <div class="col-md-6">
                            <div class="order-type-card">
                                <input type="radio" name="order_type" value="stitching" id="stitchingOnly" 
                                       class="order-type-radio" onchange="updateOrderType('stitching')">
                                <label for="stitchingOnly" class="order-type-label">
                                    <div class="type-icon">
                                        <i class="fas fa-needle"></i>
                                    </div>
                                    <div class="type-content">
                                        <h6>Stitching Only</h6>
                                        <p>Customer provides fabric</p>
                                    </div>
                                    <div class="type-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Combined -->
                        <div class="col-md-6">
                            <div class="order-type-card">
                                <input type="radio" name="order_type" value="combined" id="combined" 
                                       class="order-type-radio" onchange="updateOrderType('combined')">
                                <label for="combined" class="order-type-label">
                                    <div class="type-icon">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div class="type-content">
                                        <h6>Cloth + Stitching</h6>
                                        <p>Purchase & customize together</p>
                                    </div>
                                    <div class="type-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Order Items (Dynamic) -->

            <!-- CLOTH ONLY / COMBINED: Product Selection -->
            <div id="clothSection" style="display: none;">
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-box me-2"></i>Select Products
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category" class="form-label fw-bold">Category</label>
                            <select class="form-select" id="category" onchange="filterProducts()">
                                <option value="">-- All Categories --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="productsContainer">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Color</th>
                                            <th>Size</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productsTable">
                                        @foreach($products as $product)
                                        <tr class="product-row" data-category="{{ $product->category_id }}">
                                            <td>{{ $product->name }}</td>
                                            <td><span class="badge" style="background-color: {{ $product->color ?? '#ddd' }}">{{ $product->color }}</span></td>
                                            <td>{{ $product->size ?? 'N/A' }}</td>
                                            <td>Rs. {{ number_format($product->final_price, 2) }}</td>
                                            <td>
                                                @if($product->stock_quantity > 0)
                                                    <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                                @else
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm product-qty" 
                                                       name="quantities[]" value="0" min="0" max="{{ $product->stock_quantity }}"
                                                       data-product-id="{{ $product->id }}" 
                                                       data-product-price="{{ $product->final_price }}"
                                                       style="width: 70px;">
                                                <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STITCHING ONLY / COMBINED: Stitching Details -->
            <div id="stitchingSection" style="display: none;">
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-pencil-ruler me-2"></i>Stitching Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fabric_type" class="form-label fw-bold">
                                    <i class="fas fa-cube me-2"></i>Fabric Type
                                </label>
                                <input type="text" class="form-control" id="fabric_type" name="fabric_type" 
                                       placeholder="e.g., Cotton, Silk, Linen">
                            </div>
                            <div class="col-md-6">
                                <label for="fabric_color" class="form-label fw-bold">
                                    <i class="fas fa-palette me-2"></i>Color
                                </label>
                                <input type="text" class="form-control" id="fabric_color" name="fabric_color" 
                                       placeholder="e.g., Blue, Red, Green">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="garment_type" class="form-label fw-bold">
                                <i class="fas fa-shirt me-2"></i>Garment Type
                            </label>
                            <select class="form-select" id="garment_type" name="garment_type">
                                <option value="">Select Garment Type</option>
                                <option value="shirt">Shirt</option>
                                <option value="pant">Pant</option>
                                <option value="kurta">Kurta</option>
                                <option value="saree">Saree</option>
                                <option value="suit">Suit</option>
                                <option value="dress">Dress</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        @if($measurements->count() > 0)
                        <div class="mb-3">
                            <label for="measurement_id" class="form-label fw-bold">
                                <i class="fas fa-ruler-vertical me-2"></i>Measurement Profile
                            </label>
                            <select class="form-select" id="measurement_id" name="measurement_id">
                                <option value="">-- Create New Measurement --</option>
                                @foreach($measurements as $measurement)
                                <option value="{{ $measurement->id }}">
                                    {{ $measurement->title ?? 'Default' }} 
                                    @if($measurement->is_default) (Default) @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="design_image" class="form-label fw-bold">
                                <i class="fas fa-image me-2"></i>Upload Design Image (Optional)
                            </label>
                            <input type="file" class="form-control" id="design_image" name="design_image" 
                                   accept="image/*">
                            <small class="text-muted">Maximum file size: 5MB</small>
                        </div>

                        <div class="mb-3">
                            <label for="stitching_instructions" class="form-label fw-bold">
                                <i class="fas fa-list me-2"></i>Special Instructions
                            </label>
                            <textarea class="form-control" id="stitching_instructions" name="stitching_instructions" 
                                      rows="3" placeholder="Any special stitching requirements..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charges Section -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>Additional Charges
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="stitching_charge" class="form-label fw-bold">Stitching Charge (Rs.)</label>
                            <input type="number" class="form-control" id="stitching_charge" name="stitching_charge" 
                                   value="0" min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label for="tax" class="form-label fw-bold">Tax (Rs.)</label>
                            <input type="number" class="form-control" id="tax" name="tax" 
                                   value="0" min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label for="discount" class="form-label fw-bold">Discount (Rs.)</label>
                            <input type="number" class="form-control" id="discount" name="discount" 
                                   value="0" min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label for="notes" class="form-label fw-bold">Order Notes</label>
                            <input type="text" class="form-control" id="notes" name="notes" 
                                   placeholder="Any special notes...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 mb-4">
                <a href="{{ route('receptionist.orders.create') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-right me-2"></i>Continue to Summary
                </button>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted d-block">Subtotal</small>
                        <h5 id="subtotalDisplay" class="mb-0">Rs. 0.00</h5>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted d-block">Charges & Tax</small>
                        <div class="d-flex justify-content-between">
                            <span>Stitching:</span>
                            <span id="stitchingDisplay">Rs. 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tax:</span>
                            <span id="taxDisplay">Rs. 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Discount:</span>
                            <span id="discountDisplay">-Rs. 0.00</span>
                        </div>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <h6>Items Selected</h6>
                        <ul class="small mb-0" id="itemsList">
                            <li class="text-muted">No items selected</li>
                        </ul>
                    </div>

                    <div>
                        <small class="text-muted d-block">Total Amount</small>
                        <h4 id="totalDisplay" class="text-primary mb-0">Rs. 0.00</h4>
                    </div>

                    <input type="hidden" id="subtotal" name="subtotal" value="0">
                    <input type="hidden" id="total" name="total" value="0">
                </div>
            </div>
        </div>
    </div>
</form>

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

.order-type-card {
    position: relative;
}

.order-type-radio {
    display: none;
}

.order-type-label {
    display: block;
    padding: 20px;
    border: 2px solid #ddd;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 0;
}

.order-type-radio:checked + .order-type-label {
    border-color: #d4af37;
    background-color: rgba(212, 175, 55, 0.05);
}

.order-type-label:hover {
    border-color: #d4af37;
}

.type-icon {
    font-size: 32px;
    color: #d4af37;
    margin-bottom: 10px;
}

.type-content h6 {
    font-weight: 600;
    margin-bottom: 5px;
}

.type-content p {
    font-size: 12px;
    color: #666;
    margin: 0;
}

.type-check {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.order-type-radio:checked + .order-type-label .type-check {
    opacity: 1;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}
</style>

<script>
function updateOrderType(type) {
    document.getElementById('clothSection').style.display = type === 'cloth' || type === 'combined' ? 'block' : 'none';
    document.getElementById('stitchingSection').style.display = type === 'stitching' || type === 'combined' ? 'block' : 'none';
    calculateTotals();
}

function filterProducts() {
    const categoryId = document.getElementById('category').value;
    const rows = document.querySelectorAll('.product-row');
    
    rows.forEach(row => {
        if (!categoryId || row.dataset.category === categoryId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function calculateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.product-qty').forEach(input => {
        const qty = parseInt(input.value) || 0;
        const price = parseFloat(input.dataset.productPrice) || 0;
        subtotal += qty * price;
    });

    const stitching = parseFloat(document.getElementById('stitching_charge').value) || 0;
    const tax = parseFloat(document.getElementById('tax').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;

    const total = subtotal + stitching + tax - discount;

    document.getElementById('subtotalDisplay').textContent = 'Rs. ' + subtotal.toFixed(2);
    document.getElementById('stitchingDisplay').textContent = 'Rs. ' + stitching.toFixed(2);
    document.getElementById('taxDisplay').textContent = 'Rs. ' + tax.toFixed(2);
    document.getElementById('discountDisplay').textContent = '-Rs. ' + discount.toFixed(2);
    document.getElementById('totalDisplay').textContent = 'Rs. ' + total.toFixed(2);

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);
}

// Event listeners
document.querySelectorAll('.product-qty, #stitching_charge, #tax, #discount').forEach(input => {
    input.addEventListener('change', calculateTotals);
    input.addEventListener('input', calculateTotals);
});

// Initialize
document.getElementById('clothOnly').checked = true;
updateOrderType('cloth');
</script>
@endsection
