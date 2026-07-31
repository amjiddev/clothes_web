@extends('tailor.layouts.app')

@section('title', 'Measurement Management - Tailor Panel')

@section('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 40px;
        border-radius: 14px;
        margin-bottom: 35px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .page-hero h1 {
        margin: 0;
        font-size: 2.2rem;
        font-weight: 800;
    }

    .page-hero p {
        color: rgba(255, 255, 255, 0.85);
        margin: 10px 0 0 0;
        font-size: 1rem;
    }

    .filter-card {
        background: white;
        border-radius: 14px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
    }

    .filter-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-title i {
        color: #d4af37;
        font-size: 1.3rem;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        color: #666;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .filter-buttons {
        display: flex;
        gap: 12px;
    }

    .btn-filter {
        background: linear-gradient(135deg, #1a1a2e, #0f0f1e);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
        flex: 1;
    }

    .btn-filter:hover {
        background: linear-gradient(135deg, #d4af37, #c9a227);
        color: #1a1a2e;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.3);
    }

    .btn-reset {
        background: white;
        color: #666;
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
        flex: 1;
    }

    .btn-reset:hover {
        border-color: #1a1a2e;
        color: #1a1a2e;
        background: #f8f9fa;
    }

    .measurements-table {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .table thead th {
        color: #666;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        padding: 18px 15px;
        border: none;
    }

    .table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .table tbody td {
        padding: 18px 15px;
        vertical-align: middle;
        color: #555;
    }

    .customer-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .customer-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #1a1a2e, #d4af37);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
    }

    .customer-name {
        font-weight: 700;
        color: #1a1a2e;
    }

    .customer-phone {
        font-size: 0.9rem;
        color: #666;
    }

    .measurement-date {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #666;
        font-size: 0.95rem;
    }

    .measurement-date i {
        color: #d4af37;
    }

    .order-count-badge {
        background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(52, 152, 219, 0.05));
        color: #0066cc;
        border-left: 3px solid #3498db;
        padding: 8px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
    }

    .btn-view {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, #2980b9, #1a5276);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 30px;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 15px;
    }

    .empty-state-text {
        color: #999;
        font-size: 1.1rem;
        margin: 0;
    }

    .pagination-footer {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 20px;
        border-radius: 0 0 14px 14px;
    }

    @media (max-width: 1024px) {
        .filter-buttons {
            flex-direction: column;
        }

        .btn-filter, .btn-reset {
            flex: auto;
        }
    }

    @media (max-width: 768px) {
        .page-hero {
            padding: 25px;
        }

        .page-hero h1 {
            font-size: 1.8rem;
        }

        .filter-card {
            padding: 18px;
        }

        .table tbody td {
            padding: 12px 10px;
            font-size: 0.9rem;
        }

        .customer-info {
            flex-direction: column;
            align-items: flex-start;
        }

        .customer-avatar {
            width: 35px;
            height: 35px;
            font-size: 0.85rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Hero -->
<div class="page-hero">
    <h1>Measurement Management</h1>
    <p>View and manage customer measurements for your assigned stitching orders</p>
</div>

<!-- Filter Card -->
<div class="filter-card">
    <div class="filter-title">
        <i class="fas fa-filter"></i>
        Search & Filter
    </div>
    <form method="GET" action="{{ route('tailor.measurements.index') }}">
        <div class="row g-3">
            <div class="col-md-5">
                <div class="form-group">
                    <label class="form-label">Search Customer</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Customer Name or Phone" 
                           value="{{ $searchQuery }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Measurement Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        @foreach($measurementTypes as $key => $label)
                            <option value="{{ $key }}" {{ $currentType === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" style="visibility: hidden;">Actions</label>
                    <div class="filter-buttons">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('tailor.measurements.index') }}" class="btn-reset" style="text-decoration: none;">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Measurements Table -->
<div class="measurements-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 25%;">Customer Name</th>
                    <th style="width: 18%;">Order ID</th>
                    <th style="width: 20%;">Measurement Date</th>
                    <th style="width: 18%;">Product</th>
                    <th style="width: 19%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($measurements as $measurement)
                    <tr>
                        <td>
                            <div class="customer-info">
                                <div class="customer-avatar">
                                    {{ strtoupper(substr($measurement->user->name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="customer-name">{{ $measurement->user->name ?? 'N/A' }}</div>
                                    <div class="customer-phone">
                                        <i class="fas fa-phone" style="color: #d4af37; font-size: 0.85rem;"></i>
                                        {{ $measurement->user->phone ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $relatedOrders = $measurement->stitchingOrders;
                                $orderNumber = $relatedOrders->first()?->order?->order_number ?? '-';
                            @endphp
                            <strong style="color: #1a1a2e; font-size: 0.95rem;">
                                #{{ $orderNumber }}
                            </strong>
                        </td>
                        <td>
                            <div class="measurement-date">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ $measurement->created_at->format('M d, Y') }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $product = $relatedOrders->first()?->order?->orderItems->first();
                            @endphp
                            <span style="color: #555; font-size: 0.95rem;">
                                @if($product)
                                    {{ $product->product_name ?? 'Custom' }}
                                @else
                                    —
                                @endif
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('tailor.measurements.show', $measurement->id) }}" class="btn-view">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-ruler-combined"></i>
                                </div>
                                <p class="empty-state-text">No measurements found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($measurements->hasPages())
        <div class="pagination-footer">
            {{ $measurements->links() }}
        </div>
    @endif
</div>

@endsection
