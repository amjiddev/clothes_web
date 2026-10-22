@extends('receptionist.layouts.app')

@section('title', 'Invoice - ' . ($invoice->invoice_number ?? 'N/A'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.invoices.index') }}">Invoices</a></li>
    <li class="breadcrumb-item active">{{ $invoice->invoice_number ?? 'N/A' }}</li>
@endsection

@section('content')
<!-- HEADER SECTION -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <a href="{{ route('receptionist.invoices.index') }}" class="text-muted text-decoration-none small mb-2 d-inline-block">
                <i class="fas fa-arrow-left me-1"></i>Back to Invoices
            </a>
            <h1 class="mb-1" style="font-size: 2.5rem; font-weight: 700;">{{ $invoice->invoice_number }}</h1>
            <p class="text-muted mb-0 small">
                <i class="fas fa-calendar me-1"></i>Invoice Date: {{ $invoice->invoice_date->format('M d, Y') }}
                @if($invoice->due_date)
                    | Due: {{ $invoice->due_date->format('M d, Y') }}
                @endif
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 flex-wrap justify-content-end" style="max-width: 300px;">
            @if($invoice->balance_due > 0)
                <a href="{{ route('receptionist.invoices.payment', $invoice) }}" class="btn btn-success btn-sm" title="Record Payment">
                    <i class="fas fa-money-bill-wave me-1"></i>Record Payment
                </a>
            @endif
            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()" id="print-button" title="Print Invoice">
                <i class="fas fa-print me-1"></i>Print
            </button>
        </div>
    </div>
</div>

<!-- TOP SUMMARY CARDS (3 Cards) -->
<div class="row g-3 mb-5">
    <!-- Card 1: Invoice Details -->
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 rounded-3 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-file-invoice text-primary me-2" style="font-size: 1.25rem;"></i>
                    <h6 class="mb-0 fw-600">Invoice Details</h6>
                </div>
                <div style="font-size: 0.9rem;">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Invoice Number</small>
                        <strong class="d-block">{{ $invoice->invoice_number }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Order Reference</small>
                        <a href="{{ route('receptionist.orders.show', $invoice->order) }}" class="text-primary text-decoration-none">
                            <strong>{{ $invoice->order->order_number }}</strong>
                        </a>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Invoice Date</small>
                        <strong class="d-block">{{ $invoice->invoice_date->format('M d, Y') }}</strong>
                    </div>
                    @if($invoice->due_date)
                        <div>
                            <small class="text-muted d-block mb-1">Due Date</small>
                            <strong class="d-block">{{ $invoice->due_date->format('M d, Y') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Customer Information -->
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 rounded-3 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-user text-success me-2" style="font-size: 1.25rem;"></i>
                    <h6 class="mb-0 fw-600">Customer Information</h6>
                </div>
                <div style="font-size: 0.9rem;">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Name</small>
                        <strong class="d-block">{{ $invoice->order->user->name }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Email</small>
                        <a href="mailto:{{ $invoice->order->user->email }}" class="text-primary text-decoration-none">
                            {{ $invoice->order->user->email }}
                        </a>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Phone</small>
                        <a href="tel:{{ $invoice->order->user->contact_number }}" class="text-primary text-decoration-none">
                            {{ $invoice->order->user->contact_number ?? 'N/A' }}
                        </a>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1">Address</small>
                        <strong class="d-block small">{{ $invoice->order->user->address?->address_line_1 ?? 'Not provided' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Financial Summary -->
    <div class="col-md-12 col-lg-4">
        <div class="card border-0 rounded-3 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-credit-card text-info me-2" style="font-size: 1.25rem;"></i>
                    <h6 class="mb-0 fw-600">Financial Summary</h6>
                </div>
                <div style="font-size: 0.9rem;">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">Subtotal</small>
                            <strong class="d-block">Rs. {{ number_format($invoice->subtotal, 2) }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">Tax</small>
                            <strong class="d-block">Rs. {{ number_format($invoice->tax_amount, 2) }}</strong>
                        </div>
                    </div>
                    @if($invoice->discount_amount > 0)
                        <div class="mb-3" style="padding-bottom: 0.75rem; border-bottom: 1px solid #e9ecef;">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Discount</small>
                                <strong class="text-success">-Rs. {{ number_format($invoice->discount_amount, 2) }}</strong>
                            </div>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2 pb-2" style="border-bottom: 2px solid #e9ecef;">
                        <strong>Total Amount</strong>
                        <strong class="text-primary">Rs. {{ number_format($invoice->total_amount, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-2" style="border-bottom: 1px solid #e9ecef;">
                        <small class="text-muted">Amount Paid</small>
                        <small class="text-success"><strong>Rs. {{ number_format($invoice->amount_paid, 2) }}</strong></small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>Balance Due</strong>
                        <strong class="{{ $invoice->balance_due > 0 ? 'text-danger' : 'text-success' }}">
                            Rs. {{ number_format($invoice->balance_due, 2) }}
                        </strong>
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
        @if($invoice->order->orderItems->count() > 0)
        <div class="card border-0 rounded-3 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="mb-3 fw-600">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th class="border-0 ps-0">Product</th>
                                <th class="border-0">Category</th>
                                <th class="border-0 text-center">Qty</th>
                                <th class="border-0 text-end">Price</th>
                                <th class="border-0 text-end pe-0">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->order->orderItems as $item)
                                <tr style="border-bottom: 1px solid #f0f0f0;">
                                    <td class="ps-0">
                                        <div>
                                            <strong class="d-block">{{ $item->product->name }}</strong>
                                            <small class="text-muted">{{ $item->product->product_code ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark" style="background-color: #e3f2fd !important;">
                                            {{ $item->product->category?->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rs. {{ number_format($item->price, 2) }}</td>
                                    <td class="text-end pe-0">
                                        <strong>Rs. {{ number_format($item->quantity * $item->price, 2) }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Notes Section -->
        @if($invoice->notes)
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body">
                <h6 class="mb-3 fw-600">Notes</h6>
                <p class="mb-0" style="font-size: 0.9rem; line-height: 1.6;">{{ $invoice->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- RIGHT COLUMN (30%) -->
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card border-0 rounded-3 shadow-sm mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-badge-check" style="font-size: 2rem; color: #0d6efd;"></i>
                </div>
                <h6 class="mb-3 fw-600">Invoice Status</h6>
                @php
                    $statusClass = match($invoice->status) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'unpaid' => 'danger',
                        'cancelled' => 'secondary',
                        default => 'secondary',
                    };
                    $statusIcon = match($invoice->status) {
                        'paid' => 'fas fa-check-circle',
                        'partial' => 'fas fa-hourglass-half',
                        'unpaid' => 'fas fa-exclamation-circle',
                        'cancelled' => 'fas fa-times-circle',
                        default => 'fas fa-question-circle',
                    };
                @endphp
                <div style="padding: 1rem; background-color: #f8f9fa; border-radius: 8px; margin-bottom: 1rem;">
                    <h4 class="mb-2">
                        <span class="badge bg-{{ $statusClass }} p-2" style="font-size: 0.9rem;">
                            <i class="{{ $statusIcon }} me-1"></i>{{ ucfirst($invoice->status) }}
                        </span>
                    </h4>
                    @if($invoice->paid_at && $invoice->status === 'paid')
                        <p class="text-muted small mb-0">
                            <i class="fas fa-calendar me-1"></i>Paid on {{ $invoice->paid_at->format('M d, Y') }}
                        </p>
                    @endif
                </div>

                <!-- Progress Bar -->
                <div class="mb-3">
                    <small class="text-muted d-block mb-2">Payment Progress</small>
                    <div class="progress" style="height: 8px;">
                        @php
                            $progressPercentage = $invoice->total_amount > 0 
                                ? round(($invoice->amount_paid / $invoice->total_amount) * 100) 
                                : 0;
                        @endphp
                        <div class="progress-bar bg-{{ $invoice->balance_due > 0 ? 'warning' : 'success' }}" 
                             role="progressbar" 
                             style="width: {{ $progressPercentage }}%;" 
                             aria-valuenow="{{ $progressPercentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted d-block mt-1">{{ $progressPercentage }}% Paid</small>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        @if($invoice->payments->count() > 0)
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body">
                <h6 class="mb-3 fw-600">Payment History</h6>
                <div style="max-height: 400px; overflow-y: auto;">
                    @foreach($invoice->payments as $payment)
                    <div class="mb-3 pb-3" style="border-bottom: 1px solid #e9ecef;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong style="font-size: 0.95rem;">Rs. {{ number_format($payment->amount, 2) }}</strong>
                                <div style="font-size: 0.85rem;">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-money-bill-wave me-1" style="color: #28a745;"></i>
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $payment->payment_date->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Paid
                            </span>
                        </div>
                        @if($payment->notes)
                            <small class="text-muted d-block" style="margin-left: 0;">
                                <em>{{ $payment->notes }}</em>
                            </small>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-info border-0 rounded-3" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <small>No payments recorded yet</small>
        </div>
        @endif
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

    .progress {
        background-color: #f0f0f0;
        border-radius: 10px;
        overflow: hidden;
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
    }

    @media print {
        .btn,
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

<script>
// Auto-trigger print if ?print=1 parameter is present
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === '1') {
        // Delay print to allow page to fully render
        setTimeout(function() {
            window.print();
        }, 500);
    }
});
</script>
