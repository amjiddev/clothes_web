@extends('receptionist.layouts.app')

@section('title', 'Create Order - Unified POS')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Create Order</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-shopping-cart me-2"></i>Create New Order
            </h1>
            <p class="text-muted">Professional POS-Style Order Creation</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Form - Left Side (70%) -->
    <div class="col-lg-8">
        <form id="orderForm" method="POST" action="{{ route('receptionist.orders.store-unified') }}" enctype="multipart/form-data">
            @csrf

            <!-- 1. CUSTOMER SECTION -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2 text-primary"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="customer_id" class="form-label fw-bold">Select Customer <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="customer_id" name="customer_id" required>
                                <option value="">-- Select a Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" data-email="{{ $customer->email }}" data-phone="{{ $customer->phone }}">
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-info w-100 mt-4" data-bs-toggle="modal" data-bs-target="#newCustomerModal">
                                <i class="fas fa-plus me-1"></i>New Customer
                            </button>
                        </div>
                    </div>

                    <!-- Customer Info Display -->
                    <div id="customerInfo" class="mt-3 p-3 bg-light rounded d-none">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Email:</small>
                                <div id="customerEmail" class="fw-bold"></div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Phone:</small>
                                <div id="customerPhone" class="fw-bold"></div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Status:</small>
                                <div id="customerStatus" class="fw-bold"><span class="badge bg-success">Active</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. ORDER TYPE SECTION -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>Order Type
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-check order-type-radio">
                                <input class="form-check-input" type="radio" id="type_ready_made" name="order_type" value="ready_made" required>
                                <label class="form-check-label" for="type_ready_made">
                                    <strong>Cloth Only</strong>
                                    <br>
                                    <small class="text-muted">Ready-made products</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check order-type-radio">
                                <input class="form-check-input" type="radio" id="type_stitching" name="order_type" value="stitching" required>
                                <label class="form-check-label" for="type_stitching">
                                    <strong>Stitching Only</strong>
                                    <br>
                                    <small class="text-muted">Custom tailoring</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check order-type-radio">
                                <input class="form-check-input" type="radio" id="type_combined" name="order_type" value="combined" required>
                                <label class="form-check-label" for="type_combined">
                                    <strong>Cloth + Stitching</strong>
                                    <br>
                                    <small class="text-muted">Both products & tailoring</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. PRODUCTS SECTION (Hidden for Stitching Only) -->
            <div class="card border-0 shadow-sm mb-4" id="productsSection" style="display: none;">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-box me-2 text-primary"></i>Products
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless" id="productsTable">
                            <thead>
                                <tr class="border-bottom">
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th width="80">Qty</th>
                                    <th width="100">Total</th>
                                    <th width="60">Action</th>
                                </tr>
                            </thead>
                            <tbody id="productRows">
                                <tr class="product-row" data-row-index="0">
                                    <td>
                                        <select class="form-select form-select-sm product-select" name="product_ids[]" required>
                                            <option value="">-- Select Product --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->final_price }}" data-stock="{{ $product->stock_quantity }}">
                                                    {{ $product->name }} (Stock: {{ $product->stock_quantity }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm product-price" readonly value="0.00">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm product-qty" name="quantities[]" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm product-total" readonly value="0.00">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger btn-remove-product d-none">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary mt-2" id="addProductBtn">
                        <i class="fas fa-plus me-1"></i>Add Product
                    </button>
                </div>
            </div>

            <!-- 4. STITCHING DETAILS SECTION (Hidden for Cloth Only) -->
            <div class="card border-0 shadow-sm mb-4" id="stitchingSection" style="display: none;">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-needle me-2 text-primary"></i>Stitching Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fabric_type" class="form-label">Fabric Type</label>
                            <input type="text" class="form-control" id="fabric_type" name="fabric_type" placeholder="e.g., Cotton, Silk">
                        </div>
                        <div class="col-md-6">
                            <label for="fabric_color" class="form-label">Fabric Color</label>
                            <input type="text" class="form-control" id="fabric_color" name="fabric_color" placeholder="e.g., Black, White">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="garment_type" class="form-label">Garment Type</label>
                            <select class="form-select" id="garment_type" name="garment_type">
                                <option value="">-- Select Garment --</option>
                                <option value="shirt">Shirt</option>
                                <option value="pants">Pants</option>
                                <option value="dress">Dress</option>
                                <option value="kurta">Kurta</option>
                                <option value="suit">Suit</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="measurement_id" class="form-label">Customer Measurement</label>
                            <select class="form-select" id="measurement_id" name="measurement_id">
                                <option value="">-- Select Measurement --</option>
                            </select>
                            <small class="text-muted">Select customer first to load measurements</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stitching_instructions" class="form-label">Stitching Instructions</label>
                        <textarea class="form-control" id="stitching_instructions" name="stitching_instructions" rows="3" placeholder="Special instructions for stitching..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="design_image" class="form-label">Design Image</label>
                        <input type="file" class="form-control" id="design_image" name="design_image" accept="image/*">
                        <small class="text-muted">Upload design image if available</small>
                    </div>
                </div>
            </div>

            <!-- 5. ADDITIONAL CHARGES & DISCOUNT SECTION -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2 text-primary"></i>Charges & Adjustments
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="additional_charges" class="form-label">Additional Charges</label>
                            <input type="number" class="form-control" id="additional_charges" name="additional_charges" placeholder="0.00" min="0" step="0.01" value="0">
                        </div>
                        <div class="col-md-6">
                            <label for="additional_charges_description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="additional_charges_description" name="additional_charges_description" placeholder="e.g., Delivery fee">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="discount" class="form-label">Discount</label>
                            <input type="number" class="form-control" id="discount" name="discount" placeholder="0.00" min="0" step="0.01" value="0">
                        </div>
                        <div class="col-md-4">
                            <label for="discount_type" class="form-label">Type</label>
                            <select class="form-select" id="discount_type" name="discount_type">
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tax" class="form-label">Tax</label>
                            <input type="number" class="form-control" id="tax" name="tax" placeholder="0.00" min="0" step="0.01" value="0">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <label for="tax_type" class="form-label">Tax Type</label>
                            <select class="form-select" id="tax_type" name="tax_type">
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. ORDER NOTES SECTION -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note me-2 text-primary"></i>Order Notes
                    </h5>
                </div>
                <div class="card-body">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special instructions or notes for this order..."></textarea>
                </div>
            </div>

            <!-- 7. DELIVERY DATE SECTION -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar me-2 text-primary"></i>Delivery Information
                    </h5>
                </div>
                <div class="card-body">
                    <label for="delivery_date" class="form-label">Expected Delivery Date</label>
                    <input type="date" class="form-control" id="delivery_date" name="delivery_date">
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Panel - Right Side (30%) -->
    <div class="col-lg-4">
        <!-- 8. LIVE ORDER SUMMARY -->
        <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-receipt me-2"></i>Order Summary
                </h5>
            </div>
            <div class="card-body">
                <!-- Products Summary -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Subtotal:</small>
                        <strong>₹<span id="summarySubtotal">0.00</span></strong>
                    </div>
                    <small id="productSummary" class="text-muted d-block">No products added</small>
                </div>

                <hr class="my-2">

                <!-- Additional Charges -->
                <div id="additionalChargesDisplay" class="d-none">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Additional Charges:</small>
                        <strong>₹<span id="summaryAdditionalCharges">0.00</span></strong>
                    </div>
                    <hr class="my-2">
                </div>

                <!-- Discount -->
                <div id="discountDisplay" class="d-none">
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <small>Discount:</small>
                        <strong>-₹<span id="summaryDiscount">0.00</span></strong>
                    </div>
                    <hr class="my-2">
                </div>

                <!-- Tax -->
                <div id="taxDisplay" class="d-none">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Tax:</small>
                        <strong>₹<span id="summaryTax">0.00</span></strong>
                    </div>
                    <hr class="my-2">
                </div>

                <!-- Total -->
                <div class="d-flex justify-content-between mb-3">
                    <strong class="fs-5">Total Amount:</strong>
                    <strong class="fs-5 text-primary">₹<span id="summaryTotal">0.00</span></strong>
                </div>

                <hr>

                <!-- 9. PAYMENT SECTION -->
                <div class="mb-3">
                    <label for="payment_method" class="form-label fw-bold">Payment Method <span class="text-danger">*</span></label>
                    <select class="form-select" id="payment_method" name="payment_method" form="orderForm" required>
                        <option value="">-- Select Method --</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="online">Online Payment</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="paid_amount" class="form-label fw-bold">Amount Paid <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="paid_amount" name="paid_amount" form="orderForm" placeholder="0.00" min="0" step="0.01" value="0" required>
                </div>

                <div class="p-2 bg-light rounded mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Due Amount:</small>
                        <strong>₹<span id="dueAmount">0.00</span></strong>
                    </div>
                </div>

                <!-- Validation Messages -->
                <div id="validationMessages" class="mb-3"></div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg" form="orderForm" id="createOrderBtn">
                        <i class="fas fa-save me-2"></i>Create Order
                    </button>
                    <a href="{{ route('receptionist.orders.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- NEW CUSTOMER MODAL -->
<div class="modal fade" id="newCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Create New Customer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="newCustomerForm" method="POST" action="{{ route('receptionist.customers.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customer_email" name="email">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="customer_phone" name="phone" required>
                        </div>
                        <div class="col-md-6">
                            <label for="customer_contact_number" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="customer_contact_number" name="contact_number">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save me-1"></i>Create Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderForm = document.getElementById('orderForm');
    const customerSelect = document.getElementById('customer_id');
    const orderTypeRadios = document.querySelectorAll('input[name="order_type"]');
    const productsSection = document.getElementById('productsSection');
    const stitchingSection = document.getElementById('stitchingSection');
    const productRows = document.getElementById('productRows');
    const addProductBtn = document.getElementById('addProductBtn');
    const newCustomerForm = document.getElementById('newCustomerForm');
    const newCustomerModal = new bootstrap.Modal(document.getElementById('newCustomerModal'));

    let productRowCount = 1;

    // Initialize events
    initializeOrderTypeListeners();
    initializeProductListeners();
    initializePaymentListeners();
    initializeCustomerListeners();
    initializeChargesListeners();

    function initializeOrderTypeListeners() {
        orderTypeRadios.forEach(radio => {
            radio.addEventListener('change', handleOrderTypeChange);
        });
    }

    function handleOrderTypeChange() {
        const orderType = document.querySelector('input[name="order_type"]:checked').value;
        
        // Show/hide sections based on order type
        if (orderType === 'ready_made') {
            productsSection.style.display = 'block';
            stitchingSection.style.display = 'none';
            // Clear stitching fields
            clearStitchingFields();
        } else if (orderType === 'stitching') {
            productsSection.style.display = 'none';
            stitchingSection.style.display = 'block';
            // Clear products
            clearProducts();
        } else if (orderType === 'combined') {
            productsSection.style.display = 'block';
            stitchingSection.style.display = 'block';
        }

        calculateSummary();
    }

    function initializeProductListeners() {
        addProductBtn.addEventListener('click', addProductRow);
        
        // Event delegation for product changes
        productRows.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select') || e.target.classList.contains('product-qty')) {
                updateProductRow(e.target.closest('.product-row'));
                calculateSummary();
            }
        });

        // Event delegation for remove buttons
        productRows.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-product')) {
                e.target.closest('.product-row').remove();
                updateRemoveButtons();
                calculateSummary();
            }
        });
    }

    function addProductRow() {
        const row = document.querySelector('.product-row').cloneNode(true);
        row.dataset.rowIndex = productRowCount++;
        
        // Reset select and inputs
        const select = row.querySelector('.product-select');
        select.value = '';
        row.querySelector('.product-price').value = '0.00';
        row.querySelector('.product-qty').value = '1';
        row.querySelector('.product-total').value = '0.00';

        productRows.appendChild(row);
        updateRemoveButtons();
    }

    function updateProductRow(row) {
        const select = row.querySelector('.product-select');
        const qty = row.querySelector('.product-qty');
        const priceInput = row.querySelector('.product-price');
        const totalInput = row.querySelector('.product-total');

        if (select.value) {
            const price = parseFloat(select.options[select.selectedIndex].dataset.price) || 0;
            const quantity = parseInt(qty.value) || 1;
            const total = price * quantity;

            priceInput.value = price.toFixed(2);
            totalInput.value = total.toFixed(2);
        } else {
            priceInput.value = '0.00';
            totalInput.value = '0.00';
        }
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.product-row');
        rows.forEach(row => {
            const removeBtn = row.querySelector('.btn-remove-product');
            if (rows.length > 1) {
                removeBtn.classList.remove('d-none');
            } else {
                removeBtn.classList.add('d-none');
            }
        });
    }

    function clearProducts() {
        productRows.innerHTML = `
            <tr class="product-row" data-row-index="0">
                <td>
                    <select class="form-select form-select-sm product-select" name="product_ids[]" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->final_price }}" data-stock="{{ $product->stock_quantity }}">
                                {{ $product->name }} (Stock: {{ $product->stock_quantity }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm product-price" readonly value="0.00">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm product-qty" name="quantities[]" min="1" value="1" required>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm product-total" readonly value="0.00">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger btn-remove-product d-none">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        productRowCount = 1;
        updateRemoveButtons();
    }

    function clearStitchingFields() {
        document.getElementById('fabric_type').value = '';
        document.getElementById('fabric_color').value = '';
        document.getElementById('garment_type').value = '';
        document.getElementById('measurement_id').value = '';
        document.getElementById('stitching_instructions').value = '';
        document.getElementById('design_image').value = '';
    }

    function initializeCustomerListeners() {
        customerSelect.addEventListener('change', handleCustomerSelect);
        
        newCustomerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Submit form via AJAX and add to dropdown
            const formData = new FormData(newCustomerForm);
            
            fetch('{{ route("receptionist.customers.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new customer to dropdown
                    const option = document.createElement('option');
                    option.value = data.customer.id;
                    option.textContent = `${data.customer.name} (${data.customer.email})`;
                    option.selected = true;
                    customerSelect.appendChild(option);
                    
                    // Reset form and close modal
                    newCustomerForm.reset();
                    newCustomerModal.hide();
                    
                    // Trigger customer change
                    handleCustomerSelect();
                } else {
                    alert('Error creating customer: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    function handleCustomerSelect() {
        const customerId = customerSelect.value;
        const customerInfo = document.getElementById('customerInfo');

        if (customerId) {
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const email = selectedOption.dataset.email || '';
            const phone = selectedOption.dataset.phone || '';

            document.getElementById('customerEmail').textContent = email;
            document.getElementById('customerPhone').textContent = phone;
            customerInfo.classList.remove('d-none');

            // Load customer measurements for stitching section
            loadCustomerMeasurements(customerId);
        } else {
            customerInfo.classList.add('d-none');
        }
    }

    function loadCustomerMeasurements(customerId) {
        // TODO: Implement AJAX call to load customer measurements
        // For now, this is a placeholder
        const measurementSelect = document.getElementById('measurement_id');
        // In a real implementation, fetch measurements via AJAX
    }

    function initializeChargesListeners() {
        const discountInput = document.getElementById('discount');
        const taxInput = document.getElementById('tax');
        const additionalChargesInput = document.getElementById('additional_charges');

        discountInput.addEventListener('change', calculateSummary);
        taxInput.addEventListener('change', calculateSummary);
        additionalChargesInput.addEventListener('change', calculateSummary);
    }

    function initializePaymentListeners() {
        const paidAmountInput = document.getElementById('paid_amount');
        paidAmountInput.addEventListener('change', calculateDue);
    }

    function calculateSummary() {
        const subtotal = calculateSubtotal();
        const additionalCharges = parseFloat(document.getElementById('additional_charges').value) || 0;
        const discountType = document.getElementById('discount_type').value;
        const discountValue = parseFloat(document.getElementById('discount').value) || 0;
        const taxType = document.getElementById('tax_type').value;
        const taxValue = parseFloat(document.getElementById('tax').value) || 0;

        // Calculate discount
        let discount = 0;
        if (discountType === 'percentage') {
            discount = (subtotal + additionalCharges) * (discountValue / 100);
        } else {
            discount = discountValue;
        }

        // Calculate tax
        let tax = 0;
        if (taxType === 'percentage') {
            tax = (subtotal + additionalCharges - discount) * (taxValue / 100);
        } else {
            tax = taxValue;
        }

        const total = subtotal + additionalCharges + tax - discount;

        // Update summary display
        document.getElementById('summarySubtotal').textContent = subtotal.toFixed(2);
        document.getElementById('summaryAdditionalCharges').textContent = additionalCharges.toFixed(2);
        document.getElementById('summaryDiscount').textContent = discount.toFixed(2);
        document.getElementById('summaryTax').textContent = tax.toFixed(2);
        document.getElementById('summaryTotal').textContent = total.toFixed(2);

        // Update hidden inputs for form submission
        document.querySelector('input[name="subtotal"]') || createHiddenInput('subtotal', subtotal.toFixed(2));
        document.querySelector('input[name="discount"]') || createHiddenInput('discount', discount.toFixed(2));
        document.querySelector('input[name="tax"]') || createHiddenInput('tax', tax.toFixed(2));

        // Show/hide sections
        if (additionalCharges > 0) {
            document.getElementById('additionalChargesDisplay').classList.remove('d-none');
        } else {
            document.getElementById('additionalChargesDisplay').classList.add('d-none');
        }

        if (discount > 0) {
            document.getElementById('discountDisplay').classList.remove('d-none');
        } else {
            document.getElementById('discountDisplay').classList.add('d-none');
        }

        if (tax > 0) {
            document.getElementById('taxDisplay').classList.remove('d-none');
        } else {
            document.getElementById('taxDisplay').classList.add('d-none');
        }

        calculateDue();
    }

    function calculateSubtotal() {
        let subtotal = 0;
        document.querySelectorAll('.product-total').forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });
        
        // Update product summary
        const count = document.querySelectorAll('.product-row').length;
        if (count > 0 && subtotal > 0) {
            document.getElementById('productSummary').textContent = `${count} product(s)`;
        } else {
            document.getElementById('productSummary').textContent = 'No products added';
        }
        
        return subtotal;
    }

    function calculateDue() {
        const total = parseFloat(document.getElementById('summaryTotal').textContent) || 0;
        const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
        const due = Math.max(0, total - paidAmount);
        document.getElementById('dueAmount').textContent = due.toFixed(2);
    }

    function createHiddenInput(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        orderForm.appendChild(input);
    }

    // Initial calculation
    calculateSummary();
});
</script>
@endsection
