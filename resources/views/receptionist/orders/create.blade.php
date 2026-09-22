@extends('receptionist.layouts.app')

@section('title', 'Create Order')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Create Order</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>Create New Order
                    </h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i>Validation Errors
                            </h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li class="mb-2">{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('receptionist.orders.store') }}" method="POST" enctype="multipart/form-data" id="orderForm">
                        @csrf

                        <div x-data="orderForm()" class="row">
                            <!-- SECTION A: CUSTOMER SELECTION -->
                            <div class="col-lg-12 mb-4">
                                <div class="card border-1">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-user me-2"></i>Customer Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Tabs: Existing vs New Customer -->
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="existingCustomerTab" data-bs-toggle="tab" data-bs-target="#existingCustomer" type="button" role="tab" aria-controls="existingCustomer" aria-selected="false">
                                                    <i class="fas fa-search me-2"></i>Existing Customer
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="newCustomerTab" data-bs-toggle="tab" data-bs-target="#newCustomer" type="button" role="tab" aria-controls="newCustomer" aria-selected="true">
                                                    <i class="fas fa-user-plus me-2"></i>New Customer
                                                </button>
                                            </li>
                                        </ul>

                                        <div class="tab-content mt-3">
                                            <!-- Existing Customer Tab -->
                                            <div class="tab-pane fade" id="existingCustomer" role="tabpanel" aria-labelledby="existingCustomerTab">
                                                <div class="mb-3">
                                                    <label for="customerSearch" class="form-label">Search Customer <span class="text-danger">*</span></label>
                                                    <div class="position-relative">
                                                        <input 
                                                            type="text" 
                                                            id="customerSearch" 
                                                            class="form-control form-control-lg"
                                                            x-model="customerSearch"
                                                            placeholder="Search by name, email, or phone..."
                                                            autocomplete="off"
                                                        >
                                                        <div class="position-absolute top-100 start-0 end-0 mt-1 bg-white border rounded shadow-sm" 
                                                             x-show="customerSearch.length > 0 && filteredCustomers.length > 0"
                                                             style="max-height: 200px; overflow-y: auto; z-index: 1000;">
                                                            <template x-for="customer in filteredCustomers" :key="customer.id">
                                                                <div class="p-2 border-bottom cursor-pointer hover-light" 
                                                                     @click="selectExistingCustomer(customer)"
                                                                     :class="{ 'bg-light': selectedCustomerId === customer.id }">
                                                                    <div class="fw-bold" x-text="customer.name"></div>
                                                                    <small class="text-muted">
                                                                        <span x-text="customer.email"></span>
                                                                        <template x-if="customer.phone">
                                                                            | <span x-text="customer.phone"></span>
                                                                        </template>
                                                                    </small>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted d-block mt-2">
                                                        <i class="fas fa-info-circle me-1"></i>Start typing to search customers
                                                    </small>
                                                </div>

                                                <!-- Selected Customer Display -->
                                                <div x-show="selectedCustomerId !== null" class="alert alert-info">
                                                    <strong>Selected Customer:</strong>
                                                    <div x-text="getSelectedCustomerName()"></div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2" @click="clearCustomerSelection()">
                                                        <i class="fas fa-times me-1"></i>Clear Selection
                                                    </button>
                                                </div>

                                                <!-- Hidden input for form submission -->
                                                <input type="hidden" name="customer_id" x-model="selectedCustomerId" @change="clearNewCustomerFields()">
                                                @error('customer_id')
                                                    <div class="alert alert-danger mt-2">
                                                        <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <!-- New Customer Tab -->
                                            <div class="tab-pane fade show active" id="newCustomer" role="tabpanel" aria-labelledby="newCustomerTab">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newCustomerName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="text" 
                                                            id="newCustomerName"
                                                            class="form-control @error('new_customer_name') is-invalid @enderror"
                                                            name="new_customer_name"
                                                            x-model="newCustomer.name"
                                                            placeholder="Enter customer name"
                                                            @change="clearExistingCustomerSelection()"
                                                        >
                                                        @error('new_customer_name')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newCustomerEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="email" 
                                                            id="newCustomerEmail"
                                                            class="form-control @error('new_customer_email') is-invalid @enderror"
                                                            name="new_customer_email"
                                                            x-model="newCustomer.email"
                                                            placeholder="email@example.com"
                                                            @change="clearExistingCustomerSelection()"
                                                        >
                                                        @error('new_customer_email')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newCustomerPhone" class="form-label">Phone <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="tel" 
                                                            id="newCustomerPhone"
                                                            class="form-control @error('new_customer_phone') is-invalid @enderror"
                                                            name="new_customer_phone"
                                                            x-model="newCustomer.phone"
                                                            placeholder="03001234567"
                                                            @change="clearExistingCustomerSelection()"
                                                        >
                                                        @error('new_customer_phone')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newCustomerCity" class="form-label">City</label>
                                                        <input 
                                                            type="text" 
                                                            id="newCustomerCity"
                                                            class="form-control"
                                                            name="new_customer_city"
                                                            x-model="newCustomer.city"
                                                            placeholder="Enter city"
                                                            @change="clearExistingCustomerSelection()"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="newCustomerAddress" class="form-label">Address</label>
                                                    <textarea 
                                                        id="newCustomerAddress"
                                                        class="form-control"
                                                        name="new_customer_address"
                                                        x-model="newCustomer.address"
                                                        placeholder="Enter address"
                                                        rows="2"
                                                        @change="clearExistingCustomerSelection()"
                                                    ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION B: ORDER TYPE SELECTION -->
                            <div class="col-lg-12 mb-4">
                                <div class="card border-1">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-box me-2"></i>Order Type
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        name="order_type" 
                                                        id="orderTypeReady" 
                                                        value="ready_made"
                                                        x-model="orderType"
                                                    >
                                                    <label class="form-check-label w-100 p-3 border rounded cursor-pointer" for="orderTypeReady" :class="{ 'bg-light border-primary': orderType === 'ready_made' }">
                                                        <strong>Ready Made</strong>
                                                        <div class="small text-muted mt-1">Cloth/fabric products only</div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        name="order_type" 
                                                        id="orderTypeStitching" 
                                                        value="stitching"
                                                        x-model="orderType"
                                                        :checked="orderType === 'stitching'"
                                                    >
                                                    <label class="form-check-label w-100 p-3 border rounded cursor-pointer" for="orderTypeStitching" :class="{ 'bg-light border-primary': orderType === 'stitching' }">
                                                        <strong>Custom Stitching</strong>
                                                        <div class="small text-muted mt-1">Tailoring/custom stitching only</div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        name="order_type" 
                                                        id="orderTypeCombined" 
                                                        value="combined"
                                                        x-model="orderType"
                                                        :checked="orderType === 'combined'"
                                                    >
                                                    <label class="form-check-label w-100 p-3 border rounded cursor-pointer" for="orderTypeCombined" :class="{ 'bg-light border-primary': orderType === 'combined' }">
                                                        <strong>Cloth + Stitching</strong>
                                                        <div class="small text-muted mt-1">Products and tailoring service</div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden input to ensure order_type is submitted -->
                            <input type="hidden" name="order_type_hidden" x-model="orderType">
                            @error('order_type')
                                <div class="col-lg-12 mb-4">
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                    </div>
                                </div>
                            @enderror

                            <!-- SECTION C: PRODUCTS (for ready_made and combined) -->
                            <div class="col-lg-12 mb-4" x-show="['ready_made', 'combined'].includes(orderType)">
                                <div class="card border-1">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0" x-show="!productSearch.trim() && !selectedCategory">
                                            <i class="fas fa-clock me-2"></i>Recently Used Products
                                        </h5>
                                        <h5 class="card-title mb-0" x-show="productSearch.trim() || selectedCategory">
                                            <i class="fas fa-shopping-bag me-2"></i>Products
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="productSearch" class="form-label">Search Products</label>
                                                <input 
                                                    type="text" 
                                                    id="productSearch"
                                                    class="form-control"
                                                    x-model="productSearch"
                                                    placeholder="Search by product name..."
                                                    autocomplete="off"
                                                >
                                            </div>
                                            <div class="col-md-6">
                                                <label for="categoryFilter" class="form-label">Filter by Category</label>
                                                <select 
                                                    id="categoryFilter"
                                                    class="form-select"
                                                    x-model="selectedCategory"
                                                >
                                                    <option value="">All Categories</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Products List -->
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Product Name</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                        <th>Quantity</th>
                                                        <th>Total</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="product in filteredProducts" :key="product.id">
                                                        <tr>
                                                            <td x-text="product.name"></td>
                                                            <td>
                                                                <strong>{{ env('CURRENCY_SYMBOL', 'Rs.') }} <span x-text="(parseFloat(product.final_price || product.price)).toFixed(2)"></span></strong>
                                                            </td>
                                                            <td>
                                                                <span class="badge" :class="product.stock_quantity > 5 ? 'bg-success' : (product.stock_quantity > 0 ? 'bg-warning' : 'bg-danger')">
                                                                    <span x-text="product.stock_quantity"></span>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <input 
                                                                    type="number" 
                                                                    class="form-control form-control-sm" 
                                                                    style="width: 80px;"
                                                                    :value="getProductQuantity(product.id)"
                                                                    @input="updateProductQuantity(product.id, $event.target.value)"
                                                                    min="0"
                                                                    :max="product.stock_quantity"
                                                                >
                                                            </td>
                                                            <td>
                                                                {{ env('CURRENCY_SYMBOL', 'Rs.') }} <span x-text="(getProductQuantity(product.id) * parseFloat(product.final_price || product.price)).toFixed(2)"></span>
                                                            </td>
                                                            <td>
                                                                <button 
                                                                    type="button" 
                                                                    class="btn btn-sm btn-danger"
                                                                    @click="removeProduct(product.id)"
                                                                    x-show="getProductQuantity(product.id) > 0"
                                                                >
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div x-show="filteredProducts.length === 0" class="alert alert-info mt-3">
                                            <span x-show="!productSearch.trim() && !selectedCategory">
                                                <i class="fas fa-info-circle me-2"></i>No recently used products. Search or filter to find products.
                                            </span>
                                            <span x-show="productSearch.trim() || selectedCategory">
                                                <i class="fas fa-info-circle me-2"></i>No products found. Try adjusting your search or filters.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION E: STITCHING INFORMATION (for stitching and combined) -->
                            <div class="col-lg-12 mb-4" x-show="['stitching', 'combined'].includes(orderType)">
                                <div class="card border-1">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-scissors me-2"></i>Stitching Details
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="fabricType" class="form-label">Fabric Type</label>
                                                <input 
                                                    type="text" 
                                                    id="fabricType"
                                                    class="form-control @error('fabric_type') is-invalid @enderror"
                                                    name="fabric_type"
                                                    x-model="stitching.fabricType"
                                                    placeholder="e.g., Cotton, Silk, Wool"
                                                >
                                                @error('fabric_type')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="fabricColor" class="form-label">Fabric Color</label>
                                                <input 
                                                    type="text" 
                                                    id="fabricColor"
                                                    class="form-control @error('fabric_color') is-invalid @enderror"
                                                    name="fabric_color"
                                                    x-model="stitching.fabricColor"
                                                    placeholder="e.g., Black, Blue, Red"
                                                >
                                                @error('fabric_color')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="garmentType" class="form-label">Garment Type</label>
                                                <select 
                                                    id="garmentType"
                                                    class="form-select @error('garment_type') is-invalid @enderror"
                                                    name="garment_type"
                                                    x-model="stitching.garmentType"
                                                >
                                                    <option value="">Select garment type</option>
                                                    <option value="shirt">Shirt</option>
                                                    <option value="pants">Pants</option>
                                                    <option value="suit">Suit</option>
                                                    <option value="dress">Dress</option>
                                                    <option value="custom">Custom</option>
                                                </select>
                                                @error('garment_type')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="measurementId" class="form-label">Measurement</label>
                                                <select 
                                                    id="measurementId"
                                                    class="form-select @error('measurement_id') is-invalid @enderror"
                                                    name="measurement_id"
                                                    x-model="stitching.measurementId"
                                                >
                                                    <option value="">Select measurement</option>
                                                    <template x-if="selectedCustomerId !== null">
                                                        <template x-for="measurement in customerMeasurements" :key="measurement.id">
                                                            <option :value="measurement.id" x-text="measurement.name + ' (' + measurement.created_at + ')'"></option>
                                                        </template>
                                                    </template>
                                                </select>
                                                <small class="text-muted d-block mt-1">Select customer first to load measurements or create a new one below</small>
                                                @error('measurement_id')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- New Measurement Fields -->
                                        <div class="alert alert-light border mb-3">
                                            <h6 class="alert-heading mb-3">
                                                <i class="fas fa-tape me-2"></i>Or Create New Measurement
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="newMeasurementName" class="form-label">Measurement Profile Name</label>
                                                    <input 
                                                        type="text" 
                                                        id="newMeasurementName"
                                                        class="form-control"
                                                        x-model="newMeasurement.profile_name"
                                                        placeholder="e.g., My Standard Shirt"
                                                    >
                                                    <small class="text-muted">Save this measurement for future use</small>
                                                </div>
                                            </div>
                                            
                                            <div class="border-top pt-3 mb-3">
                                                <h6 class="mb-3"><i class="fas fa-ruler me-2"></i>Enter Your Measurements (in cm)</h6>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newMeasurementChest" class="form-label">Chest <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="number" 
                                                            id="newMeasurementChest"
                                                            class="form-control"
                                                            x-model.number="newMeasurement.chest"
                                                            placeholder="Chest circumference"
                                                            step="0.5"
                                                        >
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newMeasurementShoulder" class="form-label">Shoulder <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="number" 
                                                            id="newMeasurementShoulder"
                                                            class="form-control"
                                                            x-model.number="newMeasurement.shoulder"
                                                            placeholder="Shoulder width"
                                                            step="0.5"
                                                        >
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newMeasurementSleeveLength" class="form-label">Sleeve Length <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="number" 
                                                            id="newMeasurementSleeveLength"
                                                            class="form-control"
                                                            x-model.number="newMeasurement.sleeve_length"
                                                            placeholder="Length from shoulder to wrist"
                                                            step="0.5"
                                                        >
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newMeasurementShirtLength" class="form-label">Shirt Length <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="number" 
                                                            id="newMeasurementShirtLength"
                                                            class="form-control"
                                                            x-model.number="newMeasurement.shirt_length"
                                                            placeholder="Length from shoulder to hem"
                                                            step="0.5"
                                                        >
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newMeasurementNeck" class="form-label">Neck <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="number" 
                                                            id="newMeasurementNeck"
                                                            class="form-control"
                                                            x-model.number="newMeasurement.neck"
                                                            placeholder="Neck circumference"
                                                            step="0.5"
                                                        >
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newMeasurementWaist" class="form-label">Waist <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="number" 
                                                            id="newMeasurementWaist"
                                                            class="form-control"
                                                            x-model.number="newMeasurement.waist"
                                                            placeholder="Waist circumference"
                                                            step="0.5"
                                                        >
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newMeasurementTrouserLength" class="form-label">Trouser Length <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="number" 
                                                            id="newMeasurementTrouserLength"
                                                            class="form-control"
                                                            x-model.number="newMeasurement.trouser_length"
                                                            placeholder="Inseam length"
                                                            step="0.5"
                                                        >
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newMeasurementBottom" class="form-label">Bottom (Pant Width) <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="number" 
                                                            id="newMeasurementBottom"
                                                            class="form-control"
                                                            x-model.number="newMeasurement.bottom"
                                                            placeholder="Bottom opening width"
                                                            step="0.5"
                                                        >
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newMeasurementThigh" class="form-label">Thigh</label>
                                                        <input 
                                                            type="number" 
                                                            id="newMeasurementThigh"
                                                            class="form-control"
                                                            x-model.number="newMeasurement.thigh"
                                                            placeholder="Thigh circumference"
                                                            step="0.5"
                                                        >
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newMeasurementCuff" class="form-label">Cuff Size</label>
                                                        <input 
                                                            type="number" 
                                                            id="newMeasurementCuff"
                                                            class="form-control"
                                                            x-model.number="newMeasurement.cuff_size"
                                                            placeholder="Cuff circumference"
                                                            step="0.5"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="stitchingInstructions" class="form-label">Stitching Instructions</label>
                                            <textarea 
                                                id="stitchingInstructions"
                                                class="form-control @error('stitching_instructions') is-invalid @enderror"
                                                name="stitching_instructions"
                                                x-model="stitching.instructions"
                                                placeholder="Provide any special stitching instructions..."
                                                rows="3"
                                            ></textarea>
                                            @error('stitching_instructions')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="designImage" class="form-label">Design Image</label>
                                            <div class="input-group">
                                                <input 
                                                    type="file" 
                                                    id="designImage"
                                                    class="form-control @error('design_image') is-invalid @enderror"
                                                    name="design_image"
                                                    accept="image/*"
                                                    @change="previewDesignImage($event)"
                                                >
                                                <small class="form-text text-muted d-block mt-1">Accepted formats: JPG, PNG, GIF (Max: 5MB)</small>
                                            </div>
                                            <div x-show="designImagePreview" class="mt-2">
                                                <img :src="designImagePreview" style="max-width: 150px; max-height: 150px;" class="rounded border">
                                            </div>
                                            @error('design_image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="stitchingCharge" class="form-label">Stitching Charge</label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{ env('CURRENCY_SYMBOL', 'Rs.') }}</span>
                                                <input 
                                                    type="number" 
                                                    id="stitchingCharge"
                                                    class="form-control @error('stitching_charge') is-invalid @enderror"
                                                    name="stitching_charge"
                                                    x-model.number="stitching.charge"
                                                    placeholder="0.00"
                                                    step="0.01"
                                                    min="0"
                                                    @input="recalculateTotal()"
                                                >
                                            </div>
                                            @error('stitching_charge')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION F: FINANCIAL INFORMATION -->
                            <div class="col-lg-12 mb-4">
                                <div class="card border-1 bg-light">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-calculator me-2"></i>Financial Summary
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-bold">Subtotal</label>
                                                <div class="form-control-plaintext fs-5">
                                                    {{ env('CURRENCY_SYMBOL', 'Rs.') }} <span x-text="subtotal.toFixed(2)"></span>
                                                </div>
                                                <input type="hidden" name="subtotal" :value="subtotal.toFixed(2)">
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label for="discount" class="form-label fw-bold">Discount</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">{{ env('CURRENCY_SYMBOL', 'Rs.') }}</span>
                                                    <input 
                                                        type="number" 
                                                        id="discount"
                                                        class="form-control @error('discount') is-invalid @enderror"
                                                        name="discount"
                                                        x-model.number="discount"
                                                        placeholder="0.00"
                                                        step="0.01"
                                                        min="0"
                                                        @input="recalculateTotal()"
                                                    >
                                                </div>
                                                @error('discount')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label for="tax" class="form-label fw-bold">Tax</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">{{ env('CURRENCY_SYMBOL', 'Rs.') }}</span>
                                                    <input 
                                                        type="number" 
                                                        id="tax"
                                                        class="form-control @error('tax') is-invalid @enderror"
                                                        name="tax"
                                                        x-model.number="tax"
                                                        placeholder="0.00"
                                                        step="0.01"
                                                        min="0"
                                                        @input="recalculateTotal()"
                                                    >
                                                </div>
                                                @error('tax')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-bold text-success">Total</label>
                                                <div class="form-control-plaintext fs-5 fw-bold text-success">
                                                    {{ env('CURRENCY_SYMBOL', 'Rs.') }} <span x-text="total.toFixed(2)"></span>
                                                </div>
                                                <input type="hidden" name="total" :value="total.toFixed(2)">
                                            </div>
                                        </div>

                                        <div class="alert alert-info mt-3 mb-0">
                                            <small>
                                                <strong>Formula:</strong> Subtotal + Stitching Charge + Tax - Discount = Total
                                            </small>
                                        </div>

                                        @error('subtotal')
                                            <div class="alert alert-danger mt-2 mb-0">
                                                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        @error('total')
                                            <div class="alert alert-danger mt-2 mb-0">
                                                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION G: ADDITIONAL INFORMATION -->
                            <div class="col-lg-12 mb-4">
                                <div class="card border-1">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-sticky-note me-2"></i>Additional Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea 
                                                id="notes"
                                                class="form-control @error('notes') is-invalid @enderror"
                                                name="notes"
                                                x-model="notes"
                                                placeholder="Add any special notes about this order..."
                                                rows="2"
                                            ></textarea>
                                            @error('notes')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="deliveryDate" class="form-label">Delivery Date</label>
                                            <input 
                                                type="date" 
                                                id="deliveryDate"
                                                class="form-control @error('delivery_date') is-invalid @enderror"
                                                name="delivery_date"
                                                x-model="deliveryDate"
                                                :min="minDeliveryDate"
                                            >
                                            <small class="text-muted d-block mt-1">Must be a future date</small>
                                            @error('delivery_date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs for products array -->
                            <template x-for="(product, index) in selectedProducts" :key="product.id">
                                <input type="hidden" :name="`product_ids[${index}]`" :value="product.id">
                                <input type="hidden" :name="`quantities[${index}]`" :value="product.quantity">
                            </template>

                            <!-- Hidden inputs for new measurement -->
                            <template x-if="newMeasurement.profile_name.trim() !== ''">
                                <input type="hidden" name="new_measurement_profile_name" x-model="newMeasurement.profile_name">
                                <input type="hidden" name="new_measurement_chest" x-model.number="newMeasurement.chest">
                                <input type="hidden" name="new_measurement_shoulder" x-model.number="newMeasurement.shoulder">
                                <input type="hidden" name="new_measurement_sleeve_length" x-model.number="newMeasurement.sleeve_length">
                                <input type="hidden" name="new_measurement_shirt_length" x-model.number="newMeasurement.shirt_length">
                                <input type="hidden" name="new_measurement_neck" x-model.number="newMeasurement.neck">
                                <input type="hidden" name="new_measurement_waist" x-model.number="newMeasurement.waist">
                                <input type="hidden" name="new_measurement_trouser_length" x-model.number="newMeasurement.trouser_length">
                                <input type="hidden" name="new_measurement_bottom" x-model.number="newMeasurement.bottom">
                                <input type="hidden" name="new_measurement_thigh" x-model.number="newMeasurement.thigh">
                                <input type="hidden" name="new_measurement_cuff_size" x-model.number="newMeasurement.cuff_size">
                            </template>

                            <!-- SUBMIT BUTTON -->
                            <div class="col-lg-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg" @click="prepareSubmission($event)">
                                        <i class="fas fa-save me-2"></i>Create Order
                                    </button>
                                    <a href="{{ route('receptionist.orders.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function orderForm() {
    return {
        // Customer section
        customerSearch: '',
        selectedCustomerId: null,
        customerMeasurements: [],
        newCustomer: {
            name: '',
            email: '',
            phone: '',
            city: '',
            address: ''
        },

        // Order type
        orderType: 'ready_made',

        // Products section
        productSearch: '',
        selectedCategory: '',
        productQuantities: {},

        // Stitching section
        stitching: {
            fabricType: '',
            fabricColor: '',
            garmentType: '',
            measurementId: null,
            instructions: '',
            charge: 0
        },
        
        // New measurement section
        newMeasurement: {
            profile_name: '',
            chest: null,
            shoulder: null,
            sleeve_length: null,
            shirt_length: null,
            neck: null,
            waist: null,
            trouser_length: null,
            bottom: null,
            thigh: null,
            cuff_size: null,
        },
        
        designImagePreview: null,

        // Financial section
        discount: 0,
        tax: 0,

        // Additional info
        notes: '',
        deliveryDate: null,

        // All data
        allCustomers: @json($customers),
        allProducts: @json($products),
        allCategories: @json($categories),

        // Computed properties
        get filteredCustomers() {
            if (!this.customerSearch.trim()) return [];
            const search = this.customerSearch.toLowerCase();
            return this.allCustomers.filter(c => 
                c.name.toLowerCase().includes(search) ||
                c.email.toLowerCase().includes(search) ||
                (c.phone && c.phone.includes(search))
            );
        },

        get filteredProducts() {
            let products = this.allProducts;

            // If no search term and no category filter, show last 4 used products
            if (!this.productSearch.trim() && !this.selectedCategory) {
                const lastUsedIds = this.getLastUsedProductIds();
                if (lastUsedIds.length > 0) {
                    products = products.filter(p => lastUsedIds.includes(p.id));
                    // Sort by usage order (most recent first)
                    products = products.sort((a, b) => {
                        return lastUsedIds.indexOf(a.id) - lastUsedIds.indexOf(b.id);
                    });
                }
            } else {
                // Apply filters - search through ALL products
                if (this.selectedCategory) {
                    products = products.filter(p => p.category_id == this.selectedCategory);
                }

                if (this.productSearch.trim()) {
                    const search = this.productSearch.toLowerCase();
                    products = products.filter(p => p.name.toLowerCase().includes(search));
                }
            }

            // Limit to 4 products
            return products.slice(0, 4);
        },

        get selectedProducts() {
            const products = [];
            for (const [productId, quantity] of Object.entries(this.productQuantities)) {
                if (quantity > 0) {
                    const product = this.allProducts.find(p => p.id == productId);
                    if (product) {
                        products.push({
                            id: parseInt(productId),
                            quantity: parseInt(quantity),
                            price: parseFloat(product.final_price || product.price)
                        });
                    }
                }
            }
            return products;
        },

        get subtotal() {
            return this.selectedProducts.reduce((sum, p) => sum + (p.price * p.quantity), 0);
        },

        get total() {
            return this.subtotal + this.stitching.charge + this.tax - this.discount;
        },

        get minDeliveryDate() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            return tomorrow.toISOString().split('T')[0];
        },

        // Methods
        selectExistingCustomer(customer) {
            this.selectedCustomerId = customer.id;
            this.customerSearch = '';
            this.loadCustomerMeasurements(customer.id);
        },

        getSelectedCustomerName() {
            const customer = this.allCustomers.find(c => c.id == this.selectedCustomerId);
            return customer ? `${customer.name} (${customer.email})` : '';
        },

        clearCustomerSelection() {
            this.selectedCustomerId = null;
            this.customerSearch = '';
            this.customerMeasurements = [];
        },

        clearExistingCustomerSelection() {
            this.selectedCustomerId = null;
        },

        clearNewCustomerFields() {
            this.newCustomer = {
                name: '',
                email: '',
                phone: '',
                city: '',
                address: ''
            };
        },

        loadCustomerMeasurements(customerId) {
            // Fetch customer measurements from API
            fetch(`/receptionist/customers/${customerId}/measurements-api`)
                .then(response => response.json())
                .then(data => {
                    this.customerMeasurements = data || [];
                    console.log('Loaded measurements:', this.customerMeasurements);
                })
                .catch(error => {
                    console.error('Error loading measurements:', error);
                    this.customerMeasurements = [];
                });
        },

        getProductQuantity(productId) {
            return parseInt(this.productQuantities[productId] || 0);
        },

        updateProductQuantity(productId, quantity) {
            const qty = parseInt(quantity) || 0;
            if (qty > 0) {
                this.productQuantities[productId] = qty;
                // Save product to last used products
                this.saveLastUsedProduct(productId);
            } else {
                delete this.productQuantities[productId];
            }
            this.recalculateTotal();
        },

        removeProduct(productId) {
            delete this.productQuantities[productId];
            this.recalculateTotal();
        },

        // LocalStorage methods for last used products
        getLastUsedProductIds() {
            try {
                const stored = localStorage.getItem('lastUsedProducts');
                return stored ? JSON.parse(stored) : [];
            } catch (error) {
                console.error('Error reading last used products:', error);
                return [];
            }
        },

        saveLastUsedProduct(productId) {
            try {
                let lastUsed = this.getLastUsedProductIds();
                // Remove if already exists
                lastUsed = lastUsed.filter(id => id !== productId);
                // Add to beginning
                lastUsed.unshift(productId);
                // Keep only last 4
                lastUsed = lastUsed.slice(0, 4);
                localStorage.setItem('lastUsedProducts', JSON.stringify(lastUsed));
            } catch (error) {
                console.error('Error saving last used product:', error);
            }
        },

        previewDesignImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.designImagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        recalculateTotal() {
            // Just trigger reactivity
            this.tax = this.tax;
        },

        prepareSubmission(event) {
            // Client-side validation - prevent submission if required fields are missing
            if (this.selectedCustomerId === null && this.newCustomer.name.trim() === '') {
                event.preventDefault();
                alert('❌ Please select an existing customer or enter a new customer name');
                return;
            }

            if (!this.orderType) {
                event.preventDefault();
                alert('❌ Please select an order type (Ready Made, Stitching, or Combined)');
                return;
            }

            if (['ready_made', 'combined'].includes(this.orderType) && this.selectedProducts.length === 0) {
                event.preventDefault();
                alert('❌ Please select at least one product for ' + this.orderType + ' order');
                return;
            }

            // Server-side validation will check all other fields and show detailed errors
            // Update all hidden inputs BEFORE form submission
            const form = document.getElementById('orderForm');
            
            // Remove old hidden product inputs before adding new ones
            const oldProductInputs = form.querySelectorAll('input[name*="product_ids"], input[name*="quantities"]');
            oldProductInputs.forEach(input => input.remove());
            
            // Create new hidden inputs for selected products
            this.selectedProducts.forEach((product, index) => {
                const productIdInput = document.createElement('input');
                productIdInput.type = 'hidden';
                productIdInput.name = `product_ids[${index}]`;
                productIdInput.value = product.id;
                form.appendChild(productIdInput);
                
                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = `quantities[${index}]`;
                quantityInput.value = product.quantity;
                form.appendChild(quantityInput);
            });
            
            // Ensure order_type is set - check which radio button should be checked
            const radioButtons = form.querySelectorAll('input[name="order_type"]');
            radioButtons.forEach(radio => {
                radio.checked = (radio.value === this.orderType);
            });
            
            // Update financial hidden inputs
            const subtotalInput = form.querySelector('input[name="subtotal"]');
            const totalInput = form.querySelector('input[name="total"]');
            const orderTypeHidden = form.querySelector('input[name="order_type_hidden"]');
            
            if (subtotalInput) subtotalInput.value = this.subtotal.toFixed(2);
            if (totalInput) totalInput.value = this.total.toFixed(2);
            if (orderTypeHidden) orderTypeHidden.value = this.orderType;
            
            console.log('Form validation passed, submitting to server...');
            console.log('Order Type:', this.orderType);
            console.log('Customer ID:', this.selectedCustomerId);
            console.log('Selected Products:', this.selectedProducts.length);
            console.log('Products:', this.selectedProducts);
        }
    };
}
</script>

<style>
.cursor-pointer {
    cursor: pointer;
}

.hover-light:hover {
    background-color: #f8f9fa !important;
}

.form-check-label {
    margin: 0 !important;
    display: block !important;
}

@media (max-width: 768px) {
    .col-md-3, .col-md-4, .col-md-6 {
        margin-bottom: 1rem;
    }
}
</style>
@endsection
