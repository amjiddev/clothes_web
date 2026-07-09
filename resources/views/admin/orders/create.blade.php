@extends('layout.app')

@section('title', 'Create Order')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-dark">Create New Order</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                        @csrf

                        <!-- Customer Selection -->
                        <div class="mb-3">
                            <label for="user_id" class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Select Customer</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Order Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label fw-bold">Order Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required onchange="updateFormFields()">
                                <option value="">Select Order Type</option>
                                <option value="ready_made" {{ old('type') == 'ready_made' ? 'selected' : '' }}>Ready Made</option>
                                <option value="stitching" {{ old('type') == 'stitching' ? 'selected' : '' }}>Stitching Only</option>
                                <option value="combined" {{ old('type') == 'combined' ? 'selected' : '' }}>Combined (Clothes + Stitching)</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Ready Made / Combined Items -->
                        <div id="itemsSection" style="display: none;">
                            <h5 class="fw-bold mt-4 mb-3">Products</h5>
                            <div id="itemsContainer"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addProductRow()">
                                <i class="fas fa-plus me-1"></i>Add Product
                            </button>
                        </div>

                        <!-- Stitching / Combined Details -->
                        <div id="stitchingSection" style="display: none;">
                            <hr class="my-4">
                            <h5 class="fw-bold mb-3">Stitching Details</h5>

                            <div class="mb-3">
                                <label for="garment_type" class="form-label fw-bold">Garment Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('garment_type') is-invalid @enderror" id="garment_type" name="garment_type" placeholder="e.g., Shirt, Pants, Kurta" value="{{ old('garment_type') }}">
                                @error('garment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="fabric_details" class="form-label fw-bold">Fabric Details</label>
                                <textarea class="form-control @error('fabric_details') is-invalid @enderror" id="fabric_details" name="fabric_details" rows="2" placeholder="e.g., Color, texture, etc.">{{ old('fabric_details') }}</textarea>
                                @error('fabric_details')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="special_instructions" class="form-label fw-bold">Special Instructions</label>
                                <textarea class="form-control @error('special_instructions') is-invalid @enderror" id="special_instructions" name="special_instructions" rows="3" placeholder="Any special requirements...">{{ old('special_instructions') }}</textarea>
                                @error('special_instructions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="stitching_charge" class="form-label fw-bold">Stitching Charge <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" class="form-control @error('stitching_charge') is-invalid @enderror" id="stitching_charge" name="stitching_charge" value="{{ old('stitching_charge', 0) }}">
                                </div>
                                @error('stitching_charge')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <hr class="my-4">
                        <h5 class="fw-bold mb-3">Order Details</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount" class="form-label fw-bold">Discount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" step="0.01" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" value="{{ old('discount', 0) }}">
                                    </div>
                                    @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label fw-bold">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                        <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    </select>
                                    @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-bold">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-save me-2"></i>Create Order
                                </button>
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="card border-0 shadow sticky-top" style="top: 20px;">
                <div class="card-header bg-dark text-white">
                    <h5 class="fw-bold mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div id="orderSummary">
                        <p class="text-muted text-center">Select order type to see summary</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFormFields() {
    const type = document.getElementById('type').value;
    const itemsSection = document.getElementById('itemsSection');
    const stitchingSection = document.getElementById('stitchingSection');

    // Reset sections
    itemsSection.style.display = 'none';
    stitchingSection.style.display = 'none';

    if (type === 'ready_made' || type === 'combined') {
        itemsSection.style.display = 'block';
        if (document.getElementById('itemsContainer').innerHTML === '') {
            addProductRow();
        }
    }

    if (type === 'stitching' || type === 'combined') {
        stitchingSection.style.display = 'block';
    }
}

function addProductRow() {
    const container = document.getElementById('itemsContainer');
    const rowCount = container.children.length;
    
    const html = `
        <div class="row mb-2 product-row">
            <div class="col-md-7">
                <select class="form-select form-select-sm" name="items[${rowCount}][product_id]" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} - ₹{{ $product->price }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control form-control-sm" name="items[${rowCount}][quantity]" min="1" value="1" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeProductRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

function removeProductRow(btn) {
    btn.closest('.product-row').remove();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateFormFields();
});
</script>
@endsection
