@extends('receptionist.layouts.app')

@section('title', 'Edit Order - ' . $order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.show', $order) }}">{{ $order->order_number }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-edit me-2"></i>Edit Order {{ $order->order_number }}
            </h1>
            <p class="text-muted">Update basic order details</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.orders.show', $order) }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
    </div>
</div>

<!-- Success Message -->
@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Error Messages -->
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>Please fix the errors below
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <!-- Order Details Form -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle me-2"></i>Order Details
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('receptionist.orders.update', $order) }}">
                    @csrf
                    @method('PUT')

                    <!-- Order Information (Read-only) -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <small class="text-muted d-block">Order Number</small>
                                <strong>{{ $order->order_number }}</strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <small class="text-muted d-block">Order Type</small>
                                <span class="badge bg-info p-2">
                                    @if($order->type === 'ready_made')
                                        Cloth Only
                                    @elseif($order->type === 'stitching')
                                        Stitching Only
                                    @else
                                        Cloth + Stitching
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <small class="text-muted d-block">Customer</small>
                                <strong>{{ $order->user->name }}</strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <small class="text-muted d-block">Total Amount</small>
                                <strong class="text-primary">₹{{ number_format($order->total, 2) }}</strong>
                            </p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Editable Fields -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="delivery_date" class="form-label fw-bold">
                                <i class="fas fa-calendar me-2"></i>Delivery Date
                            </label>
                            <input type="date" 
                                   class="form-control @error('delivery_date') is-invalid @enderror" 
                                   id="delivery_date" 
                                   name="delivery_date" 
                                   value="{{ $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '' }}">
                            @error('delivery_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-bold">
                            <i class="fas fa-sticky-note me-2"></i>Order Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="4"
                                  placeholder="Add any special instructions or notes for this order">{{ old('notes', $order->notes) }}</textarea>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1"></i>Additional instructions or details for handling this order
                        </small>
                        @error('notes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                        <a href="{{ route('receptionist.orders.show', $order) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <!-- Order Status -->
        <div class="card border-0 shadow mb-3">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-tag me-2"></i>Current Status
                </h6>
            </div>
            <div class="card-body">
                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'in_progress' => 'primary',
                        'assigned_to_tailor' => 'info',
                        'stitching_started' => 'primary',
                        'completed' => 'success',
                        'quality_check' => 'warning',
                        'ready_for_delivery' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} p-3 fs-6">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="card border-0 shadow mb-3">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-credit-card me-2"></i>Payment Status
                </h6>
            </div>
            <div class="card-body">
                @if($order->payment_status === 'paid')
                    <span class="badge bg-success p-3 fs-6">
                        <i class="fas fa-check-circle me-1"></i>Fully Paid
                    </span>
                @elseif($order->payment_status === 'pending')
                    <span class="badge bg-warning text-dark p-3 fs-6">
                        <i class="fas fa-hourglass-half me-1"></i>Pending Payment
                    </span>
                @else
                    <span class="badge bg-danger p-3 fs-6">
                        <i class="fas fa-times-circle me-1"></i>Payment Failed
                    </span>
                @endif
            </div>
        </div>

        <!-- Created Info -->
        <div class="card border-0 shadow">
            <div class="card-body">
                <p class="mb-2">
                    <small class="text-muted d-block">Created Date</small>
                    <strong>{{ $order->created_at->format('M d, Y') }}</strong>
                </p>
                <p class="mb-2">
                    <small class="text-muted d-block">Created At</small>
                    <strong>{{ $order->created_at->format('h:i A') }}</strong>
                </p>
                <p>
                    <small class="text-muted d-block">Last Updated</small>
                    <strong>{{ $order->updated_at->format('M d, Y h:i A') }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}
</style>
@endsection
