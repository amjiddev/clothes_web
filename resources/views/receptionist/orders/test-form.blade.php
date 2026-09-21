@extends('receptionist.layouts.app')

@section('title', 'Test Order Form')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Test Order Creation Form</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h5>Validation Errors:</h5>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('receptionist.orders.store') }}" id="testForm">
                @csrf

                <div class="mb-3">
                    <label for="customer_id" class="form-label">Customer ID</label>
                    <input type="number" class="form-control" id="customer_id" name="customer_id" value="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Order Type</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="order_type" id="orderType1" value="ready_made" checked>
                        <label class="form-check-label" for="orderType1">
                            Ready Made
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="order_type" id="orderType2" value="stitching">
                        <label class="form-check-label" for="orderType2">
                            Stitching
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="order_type" id="orderType3" value="combined">
                        <label class="form-check-label" for="orderType3">
                            Combined
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="subtotal" class="form-label">Subtotal</label>
                    <input type="number" class="form-control" id="subtotal" name="subtotal" value="1000" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="total" class="form-label">Total</label>
                    <input type="number" class="form-control" id="total" name="total" value="1000" step="0.01" required>
                </div>

                <button type="submit" class="btn btn-primary">Submit Test Order</button>
            </form>

            <hr>
            <h4>Debug Info:</h4>
            <p><strong>Old order_type value:</strong> {{ old('order_type') ?? 'None' }}</p>
            <p><strong>Form action:</strong> {{ route('receptionist.orders.store') }}</p>
        </div>
    </div>
</div>
@endsection
