@extends('admin.layouts.app')

@section('title', 'Payment Details')

@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title"><i class="fas fa-receipt me-2"></i>Payment Details</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="chart-container">
                <div class="mb-4">
                    <h5 class="mb-3">Transaction Information</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Transaction ID:</strong></p>
                            <p>{{ $payment->transaction_id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Order #:</strong></p>
                            <p>{{ $payment->order->order_number }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Payment Method:</strong></p>
                            <p><span class="badge badge-info">{{ ucfirst($payment->payment_method) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong></p>
                            <p><span class="badge badge-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Amount:</strong></p>
                            <p style="font-size: 1.5rem; color: #d4af37;">{{ currency_format($payment->amount) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Processed At:</strong></p>
                            <p>{{ $payment->processed_at ? $payment->processed_at->format('M d, Y H:i:s') : 'Not Processed' }}</p>
                        </div>
                    </div>
                </div>

                @if ($payment->status !== 'completed')
                    <hr>
                    <div class="mt-4">
                        <h5 class="mb-3">Update Status</h5>
                        <form action="{{ route('admin.payments.update-status', $payment) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="status" class="form-select" required>
                                        <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $payment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="failed" {{ $payment->status === 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="refunded" {{ $payment->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-accent w-100">Update Status</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="chart-container">
                <h5 class="mb-4">Customer Details</h5>
                <p><strong>Name:</strong> {{ $payment->user->name }}</p>
                <p><strong>Email:</strong> {{ $payment->user->email }}</p>
                <p><strong>Created At:</strong> {{ $payment->created_at->format('M d, Y H:i') }}</p>
            </div>

            <div class="chart-container mt-4">
                <h5 class="mb-4">Order Summary</h5>
                <p><strong>Order Total:</strong> {{ currency_format($payment->order->total) }}</p>
                <p><strong>Order Status:</strong> <span class="badge badge-{{ $payment->order->status }}">{{ ucfirst($payment->order->status) }}</span></p>
                <p><strong>Payment Status:</strong> <span class="badge badge-{{ $payment->payment_status }}">{{ ucfirst($payment->status) }}</span></p>
            </div>
        </div>
    </div>
@endsection
