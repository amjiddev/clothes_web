@extends('tailor.layouts.app')

@section('title', 'Stitching Status - Tailor Panel')

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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #d4af37;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #666;
        font-weight: 600;
    }

    .stat-card.active {
        background: linear-gradient(135deg, #d4af37, #c9a227);
        color: #1a1a2e;
    }

    .stat-card.active .stat-number,
    .stat-card.active .stat-label {
        color: #1a1a2e;
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
    }

    .btn-filter:hover {
        background: linear-gradient(135deg, #d4af37, #c9a227);
        color: #1a1a2e;
        transform: translateY(-2px);
    }

    .orders-table {
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

    .order-link {
        font-weight: 700;
        color: #1a1a2e;
        text-decoration: none;
        cursor: pointer;
    }

    .order-link:hover {
        color: #d4af37;
    }

    .customer-name {
        font-weight: 600;
        color: #1a1a2e;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }

    .badge-stitching-started {
        background: #e7f3ff;
        color: #0066cc;
    }

    .badge-cutting-completed {
        background: #d4edda;
        color: #155724;
    }

    .badge-stitching-in-progress {
        background: #e8d5ff;
        color: #5a0066;
    }

    .badge-quality-checking {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-completed {
        background: #d4edda;
        color: #155724;
    }

    .badge-delivered {
        background: #cfe2ff;
        color: #084298;
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
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .page-hero {
            padding: 25px;
        }

        .page-hero h1 {
            font-size: 1.8rem;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
        }

        .table tbody td {
            padding: 12px 10px;
            font-size: 0.9rem;
        }

        .filter-card {
            padding: 18px;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Hero -->
<div class="page-hero">
    <h1>Stitching Status Management</h1>
    <p>Track and manage the status of all your assigned stitching orders</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    @php
        $statuses = [
            'pending' => 'Pending',
            'stitching_started' => 'Stitching Started',
            'cutting_completed' => 'Cutting Completed',
            'stitching_in_progress' => 'Stitching In Progress',
            'quality_checking' => 'Quality Checking',
            'completed' => 'Completed',
            'delivered' => 'Delivered',
        ];
    @endphp

    @foreach($statuses as $statusKey => $statusLabel)
        <a href="{{ route('tailor.status.index', ['status' => $statusKey]) }}" style="text-decoration: none;">
            <div class="stat-card {{ $currentStatus === $statusKey ? 'active' : '' }}">
                <div class="stat-number">{{ $stats[$statusKey] ?? 0 }}</div>
                <div class="stat-label">{{ $statusLabel }}</div>
            </div>
        </a>
    @endforeach
</div>

<!-- Filter Card -->
<div class="filter-card">
    <div class="filter-title">
        <i class="fas fa-filter"></i>
        Search & Filter
    </div>
    <form method="GET" action="{{ route('tailor.status.index') }}">
        <div class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search by Order ID or Customer Name" 
                       value="{{ $searchQuery }}">
            </div>
            <div class="col-md-4">
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-filter" style="flex: 1;">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('tailor.status.index') }}" class="btn-filter" style="flex: 1; background: white; color: #666; border: 2px solid #ddd;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="orders-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 12%;">Order ID</th>
                    <th style="width: 20%;">Customer</th>
                    <th style="width: 18%;">Garment Type</th>
                    <th style="width: 20%;">Current Status</th>
                    <th style="width: 18%;">Last Updated</th>
                    <th style="width: 12%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <span class="order-link">#{{ $order->order->order_number }}</span>
                        </td>
                        <td>
                            <span class="customer-name">{{ $order->order->customer->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            {{ ucfirst($order->garment_type) }}
                        </td>
                        <td>
                            <span class="status-badge badge-{{ str_replace('_', '-', $order->stitching_status) }}">
                                {{ str_replace('_', ' ', ucfirst($order->stitching_status)) }}
                            </span>
                        </td>
                        <td>
                            {{ $order->updated_at->diffForHumans() }}
                        </td>
                        <td>
                            <a href="{{ route('tailor.status.show', $order->id) }}" class="btn-view">
                                <i class="fas fa-arrow-right"></i> Manage
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <p class="empty-state-text">No orders found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="pagination-footer">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@endsection
