@extends('receptionist.layouts.app')

@section('title', 'Payment Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.payments.index') }}">Payments</a></li>
    <li class="breadcrumb-item active">Payment #{{ $payment->id }}</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">
                <i class="fas fa-receipt me-2"></i>Payment #{{ $payment->id }}
            </h1>
            <p class="text-muted">Payment Details & Information</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('receptionist.payments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <!-- Main Payment Details -->
    <div class="col-lg-8">
        <!-- Payment Information -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle me-2"></i>Payment Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Payment ID</p>
                        <p class="fw-bold h5">#{{ $payment->id }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Payment Status</p>
                        <p>
                            @if($payment->status === 'completed')
                            <span class="badge bg-success p-2">
                                <i class="fas fa-check-circle me-1"></i>Completed
                            </span>
                            @elseif($payment->status === 'pending')
                            <span class="badge bg-warning p-2">
                                <i class="fas fa-clock me-1"></i>Pending
                            </span>
                            @else
                            <span class="badge bg-danger p-2">
                                <i class="fas fa-times-circle me-1"></i>Failed
                            </span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Payment Amount</p>
                        <p class="fw-bold text-success h5">PKR {{ number_format($payment->amount, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Payment Method</p>
                        <p class="fw-bold">
                            @php
                                $methodIcons = [
                                    'cash' => 'fa-money-bill',
                                    'card' => 'fa-credit-card',
                                    'bank_transfer' => 'fa-university',
                                    'online' => 'fa-wifi',
                                ];
                                $icon = $methodIcons[$payment->payment_method] ?? 'fa-wallet';
                            @endphp
                            <i class="fas {{ $icon }} me-2"></i>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Payment Date</p>
                        <p class="fw-bold">{{ $payment->processed_at ? $payment->processed_at->format('M d, Y - h:i A') : 'Not yet processed' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Transaction ID</p>
                        <p class="fw-bold">{{ $payment->transaction_id ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Recorded By</p>
                        <p class="fw-bold">{{ $payment->user->name ?? 'System' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Created On</p>
                        <p class="fw-bold">{{ $payment->created_at->format('M d, Y - h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Order Information -->
        <div class="card border-0 shadow">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-shopping-bag me-2"></i>Related Order Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Order Number</p>
                        <p class="fw-bold">
                            <a href="{{ route('receptionist.orders.show', $payment->order) }}" class="text-decoration-none">
                                {{ $payment->order->order_number }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Order Date</p>
                        <p class="fw-bold">{{ $payment->order->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Order Type</p>
                        <p class="fw-bold">{{ $payment->order->type_text }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Order Status</p>
                        <p>
                            <span class="badge bg-{{ $payment->order->status_badge }}">
                                {{ $payment->order->status_text }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Order Total</p>
                        <p class="fw-bold text-info">PKR {{ number_format($payment->order->total, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Order Payment Status</p>
                        <p>
                            @if($payment->order->payment_status === 'paid')
                            <span class="badge bg-success">Paid</span>
                            @elseif($payment->order->payment_status === 'pending')
                            <span class="badge bg-warning">Pending</span>
                            @else
                            <span class="badge bg-danger">Failed</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Customer Information -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-user me-2"></i>Customer Information
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex mb-3">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-1">Customer Name</p>
                        <p class="fw-bold">{{ $payment->order->user->name }}</p>
                    </div>
                </div>

                <p class="text-muted small mb-1">Email</p>
                <p class="mb-3">
                    <a href="mailto:{{ $payment->order->user->email }}" class="text-decoration-none">
                        {{ $payment->order->user->email }}
                    </a>
                </p>

                <p class="text-muted small mb-1">Phone</p>
                <p class="mb-3">
                    <a href="tel:{{ $payment->order->user->phone }}" class="text-decoration-none">
                        {{ $payment->order->user->phone ?? 'N/A' }}
                    </a>
                </p>

                <p class="text-muted small mb-1">Address</p>
                <p>{{ $payment->order->user->address ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-chart-bar me-2"></i>Payment Summary
                </h6>
            </div>
            <div class="card-body">
                @php
                    $totalPaid = $payment->order->payments()
                        ->where('status', 'completed')
                        ->sum('amount');
                    $remaining = max(0, $payment->order->total - $totalPaid);
                @endphp

                <div class="mb-3 pb-3 border-bottom">
                    <p class="text-muted small mb-1">Order Total</p>
                    <p class="fw-bold h6">PKR {{ number_format($payment->order->total, 2) }}</p>
                </div>

                <div class="mb-3 pb-3 border-bottom">
                    <p class="text-muted small mb-1">Total Paid</p>
                    <p class="fw-bold text-success h6">PKR {{ number_format($totalPaid, 2) }}</p>
                </div>

                <div>
                    <p class="text-muted small mb-1">Remaining Balance</p>
                    <p class="fw-bold text-warning h6">PKR {{ number_format($remaining, 2) }}</p>
                </div>

                @if($remaining > 0)
                <div class="progress mt-3" style="height: 25px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($totalPaid / $payment->order->total) * 100 }}%; font-size: 0.75rem;" aria-valuenow="{{ $totalPaid }}" aria-valuemin="0" aria-valuemax="{{ $payment->order->total }}">
                        {{ round(($totalPaid / $payment->order->total) * 100) }}%
                    </div>
                </div>
                @else
                <div class="alert alert-success mt-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    Fully Paid
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <a href="{{ route('receptionist.orders.show', $payment->order) }}" 
                   class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-eye me-2"></i>View Order
                </a>
                <a href="{{ route('receptionist.invoices.show', $payment->order) }}" 
                   class="btn btn-outline-secondary w-100 mb-2">
                    <i class="fas fa-invoice me-2"></i>View Invoice
                </a>
                <a href="{{ route('receptionist.payments.index') }}" 
                   class="btn btn-outline-secondary w-100">
                    <i class="fas fa-list me-2"></i>Back to Payments
                </a>
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
