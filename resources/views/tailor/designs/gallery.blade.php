@extends('tailor.layouts.app')

@section('title', 'Design Gallery - Tailor Panel')

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

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 30px;
    }

    .design-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .design-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .design-image-container {
        position: relative;
        width: 100%;
        height: 280px;
        background: #f8f9fa;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .design-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: zoom-in;
        transition: all 0.3s ease;
    }

    .design-card:hover .design-image-container img {
        transform: scale(1.05);
    }

    .design-image-placeholder {
        text-align: center;
        color: #999;
    }

    .design-image-placeholder i {
        font-size: 3rem;
        margin-bottom: 10px;
        opacity: 0.3;
    }

    .design-card-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .design-order-id {
        font-size: 0.9rem;
        font-weight: 700;
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .design-customer-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 8px;
    }

    .design-product-name {
        font-size: 0.95rem;
        color: #666;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .design-product-name i {
        color: #d4af37;
    }

    .design-status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }

    .badge-stitching-started {
        background: #e7f3ff;
        color: #0066cc;
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

    .design-card-actions {
        display: flex;
        gap: 10px;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    .btn-view-design {
        flex: 1;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-view-design:hover {
        background: linear-gradient(135deg, #2980b9, #1a5276);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        color: white;
    }

    .btn-download-design {
        flex: 1;
        background: white;
        color: #666;
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-download-design:hover {
        border-color: #d4af37;
        color: #d4af37;
        background: #f8f9fa;
    }

    .empty-state {
        text-align: center;
        padding: 80px 30px;
        background: white;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
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

    /* Image Viewer Modal */
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

    @media (max-width: 1024px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .page-hero {
            padding: 25px;
        }

        .page-hero h1 {
            font-size: 1.8rem;
        }

        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }

        .design-image-container {
            height: 200px;
        }
    }

    @media (max-width: 480px) {
        .gallery-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Hero -->
<div class="page-hero">
    <h1>Design Gallery</h1>
    <p>Browse and view design images for your stitching orders</p>
</div>

<!-- Filter Card -->
<div class="filter-card">
    <div class="filter-title">
        <i class="fas fa-filter"></i>
        Filter by Garment Type
    </div>
    <form method="GET" action="{{ route('tailor.designs.gallery') }}">
        <div class="row g-3">
            <div class="col-md-8">
                <select name="garment_type" class="form-select">
                    <option value="">All Garments</option>
                    @foreach($garmentTypes as $key => $label)
                        <option value="{{ $key }}" {{ $currentGarmentType === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-filter" style="flex: 1;">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('tailor.designs.gallery') }}" class="btn-filter" style="background: white; color: #666; border: 2px solid #ddd; flex: 1;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Design Gallery Grid -->
@if($orders->count() > 0)
    <div class="gallery-grid">
        @forelse($orders as $order)
            <div class="design-card">
                <div class="design-image-container">
                    @if($order->design_image)
                        <img src="{{ asset('storage/' . $order->design_image) }}" 
                             alt="Design for {{ $order->garment_type }}"
                             onclick="openImageModal('{{ asset('storage/' . $order->design_image) }}', '{{ $order->order->order_number }}')">
                    @else
                        <div class="design-image-placeholder">
                            <i class="fas fa-image"></i>
                            <p style="margin-top: 10px;">No Image</p>
                        </div>
                    @endif
                </div>

                <div class="design-card-body">
                    <div class="design-order-id">Order #{{ $order->order->order_number }}</div>
                    <div class="design-customer-name">{{ $order->order->customer->name ?? 'N/A' }}</div>
                    <div class="design-product-name">
                        <i class="fas fa-shirt"></i>
                        {{ ucfirst($order->garment_type) }}
                    </div>

                    <div class="design-status-badge badge-{{ str_replace('_', '-', $order->stitching_status) }}">
                        {{ str_replace('_', ' ', ucfirst($order->stitching_status)) }}
                    </div>

                    <div class="design-card-actions">
                        <a href="{{ route('tailor.designs.show', $order->id) }}" class="btn-view-design">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        @if($order->design_image)
                            <a href="{{ asset('storage/' . $order->design_image) }}" download class="btn-download-design" title="Download design image">
                                <i class="fas fa-download"></i> Download
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div style="margin-top: 40px; display: flex; justify-content: center;">
            {{ $orders->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-images"></i>
        </div>
        <p class="empty-state-text">No design images found</p>
        <p style="color: #ccc; margin-top: 10px; font-size: 0.95rem;">No designs have been uploaded yet for your assigned orders</p>
    </div>
@endif

<!-- Image Viewer Modal -->
<div class="image-modal" id="imageModal">
    <button class="close-modal-btn" onclick="closeImageModal()">
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
        document.getElementById('modalOrderInfo').textContent = 'Design for ' + orderNumber;
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

