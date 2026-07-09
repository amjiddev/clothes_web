@extends('receptionist.layouts.app')

@section('title', 'Invoice #' . $order->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.invoices.index') }}">Invoices</a></li>
    <li class="breadcrumb-item active">Invoice #{{ $order->id }}</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Invoice #{{ $order->id }}</h1>
        <p class="page-subtitle">Order placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
    </div>
    <div>
        <a href="{{ route('receptionist.invoices.download', $order->id) }}" class="btn btn-custom btn-primary-custom">
            <i class="fas fa-download"></i> Download PDF
        </a>
        <a href="{{ route('receptionist.invoices.print', $order->id) }}" class="btn btn-custom btn-secondary-custom" target="_blank">
            <i class="fas fa-print"></i> Print
        </a>
    </div>
</div>

<div class="row">
    <!-- Invoice Details -->
    <div class="col-lg-8">
        <div class="table-card mb-3">
            <div class="table-header">
                <h5 class="table-title">Order Information</h5>
            </div>
            <div style="padding: 20px;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Order ID:</strong> #{{ $order->id }}<br>
                        <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                        <strong>Order Type:</strong> {{ ucfirst($order->order_type ?? 'Regular') }}<br>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong> 
                        @php
                            $statusClass = match($order->status) {
                                'pending' => 'status-pending',
                                'completed' => 'status-completed',
                                'ready_for_delivery' => 'status-ready',
                                default => 'status-pending',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span><br>
                        <strong>Total Amount:</strong> <span style="color: var(--accent-color); font-weight: bold;">₹{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="table-card mb-3">
            <div class="table-header">
                <h5 class="table-title">Customer Information</h5>
            </div>
            <div style="padding: 20px;">
                <strong>Name:</strong> {{ $order->customer?->name ?? 'N/A' }}<br>
                <strong>Email:</strong> {{ $order->customer?->email ?? 'N/A' }}<br>
                <strong>Phone:</strong> {{ $order->customer?->phone ?? 'N/A' }}<br>
                <strong>Address:</strong> {{ $order->customer?->address ?? 'N/A' }}
            </div>
        </div>

        <!-- Order Items -->
        <div class="table-card">
            <div class="table-header">
                <h5 class="table-title">Order Items</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items ?? [] as $item)
                            <tr>
                                <td>{{ $item->product?->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                <td>₹{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No items found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary Sidebar -->
    <div class="col-lg-4">
        <!-- Payment Status -->
        <div class="table-card mb-3">
            <div class="table-header">
                <h5 class="table-title">Payment Summary</h5>
            </div>
            <div style="padding: 20px;">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <strong>₹{{ number_format($order->subtotal ?? $order->total, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax (if applicable):</span>
                    <strong>₹{{ number_format($order->tax ?? 0, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3" style="border-top: 1px solid #e0e6ed; padding-top: 10px; margin-top: 10px;">
                    <strong>Total:</strong>
                    <strong style="color: var(--accent-color); font-size: 1.2rem;">₹{{ number_format($order->total, 2) }}</strong>
                </div>
                @php
                    $payment = $order->payments?->first();
                    $paymentStatus = $payment?->status ?? 'pending';
                @endphp
                <div>
                    <strong>Payment Status:</strong><br>
                    @if($paymentStatus === 'completed')
                        <span class="badge bg-success w-100 mt-2">Paid</span>
                    @elseif($paymentStatus === 'pending')
                        <span class="badge bg-warning w-100 mt-2">Pending</span>
                    @else
                        <span class="badge bg-danger w-100 mt-2">Failed</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="table-card">
            <div class="table-header">
                <h5 class="table-title">Actions</h5>
            </div>
            <div style="padding: 20px;">
                <a href="{{ route('receptionist.orders.show', $order->id) }}" class="btn btn-outline-secondary w-100 mb-2">
                    <i class="fas fa-eye"></i> View Order
                </a>
                <a href="{{ route('receptionist.payments.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                    <i class="fas fa-credit-card"></i> View Payments
                </a>
                <a href="{{ route('receptionist.invoices.download', $order->id) }}" class="btn btn-custom btn-primary-custom w-100 mb-2">
                    <i class="fas fa-download"></i> Download
                </a>
                <a href="{{ route('receptionist.invoices.print', $order->id) }}" class="btn btn-custom btn-secondary-custom w-100" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
