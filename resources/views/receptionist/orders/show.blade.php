@extends('receptionist.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
@endsection

@section('content')
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
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Print Invoice
            </button>
        </div>
    </div>
</div>

<!-- Status & Payment Overview -->
<div class="row mb-4">
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
                        @if($order->type === 'ready_made')
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
                        @if($order->payment_method === 'cash')
                            <i class="fas fa-money-bill-wave me-1"></i>Cash
                        @elseif($order->payment_method === 'card')
                            <i class="fas fa-credit-card me-1"></i>Card
                        @elseif($order->payment_method === 'bank_transfer')
                            <i class="fas fa-university me-1"></i>Bank Transfer
                        @else
                            <i class="fas fa-mobile-alt me-1"></i>Online Payment
                        @endif
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
                <p>
                    <small class="text-muted d-block">Customer ID</small>
                    <strong>#{{ $order->user->id }}</strong>
                </p>
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
                            ₹{{ number_format($item->price, 2) }}
                        </td>
                        <td class="text-right fw-bold">
                            ₹{{ number_format($item->total, 2) }}
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
                    <strong>₹{{ number_format($order->stitchingOrder->estimated_cost, 2) }}</strong>
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
    <div class="col-md-6"></div>
    <div class="col-md-6">
        <div class="card border-0 shadow">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-calculator me-2"></i>Order Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong>₹{{ number_format($order->subtotal, 2) }}</strong>
                </div>
                @if($order->stitching_charge > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Stitching Charges</span>
                    <strong>₹{{ number_format($order->stitching_charge, 2) }}</strong>
                </div>
                @endif
                @if($order->tax > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax ({{ $order->tax > 0 ? '18%' : '0%' }})</span>
                    <strong>₹{{ number_format($order->tax, 2) }}</strong>
                </div>
                @endif
                @if($order->discount > 0)
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom text-danger">
                    <span>Discount</span>
                    <strong>-₹{{ number_format($order->discount, 2) }}</strong>
                </div>
                @endif
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Total Amount</span>
                    <strong class="fs-5">₹{{ number_format($order->total, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payments History -->
<div class="card border-0 shadow mb-4">
    <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">
            <i class="fas fa-history me-2"></i>Payment History
        </h6>
        @if($order->payment_status !== 'paid')
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
            <i class="fas fa-plus me-1"></i>Record Payment
        </button>
        @endif
    </div>
    <div class="card-body">
        @if($order->payments && $order->payments->count())
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->payments as $payment)
                    <tr>
                        <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                        <td class="fw-bold">₹{{ number_format($payment->amount, 2) }}</td>
                        <td>
                            @if($payment->payment_method === 'cash')
                                <i class="fas fa-money-bill-wave me-1"></i>Cash
                            @elseif($payment->payment_method === 'card')
                                <i class="fas fa-credit-card me-1"></i>Card
                            @elseif($payment->payment_method === 'bank_transfer')
                                <i class="fas fa-university me-1"></i>Bank Transfer
                            @else
                                <i class="fas fa-mobile-alt me-1"></i>Online
                            @endif
                        </td>
                        <td>
                            @if($payment->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($payment->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                        <td>{{ $payment->processed_at?->format('M d, Y h:i A') ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-muted text-center py-3">
            <i class="fas fa-inbox me-2"></i>No payments recorded yet
        </p>
        @endif
    </div>
</div>

<!-- Order Timeline -->
<div class="card border-0 shadow mb-4">
    <div class="card-header bg-light border-bottom">
        <h6 class="mb-0 fw-bold">
            <i class="fas fa-clock me-2"></i>Order Timeline & Status Flow
        </h6>
    </div>
    <div class="card-body">
        <!-- Status Flow Diagram -->
        <div class="status-flow-container mb-4">
            @php
                $statusFlows = [
                    'pending' => ['step' => 1, 'label' => 'Pending'],
                    'confirmed' => ['step' => 2, 'label' => 'Confirmed'],
                    'in_progress' => ['step' => 3, 'label' => 'In Progress'],
                    'assigned_to_tailor' => ['step' => 4, 'label' => 'Assigned'],
                    'stitching_started' => ['step' => 5, 'label' => 'Stitching'],
                    'completed' => ['step' => 6, 'label' => 'Completed'],
                    'quality_check' => ['step' => 7, 'label' => 'QC'],
                    'ready_for_delivery' => ['step' => 8, 'label' => 'Ready'],
                    'delivered' => ['step' => 9, 'label' => 'Delivered'],
                ];
                
                $currentStep = $statusFlows[$order->status]['step'] ?? 0;
                $isCancelled = $order->status === 'cancelled';
            @endphp
            
            @if($isCancelled)
            <div class="alert alert-danger mb-3">
                <i class="fas fa-ban me-2"></i>
                <strong>Order Cancelled</strong> - This order is no longer active
            </div>
            @else
            <div class="status-flow d-flex align-items-center gap-2 flex-wrap">
                @foreach($statusFlows as $statusKey => $statusInfo)
                <div class="status-step {{ $statusKey === $order->status ? 'active' : '' }} {{ $statusInfo['step'] < $currentStep ? 'completed' : '' }}">
                    <div class="step-circle">
                        @if($statusInfo['step'] < $currentStep)
                        <i class="fas fa-check"></i>
                        @else
                        {{ $statusInfo['step'] }}
                        @endif
                    </div>
                    <div class="step-text">{{ $statusInfo['label'] }}</div>
                </div>
                @if($loop->index < count($statusFlows) - 1)
                <div class="status-connector {{ $statusInfo['step'] < $currentStep ? 'completed' : '' }}"></div>
                @endif
                @endforeach
            </div>
            @endif
        </div>

        <hr class="my-4">

        <!-- Timeline Events -->
        <div class="timeline">
            @foreach($order->getTimeline() as $event)
            <div class="timeline-item">
                <div class="timeline-marker">
                    <i class="fas fa-circle"></i>
                </div>
                <div class="timeline-content">
                    <h6 class="fw-bold">{{ $event['event'] }}</h6>
                    <small class="text-muted">{{ $event['date']->format('M d, Y h:i A') }}</small>
                </div>
            </div>
            @endforeach
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

<!-- Action Buttons -->
<div class="card border-0 shadow">
    <div class="card-body text-center">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print me-2"></i>Print Invoice
        </button>
        <a href="{{ route('receptionist.orders.edit', $order) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit Order
        </a>
        @if(!in_array($order->status, ['delivered', 'cancelled']))
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
            <i class="fas fa-sync me-2"></i>Update Status
        </button>
        @endif
        @if(in_array($order->status, ['pending', 'confirmed']))
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
            <i class="fas fa-ban me-2"></i>Cancel Order
        </button>
        @endif
        <a href="{{ route('receptionist.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>
</div>

<!-- Add Payment Modal -->
@if($order->payment_status !== 'paid')
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('receptionist.orders.recordPayment', $order) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" 
                               placeholder="Enter amount" max="{{ $order->total }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Select Method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="online">Online Payment</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Transaction ID (Optional)</label>
                        <input type="text" name="transaction_id" class="form-control" 
                               placeholder="For reference">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i>Record Payment
                    </button>
                </div>
            </form>
        </div>
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

/* Status Flow Styles */
.status-flow-container {
    overflow-x: auto;
}

.status-flow {
    min-height: 100px;
    padding: 20px 0;
}

.status-step {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.status-step .step-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    border: 2px solid #e9ecef;
}

.status-step.completed .step-circle {
    background-color: #28a745;
    color: white;
    border-color: #28a745;
}

.status-step.active .step-circle {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}

.status-step .step-text {
    font-size: 11px;
    color: #6c757d;
    font-weight: 500;
    text-align: center;
    max-width: 50px;
}

.status-step.active .step-text {
    color: #0d6efd;
    font-weight: 600;
}

.status-step.completed .step-text {
    color: #28a745;
}

.status-connector {
    width: 30px;
    height: 3px;
    background-color: #e9ecef;
    flex-shrink: 0;
    margin-top: 20px;
}

.status-connector.completed {
    background-color: #28a745;
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
    .btn, .card-header, .card-footer, .modal {
        display: none !important;
    }
    
    .page-header {
        display: none !important;
    }
    
    .card {
        page-break-inside: avoid;
    }
    
    .status-flow-container {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .status-flow {
        flex-direction: column;
        gap: 15px;
    }
    
    .status-connector {
        width: 3px;
        height: 30px;
        margin-top: 0;
        margin-left: 20px;
    }
}
</style>
@endsection
