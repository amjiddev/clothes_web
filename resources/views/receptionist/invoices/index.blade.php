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

@if(isset($error))
<div class="alert alert-danger">
    <h4><i class="fas fa-exclamation-triangle me-2"></i>Database Error</h4>
    <p>{{ $error }}</p>
    <hr>
    <p><strong>To fix this:</strong></p>
    <ol>
        <li>Open phpMyAdmin</li>
        <li>Select your database</li>
        <li>Click "SQL" tab</li>
        <li>Run the SQL commands to create the invoices and payments tables</li>
        <li>Or run: <code>php artisan migrate</code> in your terminal</li>
    </ol>
    <a href="{{ route('receptionist.orders.index') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left me-2"></i>Go to Orders
    </a>
</div>
@else

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by Invoice, Order ID or Customer..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
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
                    <th>Order Ref</th>
                    <th>Customer</th>
                    <th>Invoice Date</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Balance Due</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>
                            <a href="{{ route('receptionist.invoices.show', $invoice) }}" style="color: var(--accent-color); text-decoration: none;">
                                {{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('receptionist.orders.show', $invoice->order) }}">
                                {{ $invoice->order->order_number }}
                            </a>
                        </td>
                        <td>{{ $invoice->order->user->name }}</td>
                        <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                        <td>Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                        <td>Rs. {{ number_format($invoice->amount_paid, 2) }}</td>
                        <td>Rs. {{ number_format($invoice->balance_due, 2) }}</td>
                        <td>
                            @php
                                $statusClass = match($invoice->status) {
                                    'paid' => 'status-completed',
                                    'partial' => 'status-pending',
                                    'unpaid' => 'status-danger',
                                    'cancelled' => 'status-danger',
                                    default => 'status-pending',
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ ucfirst($invoice->status) }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('receptionist.invoices.show', $invoice) }}" 
                                   class="btn btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($invoice->balance_due > 0)
                                <a href="{{ route('receptionist.invoices.payment', $invoice) }}" 
                                   class="btn btn-outline-success" title="Record Payment">
                                    <i class="fas fa-money-bill"></i>
                                </a>
                                @endif
                                <!-- Delete Button -->
                                <button type="button"
                                   class="btn btn-outline-danger delete-btn" 
                                   title="Delete"
                                   data-bs-toggle="modal" 
                                   data-bs-target="#deleteModal{{ $invoice->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3"></i><br>
                            No invoices found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Delete Confirmation Modals -->
    @foreach($invoices as $invoice)
    <div class="modal fade" id="deleteModal{{ $invoice->id }}" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger bg-opacity-10">
                    <h6 class="modal-title text-danger">
                        <i class="fas fa-trash me-2"></i>Delete Invoice
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3"><strong>Are you sure you want to delete this invoice?</strong></p>
                    <p class="text-muted mb-2">
                        Invoice: <strong>{{ $invoice->invoice_number }}</strong><br>
                        Customer: <strong>{{ $invoice->order->user->name }}</strong><br>
                        Amount: <strong>Rs. {{ number_format($invoice->total_amount, 2) }}</strong>
                    </p>
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <small>This action cannot be undone. All related payments will also be deleted.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('receptionist.invoices.destroy', $invoice) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="row mt-4">
    <div class="col-12">
        {{ $invoices->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const printLinks = document.querySelectorAll('.print-from-show[data-action="print"]');
    
    printLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            // Simulate clicking the link and then triggering print when the page loads
            const invoiceId = this.getAttribute('data-invoice-id');
            const invoiceUrl = this.getAttribute('href');
            
            // Open the invoice page and trigger print after a short delay
            window.open(invoiceUrl + '?print=1', '_blank');
        });
    });
});
</script>

@endif
@endsection
