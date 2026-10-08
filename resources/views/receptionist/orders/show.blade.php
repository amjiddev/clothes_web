@extends('receptionist.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
@endsection

@section('content')
<!-- HEADER SECTION -->
<div class="mb-5">
    <!-- Back Link & Title Row -->
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <a href="{{ route('receptionist.orders.index') }}" class="text-muted text-decoration-none small mb-2 d-inline-block">
                <i class="fas fa-arrow-left me-1"></i>Back to Orders
            </a>
            <div class="d-flex align-items-center gap-3 mb-2">
                <h1 class="mb-0" style="font-size: 2rem; font-weight: 700;">Order #{{ $order->order_number }}</h1>
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
                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} rounded-pill" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
            <p class="text-muted mb-0 small">Placed on {{ $order->created_at->format('M d, Y') }} at {{ $order->created_at->format('h:i A') }}</p>
        </div>

        <!-- Action Buttons (Top Right) -->
        <div class="d-flex gap-2 flex-wrap justify-content-end" style="max-width: 500px;">
            <button class="btn btn-primary btn-sm" onclick="window.print()" title="Print Invoice" style="white-space: nowrap;">
                <i class="fas fa-print me-1"></i>Print Invoice
            </button>
            @if($order->invoice)
                <a href="{{ route('receptionist.invoices.show', $order->invoice) }}" class="btn btn-success btn-sm" title="View Invoice" style="white-space: nowrap;">
                    <i class="fas fa-eye me-1"></i>View Invoice
                </a>
            @else
                <a href="{{ route('receptionist.invoices.create', $order) }}" class="btn btn-success btn-sm" title="Create Invoice" style="white-space: nowrap;">
                    <i class="fas fa-plus me-1"></i>Create Invoice
                </a>
            @endif
            <a href="{{ route('receptionist.orders.edit', $order) }}" class="btn btn-outline-secondary btn-sm" title="Edit Order" style="white-space: nowrap;">
                <i class="fas fa-pencil-alt me-1"></i>Edit Order
            </a>
            @if(in_array($order->status, ['pending', 'confirmed']))
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelOrderModal" title="Cancel Order" style="white-space: nowrap;">
                    <i class="fas fa-times me-1"></i>Cancel Order
                </button>
            @endif
        </div>
    </div>
</div>

