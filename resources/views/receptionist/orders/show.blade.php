@extends('receptionist.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
@endsection

@section('content')
<!-- Print-only header with logo and company name -->
<div class="print-header" style="display: none;">
    <div class="print-logo-container">
        @if(config('app.logo'))
            <img src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}" class="print-logo">
        @else
            <div class="print-logo-placeholder">
                <i class="fas fa-store"></i>
            </div>
        @endif
        <div class="print-company-info">
            <h1 class="print-company-name">{{ config('app.name', 'CLOTHES') }}</h1>
            <p class="print-tagline">Tailoring & Fashion Store</p>
        </div>
    </div>
    <div class="print-invoice-title">
        <h2>ORDER INVOICE</h2>
        <p class="invoice-number">{{ $order->order_number }}</p>
        <p class="invoice-date">{{ $order->created_at->format('M d, Y') }}</p>
    </div>
</div>

<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-receipt me-2"></i>{{ $order->order_number }}
            </h1>
            <p class="text-muted">Order placed on {{ $order->created_at->format('M d, Y') }} at {{ $order->created_at->format('h:i A') }}</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
                    <a href="{{ route('receptionist.orders.edit', $order) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit Order
        </a>
        @if(in_array($order->status, ['pending', 'confirmed']))
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
            <i class="fas fa-ban me-2"></i>Cancel Order
        </button>
        @endif
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Print Invoice
            </button>
        </div>
    </div>
</div>

