@extends('receptionist.layouts.app')

@section('title', 'Record Payment - ' . $invoice->invoice_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.invoices.index') }}">Invoices</a></li>
    <li class="breadcrumb-item"><a href="{{ route('receptionist.invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a></li>
    <li class="breadcrumb-item active">Record Payment</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-money-bill-wave me-2"></i>Record Payment for {{ $invoice->invoice_number }}
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Invoice Summary -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Total Amount:</strong><br>
                                <span class="h5">Rs. {{ number_format($invoice->total_amount, 2) }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Amount Paid:</strong><br>
                                <span class="h5 text-success">Rs. {{ number_format($invoice->amount_paid, 2) }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Balance Due:</strong><br>
                                <span class="h5 text-danger">Rs. {{ number_format($invoice->balance_due, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($invoice->balance_due <= 0)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        This invoice is fully paid. No payment needed.
                    </div>
                    <a href="{{ route('receptionist.invoices.show', $invoice) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Invoice
                    </a>
                    @else
                    <!-- Payment Form -->
                    <form method="POST" action="{{ route('receptionist.invoices.record-payment', $invoice) }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Payment Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number" 
                                           name="amount" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           step="0.01" 
                                           min="0.01" 
                                           max="{{ $invoice->balance_due }}"
                                           value="{{ old('amount', $invoice->balance_due) }}"
                                           required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maximum: Rs. {{ number_format($invoice->balance_due, 2) }}</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Payment Date</label>
                                <input type="date" 
                                       name="payment_date" 
                                       class="form-control @error('payment_date') is-invalid @enderror" 
                                       value="{{ old('payment_date', date('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}"
                                       required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Payment Method</label>
                                <select name="payment_method" 
                                        class="form-select @error('payment_method') is-invalid @enderror" 
                                        required>
                                    <option value="">Select Payment Method</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Transaction ID (Optional)</label>
                                <input type="text" 
                                       name="transaction_id" 
                                       class="form-control @error('transaction_id') is-invalid @enderror" 
                                       value="{{ old('transaction_id') }}"
                                       placeholder="e.g., TXN123456">
                                @error('transaction_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Reference number for electronic payments</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Notes (Optional)</label>
                                <textarea name="notes" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          rows="3" 
                                          placeholder="Add any additional notes about this payment...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Quick Amount Buttons -->
                        <div class="mb-4">
                            <label class="form-label">Quick Amount:</label>
                            <div class="btn-group" role="group">
                                @php
                                    $quickAmounts = [];
                                    $balance = $invoice->balance_due;
                                    
                                    if ($balance > 1000) {
                                        $quickAmounts[] = 1000;
                                        $quickAmounts[] = 2000;
                                        $quickAmounts[] = 5000;
                                    }
                                    if ($balance > 100) {
                                        $quickAmounts[] = 500;
                                    }
                                    $quickAmounts[] = round($balance / 2, 2);
                                    $quickAmounts[] = $balance;
                                @endphp

                                @foreach(array_unique($quickAmounts) as $amount)
                                    @if($amount > 0 && $amount <= $balance)
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-sm" 
                                            onclick="document.querySelector('input[name=amount]').value = {{ $amount }}">
                                        Rs. {{ number_format($amount, 0) }}
                                    </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3">Customer Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Name:</strong> {{ $invoice->order->user->name }}</p>
                                        <p class="mb-1"><strong>Email:</strong> {{ $invoice->order->user->email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Phone:</strong> {{ $invoice->order->user->phone ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Order:</strong> 
                                            <a href="{{ route('receptionist.orders.show', $invoice->order) }}">
                                                {{ $invoice->order->order_number }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('receptionist.invoices.show', $invoice) }}" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>Record Payment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Previous Payments -->
            @if($invoice->payments->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Payment History
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Transaction ID</th>
                                    <th>Received By</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td><strong class="text-success">Rs. {{ number_format($payment->amount, 2) }}</strong></td>
                                    <td>{{ $payment->payment_method_text }}</td>
                                    <td>{{ $payment->transaction_id ?? '-' }}</td>
                                    <td>{{ $payment->receivedByUser?->name ?? 'System' }}</td>
                                    <td>{{ $payment->notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.querySelector('input[name="amount"]');
    const maxAmount = {{ $invoice->balance_due }};
    
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            if (parseFloat(this.value) > maxAmount) {
                this.value = maxAmount;
            }
        });
    }
});
</script>
@endpush
