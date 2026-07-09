@extends('admin.layouts.app')

@section('title', 'Products Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-shirt me-2"></i>Men's Clothing Products</h1>
                <p class="text-muted">Manage all men's clothing products</p>
            </div>
            <div class="col-auto">
                @can('create_products')
                <a href="{{ route('admin.products.create') }}" class="btn btn-dark">
                    <i class="fas fa-plus me-2"></i>Add Product
                </a>
                @endcan
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

    <!-- Search & Filter Section -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
                <!-- Search Input -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Product name or SKU..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Stock</label>
                    <select name="stock_status" class="form-select">
                        <option value="">All Stock</option>
                        <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="fw-bold">
                            <i class="fas fa-image me-2"></i>Image
                        </th>
                        <th class="fw-bold">Product Name</th>
                        <th class="fw-bold">Category</th>
                        <th class="fw-bold text-end">Price</th>
                        <th class="fw-bold text-center">Stock</th>
                        <th class="fw-bold text-center">Status</th>
                        <th class="fw-bold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <!-- Image -->
                        <td>
                            @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                 class="img-thumbnail rounded" width="50" height="50" style="object-fit: cover;">
                            @else
                            <div class="bg-light rounded d-inline-block p-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                            @endif
                        </td>

                        <!-- Product Name & SKU -->
                        <td>
                            <div>
                                <strong class="d-block">{{ $product->name }}</strong>
                                <small class="text-muted">SKU: {{ $product->sku }}</small>
                            </div>
                        </td>

                        <!-- Category -->
                        <td>
                            @if($product->category)
                            <span class="badge bg-light text-dark">{{ $product->category->name }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Price -->
                        <td class="text-end">
                            <div>
                                @if($product->has_discount)
                                <span class="text-danger text-decoration-line-through">₹{{ number_format($product->price, 2) }}</span><br>
                                <strong>₹{{ number_format($product->discount_price, 2) }}</strong>
                                <small class="text-success">({{ $product->discount_percentage }}% OFF)</small>
                                @else
                                <strong>₹{{ number_format($product->price, 2) }}</strong>
                                @endif
                            </div>
                        </td>

                        <!-- Stock -->
                        <td class="text-center">
                            <span class="badge bg-{{ $product->stock_status_badge }}">
                                {{ $product->stock_quantity }}
                            </span>
                            <small class="d-block text-muted">{{ $product->stock_status }}</small>
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            @if($product->is_active)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>Inactive
                            </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('edit_products')
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete_products')
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No products found.</p>
                                @can('create_products')
                                <a href="{{ route('admin.products.create') }}" class="btn btn-dark btn-sm">
                                    <i class="fas fa-plus me-2"></i>Add First Product
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </small>
                </div>
                <div class="col-auto">
                    {{ $products->links() }}
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

.btn-group .btn {
    padding: 6px 12px;
}
</style>
@endsection
