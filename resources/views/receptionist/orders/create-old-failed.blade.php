@extends('receptionist.layouts.app')

@section('title', 'Create Order')

@section('breadcrumb')
    <li class="breadcrumb-item active">Create Order</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Create New Order</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="submitForm" action="{{ route('receptionist.orders.store') }}" method="POST" id="orderForm">
                        @csrf
                        <div x-data="orderForm()" class="row">
                            <!-- SECTION 1: CUSTOMER DETAILS -->
                            <div class="col-md-6 mb-4">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h5 class="card-title">Customer Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Hidden Customer ID -->
                                        <input type="hidden" name="customer_id" x-model="selectedCustomerId">

                                        <!-- Select Existing Customer Button -->
                                        <div class="mb-3">
                                            <button type="button" 
                                                @click="showCustomerModal = true"
                                                class="btn btn-outline-primary btn-block"
                                                style="width: 100%; border-radius: 6px;">
                                                <i class="fas fa-search me-2"></i>Select Existing Customer
                                            </button>
                                        </div>

                                        <!-- Divider -->
                                        <div class="text-center mb-3">
                                            <small class="text-muted">
                                                <span style="display: inline-block; width: 30%; border-bottom: 1px solid #ccc;"></span>
                                                <span style="margin: 0 10px;">OR ENTER NEW DETAILS</span>
                                                <span style="display: inline-block; width: 30%; border-bottom: 1px solid #ccc;"></span>
                                            </small>
                                        </div>

                                        <!-- Walk-in / New Customer Form (Default) -->
                                        <div>
                                            <!-- Full Name -->
                                            <div class="form-group mb-3">
                                                <label for="walkInName">Full Name *</label>
                                                <input type="text" 
                                                    id="walkInName" 
                                                    x-model="walkInName"
                                                    name="new_customer_name" 
                                                    class="form-control @error('new_customer_name') is-invalid @enderror" 
                                                    placeholder="Full Name"
                                                    :disabled="selectedCustomerId !== null"
                                                    required>
                                                @error('new_customer_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Email -->
                                            <div class="form-group mb-3">
                                                <label for="walkInEmail">Email</label>
                                                <input type="email" 
                                                    id="walkInEmail" 
                                                    x-model="walkInEmail"
                                                    name="new_customer_email" 
                                                    class="form-control @error('new_customer_email') is-invalid @enderror" 
                                                    placeholder="email@example.com"
                                                    :disabled="selectedCustomerId !== null">
                                                @error('new_customer_email')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Phone Number -->
                                            <div class="form-group mb-3">
                                                <label for="walkInPhone">Phone Number *</label>
                                                <input type="tel" 
                                                    id="walkInPhone" 
                                                    x-model="walkInPhone"
                                                    name="new_customer_phone" 
                                                    class="form-control @error('new_customer_phone') is-invalid @enderror" 
                                                    placeholder="03001234567"
                                                    :disabled="selectedCustomerId !== null"
                                                    required>
                                                @error('new_customer_phone')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Address -->
                                            <div class="form-group mb-3">
                                                <label for="walkInAddress">Address</label>
                                                <textarea 
                                                    id="walkInAddress" 
                                                    x-model="walkInAddress"
                                                    name="new_customer_address" 
                                                    class="form-control @error('new_customer_address') is-invalid @enderror" 
                                                    placeholder="Street Address (Optional)"
                                                    rows="2"
                                                    :disabled="selectedCustomerId !== null"></textarea>
                                                @error('new_customer_address')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Selected Customer Indicator -->
                                            <template x-if="selectedCustomerId !== null">
                                                <div class="alert alert-info mb-0" style="display: flex; justify-content: space-between; align-items: center;">
                                                    <div>
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        <strong>Selected:</strong> <span x-text="walkInName"></span>
                                                    </div>
                                                    <button type="button" 
                                                        @click="selectedCustomerId = null; walkInName = 'Walk-in Customer'; walkInEmail = ''; walkInPhone = ''; walkInAddress = '';"
                                                        class="btn btn-sm btn-light"
                                                        style="padding: 0.25rem 0.5rem;">
                                                        Change
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 2: ORDER TYPE -->
                            <div class="col-md-6 mb-4">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Order Type</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Select Order Type</label>
                                            <div class="custom-control custom-radio mb-2">
                                                <input type="radio" id="readyMade" class="custom-control-input" 
                                                    x-model="orderType" value="ready_made" name="order_type" required>
                                                <label class="custom-control-label" for="readyMade">
                                                    Ready Made
                                                </label>
                                            </div>
                                            <div class="custom-control custom-radio mb-2">
                                                <input type="radio" id="stitchingOnly" class="custom-control-input" 
                                                    x-model="orderType" value="stitching" name="order_type" required>
                                                <label class="custom-control-label" for="stitchingOnly">
                                                    Stitching
                                                </label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="combined" class="custom-control-input" 
                                                    x-model="orderType" value="combined" name="order_type" required>
                                                <label class="custom-control-label" for="combined">
                                                    Cloth + Stitching
                                                </label>
                                            </div>
                                            @error('order_type')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 3: PRODUCTS GRID -->
                            <template x-if="['ready_made', 'combined'].includes(orderType)">
                                <div class="col-12 mb-4">
                                    <div class="card card-outline card-success">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0">Select Products</h5>
                                                <input type="text" 
                                                    x-model="productSearch"
                                                    placeholder="🔍 Search products..." 
                                                    class="form-control" 
                                                    style="max-width: 300px; border-radius: 20px; padding: 0.5rem 1rem;">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Category Tabs -->
                                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                                <button type="button" 
                                                    @click="selectedCategory = null"
                                                    :class="['btn', 'btn-sm', selectedCategory === null ? 'btn-primary' : 'btn-outline-primary']"
                                                    style="border-radius: 20px;">
                                                    All Products
                                                </button>
                                                @foreach($categories as $category)
                                                    <button type="button" 
                                                        @click="selectedCategory = {{ $category->id }}"
                                                        :class="['btn', 'btn-sm', selectedCategory === {{ $category->id }} ? 'btn-primary' : 'btn-outline-primary']"
                                                        style="border-radius: 20px;">
                                                        {{ $category->name }}
                                                    </button>
                                                @endforeach
                                            </div>

                                            <!-- Products Grid -->
                                            <div class="row g-2">
                                                @foreach($products as $product)
                                                    <div class="col-lg-3 col-md-4 col-sm-6" 
                                                        x-show="isProductVisible({{ $product->category_id }}, '{{ addslashes($product->name) }}', '{{ $product->color }}', '{{ $product->size }}')">
                                                        <div class="product-card h-100" :class="quantities[{{ $product->id }}] > 0 ? 'selected' : ''">
                                                            <div class="product-header">
                                                                <h6 class="product-name">{{ $product->name }}</h6>
                                                                <div class="product-badges">
                                                                    <span class="badge badge-light">{{ $product->color }}</span>
                                                                    <span class="badge badge-light">{{ $product->size }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="product-price">
                                                                PKR <strong>{{ number_format($product->price, 2) }}</strong>
                                                                @if($product->discount_price)
                                                                    <del class="text-muted ms-2" style="font-size: 0.85rem;">
                                                                        {{ number_format($product->discount_price, 2) }}
                                                                    </del>
                                                                @endif
                                                            </div>

                                                            <div class="product-stock">
                                                                <small class="text-muted">Stock: <strong>{{ $product->stock_quantity }}</strong></small>
                                                            </div>

                                                            <input type="hidden" 
                                                                name="products[{{ $loop->index }}][id]" 
                                                                value="{{ $product->id }}">

                                                            <div class="product-quantity">
                                                                <div class="input-group input-group-sm">
                                                                    <button type="button" 
                                                                        @click="quantities[{{ $product->id }}] = Math.max(0, (quantities[{{ $product->id }}] || 0) - 1)"
                                                                        class="btn btn-outline-secondary btn-sm"
                                                                        style="padding: 0.25rem 0.5rem;">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <input type="number" 
                                                                        x-model.number="quantities[{{ $product->id }}]"
                                                                        name="products[{{ $loop->index }}][qty]"
                                                                        min="0" max="{{ $product->stock_quantity }}"
                                                                        class="form-control form-control-sm text-center" 
                                                                        placeholder="0"
                                                                        style="width: 50px;">
                                                                    <button type="button" 
                                                                        @click="quantities[{{ $product->id }}] = Math.min({{ $product->stock_quantity }}, (quantities[{{ $product->id }}] || 0) + 1)"
                                                                        class="btn btn-outline-secondary btn-sm"
                                                                        style="padding: 0.25rem 0.5rem;">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <div class="product-total">
                                                                <span class="line-total" x-text="(quantities[{{ $product->id }}] || 0) > 0 ? 'PKR ' + ((quantities[{{ $product->id }}] || 0) * {{ $product->price }}).toFixed(2) : '-'"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- No Products Message -->
                                            <template x-if="!hasVisibleProducts()">
                                                <div class="text-center py-5">
                                                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                                    <p class="text-muted mt-3">No products match your search</p>
                                                </div>
                                            </template>

                                            <!-- Selected Products Summary -->
                                            <template x-if="getSelectedProductsCount() > 0">
                                                <div class="mt-3 p-3 bg-light rounded">
                                                    <small class="text-muted">
                                                        <strong x-text="getSelectedProductsCount()"></strong> product<span x-show="getSelectedProductsCount() !== 1">s</span> selected | 
                                                        Total: <strong class="text-primary" x-text="'PKR ' + calculateSubtotal().toFixed(2)"></strong>
                                                    </small>
                                                </div>
                                            </template>

                                            @error('products.*')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- SECTION 4: STITCHING DETAILS -->
                            <template x-if="['stitching', 'combined'].includes(orderType)">
                                <div class="col-12 mb-4">
                                    <div class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h5 class="card-title">Stitching Details</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Row 1: Fabric Type & Color -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="fabricType">Fabric Type</label>
                                                    <input type="text" id="fabricType" name="fabric_type" 
                                                        class="form-control @error('fabric_type') is-invalid @enderror"
                                                        placeholder="e.g., Cotton, Silk">
                                                    @error('fabric_type')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="fabricColor">Fabric Color</label>
                                                    <input type="text" id="fabricColor" name="fabric_color" 
                                                        class="form-control @error('fabric_color') is-invalid @enderror"
                                                        placeholder="e.g., Navy, White">
                                                    @error('fabric_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Row 2: Garment Type & Measurement ID -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="garmentType">Garment Type</label>
                                                    <select id="garmentType" name="garment_type" 
                                                        class="form-control @error('garment_type') is-invalid @enderror">
                                                        <option value="">-- Select Garment Type --</option>
                                                        <option value="custom">Custom</option>
                                                        <option value="shirt">Shirt</option>
                                                        <option value="trouser">Trouser</option>
                                                        <option value="kurta">Kurta</option>
                                                        <option value="dress">Dress</option>
                                                    </select>
                                                    @error('garment_type')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="measurementId">Measurement ID (if existing)</label>
                                                    <input type="number" id="measurementId" name="measurement_id" 
                                                        class="form-control @error('measurement_id') is-invalid @enderror"
                                                        placeholder="Optional - Enter existing measurement ID"
                                                        min="0">
                                                    @error('measurement_id')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Row 3: Design Details (Full Width) -->
                                                <div class="col-12 mb-3">
                                                    <label for="stitchingInstructions">Design Details / Instructions</label>
                                                    <textarea id="stitchingInstructions" name="stitching_instructions" 
                                                        class="form-control @error('stitching_instructions') is-invalid @enderror"
                                                        rows="3" placeholder="Describe the design or special instructions..."></textarea>
                                                    @error('stitching_instructions')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Row 4: Design Image (Full Width) -->
                                                <div class="col-12 mb-3">
                                                    <label for="designImage">Design Image (Optional)</label>
                                                    <input type="file" id="designImage" name="design_image" 
                                                        class="form-control @error('design_image') is-invalid @enderror"
                                                        accept="image/*">
                                                    <small class="text-muted d-block mt-1">Supported: JPG, PNG, GIF (Max 5MB)</small>
                                                    @error('design_image')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- SECTION 5: ADDITIONAL CHARGES -->
                            <div class="col-md-6 mb-4">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header">
                                        <h5 class="card-title">Additional Charges</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="stitchingCharge">Stitching Charge (PKR)</label>
                                            <input type="number" id="stitchingCharge" x-model.number="stitchingCharge"
                                                name="stitching_charge" class="form-control @error('stitching_charge') is-invalid @enderror"
                                                step="0.01" min="0" placeholder="0.00">
                                            @error('stitching_charge')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="discount">Discount (PKR)</label>
                                            <input type="number" id="discount" x-model.number="discount"
                                                name="discount" class="form-control @error('discount') is-invalid @enderror"
                                                step="0.01" min="0" placeholder="0.00">
                                            @error('discount')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="advancePayment">Advance Payment (PKR)</label>
                                            <input type="number" id="advancePayment" x-model.number="advancePayment"
                                                name="advance_payment" class="form-control @error('advance_payment') is-invalid @enderror"
                                                step="0.01" min="0" placeholder="0.00">
                                            @error('advance_payment')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 6: LIVE ORDER SUMMARY -->
                            <div class="col-md-6 mb-4">
                                <div class="card card-outline card-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="card-title mb-0">Order Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-8">Subtotal (Products):</div>
                                            <div class="col-4 text-right font-weight-bold">
                                                PKR <span x-text="calculateSubtotal().toFixed(2)">0.00</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-8">Stitching Charge:</div>
                                            <div class="col-4 text-right">
                                                PKR <span x-text="(stitchingCharge || 0).toFixed(2)">0.00</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-8">Discount:</div>
                                            <div class="col-4 text-right text-danger">
                                                -PKR <span x-text="(discount || 0).toFixed(2)">0.00</span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row mb-3">
                                            <div class="col-8 font-weight-bold">Grand Total:</div>
                                            <div class="col-4 text-right font-weight-bold text-danger" style="font-size: 1.25rem;">
                                                PKR <span x-text="calculateGrandTotal().toFixed(2)">0.00</span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-8">Advance Payment:</div>
                                            <div class="col-4 text-right">
                                                PKR <span x-text="(advancePayment || 0).toFixed(2)">0.00</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8 text-muted">Remaining Due:</div>
                                            <div class="col-4 text-right text-muted font-weight-bold">
                                                PKR <span x-text="(calculateGrandTotal() - (advancePayment || 0)).toFixed(2)">0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FORM ACTIONS -->
                            <div class="col-12 mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Create Order
                                </button>
                                <a href="{{ route('receptionist.orders.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: SELECT EXISTING CUSTOMER -->
<div x-cloak x-show="showCustomerModal" style="display: none;" class="modal fade" style="display: block !important; background: rgba(0,0,0,0.5);" @click.self="showCustomerModal = false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Existing Customer</h5>
                <button type="button" class="btn-close" @click="showCustomerModal = false"></button>
            </div>
            <div class="modal-body">
                <!-- Search Input -->
                <div class="mb-3">
                    <input type="text" 
                        x-model="customerSearch"
                        placeholder="🔍 Search by name, email, or phone..."
                        class="form-control form-control-lg"
                        style="border-radius: 6px;">
                </div>

                <!-- Customer List -->
                <div class="table-responsive">
                    <table class="table table-hover" style="margin-bottom: 0;">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="customer in filteredCustomers()" :key="customer.id">
                                <tr style="cursor: pointer;" @click="selectCustomer(customer)" class="align-middle">
                                    <td>
                                        <strong x-text="customer.name"></strong>
                                    </td>
                                    <td>
                                        <small x-text="customer.email || '-'"></small>
                                    </td>
                                    <td>
                                        <small x-text="customer.phone || '-'"></small>
                                    </td>
                                    <td>
                                        <small class="text-muted" x-text="customer.address || '-'"></small>
                                    </td>
                                    <td class="text-end">
                                        <i class="fas fa-arrow-right text-primary"></i>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <template x-if="filteredCustomers().length === 0">
                    <div class="text-center py-5">
                        <i class="fas fa-search" style="font-size: 2rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">No customers found</p>
                    </div>
                </template>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showCustomerModal = false">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
function orderForm() {
    return {
        // Customer selection state
        showCustomerModal: false,
        customerSearch: '',
        selectedCustomerId: null,
        walkInName: 'Walk-in Customer',
        walkInEmail: '',
        walkInPhone: '',
        walkInAddress: '',
        allCustomers: {!! json_encode($customers->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'email' => $c->email, 'phone' => $c->phone ?? '', 'address' => $c->address ?? ''])->values()) !!},

        // Order state
        orderType: '',
        quantities: {},
        stitchingCharge: 0,
        discount: 0,
        advancePayment: 0,
        productSearch: '',
        selectedCategory: null,
        allProducts: {!! json_encode($products->map(fn($p) => ['id' => $p->id, 'category_id' => $p->category_id, 'name' => $p->name, 'color' => $p->color, 'size' => $p->size])->values()) !!},

        // Product prices (passed from controller as JSON)
        productPrices: {!! $productPrices !!},

        // ==================== CUSTOMER METHODS ====================
        
        // Filter customers based on search
        filteredCustomers() {
            if (!this.customerSearch) return this.allCustomers;
            const search = this.customerSearch.toLowerCase();
            return this.allCustomers.filter(c => 
                c.name.toLowerCase().includes(search) || 
                c.email.toLowerCase().includes(search) || 
                c.phone.includes(search)
            );
        },

        // Select a customer from modal
        selectCustomer(customer) {
            this.selectedCustomerId = customer.id;
            this.walkInName = customer.name;
            this.walkInEmail = customer.email || '';
            this.walkInPhone = customer.phone || '';
            this.walkInAddress = customer.address || '';
            this.showCustomerModal = false;
            this.customerSearch = '';
        },

        // Clear customer selection
        clearCustomerSelection() {
            this.selectedCustomerId = null;
            this.walkInName = 'Walk-in Customer';
            this.walkInEmail = '';
            this.walkInPhone = '';
            this.walkInAddress = '';
        },

        // ==================== PRODUCT METHODS ====================

        // Check if product is visible based on category and search
        isProductVisible(categoryId, name, color, size) {
            const matchesCategory = this.selectedCategory === null || this.selectedCategory === categoryId;
            const matchesSearch = this.productMatchesSearch(name, color, size);
            return matchesCategory && matchesSearch;
        },

        // Check if product matches search
        productMatchesSearch(name, color, size) {
            if (!this.productSearch) return true;
            const search = this.productSearch.toLowerCase();
            return name.toLowerCase().includes(search) || 
                   color.toLowerCase().includes(search) || 
                   size.toLowerCase().includes(search);
        },

        // Check if any products are visible
        hasVisibleProducts() {
            return this.allProducts.some(p => this.isProductVisible(p.category_id, p.name, p.color, p.size));
        },

        // Get count of selected products
        getSelectedProductsCount() {
            return Object.values(this.quantities).filter(q => q > 0).length;
        },

        // Calculate subtotal by summing all selected product quantities × prices
        calculateSubtotal() {
            let subtotal = 0;
            for (let productId in this.quantities) {
                if (this.quantities[productId] > 0) {
                    subtotal += (this.quantities[productId] * this.productPrices[productId]);
                }
            }
            return subtotal;
        },

        // Calculate grand total: Subtotal + Stitching Charge - Discount
        calculateGrandTotal() {
            return this.calculateSubtotal() + (this.stitchingCharge || 0) - (this.discount || 0);
        },

        // ==================== FORM SUBMISSION ====================

        // Form submission
        submitForm() {
            // Client-side validation before submission
            if (!this.selectedCustomerId && !this.walkInPhone) {
                alert('Please select a customer or enter a phone number');
                return false;
            }
            if (!this.orderType) {
                alert('Please select an order type');
                return false;
            }

            // For cloth orders, ensure at least one product is selected
            if (['ready_made', 'combined'].includes(this.orderType)) {
                let hasProducts = false;
                for (let qty in this.quantities) {
                    if (this.quantities[qty] > 0) {
                        hasProducts = true;
                        break;
                    }
                }
                if (!hasProducts) {
                    alert('Please select at least one product with quantity');
                    return false;
                }
            }

            // Submit the form
            document.getElementById('orderForm').submit();
        }
    };
}
</script>

<style>
.product-card {
    background: white;
    border: 2px solid #e0e6ed;
    border-radius: 8px;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: all 0.3s ease;
    cursor: default;
}

.product-card:hover {
    border-color: var(--accent-color);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.15);
    transform: translateY(-2px);
}

