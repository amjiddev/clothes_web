@extends('admin.layouts.app')

@section('title', 'Edit Inventory')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Inventory
                </h1>
                <p class="text-muted">Update inventory details for {{ $inventory->product->name }}</p>
            </div>
        </div>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Validation Error:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <form action="{{ route('admin.inventory.update', $inventory) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Product Info (Read-only) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-box me-2"></i>Product
                            </label>
                            <input type="text" class="form-control" value="{{ $inventory->product->name }}" readonly>
                        </div>

                        <!-- Size -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="size" class="form-label fw-bold">
                                    <i class="fas fa-ruler me-2"></i>Size
                                </label>
                                <input type="text" class="form-control" id="size" 
                                       value="{{ $inventory->size ?? 'All' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="color" class="form-label fw-bold">
                                    <i class="fas fa-palette me-2"></i>Color
                                </label>
                                <input type="text" class="form-control" id="color" 
                                       value="{{ $inventory->color ?? 'All' }}" readonly>
                            </div>
                        </div>

                        <!-- SKU -->
                        <div class="mb-4">
                            <label for="sku" class="form-label fw-bold">
                                <i class="fas fa-barcode me-2"></i>SKU
                            </label>
                            <input type="text" class="form-control" id="sku" 
                                   value="{{ $inventory->sku ?? 'Auto-generated' }}" readonly>
                        </div>

                        <hr>

                        <!-- Stock Quantity -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="quantity" class="form-label fw-bold">
                                    <i class="fas fa-cube me-2 text-primary"></i>Current Stock <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" min="0" 
                                       value="{{ old('quantity', $inventory->quantity) }}" required>
                                @error('quantity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="reserved_quantity" class="form-label fw-bold">
                                    <i class="fas fa-lock me-2 text-warning"></i>Reserved/Sold <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('reserved_quantity') is-invalid @enderror" 
                                       id="reserved_quantity" name="reserved_quantity" min="0" 
                                       value="{{ old('reserved_quantity', $inventory->reserved_quantity) }}" required>
                                @error('reserved_quantity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Reorder Level & Cost -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="reorder_level" class="form-label fw-bold">
                                    <i class="fas fa-bell me-2 text-info"></i>Reorder Level <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('reorder_level') is-invalid @enderror" 
                                       id="reorder_level" name="reorder_level" min="0" 
                                       value="{{ old('reorder_level', $inventory->reorder_level) }}" required>
                                <small class="text-muted d-block mt-1">
                                    Alert when stock reaches this level
                                </small>
                                @error('reorder_level')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="cost_per_unit" class="form-label fw-bold">
                                    <i class="fas fa-dollar-sign me-2"></i>Cost per Unit
                                </label>
                                <input type="number" class="form-control @error('cost_per_unit') is-invalid @enderror" 
                                       id="cost_per_unit" name="cost_per_unit" min="0" step="0.01"
                                       value="{{ old('cost_per_unit', $inventory->cost_per_unit) }}">
                                @error('cost_per_unit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Stock Calculation Info -->
                        <div class="alert alert-light border">
                            <strong class="d-block mb-2">
                                <i class="fas fa-info-circle me-2"></i>Stock Calculation
                            </strong>
                            <ul class="small mb-0 ps-3">
                                <li>Available Stock = Current Stock - Reserved/Sold</li>
                                <li>Low Stock Alert: When stock ≤ Reorder Level</li>
                                <li>Inventory Value = Current Stock × Cost per Unit</li>
                            </ul>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.inventory.show', $inventory) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Current Status -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Current Status
                    </h6>
                </div>
                <div class="card-body small">
                    <div class="mb-3 p-2 bg-light rounded">
                        <strong class="text-muted d-block mb-1">Current Stock</strong>
                        <h5 class="mb-0">{{ $inventory->quantity }}</h5>
                    </div>
                    <div class="mb-3 p-2 bg-light rounded">
                        <strong class="text-muted d-block mb-1">Available</strong>
                        <h5 class="mb-0">{{ $inventory->available_quantity }}</h5>
                    </div>
                    <div class="mb-0 p-2 bg-light rounded">
                        <strong class="text-muted d-block mb-1">Inventory Value</strong>
                        <h5 class="mb-0 text-success">
                            ${{ number_format($inventory->inventory_value, 2) }}
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Stock Status -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-signal me-2"></i>Status
                    </h6>
                </div>
                <div class="card-body">
                    @if($inventory->stock_status === 'out_of_stock')
                        <span class="badge bg-danger px-3 py-2 w-100">
                            <i class="fas fa-times-circle me-1"></i>Out of Stock
                        </span>
                    @elseif($inventory->stock_status === 'low_stock')
                        <span class="badge bg-warning text-dark px-3 py-2 w-100">
                            <i class="fas fa-exclamation-circle me-1"></i>Low Stock
                        </span>
                    @elseif($inventory->stock_status === 'high_stock')
                        <span class="badge bg-success px-3 py-2 w-100">
                            <i class="fas fa-check-circle me-1"></i>High Stock
                        </span>
                    @else
                        <span class="badge bg-info px-3 py-2 w-100">
                            <i class="fas fa-circle me-1"></i>{{ $inventory->status_label }}
                        </span>
                    @endif

                    <div class="mt-3">
                        <small class="text-muted">Stock Percentage</small>
                        @php
                            $percentage = $inventory->stock_percentage;
                            $bgColor = $percentage >= 75 ? 'bg-success' : ($percentage >= 50 ? 'bg-info' : ($percentage >= 25 ? 'bg-warning' : 'bg-danger'));
                        @endphp
                        <div class="progress">
                            <div class="progress-bar {{ $bgColor }}" style="width: {{ $percentage }}%">
                                {{ round($percentage) }}%
                            </div>
                        </div>
                    </div>
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
