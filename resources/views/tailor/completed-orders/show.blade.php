@extends('tailor.layouts.app')

@section('title', 'Completed Order Details - Tailor Panel')

@section('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 40px;
        border-radius: 14px;
        margin-bottom: 35px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .back-btn {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 2px solid white;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .back-btn:hover {
        background: white;
        color: #1a1a2e;
    }

    .content-wrapper {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
        margin-bottom: 30px;
    }

    .detail-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
    }

    .detail-card-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .detail-card-header i {
        color: #d4af37;
        font-size: 1.4rem;
    }

    .detail-card-body {
        padding: 25px;
    }

    .info-group {
        margin-bottom: 20px;
    }

    .info-group:last-child {
        margin-bottom: 0;
    }

    .info-label {
        color: #666;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .info-value {
        color: #1a1a2e;
        font-size: 1.05rem;
        margin: 0;
    }

    .info-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #d4af37, transparent);
        margin: 20px 0;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        background: #d4edda;
        color: #155724;
        border-radius: 6px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
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

    .notes-section {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #d4af37;
        white-space: pre-wrap;
        word-wrap: break-word;
        line-height: 1.6;
        color: #666;
    }

    .sidebar-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
        margin-bottom: 20px;
    }

    .sidebar-card-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 1.05rem;
    }

    .sidebar-card-header i {
        color: #d4af37;
        font-size: 1.2rem;
    }

    .sidebar-card-body {
        padding: 20px;
    }

    .timeline-item {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        font-size: 1.4rem;
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .timeline-icon.assigned {
        color: #27ae60;
    }

    .timeline-icon.started {
        color: #3498db;
    }

    .timeline-icon.completed {
        color: #d4af37;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        margin: 0;
        font-weight: 600;
        color: #1a1a2e;
        font-size: 0.95rem;
    }

    .timeline-date {
        margin: 5px 0 0 0;
        font-size: 0.85rem;
        color: #666;
    }

    .design-image-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .design-image {
        width: 100%;
        border-radius: 8px;
        max-height: 300px;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn-quick-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        font-size: 0.9rem;
        width: 100%;
    }

    .btn-quick-action-primary {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
    }

    .btn-quick-action-primary:hover {
        background: linear-gradient(135deg, #2980b9, #1a5276);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        color: white;
    }

    .btn-quick-action-secondary {
        background: white;
        color: #666;
        border: 2px solid #ddd;
    }

    .btn-quick-action-secondary:hover {
        border-color: #d4af37;
        color: #d4af37;
        background: #f8f9fa;
    }

    .download-btn {
        background: linear-gradient(135deg, #27ae60, #229954);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
        text-decoration: none;
    }

    .download-btn:hover {
        background: linear-gradient(135deg, #229954, #1e8449);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        color: white;
    }

    @media (max-width: 1024px) {
        .content-wrapper {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-hero {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .page-hero h1 {
            font-size: 1.8rem;
        }

        .detail-card-body {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .page-hero {
            padding: 20px;
            gap: 15px;
        }

        .page-hero h1 {
            font-size: 1.5rem;
        }

        .info-value {
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Hero with Back Button -->
<div class="page-hero">
    <div>
        <h1>Completed Order #{{ $order->order->order_number }}</h1>
        <p>Order completed successfully</p>
    </div>
    <a href="{{ route('tailor.completed-orders.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

<!-- Main Content -->
<div class="content-wrapper">
    <!-- Order Details (Left) -->
    <div>
        <!-- Order Information -->
        <div class="detail-card">
            <div class="detail-card-header">
                <i class="fas fa-info-circle"></i>
                Order Information
            </div>
            <div class="detail-card-body">
                <div class="info-group">
                    <div class="info-label">Order Number</div>
                    <p class="info-value">#{{ $order->order->order_number }}</p>
                </div>

                <div class="info-divider"></div>

                <div class="info-group">
                    <div class="info-label">Garment Type</div>
                    <p class="info-value"><span class="garment-badge">{{ ucfirst($order->garment_type) }}</span></p>
                </div>

                <div class="info-group">
                    <div class="info-label">Service Option</div>
                    <p class="info-value">{{ ucfirst($order->service_option) }}</p>
                </div>

                <div class="info-divider"></div>

                <div class="info-group">
                    <div class="info-label">Status</div>
                    <p class="info-value"><span class="status-badge">Completed</span></p>
                </div>

                <div class="info-group">
                    <div class="info-label">Completion Date</div>
                    <p class="info-value">
                        @if($order->completion_date)
                            {{ $order->completion_date->format('M d, Y \a\t g:i A') }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>

                @if($order->tailor_notes)
                    <div class="info-divider"></div>
                    <div class="info-group">
                        <div class="info-label">Tailor Notes</div>
                        <div class="notes-section">{{ $order->tailor_notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer Information -->
        <div class="detail-card" style="margin-top: 25px;">
            <div class="detail-card-header">
                <i class="fas fa-user"></i>
                Customer Information
            </div>
            <div class="detail-card-body">
                <div class="info-group">
                    <div class="info-label">Name</div>
                    <p class="info-value">{{ $order->order->customer->name ?? 'N/A' }}</p>
                </div>

                <div class="info-group">
                    <div class="info-label">Email</div>
                    <p class="info-value">{{ $order->order->customer->email ?? 'N/A' }}</p>
                </div>

                <div class="info-group">
                    <div class="info-label">Phone</div>
                    <p class="info-value">{{ $order->order->customer->phone ?? 'N/A' }}</p>
                </div>

                @if($order->measurement)
                    <div class="info-divider"></div>
                    <div class="info-group">
                        <div class="info-label">Measurement Type</div>
                        <p class="info-value">{{ ucfirst($order->measurement->measurement_type ?? 'N/A') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar (Right) -->
    <div>
        <!-- Design Image -->
        @if($order->design_image)
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fas fa-image"></i>
                    Design Image
                </div>
                <div class="sidebar-card-body">
                    <div class="design-image-container">
                        <img src="{{ asset('storage/' . $order->design_image) }}" alt="Design" class="design-image">
                    </div>
                    <a href="{{ asset('storage/' . $order->design_image) }}" download class="download-btn">
                        <i class="fas fa-download"></i> Download Design
                    </a>
                </div>
            </div>
        @endif

        <!-- Timeline -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <i class="fas fa-history"></i>
                Timeline
            </div>
            <div class="sidebar-card-body">
                @if($order->assigned_date)
                    <div class="timeline-item">
                        <div class="timeline-icon assigned">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="timeline-title">Order Assigned</p>
                            <p class="timeline-date">{{ $order->assigned_date->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                @endif

                @if($order->start_date)
                    <div class="timeline-item">
                        <div class="timeline-icon started">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="timeline-title">Stitching Started</p>
                            <p class="timeline-date">{{ $order->start_date->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                @endif

                @if($order->completion_date)
                    <div class="timeline-item">
                        <div class="timeline-icon completed">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="timeline-title">Order Completed</p>
                            <p class="timeline-date">{{ $order->completion_date->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                @endif

                @if(!$order->assigned_date && !$order->start_date && !$order->completion_date)
                    <p style="color: #999; text-align: center; padding: 20px 0; margin: 0;">
                        <i class="fas fa-calendar-times" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        No timeline available
                    </p>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <i class="fas fa-lightning-bolt"></i>
                Quick Actions
            </div>
            <div class="sidebar-card-body">
                <div class="quick-actions">
                    @if($order->measurement_id)
                        <a href="{{ route('tailor.measurements.show', $order->measurement_id) }}" class="btn-quick-action btn-quick-action-primary">
                            <i class="fas fa-ruler"></i> View Measurements
                        </a>
                    @endif

                    @if($order->design_image)
                        <a href="{{ route('tailor.designs.show', $order->id) }}" class="btn-quick-action btn-quick-action-secondary">
                            <i class="fas fa-images"></i> View Design
                        </a>
                    @else
                        <a href="{{ route('tailor.designs.gallery') }}" class="btn-quick-action btn-quick-action-secondary">
                            <i class="fas fa-gallery"></i> Design Gallery
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