<!-- TOP SUMMARY ROW (3 Cards) -->
<div class="row g-3 mb-5">
    <!-- Card 1: Order Details -->
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 rounded-3 shadow-sm" style="height: 100%;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-box text-primary me-2" style="font-size: 1.25rem;"></i>
                    <h6 class="mb-0 fw-600">Order Details</h6>
                </div>
                <div class="row g-3" style="font-size: 0.9rem;">
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Order ID</small>
                        <strong class="d-block">{{ $order->order_number }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Order Date</small>
                        <strong class="d-block">{{ $order->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Delivery</small>
                        <strong class="d-block">{{ $order->delivery_date ? $order->delivery_date->format('M d, Y') : 'Not set' }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Expected Time</small>
                        <strong class="d-block">{{ $order->created_at->format('h:i A') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Customer Information -->
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 rounded-3 shadow-sm" style="height: 100%;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-user text-success me-2" style="font-size: 1.25rem;"></i>
                    <h6 class="mb-0 fw-600">Customer Information</h6>
                </div>
                <div class="row g-3" style="font-size: 0.9rem;">
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Name</small>
                        <strong class="d-block">{{ $order->user->name }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">City</small>
                        <strong class="d-block">{{ $order->user->address?->city ?? 'Not provided' }}</strong>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block mb-1">Email</small>
                        <a href="mailto:{{ $order->user->email }}" class="text-primary" style="text-decoration: none;">{{ $order->user->email }}</a>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Phone</small>
                        <a href="tel:{{ $order->user->contact_number }}" class="text-primary" style="text-decoration: none;">{{ $order->user->contact_number ?? 'Not provided' }}</a>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Address</small>
                        <strong class="d-block">{{ $order->user->address?->address_line_1 ?? 'Not provided' }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Customer ID</small>
                        <strong class="d-block">#{{ $order->user->id }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Payment Status -->
    <div class="col-md-12 col-lg-4">
        <div class="card border-0 rounded-3 shadow-sm" style="height: 100%;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-credit-card text-info me-2" style="font-size: 1.25rem;"></i>
                    <h6 class="mb-0 fw-600">Payment Status</h6>
                </div>
                <div class="row g-3" style="font-size: 0.9rem;">
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Method</small>
                        <div>
                            @if($order->payment_method)
                                <i class="fas fa-money-bill-wave me-1" style="color: #666;"></i>
                                <strong>{{ ucfirst($order->payment_method) }}</strong>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Status</small>
                        @if($order->payment_status === 'paid')
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Paid</span>
                        @elseif($order->payment_status === 'partial')
                            <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>Partial</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>Pending</span>
                        @endif
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Paid Amount</small>
                        <strong class="d-block">{{ env('CURRENCY_SYMBOL', 'Rs.') }} {{ number_format($order->invoice?->amount_paid ?? 0, 2) }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Remaining Amount</small>
                        <strong class="d-block text-danger">{{ env('CURRENCY_SYMBOL', 'Rs.') }} {{ number_format($order->invoice?->balance_due ?? $order->total, 2) }}</strong>
                    </div>
                    <div class="col-12" style="border-top: 1px solid #e9ecef; padding-top: 0.75rem; margin-top: 0.25rem;">
                        <div class="d-flex justify-content-between">
                            <strong>Total Amount</strong>
                            <strong class="text-primary">{{ env('CURRENCY_SYMBOL', 'Rs.') }} {{ number_format($order->total, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BOTTOM SECTION (Two Columns) -->
<div class="row g-4">
    <!-- LEFT COLUMN (70%) -->
    <div class="col-lg-8">
        <!-- Order Items Card -->
        <div class="card border-0 rounded-3 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="mb-3 fw-600">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th class="border-0 ps-0">#</th>
                                <th class="border-0">Product</th>
                                <th class="border-0">Category</th>
                                <th class="border-0 text-center">Quantity</th>
                                <th class="border-0 text-end">Price</th>
                                <th class="border-0 text-end pe-0">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->orderItems as $index => $item)
                                <tr style="border-bottom: 1px solid #f0f0f0;">
                                    <td class="ps-0">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 40px; height: 40px; background-color: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <i class="fas fa-box" style="color: #999;"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $item->product->name }}</strong>
                                                <small class="text-muted">{{ $item->product->product_code ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark" style="background-color: #e3f2fd !important;">{{ $item->product->category->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ env('CURRENCY_SYMBOL', 'Rs.') }} {{ number_format($item->price, 2) }}</td>
                                    <td class="text-end pe-0"><strong>{{ env('CURRENCY_SYMBOL', 'Rs.') }} {{ number_format($item->quantity * $item->price, 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No items in this order</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="card border-0 rounded-3 shadow-sm" style="background-color: #f8f9fa;">
            <div class="card-body">
                <div style="font-size: 0.9rem;">
                    <div class="d-flex justify-content-between mb-3 pb-3" style="border-bottom: 1px solid #e9ecef;">
                        <span>Subtotal</span>
                        <strong>{{ env('CURRENCY_SYMBOL', 'Rs.') }} {{ number_format($order->subtotal, 2) }}</strong>
                    </div>
                    @if($order->stitching_charge > 0)
                        <div class="d-flex justify-content-between mb-3 pb-3" style="border-bottom: 1px solid #e9ecef;">
                            <span>Stitching Charge</span>
                            <strong>{{ env('CURRENCY_SYMBOL', 'Rs.') }} {{ number_format($order->stitching_charge, 2) }}</strong>
                        </div>
                    @endif
                    @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-3 pb-3" style="border-bottom: 1px solid #e9ecef;">
                            <span>Discount</span>
                            <strong class="text-danger">-{{ env('CURRENCY_SYMBOL', 'Rs.') }} {{ number_format($order->discount, 2) }}</strong>
                        </div>
                    @endif
                    @if($order->tax > 0)
                        <div class="d-flex justify-content-between mb-3 pb-3" style="border-bottom: 1px solid #e9ecef;">
                            <span>Tax</span>
                            <strong>{{ env('CURRENCY_SYMBOL', 'Rs.') }} {{ number_format($order->tax, 2) }}</strong>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between">
                        <strong style="font-size: 1rem;">Total Amount</strong>
                        <strong style="font-size: 1.1rem; color: #0d6efd;">{{ env('CURRENCY_SYMBOL', 'Rs.') }} {{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN (30%) -->
    <div class="col-lg-4">
        <!-- Order Timeline Card -->
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body">
                <h6 class="mb-4 fw-600">Order Timeline</h6>
                <div class="position-relative" style="padding-left: 30px;">
                    <!-- Timeline Item 1: Order Placed -->
                    <div class="mb-4 position-relative">
                        <div class="position-absolute" style="left: -24px; top: 2px; width: 16px; height: 16px; background-color: #0d6efd; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 1px #0d6efd;"></div>
                        <div class="position-absolute" style="left: -12px; top: 25px; width: 2px; height: 60px; background-color: #d1d5db;"></div>
                        <strong style="font-size: 0.9rem;">Order Placed</strong>
                        <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">{{ $order->created_at->format('M d, Y • h:i A') }}</div>
                        <div style="font-size: 0.85rem; color: #999; margin-top: 0.1rem;">By: {{ $order->user->name }}</div>
                    </div>

                    <!-- Timeline Item 2: Payment Status -->
                    <div class="mb-4 position-relative">
                        <div class="position-absolute" style="left: -24px; top: 2px; width: 16px; height: 16px; background-color: #0d6efd; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 1px #0d6efd;"></div>
                        <div class="position-absolute" style="left: -12px; top: 25px; width: 2px; height: 60px; background-color: #d1d5db;"></div>
                        <strong style="font-size: 0.9rem;">
                            @if($order->payment_status === 'paid')
                                Payment Received
                            @elseif($order->payment_status === 'partial')
                                Partial Payment
                            @else
                                Payment Pending
                            @endif
                        </strong>
                        <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">
                            @if($order->payment_status === 'paid')
                                Payment completed
                            @elseif($order->payment_status === 'partial')
                                Partial payment received
                            @else
                                Waiting for payment
                            @endif
                        </div>
                    </div>

                    <!-- Timeline Item 3: Processing -->
                    <div class="mb-4 position-relative">
                        <div class="position-absolute" style="left: -24px; top: 2px; width: 16px; height: 16px; background-color: #d1d5db; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 1px #d1d5db;"></div>
                        <div class="position-absolute" style="left: -12px; top: 25px; width: 2px; height: 60px; background-color: #d1d5db;"></div>
                        <strong style="font-size: 0.9rem;">Processing</strong>
                        <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">Order is being prepared</div>
                    </div>

                    <!-- Timeline Item 4: Completed -->
                    <div class="position-relative">
                        <div class="position-absolute" style="left: -24px; top: 2px; width: 16px; height: 16px; background-color: #d1d5db; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 1px #d1d5db;"></div>
                        <strong style="font-size: 0.9rem;">Completed</strong>
                        <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">Order delivered</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
    .rounded-3 {
        border-radius: 12px;
    }

    .fw-600 {
        font-weight: 600;
    }

    .card {
        border: none !important;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .card-body {
        padding: 1.5rem !important;
    }

    .badge {
        font-weight: 500;
        padding: 0.4rem 0.75rem !important;
        font-size: 0.8rem !important;
        border-radius: 20px;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-outline-secondary {
        border-color: #dee2e6;
        color: #666;
    }

    .btn-outline-secondary:hover {
        border-color: #999;
        color: #333;
        background-color: transparent;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background-color: transparent;
        border: none;
        font-weight: 600;
        font-size: 0.85rem;
        color: #666;
        padding: 0.75rem 0;
    }

    .table tbody td {
        border: none;
        padding: 0.75rem 0;
        vertical-align: middle;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    @media (max-width: 768px) {
        .col-lg-8,
        .col-lg-4 {
            flex: 0 0 100%;
        }

        .card-body {
            padding: 1rem !important;
        }

        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .row.g-4 {
            gap: 1.5rem !important;
        }
    }

    @media print {
        .btn,
        .btn-group,
        .d-flex.gap-2 {
            display: none !important;
        }

        .card {
            page-break-inside: avoid;
            box-shadow: none !important;
        }

        .table {
            page-break-inside: avoid;
        }
    }
</style>
