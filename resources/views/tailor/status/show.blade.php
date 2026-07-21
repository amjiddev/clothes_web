@extends('tailor.layouts.app')

@section('title', 'Stitching Status Management - Tailor Panel')

@section('styles')
<style>
    .status-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        color: white;
        padding: 40px;
        border-radius: 14px;
        margin-bottom: 35px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .status-header-content h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
    }

    .status-header-content p {
        color: rgba(255, 255, 255, 0.85);
        margin: 8px 0 0 0;
        font-size: 0.95rem;
    }

    .btn-header {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .btn-header:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .section-card {
        background: white;
        border-radius: 14px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.1);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding-bottom: 18px;
        border-bottom: 2px solid #f5f5f5;
    }

    .section-icon {
        font-size: 1.5rem;
        color: #d4af37;
        width: 35px;
        text-align: center;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }

    .status-progress {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        position: relative;
    }

    .status-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
    }

    .status-step-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        color: white;
        margin-bottom: 10px;
        border: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .status-step-label {
        text-align: center;
        font-size: 0.85rem;
        color: #666;
        font-weight: 600;
        line-height: 1.4;
    }

    .status-step.completed .status-step-circle {
        background: linear-gradient(135deg, #27ae60, #229954);
        border-color: #27ae60;
    }

    .status-step.active .status-step-circle {
        background: linear-gradient(135deg, #d4af37, #c9a227);
        border-color: #d4af37;
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
        animation: pulse 2s infinite;
    }

    .status-step.pending .status-step-circle {
        background: #ccc;
        border-color: #999;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .status-connector {
        flex: 1;
        height: 3px;
        background: #ddd;
        margin: 0 10px;
        position: relative;
        top: -35px;
        z-index: 0;
    }

    .status-connector.completed {
        background: linear-gradient(90deg, #27ae60, #27ae60);
    }

    .status-connector.active {
        background: linear-gradient(90deg, #27ae60, #d4af37);
    }

    .current-status-box {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(212, 175, 55, 0.05));
        border-radius: 12px;
        padding: 24px;
        border-left: 5px solid #d4af37;
        margin-bottom: 30px;
    }

    .current-status-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .current-status-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 5px;
    }

    .current-status-time {
        font-size: 0.9rem;
        color: #999;
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

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .btn-transition {
        flex: 1;
        min-width: 180px;
        padding: 14px 24px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 0.95rem;
        text-decoration: none;
    }

    .btn-transition:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-transition-primary {
        background: linear-gradient(135deg, #1a1a2e, #0f0f1e);
        color: white;
    }

    .btn-transition-primary:hover {
        background: linear-gradient(135deg, #d4af37, #c9a227);
        color: #1a1a2e;
    }

    .btn-transition-disabled {
        background: #ccc;
        color: #999;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-transition-disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .timeline {
        position: relative;
        padding-left: 40px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .timeline-item:last-child {
        border-bottom: none;
    }

    .timeline-marker {
        position: absolute;
        left: -55px;
        top: 5px;
        width: 35px;
        height: 35px;
        background: white;
        border: 3px solid #d4af37;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #d4af37;
        z-index: 2;
    }

    .timeline-line {
        position: absolute;
        left: -45px;
        top: 40px;
        width: 2px;
        height: calc(100% + 10px);
        background: #e9ecef;
    }

    .timeline-item:last-child .timeline-line {
        display: none;
    }

    .timeline-content {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 16px;
        border-left: 4px solid #d4af37;
    }

    .timeline-date {
        font-size: 0.8rem;
        color: #999;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .timeline-event {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 5px;
    }

    .timeline-details {
        font-size: 0.9rem;
        color: #666;
    }

    .timeline-notes {
        margin-top: 10px;
        padding: 10px;
        background: white;
        border-radius: 6px;
        border-left: 3px solid #3498db;
        font-style: italic;
        color: #555;
    }

    .order-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .info-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 16px;
        border-left: 4px solid #d4af37;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a2e;
    }

    .notes-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .notes-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 10px;
    }

    .notes-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: inherit;
        resize: vertical;
        min-height: 100px;
        transition: all 0.3s ease;
    }

    .notes-input:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        outline: none;
    }

    @media (max-width: 1024px) {
        .status-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }

    @media (max-width: 768px) {
        .status-header {
            padding: 20px;
        }

        .status-progress {
            flex-wrap: wrap;
        }

        .status-connector {
            display: none;
        }

        .section-card {
            padding: 18px;
        }

        .order-info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-transition {
            min-width: auto;
            width: 100%;
        }

        .timeline {
            padding-left: 25px;
        }

        .timeline-marker {
            left: -40px;
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }

        .timeline-line {
            left: -32px;
        }
    }

    @media (max-width: 480px) {
        .status-step-circle {
            width: 50px;
            height: 50px;
            font-size: 1rem;
        }

        .status-step-label {
            font-size: 0.75rem;
        }

        .section-title {
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Status Header -->
<div class="status-header">
    <div class="status-header-content">
        <h1>Stitching Status Management</h1>
        <p>Order #{{ $order->order->order_number }} - {{ $order->order->customer->name ?? 'Customer' }}</p>
    </div>
    <div>
        <a href="{{ route('tailor.stitching-orders.show', $order->id) }}" class="btn-header">
            <i class="fas fa-arrow-left"></i> Back to Order
        </a>
    </div>
</div>

<!-- Order Information -->
<div class="section-card">
    <div class="section-header">
        <i class="fas fa-info-circle section-icon"></i>
        <h2 class="section-title">Order Information</h2>
    </div>
    <div class="order-info-grid">
        <div class="info-item">
            <div class="info-label">Order Number</div>
            <div class="info-value">#{{ $order->order->order_number }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Customer Name</div>
            <div class="info-value">{{ $order->order->customer->name ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Garment Type</div>
            <div class="info-value">{{ ucfirst($order->garment_type) }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Assigned Date</div>
            <div class="info-value">{{ $order->assigned_date?->format('M d, Y') ?? 'N/A' }}</div>
        </div>
    </div>
</div>

<!-- Current Status -->
<div class="section-card">
    <div class="section-header">
        <i class="fas fa-circle section-icon"></i>
        <h2 class="section-title">Current Status</h2>
    </div>

    <div class="current-status-box">
        <div class="current-status-label">Current Stitching Status</div>
        <div class="current-status-value">{{ str_replace('_', ' ', ucfirst($order->stitching_status)) }}</div>
        <div class="current-status-time">Updated {{ $order->updated_at->diffForHumans() }}</div>
        <div style="margin-top: 12px;">
            <span class="status-badge badge-{{ str_replace('_', '-', $order->stitching_status) }}">
                {{ str_replace('_', ' ', ucfirst($order->stitching_status)) }}
            </span>
        </div>
    </div>
</div>

<!-- Status Progress Timeline -->
<div class="section-card">
    <div class="section-header">
        <i class="fas fa-tasks section-icon"></i>
        <h2 class="section-title">Status Workflow</h2>
    </div>

    <div class="status-progress">
        @php
            $statuses = ['pending', 'stitching_started', 'cutting_completed', 'stitching_in_progress', 'quality_checking', 'completed', 'delivered'];
            $statusNames = [
                'pending' => 'Pending',
                'stitching_started' => 'Stitching Started',
                'cutting_completed' => 'Cutting Completed',
                'stitching_in_progress' => 'In Progress',
                'quality_checking' => 'Quality Check',
                'completed' => 'Completed',
                'delivered' => 'Delivered',
            ];
        @endphp

        @foreach($statuses as $index => $status)
            @php
                $isCompleted = array_search($order->stitching_status, $statuses) > $index;
                $isActive = $order->stitching_status === $status;
                $class = $isCompleted ? 'completed' : ($isActive ? 'active' : 'pending');
            @endphp

            <div class="status-step {{ $class }}">
                <div class="status-step-circle">
                    @if($isCompleted)
                        <i class="fas fa-check"></i>
                    @elseif($isActive)
                        <i class="fas fa-circle"></i>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <div class="status-step-label">{{ $statusNames[$status] }}</div>
            </div>

            @if($index < count($statuses) - 1)
                <div class="status-connector {{ $isCompleted ? 'completed' : ($isActive ? 'active' : '') }}"></div>
            @endif
        @endforeach
    </div>
</div>

<!-- Status Update Section -->
<div class="section-card">
    <div class="section-header">
        <i class="fas fa-arrow-right section-icon"></i>
        <h2 class="section-title">Update Status</h2>
    </div>

    @if(count($allowedTransitions) > 0)
        <div class="action-buttons">
            @foreach($allowedTransitions as $nextStatus)
                @php
                    $statusName = str_replace('_', ' ', ucfirst($nextStatus));
                @endphp
                <form method="POST" action="{{ route('tailor.status.update', $order->id) }}" style="flex: 1;">
                    @csrf
                    <input type="hidden" name="new_status" value="{{ $nextStatus }}">
                    <button type="button" class="btn-transition btn-transition-primary" data-bs-toggle="modal" data-bs-target="#statusUpdateModal" data-status="{{ $nextStatus }}" data-status-name="{{ $statusName }}">
                        <i class="fas fa-check-circle"></i> {{ $statusName }}
                    </button>
                </form>
            @endforeach
        </div>
        <p style="color: #999; margin-top: 15px; font-size: 0.85rem; font-style: italic;">
            ℹ️ Select a status to update. You can only move forward through the workflow.
        </p>
    @else
        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #d4af37; color: #666;">
            <i class="fas fa-info-circle" style="color: #d4af37; margin-right: 10px;"></i>
            This order has reached its final status. No further status updates are available.
        </div>
    @endif
</div>

<!-- Status History Timeline -->
<div class="section-card">
    <div class="section-header">
        <i class="fas fa-history section-icon"></i>
        <h2 class="section-title">Status History Timeline</h2>
    </div>

    @if($timeline->count() > 0 || true)
        <div class="timeline">
            <!-- Order Created -->
            <div class="timeline-item">
                <div class="timeline-marker">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="timeline-line"></div>
                <div class="timeline-content">
                    <div class="timeline-date">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</div>
                    <div class="timeline-event">Order Created</div>
                    <div class="timeline-details">Stitching order was created in the system</div>
                </div>
            </div>

            <!-- Status History -->
            @forelse($timeline as $entry)
                <div class="timeline-item">
                    <div class="timeline-marker">
                        <i class="fas {{ $entry->status_icon }}"></i>
                    </div>
                    <div class="timeline-line"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">{{ $entry->changed_at->format('M d, Y \a\t h:i A') }}</div>
                        <div class="timeline-event">
                            Status Updated to <strong>{{ $entry->status_text }}</strong>
                        </div>
                        <div class="timeline-details">
                            <i class="fas fa-user" style="color: #d4af37; margin-right: 5px;"></i>
                            <strong>{{ $entry->tailor?->name ?? 'System' }}</strong>
                        </div>
                        @if($entry->notes)
                            <div class="timeline-notes">
                                {{ $entry->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="timeline-item">
                    <div class="timeline-marker">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="timeline-content" style="color: #999;">
                        <div class="timeline-event">No status updates yet</div>
                        <div class="timeline-details">Waiting for first status update</div>
                    </div>
                </div>
            @endforelse
        </div>
    @endif
</div>

<!-- Modal for Status Update with Notes -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a1a2e, #0f0f1e); color: white; border: none;">
                <h5 class="modal-title" id="statusUpdateModalLabel">Update Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusUpdateForm" method="POST" action="{{ route('tailor.status.update', $order->id) }}">
                @csrf
                <div class="modal-body" style="padding: 25px;">
                    <input type="hidden" name="new_status" id="newStatus">

                    <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #d4af37;">
                        <p style="margin: 0; font-size: 0.9rem; color: #666;">
                            <strong>Current Status:</strong> {{ str_replace('_', ' ', ucfirst($order->stitching_status)) }}
                        </p>
                        <p style="margin: 8px 0 0 0; font-size: 0.9rem; color: #666;">
                            <strong>New Status:</strong> <span id="newStatusText" style="color: #1a1a2e; font-weight: 700;"></span>
                        </p>
                    </div>

                    <div class="notes-section">
                        <label class="notes-label">Add Notes (Optional)</label>
                        <textarea name="notes" class="notes-input" placeholder="Add any notes about this status update..."></textarea>
                        <small style="color: #999; display: block; margin-top: 8px;">
                            Maximum 500 characters
                        </small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 15px 25px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #1a1a2e, #0f0f1e); border: none;">
                        <i class="fas fa-save me-2"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Handle status update modal
    const statusUpdateModal = document.getElementById('statusUpdateModal');
    const newStatusInput = document.getElementById('newStatus');
    const newStatusText = document.getElementById('newStatusText');

    document.querySelectorAll('[data-bs-target="#statusUpdateModal"]').forEach(button => {
        button.addEventListener('click', function() {
            const status = this.getAttribute('data-status');
            const statusName = this.getAttribute('data-status-name');
            newStatusInput.value = status;
            newStatusText.textContent = statusName;
        });
    });

    // Handle form submission
    const statusUpdateForm = document.getElementById('statusUpdateForm');
    if (statusUpdateForm) {
        statusUpdateForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = this.action;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(statusUpdateModal);
                    modal.hide();

                    // Show success message
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success alert-dismissible fade show';
                    successAlert.role = 'alert';
                    successAlert.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.querySelector('.main-content').insertBefore(successAlert, document.querySelector('.main-content').firstChild);

                    // Reload page after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the status');
            });
        });
    }
</script>
@endsection
