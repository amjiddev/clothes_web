@extends('admin.layouts.app')

@section('title', 'Payments')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Payments</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-credit-card me-2"></i>Payments Management</h1>
                <p class="text-muted">Track and manage all payment transactions</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Completed</p>
                            <h3 class="mb-0">{{ $payments->where('status', 'completed')->count() }}</h3>
                        </div>
                        <div class="text-success opacity-50 ms-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Pending</p>
                            <h3 class="mb-0">{{ $payments->where('status', 'pending')->count() }}</h3>
                        </div>
                        <div class="text-warning opacity-50 ms-3">
                            <i class="fas fa-hourglass-half fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Failed</p>
                            <h3 class="mb-0">{{ $payments->where('status', 'failed')->count() }}</h3>
                        </div>
                        <div class="text-danger opacity-50 ms-3">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Refunded</p>
                            <h3 class="mb-0">{{ $payments->where('status', 'refunded')->count() }}</h3>
                        </div>
                        <div class="text-secondary opacity-50 ms-3">
                            <i class="fas fa-undo fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light border-bottom">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="mb-0">All Payments</h6>
                </div>
                <div class="col-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search payments..." id="searchInput">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="paymentsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Transaction ID</th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded">{{ $payment->id }}</code>
                                </td>
                                <td>
                                    <a href="{{ $payment->order ? route('admin.orders.show', $payment->order->id) : '#' }}" class="text-decoration-none">
                                        #{{ $payment->order?->order_number ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $payment->user?->name ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $payment->user?->email ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <strong>{{ number_format($payment->amount, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($payment->payment_method ?? 'N/A') }}</span>
                                </td>
                                <td>
                                    @switch($payment->status)
                                        @case('completed')
                                            <span class="badge bg-success">Completed</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @break
                                        @case('failed')
                                            <span class="badge bg-danger">Failed</span>
                                            @break
                                        @case('refunded')
                                            <span class="badge bg-secondary">Refunded</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <small>{{ $payment->created_at?->format('M d, Y') ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No payments found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
    <div class="mt-4">
        {{ $payments->links() }}
    </div>
    @endif
</div>

<style>
    .page-header {
        padding: 20px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .page-title {
        font-size: 28px;
        font-weight: 600;
        margin: 0;
    }

    .card {
        border-radius: 0.5rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('#paymentsTable tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endsection
