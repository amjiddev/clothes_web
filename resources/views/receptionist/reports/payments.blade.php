@extends('receptionist.layouts.app')

@section('title', 'Payment Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Payment Report</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Payment Report</h1>
        <p class="page-subtitle">Monitor payment status and transaction history</p>
    </div>
    <form method="POST" action="{{ route('receptionist.reports.export') }}" class="d-inline">
        @csrf
        <input type="hidden" name="type" value="payments">
        <button type="submit" name="format" value="csv" class="btn btn-custom btn-primary-custom">
            <i class="fas fa-download"></i> Export as CSV
        </button>
    </form>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-custom btn-primary-custom w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Summary Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-hashtag stat-icon"></i>
            <div class="stat-value">{{ $totalPayments }}</div>
            <div class="stat-label">Total Payments</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-rupiah-sign stat-icon"></i>
            <div class="stat-value">₹{{ number_format($totalAmount, 0) }}</div>
            <div class="stat-label">Total Amount</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-check-circle stat-icon"></i>
            <div class="stat-value">₹{{ number_format($completedAmount, 0) }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-hourglass stat-icon"></i>
            <div class="stat-value">₹{{ number_format($pendingAmount, 0) }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="table-card">
    <div class="table-header">
        <h5 class="table-title">Payments</h5>
        <span class="badge bg-secondary">{{ $payments->count() }} Records</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>#{{ $payment->id }}</td>
                        <td>
                            <a href="{{ route('receptionist.orders.show', $payment->order_id) }}" style="color: var(--accent-color); text-decoration: none;">
                                #{{ $payment->order_id }}
                            </a>
                        </td>
                        <td>{{ $payment->order?->customer?->name ?? 'N/A' }}</td>
                        <td>₹{{ number_format($payment->amount, 2) }}</td>
                        <td>
                            @php
                                $statusClass = match($payment->status) {
                                    'pending' => 'bg-warning',
                                    'completed' => 'bg-success',
                                    'failed' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                        </td>
                        <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('receptionist.payments.show', $payment->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No payments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
