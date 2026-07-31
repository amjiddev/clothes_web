@extends('receptionist.layouts.app')

@section('title', 'Assign Stitching Order')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.tailors.index') }}">Tailors</a></li>
    <li class="breadcrumb-item active">Assign Order</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-tasks me-2"></i>Assign Stitching Order
            </h1>
            <p class="text-muted">Assign a pending stitching order to a tailor</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.tailors.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<!-- Error Messages -->
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>Please fix the errors below:</strong>
    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Success Message -->
@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form method="POST" action="{{ route('receptionist.tailors.assign-order') }}">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <!-- Stitching Order Selection -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-box me-2"></i>Select Stitching Order
                    </h6>
                </div>
                <div class="card-body">
                    @if($stitchingOrders->isEmpty())
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>No pending stitching orders found</strong>. All orders have been assigned!
                    </div>
                    @else
                    <div class="mb-3">
                        <label for="stitching_order_id" class="form-label fw-bold">
                            <i class="fas fa-list me-2"></i>Stitching Order <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg @error('stitching_order_id') is-invalid @enderror" 
                                id="stitching_order_id" name="stitching_order_id" required onchange="updateOrderDetails()">
                            <option value="">-- Select a Pending Order --</option>
                            @foreach($stitchingOrders as $order)
                            <option value="{{ $order->id }}" 
                                    data-customer="{{ $order->order->user->name }}"
                                    data-garment="{{ $order->garment_type }}"
                                    data-cost="{{ $order->estimated_cost }}">
                                #{{ $order->order->order_number }} - {{ $order->order->user->name }} ({{ $order->garment_type ?? 'Custom' }})
                            </option>
                            @endforeach
                        </select>
                        @error('stitching_order_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tailor Selection -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user-tie me-2"></i>Select Tailor
                    </h6>
                </div>
                <div class="card-body">
                    @if($tailors->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>No active tailors available</strong>. Please ensure there are active tailors in the system.
                    </div>
                    @else
                    <div class="mb-3">
                        <label for="tailor_id" class="form-label fw-bold">
                            <i class="fas fa-users me-2"></i>Tailor <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg @error('tailor_id') is-invalid @enderror" 
                                id="tailor_id" name="tailor_id" required onchange="updateTailorInfo()">
                            <option value="">-- Select a Tailor --</option>
                            @foreach($tailors as $tailor)
                            <option value="{{ $tailor->user_id }}" 
                                    data-specialization="{{ $tailor->specialization }}"
                                    data-phone="{{ $tailor->phone }}"
                                    data-active-orders="{{ $tailor->getActiveOrders() }}">
                                {{ $tailor->user->name }} - {{ $tailor->specialization ?? 'General' }}
                            </option>
                            @endforeach
                        </select>
                        @error('tailor_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <!-- Tailor Info Display -->
                    <div id="tailor-info" class="alert alert-info d-none" role="alert">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Specialization:</strong> <span id="tailor-spec">-</span></p>
                                <p class="mb-0"><strong>Phone:</strong> <span id="tailor-phone">-</span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Active Orders:</strong> <span id="tailor-active">-</span></p>
                                <p class="mb-0"><strong>Capacity:</strong> <span id="tailor-capacity">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Details -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>Assignment Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="delivery_date" class="form-label fw-bold">
                            <i class="fas fa-calendar me-2"></i>Delivery Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control form-control-lg @error('delivery_date') is-invalid @enderror" 
                               id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}" required>
                        <small class="text-muted d-block mt-1">
                            Expected delivery date for the completed order
                        </small>
                        @error('delivery_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="instructions" class="form-label fw-bold">
                            <i class="fas fa-comment me-2"></i>Special Instructions
                        </label>
                        <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                  id="instructions" name="instructions" rows="4"
                                  placeholder="Any special instructions for the tailor...">{{ old('instructions') }}</textarea>
                        <small class="text-muted d-block mt-1">
                            Specific tailoring instructions or customer preferences (max 500 chars)
                        </small>
                        @error('instructions')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card border-0 shadow mb-3" id="order-summary" style="display: none;">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Order Summary
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Customer:</strong> <span id="summary-customer">-</span></p>
                    <p class="mb-2"><strong>Garment Type:</strong> <span id="summary-garment">-</span></p>
                    <p class="mb-0"><strong>Estimated Cost:</strong> <span id="summary-cost" class="text-success fw-bold">-</span></p>
                </div>
            </div>

            <!-- Assignment Check -->
            <div class="card border-0 shadow mb-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-check-circle me-2"></i>Assignment Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div id="assignment-check" class="text-muted">
                        <p><i class="fas fa-info-circle me-2"></i>Select order and tailor to see details</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card border-0 shadow">
                <div class="card-body">
                    @if(!$stitchingOrders->isEmpty() && !$tailors->isEmpty())
                    <button type="submit" class="btn btn-success w-100 mb-2" id="assign-btn" disabled>
                        <i class="fas fa-check me-2"></i>Assign Order
                    </button>
                    @endif
                    <a href="{{ route('receptionist.tailors.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card border-0 shadow mt-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-question-circle me-2"></i>How It Works
                    </h6>
                </div>
                <div class="card-body">
                    <ol class="small ps-3">
                        <li class="mb-2">Select a pending stitching order</li>
                        <li class="mb-2">Choose a tailor with available capacity</li>
                        <li class="mb-2">Set the delivery date</li>
                        <li class="mb-2">Add any special instructions</li>
                        <li>Click "Assign Order" to complete</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function updateOrderDetails() {
    const select = document.getElementById('stitching_order_id');
    const option = select.options[select.selectedIndex];
    const summary = document.getElementById('order-summary');
    
    if (option.value) {
        document.getElementById('summary-customer').textContent = option.dataset.customer;
        document.getElementById('summary-garment').textContent = option.dataset.garment || 'Custom';
        document.getElementById('summary-cost').textContent = 'PKR ' + parseFloat(option.dataset.cost).toFixed(2);
        summary.style.display = 'block';
        updateAssignmentCheck();
    } else {
        summary.style.display = 'none';
        document.getElementById('assignment-check').innerHTML = '<p><i class="fas fa-info-circle me-2"></i>Select order and tailor to see details</p>';
    }
}

function updateTailorInfo() {
    const select = document.getElementById('tailor_id');
    const option = select.options[select.selectedIndex];
    const infoDiv = document.getElementById('tailor-info');
    
    if (option.value) {
        document.getElementById('tailor-spec').textContent = option.dataset.specialization || 'General';
        document.getElementById('tailor-phone').textContent = option.dataset.phone;
        document.getElementById('tailor-active').textContent = option.dataset.activeOrders + ' orders';
        document.getElementById('tailor-capacity').textContent = (10 - parseInt(option.dataset.activeOrders)) + ' slots available';
        infoDiv.classList.remove('d-none');
        updateAssignmentCheck();
    } else {
        infoDiv.classList.add('d-none');
        document.getElementById('assignment-check').innerHTML = '<p><i class="fas fa-info-circle me-2"></i>Select order and tailor to see details</p>';
    }
}

function updateAssignmentCheck() {
    const orderId = document.getElementById('stitching_order_id').value;
    const tailorId = document.getElementById('tailor_id').value;
    const assignBtn = document.getElementById('assign-btn');
    
    if (orderId && tailorId) {
        const orderOption = document.querySelector('#stitching_order_id option:checked');
        const tailorOption = document.querySelector('#tailor_id option:checked');
        
        document.getElementById('assignment-check').innerHTML = `
            <p class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong>${orderOption.text.trim()}</strong>
            </p>
            <p class="mb-0">
                <i class="fas fa-arrow-right text-primary mx-2"></i>
                <strong>${tailorOption.text.trim()}</strong>
            </p>
        `;
        assignBtn.disabled = false;
    } else {
        assignBtn.disabled = true;
    }
}

// Set minimum date to tomorrow
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    today.setDate(today.getDate() + 1);
    document.getElementById('delivery_date').min = today.toISOString().split('T')[0];
});
</script>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

.form-select-lg, .form-control-lg {
    font-size: 1rem;
    padding: 0.75rem 1rem;
}
</style>
@endsection
