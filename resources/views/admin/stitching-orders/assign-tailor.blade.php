@extends('admin.layouts.app')

@section('title', 'Assign Tailor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.stitching-orders.index') }}">Stitching Orders</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stitching-orders.show', $stitchingOrder) }}">{{ $stitchingOrder->id }}</a></li>
    <li class="breadcrumb-item active">Assign Tailor</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">
        <i class="fas fa-user-tie me-2"></i>Assign Tailor
    </h1>
    <p class="text-muted">Assign a tailor to stitching order #{{ $stitchingOrder->id }}</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-needle me-2"></i>Stitching Order Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Order ID:</small>
                        <div class="fw-bold">#{{ $stitchingOrder->id }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Customer:</small>
                        <div class="fw-bold">{{ $stitchingOrder->order->user->name }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Garment Type:</small>
                        <div class="fw-bold">{{ ucfirst($stitchingOrder->garment_type) }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Status:</small>
                        <div>
                            <span class="badge bg-{{ $stitchingOrder->status_badge }}">
                                {{ $stitchingOrder->status_text }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($stitchingOrder->special_instructions)
                <div class="mb-3">
                    <small class="text-muted">Special Instructions:</small>
                    <div class="p-2 bg-light rounded">{{ $stitchingOrder->special_instructions }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-check me-2"></i>Assign Tailor
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.stitching-orders.update-assignment', $stitchingOrder) }}">
                    @csrf
                    @method('POST')

                    <div class="mb-3">
                        <label for="tailor_id" class="form-label fw-bold">
                            Select Tailor <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg" id="tailor_id" name="tailor_id" required>
                            <option value="">-- Select a Tailor --</option>
                            @foreach($tailors as $tailor)
                                <option value="{{ $tailor->id }}">
                                    {{ $tailor->name }}
                                    @if($tailor->tailor)
                                        - {{ $tailor->tailor->specialization ?? 'Tailor' }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="assigned_date" class="form-label">Assignment Date</label>
                        <input type="date" class="form-control" id="assigned_date" name="assigned_date" value="{{ date('Y-m-d') }}">
                        <small class="text-muted">Leave empty for current date</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Assign Tailor
                        </button>
                        <a href="{{ route('admin.stitching-orders.show', $stitchingOrder) }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
