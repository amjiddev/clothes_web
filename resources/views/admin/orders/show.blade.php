@extends('admin.layouts.app')

@section('title', 'Order: ' . $order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="fas fa-chevron-left me-2"></i>Back to Orders
                </a>
                <h1 class="page-title"><i class="fas fa-shopping-bag me-2"></i>{{ $order->order_number }}</h1>
                <p class="text-muted">Order placed on {{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div class="col-auto">
                @can('edit_orders')
                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit Order
                </a>
                @endcan
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                    <i class="fas fa-sync me-2"></i>Update Status
                </button>
            </div>
        </div>
    </div>

    <!-- Order Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow text-center">
                <div class="card-body">
                    <div class="text-muted mb-2">Order Status</div>
                    <span class="badge bg-{{ $order->status_badge }} fs-6 p-2">
                        {{ $order->getStatusTextAttribute() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow text-center">
                <div class="card-body">
                    <div class="text-muted mb-2">Payment Status</div>
                    <span class="badge bg-{{ $order->payment_status_badge }} fs-6 p-2">
                        {{ $order->getPaymentStatusTextAttribute() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow text-center">
                <div class="card-body">
                    <div class="text-muted mb-2">Order Type</div>
                    @if($order->type === 'ready_made')
                    <span class="badge bg-info fs-6 p-2">Cloth Only</span>
                    @elseif($order->type === 'stitching')
                    <span class="badge bg-warning fs-6 p-2">Stitching Only</span>
                    @else
                    <span class="badge bg-success fs-6 p-2">Cloth + Stitching</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow text-center">
                <div class="card-body">
                    <div class="text-muted mb-2">Total Amount</div>
                    <h5 class="mb-0 text-dark">Rs. {{ number_format($order->total, 2) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Customer Information -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4 text-muted">Name:</dt>
                                <dd class="col-sm-8"><strong>{{ $order->user->name }}</strong></dd>

                                <dt class="col-sm-4 text-muted">Email:</dt>
                                <dd class="col-sm-8">{{ $order->user->email }}</dd>

                                <dt class="col-sm-4 text-muted">Order Date:</dt>
                                <dd class="col-sm-8">{{ $order->created_at->format('M d, Y') }}</dd>

                                <dt class="col-sm-4 text-muted">Payment Method:</dt>
                                <dd class="col-sm-8">{{ ucfirst($order->payment_method ?? 'N/A') }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4 text-muted">Order Type:</dt>
                                <dd class="col-sm-8">
                                    @if($order->type === 'ready_made')
                                    <span class="badge bg-info">Cloth Only</span>
                                    @elseif($order->type === 'stitching')
                                    <span class="badge bg-warning">Stitching Only</span>
                                    @else
                                    <span class="badge bg-success">Cloth + Stitching</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4 text-muted">Delivery Date:</dt>
                                <dd class="col-sm-8">
                                    @if($order->delivery_date)
                                    {{ $order->delivery_date->format('M d, Y') }}
                                    @else
                                    <span class="text-muted">Not set</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4 text-muted">Products:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-light text-dark">{{ $order->orderItems->sum('quantity') }} item(s)</span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            @if($order->orderItems->count() > 0)
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Products</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="fw-bold">Product</th>
                                <th class="fw-bold text-center">Quantity</th>
                                <th class="fw-bold text-end">Price</th>
                                <th class="fw-bold text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $item->product->name }}</strong><br>
                                        <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                </td>
                                <td class="text-end">Rs. {{ number_format($item->price, 2) }}</td>
                                <td class="text-end fw-bold">Rs. {{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Measurements (if stitching order) -->
            @if($order->stitchingOrder && $order->stitchingOrder->measurement)
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-ruler me-2"></i>Measurements</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-6 text-muted">Measurement Title:</dt>
                                <dd class="col-sm-6"><strong>{{ $order->stitchingOrder->measurement->title }}</strong></dd>

                                <dt class="col-sm-6 text-muted">Chest:</dt>
                                <dd class="col-sm-6">{{ $order->stitchingOrder->measurement->chest }} inches</dd>

                                <dt class="col-sm-6 text-muted">Waist:</dt>
                                <dd class="col-sm-6">{{ $order->stitchingOrder->measurement->waist }} inches</dd>

                                <dt class="col-sm-6 text-muted">Hips:</dt>
                                <dd class="col-sm-6">{{ $order->stitchingOrder->measurement->hips }} inches</dd>

                                <dt class="col-sm-6 text-muted">Shoulder:</dt>
                                <dd class="col-sm-6">{{ $order->stitchingOrder->measurement->shoulder }} inches</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-6 text-muted">Sleeve Length:</dt>
                                <dd class="col-sm-6">{{ $order->stitchingOrder->measurement->sleeve_length }} inches</dd>

                                <dt class="col-sm-6 text-muted">Torso Length:</dt>
                                <dd class="col-sm-6">{{ $order->stitchingOrder->measurement->torso_length }} inches</dd>

                                <dt class="col-sm-6 text-muted">Inseam:</dt>
                                <dd class="col-sm-6">{{ $order->stitchingOrder->measurement->inseam }} inches</dd>

                                <dt class="col-sm-6 text-muted">Neck:</dt>
                                <dd class="col-sm-6">{{ $order->stitchingOrder->measurement->neck }} inches</dd>
                            </dl>
                        </div>
                    </div>

                    @if($order->stitchingOrder->measurement->notes)
                    <div class="alert alert-info mt-3">
                        <strong>Notes:</strong> {{ $order->stitchingOrder->measurement->notes }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Stitching Details -->
            @if($order->stitchingOrder)
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-needle me-2"></i>Stitching Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-5 text-muted">Garment Type:</dt>
                                <dd class="col-sm-7"><strong>{{ $order->stitchingOrder->garment_type }}</strong></dd>

                                <dt class="col-sm-5 text-muted">Stitching Status:</dt>
                                <dd class="col-sm-7">
                                    <span class="badge bg-{{ $order->stitchingOrder->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->stitchingOrder->stitching_status)) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-5 text-muted">Assigned Tailor:</dt>
                                <dd class="col-sm-7">
                                    @if($order->stitchingOrder->tailor)
                                    <strong>{{ $order->stitchingOrder->tailor->name }}</strong>
                                    @else
                                    <span class="badge bg-warning">Unassigned</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-5 text-muted">Fabric Details:</dt>
                                <dd class="col-sm-7">{{ $order->stitchingOrder->fabric_details ?? 'N/A' }}</dd>

                                <dt class="col-sm-5 text-muted">Start Date:</dt>
                                <dd class="col-sm-7">
                                    @if($order->stitchingOrder->start_date)
                                    {{ $order->stitchingOrder->start_date->format('M d, Y') }}
                                    @else
                                    <span class="text-muted">Not started</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-5 text-muted">Est. Cost:</dt>
                                <dd class="col-sm-7">Rs. {{ number_format($order->stitchingOrder->estimated_cost ?? 0, 2) }}</dd>
                            </dl>
                        </div>
                    </div>

                    @if($order->stitchingOrder->special_instructions)
                    <div class="alert alert-info mt-3">
                        <strong><i class="fas fa-lightbulb me-1"></i>Special Instructions:</strong><br>
                        {{ $order->stitchingOrder->special_instructions }}
                    </div>
                    @endif

                    @if($order->stitchingOrder->tailor_notes)
                    <div class="alert alert-light mt-3 border-start border-3 border-info">
                        <strong><i class="fas fa-file-alt me-1"></i>Tailor Notes:</strong><br>
                        {{ $order->stitchingOrder->tailor_notes }}
                    </div>
                    @endif

                    <a href="{{ route('admin.stitching-orders.show', $order->stitchingOrder) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye me-1"></i>View Full Stitching Details
                    </a>
                </div>
            </div>
            @endif

            <!-- Order Timeline -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-timeline me-2"></i>Order Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Order Created -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <strong>Order Created</strong><br>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y H:i A') }}</small>
                            </div>
                        </div>

                        <!-- Order Confirmed -->
                        @if(in_array($order->status, ['confirmed', 'in_progress', 'ready', 'delivered']))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <strong>Order Confirmed</strong><br>
                                <small class="text-muted">{{ $order->updated_at->format('M d, Y H:i A') }}</small>
                            </div>
                        </div>
                        @endif

                        <!-- Processing Started -->
                        @if(in_array($order->status, ['in_progress', 'ready', 'delivered']))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <strong>Processing Started</strong><br>
                                <small class="text-muted">{{ $order->updated_at->format('M d, Y H:i A') }}</small>
                            </div>
                        </div>
                        @endif

                        <!-- Ready for Delivery -->
                        @if(in_array($order->status, ['ready', 'delivered']))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <strong>Ready for Delivery</strong><br>
                                <small class="text-muted">{{ $order->updated_at->format('M d, Y H:i A') }}</small>
                            </div>
                        </div>
                        @endif

                        <!-- Delivered -->
                        @if($order->status === 'delivered')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <strong>Order Delivered</strong><br>
                                <small class="text-muted">{{ $order->delivery_date?->format('M d, Y') ?? $order->updated_at->format('M d, Y H:i A') }}</small>
                            </div>
                        </div>
                        @endif

                        <!-- Cancelled -->
                        @if($order->status === 'cancelled')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <strong>Order Cancelled</strong><br>
                                <small class="text-muted">{{ $order->updated_at->format('M d, Y H:i A') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($order->notes)
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Order Notes</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-7">Subtotal:</div>
                        <div class="col-5 text-end">Rs. {{ number_format($order->subtotal, 2) }}</div>
                    </div>

                    @if($order->stitching_charge > 0)
                    <div class="row mb-2">
                        <div class="col-7">Stitching Charge:</div>
                        <div class="col-5 text-end">Rs. {{ number_format($order->stitching_charge, 2) }}</div>
                    </div>
                    @endif

                    <div class="row mb-2">
                        <div class="col-7">Tax (18%):</div>
                        <div class="col-5 text-end">Rs. {{ number_format($order->tax, 2) }}</div>
                    </div>

                    @if($order->discount > 0)
                    <div class="row mb-2">
                        <div class="col-7">Discount:</div>
                        <div class="col-5 text-end text-danger">-Rs. {{ number_format($order->discount, 2) }}</div>
                    </div>
                    @endif

                    <hr class="my-3">

                    <div class="row">
                        <div class="col-7 fw-bold fs-5">Total:</div>
                        <div class="col-5 text-end fw-bold fs-5 text-dark">Rs. {{ number_format($order->total, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-7 text-muted">Status:</dt>
                        <dd class="col-5 text-end">
                            <span class="badge bg-{{ $order->payment_status_badge }}">
                                {{ $order->getPaymentStatusTextAttribute() }}
                            </span>
                        </dd>

                        <dt class="col-7 text-muted">Method:</dt>
                        <dd class="col-5 text-end">{{ ucfirst($order->payment_method ?? 'N/A') }}</dd>

                        <dt class="col-7 text-muted">Amount:</dt>
                        <dd class="col-5 text-end fw-bold">Rs. {{ number_format($order->total, 2) }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Order Status Quick Actions -->
            @can('edit_orders')
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    @if(!$order->isDelivered() && !in_array($order->status, ['cancelled']))
                    <button type="button" class="btn btn-sm btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="fas fa-sync me-1"></i>Update Status
                    </button>
                    @endif

                    @if($order->canBeCancelled())
                    <button type="button" class="btn btn-sm btn-danger w-100" onclick="cancelOrder({{ $order->id }})">
                        <i class="fas fa-times me-1"></i>Cancel Order
                    </button>
                    @endif
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Order Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>Processing</option>
                            <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Payment Status</label>
                        <select name="payment_status" class="form-select" required>
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Delivery Date</label>
                        <input type="date" name="delivery_date" class="form-control" value="{{ $order->delivery_date?->format('Y-m-d') ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Add order notes...">{{ $order->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.page-header {
    padding: 20px 0;
    border-bottom: 1px solid #e9ecef;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.timeline {
    position: relative;
    padding: 0;
}

.timeline-item {
    display: flex;
    margin-bottom: 20px;
    position: relative;
    padding-left: 50px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #dee2e6;
}

.timeline-content {
    flex: 1;
}

.btn-group .btn {
    padding: 6px 12px;
}
</style>

<script>
function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        // Implement cancel functionality
        console.log('Cancel order:', orderId);
    }
}
</script>
@endsection
