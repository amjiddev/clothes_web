@extends('receptionist.layouts.app')

@section('title', 'Payment Collection Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Payment Collection Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-money-bill-wave me-2"></i>Payment Collection Report</h1>
                <p class="text-muted">Track payment collections, pending amounts, and transaction status</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('receptionist.reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate->toDateString() }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate->toDateString() }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="all">All Status</option>
                        <option value="completed" {{ $paymentStatus === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ $paymentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ $paymentStatus === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ $paymentStatus === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Transactions</h6>
                    <h3 class="mb-0 text-primary">{{ $summary['total_transactions'] }}</h3>
                    <small class="text-muted">transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Collected</h6>
                    <h3 class="mb-0 text-success">₹{{ number_format($summary['total_collected'], 2) }}</h3>
                    <small class="text-muted">amount</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Pending</h6>
                    <h3 class="mb-0 text-warning">₹{{ number_format($summary['total_pending'], 2) }}</h3>
                    <small class="text-muted">to collect</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Collection Rate</h6>
                    <h3 class="mb-0 text-info">{{ number_format($summary['collection_rate'], 1) }}%</h3>
                    <small class="text-muted">collected</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Daily Payment Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Status Summary</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check text-success me-2"></i>Completed</span>
                            <span class="badge bg-success">{{ $summary['completed_count'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-hourglass text-warning me-2"></i>Pending</span>
                            <span class="badge bg-warning">{{ $summary['pending_count'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-times text-danger me-2"></i>Failed</span>
                            <span class="badge bg-danger">{{ $summary['failed_count'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-undo text-secondary me-2"></i>Refunded</span>
                            <span class="badge bg-secondary">{{ $summary['refunded_count'] }}</span>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <h6 class="text-muted mb-2">Average Payment</h6>
                        <h5 class="text-primary">₹{{ number_format($summary['average_payment'], 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Amounts Summary -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="fas fa-check text-success me-2"></i>Completed Payments</h6>
                    <h4 class="text-success">₹{{ number_format($summary['total_collected'], 2) }}</h4>
                    <p class="text-muted mb-0"><small>{{ $summary['completed_count'] }} transactions</small></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="fas fa-hourglass text-warning me-2"></i>Pending Payments</h6>
                    <h4 class="text-warning">₹{{ number_format($summary['total_pending'], 2) }}</h4>
                    <p class="text-muted mb-0"><small>{{ $summary['pending_count'] }} transactions</small></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="fas fa-times text-danger me-2"></i>Failed Payments</h6>
                    <h4 class="text-danger">₹{{ number_format($summary['total_failed'], 2) }}</h4>
                    <p class="text-muted mb-0"><small>{{ $summary['failed_count'] }} transactions</small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Payment Transaction Details</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Transaction ID</th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td><strong>{{ substr($payment->transaction_id, 0, 12) }}...</strong></td>
                        <td>{{ $payment->order->order_number ?? 'N/A' }}</td>
                        <td>{{ $payment->order->user->name ?? 'Unknown' }}</td>
                        <td><strong>₹{{ number_format($payment->amount, 2) }}</strong></td>
                        <td>
                            <span class="badge bg-light text-dark">
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </span>
                        </td>
                        <td>
                            @if($payment->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($payment->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($payment->status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @else
                                <span class="badge bg-secondary">Refunded</span>
                            @endif
                        </td>
                        <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            @if($payment->order)
                                <a href="{{ route('receptionist.orders.show', $payment->order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No payment transactions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Payment Chart
    const ctx = document.getElementById('paymentChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels'] ?? []),
            datasets: [
                {
                    label: 'Completed (₹)',
                    data: @json($chartData['completed'] ?? []),
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: '#28a745',
                    borderWidth: 1,
                },
                {
                    label: 'Pending (₹)',
                    data: @json($chartData['pending'] ?? []),
                    backgroundColor: 'rgba(255, 193, 7, 0.7)',
                    borderColor: '#ffc107',
                    borderWidth: 1,
                },
                {
                    label: 'Failed (₹)',
                    data: @json($chartData['failed'] ?? []),
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: '#dc3545',
                    borderWidth: 1,
                },
                {
                    label: 'Refunded (₹)',
                    data: @json($chartData['refunded'] ?? []),
                    backgroundColor: 'rgba(108, 117, 125, 0.7)',
                    borderColor: '#6c757d',
                    borderWidth: 1,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    stacked: false,
                },
                y: {
                    stacked: false,
                    title: {
                        display: true,
                        text: 'Amount (₹)',
                    }
                }
            }
        }
    });
</script>
@endsection