.product-card.selected {
    border-color: var(--accent-color);
    background: rgba(212, 175, 55, 0.05);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
}

.product-header {
    flex-shrink: 0;
}

.product-name {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-dark);
    word-break: break-word;
}

.product-badges {
    display: flex;
    gap: 4px;
    margin-top: 4px;
    flex-wrap: wrap;
}

.product-badges .badge {
    font-size: 0.75rem;
    padding: 2px 6px;
    background: #f5f6fa;
    color: #666;
}

.product-price {
    font-size: 1rem;
    font-weight: 600;
    color: var(--accent-color);
    flex-shrink: 0;
}

.product-price del {
    opacity: 0.6;
}

.product-stock {
    font-size: 0.85rem;
    flex-shrink: 0;
}

.product-quantity {
    flex-shrink: 0;
}

.product-quantity .input-group-sm {
    height: 32px;
}

.product-quantity .btn-outline-secondary {
    border-color: #dee2e6;
    color: #666;
}

.product-quantity .btn-outline-secondary:hover {
    border-color: var(--accent-color);
    color: var(--accent-color);
    background: rgba(212, 175, 55, 0.1);
}

.product-quantity .form-control-sm {
    border-color: #dee2e6;
    font-size: 0.875rem;
}

.product-total {
    padding-top: 6px;
    border-top: 1px solid #e0e6ed;
    text-align: center;
    flex-shrink: 0;
}

.line-total {
    font-weight: 600;
    color: var(--accent-color);
    font-size: 0.9rem;
}

/* Gap utility for Bootstrap 4 compatibility */
.gap-2 {
    gap: 0.5rem;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .product-card {
        padding: 10px;
    }

    .product-name {
        font-size: 0.9rem;
    }
}

@media (max-width: 768px) {
    .product-card {
        padding: 8px;
    }

    .product-name {
        font-size: 0.85rem;
    }

    .product-price {
        font-size: 0.95rem;
    }

    .product-badges .badge {
        font-size: 0.7rem;
    }
}
</style>
@endsection
