@extends('tailor.layouts.app')

@section('title', 'Dashboard - Tailor Panel')

@section('styles')
<style>
    /* Premium Dashboard Styling */
    .dashboard-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 45px 35px;
        border-radius: 14px;
        margin-bottom: 35px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .dashboard-hero::after {
        content: '';
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        flex: 1;
    }

    .hero-content h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin: 0;
        color: white;
        letter-spacing: -0.5px;
    }

    .hero-content p {
        margin: 12px 0 0 0;
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.05rem;
        line-height: 1.5;
    }

    .hero-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    /* Premium Stats Cards Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 35px;
    }

    .stat-card {
        background: white;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        border-color: rgba(212, 175, 55, 0.3);
    }

    .stat-card-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.2), rgba(212, 175, 55, 0.05));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 16px;
        position: relative;
        z-index: 2;
    }

    .stat-card-label {
        font-size: 0.8rem;
        color: #888;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 10px;
    }

    .stat-card-value {
        font-size: 2.8rem;
        font-weight: 800;
        color: #1a1a2e;
        position: relative;
        z-index: 2;
        line-height: 1;
    }

    .stat-card-subtitle {
        font-size: 0.75rem;
        color: #aaa;
        margin-top: 8px;
    }

    /* Status Card Variants */
    .stat-card.pending {
        border-left: 5px solid #f39c12;
    }

    .stat-card.pending .stat-card-icon {
        background: linear-gradient(135deg, rgba(243, 156, 18, 0.2), rgba(243, 156, 18, 0.05));
        color: #f39c12;
    }

    .stat-card.in-progress {
        border-left: 5px solid #3498db;
    }

    .stat-card.in-progress .stat-card-icon {
        background: linear-gradient(135deg, rgba(52, 152, 219, 0.2), rgba(52, 152, 219, 0.05));
        color: #3498db;
    }

    .stat-card.completed {
        border-left: 5px solid #27ae60;
    }

    .stat-card.completed .stat-card-icon {
        background: linear-gradient(135deg, rgba(39, 174, 96, 0.2), rgba(39, 174, 96, 0.05));
        color: #27ae60;
    }

    .stat-card.total {
        border-left: 5px solid #d4af37;
    }

    .stat-card.total .stat-card-icon {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.2), rgba(212, 175, 55, 0.05));
        color: #d4af37;
    }

    /* Recent Orders Section */
    .orders-section {
        margin-top: 35px;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .section-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }

    .section-header-icon {
        font-size: 1.4rem;
        color: #d4af37;
    }

    /* Orders Table Card */
    .orders-table-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(212, 175, 55, 0.1);
    }

    .orders-table-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 22px 28px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.1rem;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table thead tr {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .orders-table thead th {
        padding: 16px 20px;
        text-align: left;
        font-weight: 700;
        color: #2c3e50;
        font-size: 0.88rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .orders-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background 0.2s ease, box-shadow 0.2s ease;
    }

    .orders-table tbody tr:hover {
        background: #f8f9fa;
    }

    .orders-table tbody td {
        padding: 18px 20px;
        color: #2c3e50;
        vertical-align: middle;
    }

    .order-id {
        font-weight: 700;
        color: #d4af37;
        font-size: 0.95rem;
    }

    .customer-name {
        font-weight: 600;
        color: #1a1a2e;
    }

    .product-type {
        display: inline-block;
        padding: 7px 14px;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(212, 175, 55, 0.05));
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #7c5a3c;
        border: 1px solid rgba(212, 175, 55, 0.2);
    }

    .fabric-name {
        color: #666;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .delivery-date {
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
    }

    .table-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 8px 14px;
        border-radius: 7px;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-view {
        background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(52, 152, 219, 0.05));
        color: #3498db;
        border: 1px solid rgba(52, 152, 219, 0.3);
    }

    .btn-view:hover {
        background: #3498db;
        color: white;
        border-color: #3498db;
        transform: translateY(-2px);
    }

    .btn-start {
        background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(243, 156, 18, 0.05));
        color: #f39c12;
        border: 1px solid rgba(243, 156, 18, 0.3);
    }

    .btn-start:hover {
        background: #f39c12;
        color: white;
        border-color: #f39c12;
        transform: translateY(-2px);
    }

    .btn-update {
        background: linear-gradient(135deg, rgba(39, 174, 96, 0.1), rgba(39, 174, 96, 0.05));
        color: #27ae60;
        border: 1px solid rgba(39, 174, 96, 0.3);
    }

    .btn-update:hover {
        background: #27ae60;
        color: white;
        border-color: #27ae60;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.2;
        color: #d4af37;
    }

    .empty-state p {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: #555;
    }

    .empty-state small {
        font-size: 0.95rem;
        color: #999;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
        }

        .dashboard-hero {
            flex-direction: column;
            align-items: flex-start;
            gap: 25px;
        }

        .hero-content h1 {
            font-size: 2.2rem;
        }
    }

    @media (max-width: 768px) {
        .dashboard-hero {
            padding: 30px 20px;
        }

        .hero-content h1 {
            font-size: 1.8rem;
        }

        .hero-content p {
            font-size: 0.95rem;
        }

        .hero-actions {
            width: 100%;
        }

        .hero-actions a,
        .hero-actions button {
            flex: 1;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-card-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .stat-card-value {
            font-size: 2rem;
        }

        .orders-table {
            font-size: 0.85rem;
        }

        .orders-table thead th,
        .orders-table tbody td {
            padding: 12px 10px;
        }

        .table-actions {
            gap: 5px;
        }

        .btn-action {
            padding: 6px 10px;
            font-size: 0.7rem;
        }

        .btn-action i {
            font-size: 0.75rem;
        }

        .product-type {
            display: block;
            margin: 4px 0;
        }

        .section-header h2 {
            font-size: 1.2rem;
        }
    }

    @media (max-width: 480px) {
        .dashboard-hero {
            padding: 20px 15px;
        }

        .hero-content h1 {
            font-size: 1.5rem;
        }

        .stat-card-value {
            font-size: 1.8rem;
        }

        .orders-table-header {
            padding: 15px 12px;
            font-size: 0.95rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Dashboard Hero Section -->
<div class="dashboard-hero">
    <div class="hero-content">
        <h1>Welcome Back, {{ auth()->user()->name }}! 👋</h1>
        <p>Here's your stitching overview for today. Keep up the great work!</p>
        <div class="hero-actions">
            <a href="{{ route('tailor.stitching-orders.index') }}" class="btn btn-primary">
                <i class="fas fa-tasks"></i> View All Orders
            </a>
            <a href="{{ route('tailor.measurements.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-ruler-combined"></i> Take Measurements
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards Grid -->
<div class="stats-grid">
    <!-- Total Assigned Orders -->
    <div class="stat-card total">
        <div class="stat-card-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <div class="stat-card-label">Total Assigned</div>
        <div class="stat-card-value">{{ $totalAssignedOrders }}</div>
        <div class="stat-card-subtitle">All time orders</div>
    </div>

    <!-- Pending Stitching Orders -->
    <div class="stat-card pending">
        <div class="stat-card-icon">
            <i class="fas fa-hourglass-start"></i>
        </div>
        <div class="stat-card-label">Pending Stitching</div>
        <div class="stat-card-value">{{ $pendingOrders }}</div>
        <div class="stat-card-subtitle">Awaiting start</div>
    </div>

    <!-- Orders In Progress -->
    <div class="stat-card in-progress">
        <div class="stat-card-icon">
            <i class="fas fa-wrench"></i>
        </div>
        <div class="stat-card-label">In Progress</div>
        <div class="stat-card-value">{{ $inProgressOrders }}</div>
        <div class="stat-card-subtitle">Currently active</div>
    </div>

    <!-- Completed Orders -->
    <div class="stat-card completed">
        <div class="stat-card-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-card-label">Completed Orders</div>
        <div class="stat-card-value">{{ $completedOrders }}</div>
        <div class="stat-card-subtitle">This month: {{ $thisMonthCompleted }}</div>
    </div>
</div>

<!-- Recent Stitching Orders -->
<div class="orders-section">
    <div class="section-header">
        <i class="fas fa-history section-header-icon"></i>
        <h2>Recent Stitching Orders</h2>
    </div>

    <div class="orders-table-card">
        <div class="orders-table-header">
            <i class="fas fa-list"></i>
            <span>Latest Assigned Orders</span>
        </div>
        <div class="table-responsive">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Product</th>
                        <th>Fabric</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td><span class="order-id">#{{ $order->order_id }}</span></td>
                            <td><span class="customer-name">{{ $order->order->customer->name ?? 'N/A' }}</span></td>
                            <td><span class="product-type">{{ ucfirst($order->garment_type) }}</span></td>
                            <td><span class="fabric-name">{{ $order->order->items[0]->fabric ?? 'Not specified' }}</span></td>
                            <td><span class="delivery-date">{{ $order->delivery_date ? $order->delivery_date->format('M d, Y') : 'N/A' }}</span></td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'badge-pending',
                                        'assigned' => 'badge-assigned',
                                        'in_progress' => 'badge-in-progress',
                                        'ready_for_fitting' => 'badge-in-progress',
                                        'in_fitting' => 'badge-in-progress',
                                        'ready' => 'badge-ready',
                                        'completed' => 'badge-completed',
                                    ];
                                    $badgeClass = $statusColors[$order->stitching_status] ?? 'badge-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ str_replace('_', ' ', ucfirst($order->stitching_status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('tailor.stitching-orders.show', $order->id) }}" class="btn-action btn-view" title="View Details">
                                        <i class="fas fa-eye"></i> <span class="d-none d-lg-inline">View</span>
                                    </a>
                                    @if($order->stitching_status === 'pending' || $order->stitching_status === 'assigned')
                                        <form method="POST" action="{{ route('tailor.stitching-orders.update-status', $order->id) }}" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="btn-action btn-start" title="Start Stitching">
                                                <i class="fas fa-play"></i> <span class="d-none d-lg-inline">Start</span>
                                            </button>
                                        </form>
                                    @endif
                                    @if($order->stitching_status !== 'completed')
                                        <a href="{{ route('tailor.stitching-orders.show', $order->id) }}#update-status" class="btn-action btn-update" title="Update Status">
                                            <i class="fas fa-edit"></i> <span class="d-none d-lg-inline">Update</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <p>No stitching orders assigned yet</p>
                                    <small>Orders will appear here once they are assigned to you.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