<!-- Status & Payment Overview -->
<div class="row mb-4 status-payment-overview">
    <div class="col-md-6">
        <div class="card border-0 shadow">
            <div class="card-body">
                <h6 class="card-title fw-bold mb-3">
                    <i class="fas fa-info-circle me-2"></i>Order Status
                </h6>
                <div class="mb-3">
                    <small class="text-muted d-block">Current Status</small>
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'confirmed' => 'info',
                            'in_progress' => 'primary',
                            'ready' => 'success',
                            'delivered' => 'success',
                            'cancelled' => 'danger'
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} p-2">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Order Type</small>
                    <span class="badge bg-info p-2">
                        @if($order->type === 'cloth')
                            <i class="fas fa-shopping-bag me-1"></i>Cloth Only
                        @elseif($order->type === 'stitching')
                            <i class="fas fa-needle me-1"></i>Stitching Only
                        @else
                            <i class="fas fa-layer-group me-1"></i>Cloth + Stitching
                        @endif
                    </span>
                </div>
                <div>
                    <small class="text-muted d-block">Expected Delivery</small>
                    <strong>{{ $order->delivery_date ? $order->delivery_date->format('M d, Y') : 'Not set' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow">
            <div class="card-body">
                <h6 class="card-title fw-bold mb-3">
                    <i class="fas fa-credit-card me-2"></i>Payment Status
                </h6>
                <div class="mb-3">
                    <small class="text-muted d-block">Payment Status</small>
                    @if($order->payment_status === 'paid')
                        <span class="badge bg-success p-2">
                            <i class="fas fa-check-circle me-1"></i>Fully Paid
                        </span>
                    @elseif($order->payment_status === 'pending')
                        <span class="badge bg-warning text-dark p-2">
                            <i class="fas fa-hourglass-half me-1"></i>Pending
                        </span>
                    @else
                        <span class="badge bg-danger p-2">
                            <i class="fas fa-times-circle me-1"></i>Failed
                        </span>
                    @endif
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Payment Method</small>
                    <strong>
                        <i class="fas fa-money-bill-wave me-1"></i>Cash
                    </strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Information -->
<div class="card border-0 shadow mb-4">
    <div class="card-header bg-light border-bottom">
        <h6 class="mb-0 fw-bold">
            <i class="fas fa-user me-2"></i>Customer Information
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2">
                    <small class="text-muted d-block">Name</small>
                    <strong>{{ $order->user->name }}</strong>
                </p>
                <p class="mb-2">
                    <small class="text-muted d-block">Email</small>
                    <strong>{{ $order->user->email }}</strong>
                </p>
                <p>
                    <small class="text-muted d-block">Phone</small>
                    <strong>{{ $order->user->phone ?? 'Not provided' }}</strong>
                </p>
            </div>
            <div class="col-md-6">
                <p class="mb-2">
                    <small class="text-muted d-block">City</small>
                    <strong>{{ $order->user->address?->city ?? 'Not provided' }}</strong>
                </p>
                <p class="mb-2">
                    <small class="text-muted d-block">Address</small>
                    <strong>{{ $order->user->address?->address_line_1 ?? 'Not provided' }}</strong>
                </p>
                <p class="mb-2">
                    <small class="text-muted d-block">Customer ID</small>
                    <strong>#{{ $order->user->id }}</strong>
                </p>
                @if($order->stitchingOrder)
                <p class="mb-2 garment-type-print">
                    <small class="text-muted d-block">Garment Type</small>
                    <strong>{{ $order->stitchingOrder->garment_type ?? 'Not specified' }}</strong>
                </p>
                <p class="fabric-type-print">
                    <small class="text-muted d-block">Fabric Type</small>
                    <strong>{{ $order->stitchingOrder->fabric_details ?? 'Not specified' }}</strong>
                </p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Order Items -->
@if($order->orderItems->count())
<div class="card border-0 shadow mb-4">
    <div class="card-header bg-light border-bottom">
        <h6 class="mb-0 fw-bold">
            <i class="fas fa-box me-2"></i>Order Items ({{ $order->orderItems->count() }})
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="fw-bold">
                            {{ $item->product->name }}
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $item->product->category?->name ?? 'N/A' }}
                            </small>
                        </td>
                        <td class="text-center">
                            {{ $item->quantity }}
                        </td>
                        <td class="text-right">
                            Rs. {{ number_format($item->price, 2) }}
                        </td>
                        <td class="text-right fw-bold">
                            Rs. {{ number_format($item->total, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Stitching Order Details -->
@if($order->stitchingOrder)
<div class="card border-0 shadow mb-4">
    <div class="card-header bg-light border-bottom">
        <h6 class="mb-0 fw-bold">
            <i class="fas fa-needle me-2"></i>Stitching Order Details
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-3">
                    <small class="text-muted d-block">Stitching Status</small>
                    @php
                        $stitchingStatusColors = [
                            'pending' => 'secondary',
                            'assigned' => 'info',
                            'in_progress' => 'primary',
                            'ready_for_fitting' => 'warning',
                            'in_fitting' => 'info',
                            'ready' => 'success',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                        ];
                    @endphp
                    <span class="badge bg-{{ $stitchingStatusColors[$order->stitchingOrder->stitching_status] ?? 'secondary' }} p-2">
                        {{ ucfirst(str_replace('_', ' ', $order->stitchingOrder->stitching_status)) }}
                    </span>
                </p>
                <p class="mb-3">
                    <small class="text-muted d-block">Garment Type</small>
                    <strong>{{ $order->stitchingOrder->garment_type ?? 'Not specified' }}</strong>
                </p>
                <p class="mb-3">
                    <small class="text-muted d-block">Fabric Type</small>
                    <strong>{{ $order->stitchingOrder->fabric_details ?? 'Not specified' }}</strong>
                </p>
                <p class="mb-3">
                    <small class="text-muted d-block">Estimated Cost</small>
                    <strong>Rs. {{ number_format($order->stitchingOrder->estimated_cost, 2) }}</strong>
                </p>
            </div>
            <div class="col-md-6">
                <p class="mb-3">
                    <small class="text-muted d-block">Assigned Tailor</small>
                    <strong>{{ $order->stitchingOrder->tailor?->name ?? 'Not assigned' }}</strong>
                </p>
                <p class="mb-3">
                    <small class="text-muted d-block">Measurement Profile</small>
                    <strong>{{ $order->stitchingOrder->measurement?->profile_name ?? 'Not selected' }}</strong>
                </p>
                <p class="mb-3">
                    <small class="text-muted d-block">Special Instructions</small>
                    <strong>{{ $order->stitchingOrder->special_instructions ?? 'None' }}</strong>
                </p>
            </div>
        </div>

        <!-- Design Image -->
        @if($order->stitchingOrder->design_image)
        <div class="mt-4 pt-3 border-top">
            <h6 class="fw-bold mb-3">Design Image</h6>
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset('storage/' . $order->stitchingOrder->design_image) }}" 
                         alt="Design Image" class="img-fluid rounded border">
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Order Summary & Pricing -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-calculator me-2"></i>Order Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong>Rs. {{ number_format($order->subtotal, 2) }}</strong>
                </div>
                @if($order->stitching_charge > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Stitching Charges</span>
                    <strong>Rs. {{ number_format($order->stitching_charge, 2) }}</strong>
                </div>
                @endif
                @if($order->tax > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax ({{ $order->tax > 0 ? '18%' : '0%' }})</span>
                    <strong>Rs. {{ number_format($order->tax, 2) }}</strong>
                </div>
                @endif
                @if($order->discount > 0)
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom text-danger">
                    <span>Discount</span>
                    <strong>-Rs. {{ number_format($order->discount, 2) }}</strong>
                </div>
                @endif
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Total Amount</span>
                    <strong class="fs-5">Rs. {{ number_format($order->total, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Notes -->
@if($order->notes)
<div class="card border-0 shadow mb-4">
    <div class="card-header bg-light border-bottom">
        <h6 class="mb-0 fw-bold">
            <i class="fas fa-sticky-note me-2"></i>Order Notes
        </h6>
    </div>
    <div class="card-body">
        <p class="mb-0">{{ $order->notes }}</p>
    </div>
</div>
@endif



<!-- Update Status Modal -->
@if(!in_array($order->status, ['delivered', 'cancelled']))
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-sync me-2"></i>Update Order Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('receptionist.orders.update-status', $order) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-tag me-2"></i>New Status
                        </label>
                        <select name="status" class="form-select form-select-lg" required>
                            <option value="">Select New Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="in_progress">In Progress</option>
                            <option value="assigned_to_tailor">Assigned to Tailor</option>
                            <option value="stitching_started">Stitching Started</option>
                            <option value="completed">Completed</option>
                            <option value="quality_check">Quality Check</option>
                            <option value="ready_for_delivery">Ready for Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>Select the new status for this order
                        </small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Add any notes about this status update"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Cancel Order Modal -->
@if(in_array($order->status, ['pending', 'confirmed']))
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-ban me-2"></i>Cancel Order
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('receptionist.orders.cancel', $order) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action will cancel the order and restore product inventory.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-comment me-2"></i>Cancellation Reason <span class="text-danger">*</span>
                        </label>
                        <textarea name="reason" class="form-control" rows="4" 
                                  placeholder="Please provide the reason for cancelling this order" required></textarea>
                        @error('reason')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Keep Order</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i>Cancel Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
/* Hide garment/fabric type on screen, show only in print */
.garment-type-print,
.fabric-type-print {
    display: none;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

.step-indicator {
    text-align: center;
}

.step-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    font-weight: bold;
    margin-bottom: 8px;
}

.step-indicator.active .step-circle {
    background-color: #0d6efd;
    color: white;
}

.step-label {
    font-size: 12px;
    color: #6c757d;
}

.step-indicator.active .step-label {
    color: #0d6efd;
    font-weight: bold;
}

/* Timeline Styles */
.timeline {
    position: relative;
    padding: 0;
}

.timeline-item {
    display: flex;
    margin-bottom: 30px;
    position: relative;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    min-width: 50px;
    text-align: center;
    color: #0d6efd;
}

.timeline-marker i {
    font-size: 12px;
}

.timeline-content {
    flex: 1;
    padding-left: 20px;
    border-left: 2px solid #e9ecef;
    padding-bottom: 20px;
}

.timeline-item:last-child .timeline-content {
    border-left: 2px solid #0d6efd;
}

@media print {
    /* Reset everything */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    @page { size: A4; margin: 0.5cm; }
    
    html, body {
        width: 100% !important;
        height: auto !important;
        font-family: Arial, sans-serif !important;
        font-size: 10pt !important;
        line-height: 1.3 !important;
        color: #000 !important;
        background: #fff !important;
    }
    
    /* HIDE UI */
    #kt_app_sidebar, #kt_app_header, #kt_app_toolbar, #kt_app_footer,
    .sidebar, nav, aside, .navbar, .breadcrumb, .page-header,
    .btn, button, .modal, .alert, .debugbar, #debugbar, .phpdebugbar,
    [class*="debug"], footer, .card-header,
    .search-bar, [class*="search"], input[type="search"],
    .user-menu, .profile, [class*="profile"], .dropdown,
    .app-navbar, .navbar-nav, form[role="search"],
    #kt_header, .app-header-menu, .header-menu,
    [data-kt-menu="true"], .menu, .menu-item,
    .separator, hr.border-gray-200,
    [class*="user-"], [class*="avatar"], .symbol,
    .card:has(.fa-needle) {
        display: none !important;
        visibility: hidden !important;
    }
    
    /* SHOW PRINT HEADER */
    .print-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
        margin-bottom: 6px !important;
        padding-bottom: 4px !important;
        border-bottom: 2px solid #000 !important;
    }
    
    .print-logo-container {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }
    
    .print-logo {
        display: block !important;
        max-width: 60px !important;
        max-height: 60px !important;
        object-fit: contain !important;
    }
    
    .print-logo-placeholder {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 60px !important;
        height: 60px !important;
        background: #f0f0f0 !important;
        border: 2px solid #000 !important;
        border-radius: 4px !important;
    }
    
    .print-logo-placeholder i {
        font-size: 30px !important;
        color: #000 !important;
    }
    
    .print-company-info {
        display: block !important;
    }
    
    .print-company-name {
        font-size: 20pt !important;
        font-weight: bold !important;
        margin: 0 !important;
        color: #000 !important;
        line-height: 1 !important;
    }
    
    .print-tagline {
        font-size: 9pt !important;
        color: #0066cc !important;
        margin: 3px 0 0 0 !important;
        font-weight: normal !important;
    }
    
    .print-invoice-title {
        text-align: right !important;
        line-height: 1.1 !important;
    }
    
    .print-invoice-title h2 {
        font-size: 16pt !important;
        font-weight: bold !important;
        margin: 0 0 2px 0 !important;
        color: #000 !important;
        letter-spacing: 0 !important;
    }
    
    .print-invoice-title .invoice-number {
        font-size: 8.5pt !important;
        margin: 0 !important;
        color: #000 !important;
        font-weight: normal !important;
        line-height: 1.3 !important;
    }
    
    .print-invoice-title .invoice-date {
        font-size: 8.5pt !important;
        margin: 0 !important;
        color: #000 !important;
        font-weight: normal !important;
        line-height: 1.3 !important;
    }
    
    /* SHOW GARMENT & FABRIC TYPE IN PRINT */
    .garment-type-print,
    .fabric-type-print {
        display: block !important;
    }
    
    .garment-type-print small,
    .fabric-type-print small {
        font-size: 7.5pt !important;
        color: #666 !important;
        text-transform: uppercase !important;
    }
    
    .garment-type-print strong,
    .fabric-type-print strong {
        font-size: 9pt !important;
        color: #000 !important;
        font-weight: normal !important;
    }
    
    /* RESET MAIN CONTAINER - FIX LEFT MARGIN ISSUE */
    #kt_app_main, #kt_app_content, #kt_app_content_container,
    .app-main, .content, .container-fluid, main {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        position: static !important;
        left: 0 !important;
        transform: none !important;
    }
    
    /* REMOVE PSEUDO-ELEMENT HEADER */
    #kt_app_content_container::before {
        content: none !important;
        display: none !important;
    }
    
    /* CARDS */
    .card {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        margin: 0 0 4px 0 !important;
        padding: 0 !important;
        page-break-inside: avoid;
    }
    
    .card-body { padding: 0 !important; margin: 0 !important; }
    
    /* HIDE FIRST ROW (STATUS CARDS AT TOP) */
    #kt_app_content_container > .row:first-child,
    .status-payment-overview,
    .row.status-payment-overview { 
        display: none !important; 
        visibility: hidden !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
    }
    
    /* Remove all spacing from hidden status section */
    .status-payment-overview * {
        display: none !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* CLEAN STATUS BADGES */
    .badge {
        display: inline !important;
        border: none !important;
        background: transparent !important;
        padding: 0 !important;
        color: #000 !important;
    }
    
    .badge i, .bg-warning, .bg-info, .bg-success, .bg-danger,
    .bg-primary, .bg-secondary, .bg-light {
        background: transparent !important;
    }
    
    /* TWO COLUMN LAYOUT */
    .row.mb-4 { display: table !important; width: 100% !important; margin: 0 0 4px 0 !important; }
    .row.mb-4 .col-md-6 {
        display: table-cell !important;
        width: 48% !important;
        vertical-align: top !important;
        padding-right: 2% !important;
    }
    
    /* SECTION HEADERS */
    h6.fw-bold, .fw-bold {
        font-size: 11pt !important;
        font-weight: bold !important;
        margin: 0 0 3px 0 !important;
        padding: 0 0 2px 0 !important;
        border-bottom: 1px solid #333 !important;
    }
    
    h6 i, .fw-bold i { display: none !important; }
    
    /* CUSTOMER INFO TABLE LAYOUT */
    .card:has(.fa-user) {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    
    .card:has(.fa-user) .row { 
        display: table !important; 
        width: 100% !important; 
        margin: 0 !important;
    }
    .card:has(.fa-user) .col-md-6 {
        display: table-cell !important;
        width: 48% !important;
        padding-right: 2% !important;
        vertical-align: top !important;
    }
    
    /* Ensure customer info fields are visible and properly formatted */
    .card:has(.fa-user) p {
        margin: 0 0 4px 0 !important;
        line-height: 1.3 !important;
        font-size: 9pt !important;
    }
    
    .card:has(.fa-user) small {
        font-size: 7.5pt !important;
        color: #666 !important;
        text-transform: uppercase !important;
        display: block !important;
        margin-bottom: 1px !important;
    }
    
    .card:has(.fa-user) strong {
        font-size: 9pt !important;
        color: #000 !important;
        font-weight: normal !important;
    }
    
    /* PRODUCT TABLE */
    .table-responsive { overflow: visible !important; }
    
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 6px 0 !important;
    }
    
    thead, tbody, tr, th, td { display: table !important; }
    thead { display: table-header-group !important; }
    tbody { display: table-row-group !important; }
    tr { display: table-row !important; page-break-inside: avoid !important; }
    
    th, td {
        display: table-cell !important;
        border: 1px solid #000 !important;
        padding: 3px 5px !important;
        font-size: 9pt !important;
    }
    
    th { background: #f5f5f5 !important; font-weight: bold !important; }
    
    .text-right, th.text-right, td.text-right { text-align: right !important; }
    .text-center, th.text-center, td.text-center { text-align: center !important; }
    
    /* HIDE SCREEN IMAGES BUT SHOW PRINT LOGO */
    .card img { display: none !important; }
    .card:has(img) div:has(img):not(.print-logo-container) { display: none !important; }
    
    /* PAYMENT SUMMARY */
    .d-flex {
        display: flex !important;
        justify-content: space-between !important;
        padding: 2px 0 !important;
    }
    
    .d-flex.border-bottom {
        border-bottom: 1px solid #ccc !important;
        margin-bottom: 4px !important;
        padding-bottom: 4px !important;
    }
    
    .d-flex:last-child {
        font-size: 12pt !important;
        font-weight: bold !important;
        border-top: 2px solid #000 !important;
        padding-top: 4px !important;
        margin-top: 4px !important;
    }
    
    .fs-5 { font-size: 12pt !important; font-weight: bold !important; }
    
    /* LABELS */
    small.text-muted {
        font-size: 8pt !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
        color: #666 !important;
        display: block !important;
        margin-bottom: 1px !important;
    }
    
    p { margin: 1px 0 !important; line-height: 1.2 !important; }
    
    /* REMOVE DUPLICATE SIGNATURES */
    body::after, .card::after, .card:last-of-type::after, #kt_app_content_container::after {
        content: none !important;
        display: none !important;
    }
    
    /* ADD FOOTER */
    .content::after, #kt_app_content::after {
        content: "___________________________\ACustomer Signature\A\AThank you for your business!";
        white-space: pre !important;
        display: block !important;
        text-align: center !important;
        margin-top: 15px !important;
        padding-top: 10px !important;
        border-top: 1px solid #000 !important;
        font-size: 9pt !important;
    }
    
    /* COMPACT SPACING */
    .mb-2, .mb-3, .mb-4 { margin-bottom: 4px !important; }
    .row { margin-bottom: 6px !important; }
    
    h6 { page-break-after: avoid !important; }
}

@media (max-width: 768px) {
    .timeline-content {
        padding-left: 10px;
    }
}
</style>
@endsection
