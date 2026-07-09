@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Inventory Management</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-boxes me-2"></i>Inventory Management</h1>
                <p class="text-muted">Track and manage product inventory levels</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">In Stock</h6>
                            <h3 class="mb-0">{{ $inventory->where('quantity', '>', 0)->count() }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Low Stock</h6>
                            <h3 class="mb-0">{{ $lowStockCount }}</h3>
                        </div>
                        <div class="text-warning" style="font-size: 2rem;">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Out of Stock</h6>
                            <h3 class="mb-0">{{ $outOfStockCount }}</h3>
                        </div>
                        <div class="text-danger" style="font-size: 2rem;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Inventory Value</h6>
                            <h3 class="mb-0">${{ number_format($totalValue ?? 0, 2) }}</h3>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.inventory.index') }}" method="GET" class="row g-3">
                <!-- Search -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by product name or SKU..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Stock Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="high_stock" {{ request('status') === 'high_stock' ? 'selected' : '' }}>High Stock</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card border-0 shadow">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4"><i class="fas fa-box me-2"></i>Product</th>
                        <th>Size / Color</th>
                        <th class="text-center">Current Stock</th>
                        <th class="text-center">Sold (Reserved)</th>
                        <th class="text-center">Available</th>
                        <th class="text-center">Reorder Level</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventory as $item)
                    <tr>
                        <td class="ps-4">
                            <div>
                                <strong>{{ $item->product->name }}</strong>
                                @if($item->sku)
                                <br><small class="text-muted">SKU: {{ $item->sku }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($item->size || $item->color)
                                <span class="badge bg-light text-dark">
                                    {{ $item->size ?? '-' }} / {{ $item->color ?? '-' }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <strong>{{ $item->quantity }}</strong>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark">{{ $item->sold_quantity }}</span>
                        </td>
                        <td class="text-center">
                            <strong>{{ $item->available_quantity }}</strong>
                        </td>
                        <td class="text-center">
                            {{ $item->reorder_level }}
                        </td>
                        <td>
                            @if($item->stock_status === 'out_of_stock')
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Out of Stock
                                </span>
                            @elseif($item->stock_status === 'low_stock')
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-exclamation-circle me-1"></i>Low Stock
                                </span>
                            @elseif($item->stock_status === 'high_stock')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>High Stock
                                </span>
                            @else
                                <span class="badge bg-info">
                                    <i class="fas fa-circle me-1"></i>{{ $item->status_label }}
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.inventory.show', $item) }}" 
                                   class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.inventory.edit', $item) }}" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted">No inventory items found</p>
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
            {{ $inventory->links() }}
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

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
@endsection
