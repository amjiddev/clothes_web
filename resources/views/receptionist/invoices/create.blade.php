@extends('receptionist.layouts.app')

@section('title', 'Create Invoice')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item"><a href="{{ route('receptionist.orders.show', $order) }}">{{ $order->order_number }}</a></li>
    <li class="breadcrumb-item active">Create Invoice</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice me-2"></i>Create Invoice for Order {{ $order->order_number }}
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('receptionist.invoices.store', $order) }}">
                        @csrf

                        <!-- Order Summary -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Order Information</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Order Number:</th>
                                        <td>{{ $order->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer:</th>
                                        <td>{{ $order->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order Date:</th>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Delivery Date:</th>
                                        <td>{{ $order->delivery_date ? $order->delivery_date->format('M d, Y') : 'Not set' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Financial Summary</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Subtotal:</th>
                                        <td class="text-end">Rs. {{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tax:</th>
                                        <td class="text-end">Rs. {{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Discount:</th>
                                        <td class="text-end">Rs. {{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                    <tr class="table-active fw-bold">
                                        <th>Total:</th>
                                        <td class="text-end">Rs. {{ number_format($order->total, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Invoice Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Invoice Date</label>
                                    <input type="date" name="invoice_date" class="form-control" 
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Due Date (Optional)</label>
                                    <input type="date" name="due_date" class="form-control" 
                                           value="{{ $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Notes (Optional)</label>
                                    <textarea name="notes" class="form-control" rows="3" 
                                              placeholder="Add any additional notes for this invoice...">{{ $order->notes }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items Preview -->
                        @if($order->orderItems->count() > 0)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5>Order Items</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end">Price</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->orderItems as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td>{{ $item->product->category?->name ?? 'N/A' }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">Rs. {{ number_format($item->price, 2) }}</td>
                                                <td class="text-end">Rs. {{ number_format($item->total, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('receptionist.orders.show', $order) }}" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check me-2"></i>Create Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
