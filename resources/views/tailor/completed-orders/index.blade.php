@extends('tailor.layouts.app')

@section('title', 'Completed Orders - Tailor Panel')

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
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 35px;
    }

    .stat-card {
        background: white;
        border-radius: 14px;
        padding: 25px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        display: inline-block;
    }

    .stat-icon.completed {
        color: #27ae60;
    }

    .stat-icon.month {
        color: #3498db;
    }

    .stat-icon.average {
        color: #d4af37;
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1a1a2e;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 0.95rem;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
    }

    .btn-reset:hover {
        border-color: #d4af37;
        color: #d4af37;
        background: #f8f9fa;
    }

    .orders-table-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table thead {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
    }

    .orders-table th {
        padding: 18px;
        font-weight: 700;
        text-align: left;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .orders-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .orders-table tbody tr:hover {
        background: #f8f9fa;
    }

    .orders-table td {
        padding: 16px 18px;
        color: #1a1a2e;
        font-size: 0.95rem;
    }

    .order-id {
        font-weight: 700;
        color: #d4af37;
    }

    .customer-name {
        font-weight: 600;
        color: #1a1a2e;
    }

    .garment-badge {
        display: inline-block;
        padding: 6px 12px;
        background: linear-gradient(135deg, #e7f3ff, #cfe2ff);
        color: #084298;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .completion-date {
        color: #666;
        font-size: 0.9rem;
    }

    .delivery-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #d4edda;
        color: #155724;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .btn-view {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
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
        padding: 80px 30px;
        background: white;
        border-radius: 14px;
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

    .pagination-wrapper {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 20px 18px;
        display: flex;
        justify-content: center;
    }

    .notes-btn {
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .notes-btn:hover {
        background: linear-gradient(135deg, #8e44ad, #7d3c98);
        transform: translateY(-2px);
    }

    /* Modal Styles */
    .notes-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 3000;
        justify-content: center;
        align-items: center;
    }

    .notes-modal.active {
        display: flex;
    }

    .notes-modal-content {
        background: white;
        border-radius: 14px;
        padding: 30px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .notes-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .notes-modal-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }

    .notes-modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #999;
        transition: all 0.3s ease;
    }

    .notes-modal-close:hover {
        color: #1a1a2e;
    }

    .notes-content {
        color: #666;
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    @media (max-width: 768px) {
        .page-hero {
            padding: 25px;
        }

        .page-hero h1 {
            font-size: 1.8rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .orders-table th, .orders-table td {
            padding: 12px;
            font-size: 0.85rem;
        }

        .filter-card {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .page-hero {
            padding: 20px;
        }

        .page-hero h1 {
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 1.8rem;
        }

        .orders-table {
            font-size: 0.8rem;
        }

        .orders-table th, .orders-table td {
            padding: 10px;
        }

        .btn-view {
            padding: 6px 10px;
            font-size: 0.75rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Hero -->
<div class="page-hero">
    <h1>Completed Orders</h1>
    <p>View your completed stitching orders and performance statistics</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <!-- All Time Completed -->
    <div class="stat-card">
        <div class="stat-icon completed">
            <i class="fas fa-trophy"></i>
        </div>
        <div class="stat-number">{{ $allTimeCompleted }}</div>
        <div class="stat-label">Total Completed</div>
        <p style="margin: 8px 0 0 0; color: #666; font-size: 0.85rem;">All time completed orders</p>
    </div>

    <!-- This Month Completed -->
    <div class="stat-card">
        <div class="stat-icon month">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-number">{{ $thisMonthCompleted }}</div>
        <div class="stat-label">This Month</div>
        <p style="margin: 8px 0 0 0; color: #666; font-size: 0.85rem;">{{ now()->format('F Y') }}</p>
    </div>

    <!-- Average Completion Time -->
    <div class="stat-card">
        <div class="stat-icon average">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-number">{{ $averageCompletionTime }}</div>
        <div class="stat-label">Avg Days</div>
        <p style="margin: 8px 0 0 0; color: #666; font-size: 0.85rem;">Average completion time</p>
    </div>
</div>

<!-- Filter Card -->
<div class="filter-card">
    <div class="filter-title">
        <i class="fas fa-filter"></i>
        Search & Filter
    </div>
    <form method="GET" action="{{ route('tailor.completed-orders.index') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" style="color: #666; font-size: 0.9rem; font-weight: 600;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Order ID or Customer Name" value="{{ $searchQuery }}">
            </div>

            <div class="col-md-3">
                <label class="form-label" style="color: #666; font-size: 0.9rem; font-weight: 600;">From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>

            <div class="col-md-3">
                <label class="form-label" style="color: #666; font-size: 0.9rem; font-weight: 600;">To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn-filter" style="width: 100%; justify-content: center;">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('tailor.completed-orders.index') }}" class="btn-reset" style="width: 100%; justify-content: center;">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Completed Orders Table -->
<div class="orders-table-card">
    @if($orders->count() > 0)
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Product</th>
                    <th>Completion Date</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="order-id">#{{ $order->order->order_number }}</td>
                        <td class="customer-name">{{ $order->order->customer->name ?? 'N/A' }}</td>
                        <td>
                            <span class="garment-badge">{{ ucfirst($order->garment_type) }}</span>
                        </td>
                        <td class="completion-date">
                            @if($order->completion_date)
                                {{ $order->completion_date->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <span class="delivery-badge">
                                <i class="fas fa-check-circle"></i> Completed
                            </span>
                        </td>
                        <td>
                            @if($order->tailor_notes)
                                <button type="button" class="notes-btn" onclick="openNotesModal('{{ $order->order->order_number }}', '{{ addslashes($order->tailor_notes) }}')">
                                    <i class="fas fa-sticky-note"></i> View
                                </button>
                            @else
                                <span style="color: #999; font-size: 0.85rem;">No notes</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('tailor.completed-orders.show', $order->id) }}" class="btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="pagination-wrapper">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <p class="empty-state-text">No completed orders found</p>
            <p style="color: #ccc; margin-top: 10px; font-size: 0.95rem;">No orders match your search or filter criteria</p>
        </div>
    @endif
</div>

<!-- Notes Modal -->
<div class="notes-modal" id="notesModal">
    <div class="notes-modal-content">
        <div class="notes-modal-header">
            <h5 class="notes-modal-title">
                <i class="fas fa-sticky-note" style="color: #9b59b6; margin-right: 10px;"></i>
                Tailor Notes - <span id="modalOrderId"></span>
            </h5>
            <button type="button" class="notes-modal-close" onclick="closeNotesModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="notes-content" id="notesContent"></div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openNotesModal(orderId, notes) {
        document.getElementById('modalOrderId').textContent = orderId;
        document.getElementById('notesContent').textContent = notes;
        document.getElementById('notesModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeNotesModal() {
        document.getElementById('notesModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Close on backdrop click
    document.getElementById('notesModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeNotesModal();
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeNotesModal();
        }
    });
</script>
@endsection
