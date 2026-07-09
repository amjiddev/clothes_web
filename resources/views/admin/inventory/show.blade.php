@extends('admin.layouts.app')

@section('title', 'Inventory Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-box me-2"></i>{{ $inventory->product->name }}
                </h1>
                <p class="text-muted">Inventory details and stock management</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.inventory.edit', $inventory) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Stock Overview Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Stock Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Product</strong>
                            <p class="mb-0">
                                <a href="{{ route('admin.products.show', $inventory->product_id) }}">
                                    {{ $inventory->product->name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">SKU</strong>
                            <code class="bg-light px-2 py-1">{{ $inventory->sku ?? 'N/A' }}</code>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Size</strong>
                            <p class="mb-0">{{ $inventory->size ?? 'All Sizes' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Color</strong>
                            <p class="mb-0">{{ $inventory->color ?? 'All Colors' }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Stock Metrics -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <strong class="text-muted d-block mb-2">
                                    <i class="fas fa-cube me-2 text-primary"></i>Current Stock
                                </strong>
                                <h3 class="mb-0">{{ $inventory->quantity }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <strong class="text-muted d-block mb-2">
                                    <i class="fas fa-lock me-2 text-warning"></i>Reserved (Sold)
                                </strong>
                                <h3 class="mb-0">{{ $inventory->sold_quantity }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <strong class="text-muted d-block mb-2">
                                    <i class="fas fa-check me-2 text-success"></i>Available
                                </strong>
                                <h3 class="mb-0">{{ $inventory->available_quantity }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6 mb-0">
                            <div class="p-3 bg-light rounded">
                                <strong class="text-muted d-block mb-2">
                                    <i class="fas fa-bell me-2 text-info"></i>Reorder Level
                                </strong>
                                <h3 class="mb-0">{{ $inventory->reorder_level }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Status Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-signal me-2"></i>Stock Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Status</strong>
                            @if($inventory->stock_status === 'out_of_stock')
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="fas fa-times-circle me-1"></i>Out of Stock
                                </span>
                            @elseif($inventory->stock_status === 'low_stock')
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>Low Stock
                                </span>
                            @elseif($inventory->stock_status === 'high_stock')
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>High Stock
                                </span>
                            @else
                                <span class="badge bg-info px-3 py-2">
                                    <i class="fas fa-circle me-1"></i>{{ $inventory->status_label }}
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-2">Stock Percentage</strong>
                            <div class="progress" style="height: 25px;">
                                @php
                                    $percentage = $inventory->stock_percentage;
                                    $bgColor = $percentage >= 75 ? 'bg-success' : ($percentage >= 50 ? 'bg-info' : ($percentage >= 25 ? 'bg-warning' : 'bg-danger'));
                                @endphp
                                <div class="progress-bar {{ $bgColor }}" style="width: {{ $percentage }}%">
                                    {{ round($percentage) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Actions -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Stock Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Add Stock Form -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Add Stock</h6>
                            <form action="{{ route('admin.inventory.add-stock', $inventory) }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label small">Quantity</label>
                                    <input type="number" name="quantity" class="form-control form-control-sm" 
                                           min="1" required placeholder="Enter quantity">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Cost per Unit</label>
                                    <input type="number" name="cost_per_unit" class="form-control form-control-sm" 
                                           step="0.01" placeholder="Optional">
                                </div>
                                <button type="submit" class="btn btn-sm btn-success w-100">
                                    <i class="fas fa-plus me-1"></i>Add Stock
                                </button>
                            </form>
                        </div>

                        <!-- Remove Stock Form -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Remove Stock</h6>
                            <form action="{{ route('admin.inventory.remove-stock', $inventory) }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label small">Quantity</label>
                                    <input type="number" name="quantity" class="form-control form-control-sm" 
                                           min="1" max="{{ $inventory->quantity }}" required 
                                           placeholder="Enter quantity">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Reason</label>
                                    <input type="text" name="reason" class="form-control form-control-sm" 
                                           placeholder="Optional">
                                </div>
                                <button type="submit" class="btn btn-sm btn-danger w-100">
                                    <i class="fas fa-minus me-1"></i>Remove Stock
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Inventory Info -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>Inventory Information
                    </h6>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">Inventory ID</strong>
                        <code class="bg-light px-2 py-1">{{ $inventory->id }}</code>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">Product ID</strong>
                        <code class="bg-light px-2 py-1">{{ $inventory->product_id }}</code>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">Cost per Unit</strong>
                        <p class="mb-0">${{ number_format($inventory->cost_per_unit ?? 0, 2) }}</p>
                    </div>
                    <div class="mb-0">
                        <strong class="text-muted d-block mb-1">Inventory Value</strong>
                        <h5 class="mb-0 text-success">
                            ${{ number_format($inventory->inventory_value, 2) }}
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="card border-0 shadow bg-light">
                <div class="card-body small">
                    <p class="mb-2">
                        <strong class="text-muted">Created:</strong><br>
                        {{ $inventory->created_at?->format('M d, Y H:i') ?? 'N/A' }}
                    </p>
                    <p class="mb-0">
                        <strong class="text-muted">Last Restock:</strong><br>
                        {{ $inventory->last_restock_date?->format('M d, Y H:i') ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    padding: 20px 0;
    border-bottom: 1px solid #e9ecef;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}
</style>
@endsection
