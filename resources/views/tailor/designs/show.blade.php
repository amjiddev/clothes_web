@extends('tailor.layouts.app')

@section('title', 'Design Details - Tailor Panel')

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

    .design-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
    }

    .design-card-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .design-card-header i {
        color: #d4af37;
        font-size: 1.4rem;
    }

    .design-image-section {
        padding: 40px;
        text-align: center;
        background: #f8f9fa;
    }

    .design-image-container {
        position: relative;
        display: inline-block;
        max-width: 100%;
        margin-bottom: 25px;
    }

    .design-image-container img {
        max-width: 100%;
        max-height: 500px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        cursor: zoom-in;
        transition: all 0.3s ease;
    }

    .design-image-container img:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .design-image-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 400px;
        background: white;
        border-radius: 12px;
        border: 2px dashed #ddd;
    }

    .design-image-placeholder i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 15px;
    }

    .design-image-placeholder p {
        color: #999;
        margin: 0;
        font-size: 1.1rem;
    }

    .design-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        font-size: 0.95rem;
    }

    .btn-download {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
    }

    .btn-download:hover {
        background: linear-gradient(135deg, #2980b9, #1a5276);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        color: white;
    }

    .order-info-section {
        padding: 25px;
    }

    .info-group {
        margin-bottom: 20px;
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
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
        background: #e8d5ff;
        color: #5a0066;
    }

    .badge-stitching-in-progress {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-quality-checking {
        background: #fff3cd;
        color: #856404;
    }

    .badge-completed {
        background: #d4edda;
        color: #155724;
    }

    .badge-delivered {
        background: #cfe2ff;
        color: #084298;
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

    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 3000;
        justify-content: center;
        align-items: center;
    }

    .image-modal.active {
        display: flex;
    }

    .image-modal-content {
        position: relative;
        max-width: 90vw;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .image-modal-image {
        max-width: 100%;
        max-height: 85vh;
        object-fit: contain;
    }

    .image-modal-toolbar {
        background: rgba(0, 0, 0, 0.8);
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
        border-radius: 0 0 8px 8px;
    }

    .image-modal-controls {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .image-modal-btn {
        background: transparent;
        border: 2px solid white;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
    }

    .image-modal-btn:hover {
        background: white;
        color: #1a1a2e;
    }

    .close-modal-btn {
        position: absolute;
        top: -50px;
        right: 0;
        background: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .close-modal-btn:hover {
        transform: scale(1.1);
        background: #f0f0f0;
    }

    .fabric-details {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #d4af37;
        white-space: pre-wrap;
        word-wrap: break-word;
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

        .design-image-section {
            padding: 25px;
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

        .design-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Hero with Back Button -->
<div class="page-hero">
    <div>
        <h1>Design Details</h1>
        <p>Order #{{ $order->order->order_number }}</p>
    </div>
    <a href="{{ route('tailor.designs.gallery') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Gallery
    </a>
</div>

<!-- Main Content -->
<div class="content-wrapper">
    <!-- Design Image Section (Left) -->
    <div>
        <div class="design-card">
            <div class="design-card-header">
                <i class="fas fa-image"></i>
                Design Image
            </div>
            <div class="design-image-section">
                @if($order->design_image)
                    <div class="design-image-container">
                        <img src="{{ asset('storage/' . $order->design_image) }}" 
                             alt="Design for {{ $order->garment_type }}"
                             onclick="openImageModal('{{ asset('storage/' . $order->design_image) }}', 'Order #{{ $order->order->order_number }}')">
                    </div>
                    <div class="design-actions">
                        <a href="{{ asset('storage/' . $order->design_image) }}" download class="btn-action btn-download">
                            <i class="fas fa-download"></i> Download Design
                        </a>
                    </div>
                @else
                    <div class="design-image-placeholder">
                        <i class="fas fa-image"></i>
                        <p>No design image uploaded</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Information Card -->
        <div class="design-card" style="margin-top: 25px;">
            <div class="design-card-header">
                <i class="fas fa-info-circle"></i>
                Order Information
            </div>
            <div class="order-info-section">
                <div class="info-group">
                    <div class="info-label">Order Number</div>
                    <p class="info-value">{{ $order->order->order_number }}</p>
                </div>

                <div class="info-divider"></div>

                <div class="info-group">
                    <div class="info-label">Garment Type</div>
                    <p class="info-value">{{ ucfirst($order->garment_type) }}</p>
                </div>

                <div class="info-group">
                    <div class="info-label">Service Option</div>
                    <p class="info-value">{{ ucfirst($order->service_option) }}</p>
                </div>

                <div class="info-divider"></div>

                <div class="info-group">
                    <div class="info-label">Current Status</div>
                    <p class="info-value">
                        <span class="status-badge badge-{{ str_replace('_', '-', $order->stitching_status) }}">
                            {{ str_replace('_', ' ', ucfirst($order->stitching_status)) }}
                        </span>
                    </p>
                </div>

                <div class="info-group">
                    <div class="info-label">Fabric Details</div>
                    <div class="fabric-details">
                        {{ $order->fabric_details ?? 'Not provided' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar (Right) -->
    <div>
        <!-- Customer Information -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <i class="fas fa-user"></i>
                Customer Info
            </div>
            <div class="sidebar-card-body">
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
            </div>
        </div>

        <!-- Important Dates -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <i class="fas fa-calendar-alt"></i>
                Important Dates
            </div>
            <div class="sidebar-card-body">
                @if($order->assigned_date)
                    <div class="info-group">
                        <div class="info-label">Assigned</div>
                        <p class="info-value">{{ $order->assigned_date->format('M d, Y') }}</p>
                    </div>
                @endif

                @if($order->start_date)
                    <div class="info-group">
                        <div class="info-label">Stitching Started</div>
                        <p class="info-value">{{ $order->start_date->format('M d, Y') }}</p>
                    </div>
                @endif

                @if($order->completion_date)
                    <div class="info-group">
                        <div class="info-label">Expected Completion</div>
                        <p class="info-value">{{ $order->completion_date->format('M d, Y') }}</p>
                    </div>
                @endif

                @if(!$order->assigned_date && !$order->start_date && !$order->completion_date)
                    <p style="color: #999; text-align: center; padding: 20px 0; margin: 0;">
                        <i class="fas fa-calendar-times" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        No dates recorded yet
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
                    <a href="{{ route('tailor.stitching-orders.show', $order->id) }}" class="btn-quick-action btn-quick-action-primary">
                        <i class="fas fa-eye"></i> View Order Details
                    </a>
                    @if($order->measurement_id)
                        <a href="{{ route('tailor.measurements.show', $order->measurement_id) }}" class="btn-quick-action btn-quick-action-secondary">
                            <i class="fas fa-ruler"></i> View Measurements
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Viewer Modal -->
<div class="image-modal" id="imageModal">
    <button class="close-modal-btn" onclick="closeImageModal()" title="Close">
        <i class="fas fa-times"></i>
    </button>
    <div class="image-modal-content">
        <img id="modalImage" src="" alt="Design Preview" class="image-modal-image">
        <div class="image-modal-toolbar">
            <div>
                <strong id="modalOrderInfo"></strong>
            </div>
            <div class="image-modal-controls">
                <button class="image-modal-btn" onclick="zoomIn()" title="Zoom In">
                    <i class="fas fa-search-plus"></i> Zoom In
                </button>
                <button class="image-modal-btn" onclick="zoomOut()" title="Zoom Out">
                    <i class="fas fa-search-minus"></i> Zoom Out
                </button>
                <button class="image-modal-btn" onclick="downloadImage()" title="Download">
                    <i class="fas fa-download"></i> Download
                </button>
                <button class="image-modal-btn" onclick="closeImageModal()" title="Close">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let currentZoom = 1;
    let currentImageSrc = '';

    function openImageModal(src, orderNumber) {
        currentImageSrc = src;
        currentZoom = 1;
        document.getElementById('modalImage').src = src;
        document.getElementById('modalOrderInfo').textContent = orderNumber;
        document.getElementById('imageModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.remove('active');
        document.body.style.overflow = 'auto';
        currentZoom = 1;
    }

    function zoomIn() {
        currentZoom += 0.2;
        updateZoom();
    }

    function zoomOut() {
        if (currentZoom > 0.5) {
            currentZoom -= 0.2;
            updateZoom();
        }
    }

    function updateZoom() {
        const img = document.getElementById('modalImage');
        img.style.transform = 'scale(' + currentZoom + ')';
    }

    function downloadImage() {
        const link = document.createElement('a');
        link.href = currentImageSrc;
        link.download = 'design.jpg';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Close modal on backdrop click
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endsection
