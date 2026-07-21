@extends('tailor.layouts.app')

@section('title', 'Assigned Stitching Orders - Tailor Panel')

@section('styles')
<style>
    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-box {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
        border-left: 4px solid #d4af37;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .stat-box.pending {
        border-left-color: #f39c12;
    }

    .stat-box.stitching {
        border-left-color: #3498db;
    }

    .stat-box.progress {
        border-left-color: #9b59b6;
    }

    .stat-box.completed {
        border-left-color: #27ae60;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 8px 0;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .filter-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        outline: none;
    }

    .btn-group-filter {
        display: flex;
        gap: 10px;
    }

    .btn-group-filter button {
        padding: 10px 15px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .orders-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
    }

    .table-responsive-custom {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .table thead th {
        padding: 15px 12px;
        text-align: left;
        font-weight: 700;
        color: #2c3e50;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background 0.2s ease;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .table tbody td {
        padding: 14px 12px;
        color: #2c3e50;
        vertical-align: middle;
    }

    .order-id-cell {
        font-weight: 700;
        color: #d4af37;
        font-size: 0.95rem;
    }

    .customer-name-cell {
        font-weight: 600;
        color: #1a1a2e;
    }

    .phone-cell {
        color: #666;
        font-size: 0.9rem;
    }

    .product-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #f0f0f0;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #333;
        border: 1px solid #ddd;
    }

    .fabric-cell {
        color: #666;
        font-size: 0.9rem;
    }

    .color-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .color-swatch {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 1px solid #ddd;
    }

    .quantity-cell {
        font-weight: 600;
        text-align: center;
    }

    .measurement-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .measurement-complete {
        background: #d4edda;
        color: #155724;
    }

    .measurement-pending {
        background: #fff3cd;
        color: #856404;
    }

    .delivery-date-cell {
        font-weight: 500;
        color: #666;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-stitching-started {
        background: #e7f3ff;
        color: #0066cc;
    }

    .status-in-progress {
        background: #e8d5ff;
        color: #5a0066;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .action-view {
        background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(52, 152, 219, 0.05));
        color: #3498db;
        border: 1px solid rgba(52, 152, 219, 0.3);
    }

    .action-view:hover {
        background: #3498db;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.2;
        color: #d4af37;
    }

    .empty-state-text {
        font-size: 1.1rem;
        font-weight: 600;
        color: #555;
    }

    .pagination {
        margin: 0;
        padding: 15px 20px;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }

    .page-info {
        font-size: 0.85rem;
        color: #666;
        text-align: center;
        margin-bottom: 10px;
    }

    @media (max-width: 1200px) {
        .filter-row {
            flex-direction: column;
        }

        .filter-group {
            min-width: 100%;
        }

        .table {
            font-size: 0.85rem;
        }

        .table thead th,
        .table tbody td {
            padding: 12px 8px;
        }
    }

    @media (max-width: 768px) {
        .stats-section {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-box {
            padding: 12px;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .stat-label {
            font-size: 0.7rem;
        }

        .action-btn {
            padding: 6px 10px;
            font-size: 0.8rem;
        }

        .table {
            font-size: 0.8rem;
        }

        .table thead th,
        .table tbody td {
            padding: 10px 6px;
        }

        .order-id-cell {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 480px) {
        .filter-row {
            flex-direction: column;
        }

        .stats-section {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .stat-box {
            padding: 10px;
        }

        .stat-number {
            font-size: 1.2rem;
        }

        .stat-label {
            font-size: 0.65rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Assigned Stitching Orders</h1>
        <p style="color: #666; margin: 5px 0 0 0;">Manage all your assigned stitching orders</p>
    </div>
</div>

<!-- Statistics Section -->
<div class="stats-section">
    <div class="stat-box">
        <div class="stat-label">Total Orders</div>
        <div class="stat-number">{{ $stats['total'] }}</div>
    </div>

    <div class="stat-box pending">
        <div class="stat-label">Pending</div>
        <div class="stat-number">{{ $stats['pending'] }}</div>
    </div>

    <div class="stat-box stitching">
        <div class="stat-label">Stitching Started</div>
        <div class="stat-number">{{ $stats['stitching_started'] }}</div>
    </div>

    <div class="stat-box progress">
        <div class="stat-label">In Progress</div>
        <div class="stat-number">{{ $stats['in_progress'] }}</div>
    </div>

    <div class="stat-box completed">
        <div class="stat-label">Completed</div>
        <div class="stat-number">{{ $stats['completed'] }}</div>
    </div>
</div>

<!-- Filters Section -->
<div class="filter-card">
    <form method="GET" action="{{ route('tailor.stitching-orders.index') }}" class="filter-row">
        <div class="filter-group" style="flex: 2; min-width: 250px;">
            <label>Search</label>
            <div style="display: flex; gap: 10px;">
                <input type="text" name="search" placeholder="Order ID, Customer Name, or Product..." 
                       value="{{ $searchQuery }}" style="flex: 1;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; white-space: nowrap;">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="{{ route('tailor.stitching-orders.index') }}" class="btn btn-outline-primary" 
                   style="padding: 10px 15px; white-space: nowrap;">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </div>

        <div class="filter-group">
            <label>Status Filter</label>
            <select name="status_filter" onchange="this.form.submit()">
                <option value="">All Status</option>
                @foreach($statusFilters as $key => $label)
                    <option value="{{ $key }}" {{ $currentStatusFilter === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label>Sort By</label>
            <select name="sort_by" onchange="this.form.submit()">
                <option value="assigned_date">Assigned Date</option>
                <option value="due_date">Due Date</option>
                <option value="customer_name">Customer Name</option>
            </select>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="orders-table-card">
    <div class="table-header">
        <i class="fas fa-list-ul"></i>
        <span>Assigned Orders List</span>
    </div>

    <div class="table-responsive-custom">
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Product</th>
                    <th>Fabric Type</th>
                    <th>Color</th>
                    <th>Qty</th>
                    <th>Measurement</th>
                    <th>Delivery Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $customer = $order->order->customer;
                        $orderItems = $order->order->orderItems ?? collect();
                        $firstItem = $orderItems->first();
                        $measurement = $order->measurement;
                    @endphp
                    <tr>
                        <td class="order-id-cell">#{{ $order->order->order_number }}</td>
                        <td class="customer-name-cell">{{ $customer->name ?? 'N/A' }}</td>
                        <td class="phone-cell">{{ $customer->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="product-badge">
                                {{ $firstItem->product_name ?? ucfirst($order->garment_type) }}
                            </span>
                        </td>
                        <td class="fabric-cell">
                            {{ $firstItem->fabric ?? 'Not specified' }}
                        </td>
                        <td class="color-cell">
                            @if($firstItem && $firstItem->color)
                                <div class="color-swatch" style="background-color: {{ $firstItem->color }};"></div>
                                {{ $firstItem->color }}
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td class="quantity-cell">
                            {{ $firstItem->quantity ?? 1 }}
                        </td>
                        <td>
                            @if($measurement && $measurement->is_completed)
                                <span class="measurement-status measurement-complete">
                                    <i class="fas fa-check"></i> Complete
                                </span>
                            @else
                                <span class="measurement-status measurement-pending">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="delivery-date-cell">
                            @if($order->order->delivery_date)
                                {{ $order->order->delivery_date->format('M d, Y') }}
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = 'status-pending';
                                if (in_array($order->stitching_status, ['assigned', 'in_progress'])) {
                                    $statusClass = 'status-stitching-started';
                                } elseif (in_array($order->stitching_status, ['ready_for_fitting', 'in_fitting'])) {
                                    $statusClass = 'status-in-progress';
                                } elseif ($order->stitching_status === 'completed') {
                                    $statusClass = 'status-completed';
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                @if($order->stitching_status === 'pending')
                                    Pending
                                @elseif(in_array($order->stitching_status, ['assigned', 'in_progress']))
                                    Stitching Started
                                @elseif(in_array($order->stitching_status, ['ready_for_fitting', 'in_fitting']))
                                    In Progress
                                @elseif($order->stitching_status === 'completed')
                                    Completed
                                @else
                                    {{ str_replace('_', ' ', ucfirst($order->stitching_status)) }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('tailor.stitching-orders.show', $order->id) }}" class="action-btn action-view">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <p class="empty-state-text">No stitching orders found</p>
                                <small style="color: #999;">
                                    @if($searchQuery)
                                        Try adjusting your search criteria
                                    @else
                                        You don't have any assigned orders yet
                                    @endif
                                </small>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="pagination">
            @if($orders->count() > 0)
                <div class="page-info">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                </div>
            @endif
            <div style="display: flex; justify-content: center;">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>

@endsection
