@extends('receptionist.layouts.app')

@section('title', 'Invoices')

@section('breadcrumb')
    <li class="breadcrumb-item active">Invoices</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Invoices</h1>
    <p class="page-subtitle">View and manage all order invoices</p>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by Order ID or Customer..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="ready_for_delivery" {{ request('status') === 'ready_for_delivery' ? 'selected' : '' }}>Ready for Delivery</option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-custom btn-primary-custom w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('receptionist.invoices.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Invoices Table -->
<div class="table-card">
    <div class="table-header">
        <h5 class="table-title">All Invoices</h5>
        <span class="badge bg-secondary">{{ $invoices->total() }} Total</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $order)
                    <tr>
                        <td>
                            <a href="{{ route('receptionist.invoices.show', $order->id) }}" style="color: var(--accent-color); text-decoration: none;">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td>{{ $order->customer?->name ?? 'N/A' }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>₹{{ number_format($order->total, 2) }}</td>
                        <td>
                            @php
                                $statusClass = match($order->status) {
                                    'pending' => 'status-pending',
                                    'completed' => 'status-completed',
                                    'ready_for_delivery' => 'status-ready',
                                    'delivered' => 'status-completed',
                                    'cancelled' => 'status-danger',
                                    default => 'status-pending',
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                        </td>
                        <td>
                            @php
                                $paymentStatus = $order->payments?->first()?->status ?? 'pending';
                                $paymentClass = $paymentStatus === 'completed' ? 'bg-success' : ($paymentStatus === 'pending' ? 'bg-warning' : 'bg-danger');
                            @endphp
                            <span class="badge {{ $paymentClass }}">{{ ucfirst($paymentStatus) }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('receptionist.invoices.show', $order->id) }}" class="btn btn-outline-secondary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('receptionist.invoices.download', $order->id) }}" class="btn btn-outline-secondary" title="Download PDF">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('receptionist.invoices.print', $order->id) }}" class="btn btn-outline-secondary" target="_blank" title="Print">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3"></i><br>
                            No invoices found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="row mt-4">
    <div class="col-12">
        {{ $invoices->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
