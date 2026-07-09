@extends('layout.app')

@section('title', $product->name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark">{{ $product->name }}</h2>
        </div>
        <div class="col-md-4 text-end">
            @can('edit_products')
            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            @endcan
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow mb-3">
                <div class="card-body">
                    @if($product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3">
                    @else
                    <div class="bg-light p-4 rounded mb-3 text-center text-muted">
                        <i class="fas fa-image fa-3x"></i>
                        <p class="mt-2">No image</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold">Category:</td>
                            <td>
                                <a href="{{ route('categories.edit', $product->category) }}">
                                    {{ $product->category->name }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">SKU:</td>
                            <td><span class="badge bg-light text-dark">{{ $product->sku }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Price:</td>
                            <td class="fs-5 text-dark">₹{{ number_format($product->price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Stock:</td>
                            <td>
                                @if($product->stock_quantity > 0)
                                <span class="badge bg-success">{{ $product->stock_quantity }} in stock</span>
                                @else
                                <span class="badge bg-danger">Out of stock</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Color:</td>
                            <td>{{ $product->color ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Material:</td>
                            <td>{{ $product->material ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Size:</td>
                            <td>{{ $product->size ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status:</td>
                            <td>
                                @if($product->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Created:</td>
                            <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Updated:</td>
                            <td>{{ $product->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($product->description)
            <div class="card border-0 shadow mt-3">
                <div class="card-header bg-light">
                    <h5 class="fw-bold mb-0">Description</h5>
                </div>
                <div class="card-body">
                    {{ $product->description }}
                </div>
            </div>
            @endif

            @if($product->orderItems->count() > 0)
            <div class="card border-0 shadow mt-3">
                <div class="card-header bg-light">
                    <h5 class="fw-bold mb-0">Orders</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="fw-bold">Order #</th>
                                <th class="fw-bold">Customer</th>
                                <th class="fw-bold">Quantity</th>
                                <th class="fw-bold">Amount</th>
                                <th class="fw-bold">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->orderItems->take(10) as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $item->order) }}" class="text-decoration-none">
                                        {{ $item->order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $item->order->user->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->total, 2) }}</td>
                                <td>{{ $item->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
