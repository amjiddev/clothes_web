@extends('receptionist.layouts.app')

@section('title', 'Invoice - ' . ($invoice->invoice_number ?? 'N/A'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.invoices.index') }}">Invoices</a></li>
    <li class="breadcrumb-item active">{{ $invoice->invoice_number ?? 'N/A' }}</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">{{ $invoice->invoice_number }}</h1>
        <p class="page-subtitle">Invoice Date: {{ $invoice->invoice_date->format('M d, Y') }}</p>
    </div>
    <div>
        @if($invoice->balance_due > 0)
        <a href="{{ route('receptionist.invoices.payment', $invoice) }}" class="btn btn-success">
            <i class="fas fa-money-bill-wave me-2"></i>Record Payment
        </a>
        @endif
        <button type="button" class="btn btn-primary" onclick="window.print()" id="print-button">
            <i class="fas fa-print me-2"></i>Print
        </button>
    </div>
</div>

<div class="row">
    <!-- Invoice Financial Summary -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Invoice Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                        <p><strong>Order Reference:</strong> 
                            <a href="{{ route('receptionist.orders.show', $invoice->order) }}">
                                {{ $invoice->order->order_number }}
                            </a>
                        </p>
                        <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
                        @if($invoice->due_date)
                        <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>Customer:</strong> {{ $invoice->order->user->name }}</p>
                        <p><strong>Email:</strong> {{ $invoice->order->user->email }}</p>
                        <p><strong>Phone:</strong> {{ $invoice->order->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-end"><strong>Subtotal:</strong></td>
                            <td class="text-end" style="width: 150px;">Rs. {{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-end"><strong>Tax:</strong></td>
                            <td class="text-end">Rs. {{ number_format($invoice->tax_amount, 2) }}</td>
                        </tr>
                        @if($invoice->discount_amount > 0)
                        <tr>
                            <td class="text-end"><strong>Discount:</strong></td>
                            <td class="text-end text-success">- Rs. {{ number_format($invoice->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="border-top">
                            <td class="text-end"><strong>Total Amount:</strong></td>
                            <td class="text-end"><strong>Rs. {{ number_format($invoice->total_amount, 2) }}</strong></td>
                        </tr>
                        <tr class="text-success">
                            <td class="text-end"><strong>Amount Paid:</strong></td>
                            <td class="text-end"><strong>Rs. {{ number_format($invoice->amount_paid, 2) }}</strong></td>
                        </tr>
                        <tr class="border-top text-danger">
                            <td class="text-end"><h5 class="mb-0">Balance Due:</h5></td>
                            <td class="text-end"><h5 class="mb-0">Rs. {{ number_format($invoice->balance_due, 2) }}</h5></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        @if($invoice->order->orderItems->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->product->category?->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">Rs. {{ number_format($item->price, 2) }}</td>
                                <td class="text-end">Rs. {{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if($invoice->notes)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Notes</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $invoice->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Invoice Status</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $statusClass = match($invoice->status) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'unpaid' => 'danger',
                        'cancelled' => 'secondary',
                        default => 'secondary',
                    };
                @endphp
                <h2>
                    <span class="badge bg-{{ $statusClass }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </h2>
                @if($invoice->paid_at)
                <p class="text-muted mb-0">Paid on {{ $invoice->paid_at->format('M d, Y') }}</p>
                @endif
            </div>
        </div>

        <!-- Payment History -->
        @if($invoice->payments->count() > 0)
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Payment History</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($invoice->payments as $payment)
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>Rs. {{ number_format($payment->amount, 2) }}</strong><br>
                                <small class="text-muted">
                                    {{ $payment->payment_method_text }}<br>
                                    {{ $payment->payment_date->format('M d, Y') }}
                                </small>
                                @if($payment->notes)
                                <br><small>{{ $payment->notes }}</small>
                                @endif
                            </div>
                            <span class="badge bg-success">Paid</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>No payments recorded yet
        </div>
        @endif
    </div>
</div>
@endsection

@section('extra-css')
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
@endsection
