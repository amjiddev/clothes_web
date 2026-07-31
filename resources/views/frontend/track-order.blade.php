@extends('frontend.layouts.app')

@section('title', 'Track Your Order - CLOTHES STORE')

@section('content')
<style>
    .track-container {
        background: #f8f5ef;
        padding: 60px 0;
        min-height: calc(100vh - 200px);
    }

    .track-content {
        background: white;
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--accent-gold);
    }

    .track-content h1 {
        font-size: 2.5rem;
        font-family: 'Playfair Display', serif;
        color: var(--primary-dark);
        margin-bottom: 1rem;
        border-bottom: 3px solid var(--accent-gold);
        padding-bottom: 1rem;
    }

    .track-content h2 {
        font-size: 1.5rem;
        color: var(--primary-dark);
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .track-content p {
        color: var(--text-muted);
        line-height: 1.8;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .search-section {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        padding: 3rem 2rem;
        border-radius: 10px;
        margin: 2rem 0;
        color: white;
    }

    .search-section h2 {
        color: white;
        margin-top: 0;
        margin-bottom: 1.5rem;
    }

    .search-form {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 1rem;
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        color: var(--accent-gold);
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .form-group input {
        padding: 12px 15px;
        border: 2px solid rgba(212, 175, 55, 0.3);
        border-radius: 5px;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .form-group input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--accent-gold);
        background: rgba(255, 255, 255, 0.15);
    }

    .btn-track {
        background: var(--accent-gold);
        color: var(--primary-dark);
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .btn-track:hover {
        background: #FFE066;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .info-boxes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin: 3rem 0;
    }

    .info-box {
        background: #f9f9f9;
        padding: 2rem;
        border-radius: 8px;
        border-left: 4px solid var(--accent-gold);
        text-align: center;
    }

    .info-box i {
        font-size: 2.5rem;
        color: var(--accent-gold);
        margin-bottom: 1rem;
    }

    .info-box h3 {
        color: var(--primary-dark);
        margin-bottom: 0.8rem;
        font-weight: 600;
    }

    .info-box p {
        color: var(--text-muted);
        margin: 0;
        font-size: 0.95rem;
    }

    .tracking-result {
        background: #f9f9f9;
        border: 2px solid var(--accent-gold);
        border-radius: 8px;
        padding: 2rem;
        margin: 2rem 0;
        display: none;
    }

    .tracking-result.show {
        display: block;
    }

    .order-header {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid #e0e0e0;
    }

    .order-detail {
        color: var(--text-muted);
    }

    .order-detail label {
        font-weight: 600;
        color: var(--primary-dark);
        display: block;
        margin-bottom: 0.3rem;
    }

    .order-detail span {
        font-size: 1.1rem;
        color: var(--primary-dark);
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .status-pending {
        background: #FFF3CD;
        color: #856404;
    }

    .status-processing {
        background: #D1ECF1;
        color: #0C5460;
    }

    .status-shipped {
        background: #D4EDDA;
        color: #155724;
    }

    .status-delivered {
        background: #C3E6CB;
        color: #0B5E20;
    }

    .status-cancelled {
        background: #F8D7DA;
        color: #721C24;
    }

    .timeline-track {
        position: relative;
        padding: 2rem 0;
        margin: 2rem 0;
    }

    .timeline-track::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #e0e0e0;
    }

    .timeline-item {
        margin-bottom: 2rem;
        padding-left: 60px;
        position: relative;
    }

    .timeline-dot {
        position: absolute;
        left: 0;
        top: 5px;
        width: 30px;
        height: 30px;
        background: white;
        border: 3px solid var(--accent-gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .timeline-dot i {
        color: var(--accent-gold);
        font-size: 1rem;
    }

    .timeline-item.completed .timeline-dot {
        background: var(--accent-gold);
        border-color: var(--primary-dark);
    }

    .timeline-item.completed .timeline-dot i {
        color: white;
    }

    .timeline-item.completed .timeline-dot::before {
        content: '';
        position: absolute;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--accent-gold);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(212, 175, 55, 0);
        }
    }

    .timeline-content h4 {
        color: var(--primary-dark);
        margin: 0 0 0.3rem 0;
        font-weight: 600;
    }

    .timeline-content p {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .timeline-date {
        font-size: 0.85rem;
        color: #999;
        margin-top: 0.3rem;
    }

    .section-title {
        text-align: center;
        margin-bottom: 2rem;
    }

    .section-title h2 {
        margin-top: 0;
    }

    .help-section {
        background: rgba(212, 175, 55, 0.1);
        border-left: 4px solid var(--accent-gold);
        padding: 2rem;
        border-radius: 5px;
        margin-top: 3rem;
    }

    .help-section h3 {
        color: var(--primary-dark);
        margin-top: 0;
    }

    .help-section ul {
        list-style: none;
        padding: 0;
    }

    .help-section ul li {
        padding: 0.5rem 0;
        color: var(--text-muted);
        padding-left: 25px;
        position: relative;
    }

    .help-section ul li:before {
        content: "→";
        position: absolute;
        left: 0;
        color: var(--accent-gold);
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .track-content {
            padding: 25px;
        }

        .track-content h1 {
            font-size: 1.8rem;
        }

        .search-form {
            grid-template-columns: 1fr;
        }

        .order-header {
            grid-template-columns: 1fr;
        }

        .info-boxes {
            grid-template-columns: 1fr;
        }

        .timeline-track::before {
            left: 15px;
        }

        .timeline-item {
            padding-left: 50px;
        }
    }
</style>

<div class="track-container">
    <div class="container">
        <div class="track-content">
            <h1>
                <i class="fas fa-box"></i> Track Your Order
            </h1>

            <p>
                Enter your Order ID or Email address below to track your shipment in real-time. You can find your Order ID in the confirmation email you received after placing your order.
            </p>

            <!-- Search Section -->
            <div class="search-section">
                <h2><i class="fas fa-search"></i> Track Your Package</h2>
                <div class="search-form">
                    <div class="form-group">
                        <label for="orderInput">Order ID or Email</label>
                        <input 
                            type="text" 
                            id="orderInput" 
                            placeholder="e.g., ORD-12345 or email@example.com"
                            autocomplete="off"
                        >
                    </div>
                    <div class="form-group">
                        <label for="phoneInput">Phone Number</label>
                        <input 
                            type="tel" 
                            id="phoneInput" 
                            placeholder="Your registered phone"
                            autocomplete="off"
                        >
                    </div>
                    <button class="btn-track" onclick="searchOrder()">
                        <i class="fas fa-search"></i> Track
                    </button>
                </div>
            </div>

            <!-- Info Boxes -->
            <div class="info-boxes">
                <div class="info-box">
                    <i class="fas fa-envelope"></i>
                    <h3>Check Your Email</h3>
                    <p>Look for confirmation and shipping emails. Your Order ID and tracking number are included.</p>
                </div>
                <div class="info-box">
                    <i class="fas fa-phone"></i>
                    <h3>Contact Support</h3>
                    <p>If you don't have your Order ID, contact us at info@clothes.com or +91-XXXXXXXXXX</p>
                </div>
                <div class="info-box">
                    <i class="fas fa-clock"></i>
                    <h3>Tracking Updates</h3>
                    <p>You'll receive SMS and email updates at each milestone of your delivery.</p>
                </div>
            </div>

            <!-- Tracking Result -->
            <div class="tracking-result" id="trackingResult">
                <!-- Order Header -->
                <div class="order-header">
                    <div>
                        <div class="order-detail">
                            <label>Order Number</label>
                            <span id="resultOrderId">ORD-12345</span>
                        </div>
                    </div>
                    <div>
                        <div class="order-detail">
                            <label>Order Status</label>
                            <div>
                                <span id="resultStatus" class="status-badge">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Details Grid -->
                <div class="order-header">
                    <div>
                        <div class="order-detail">
                            <label>Tracking Number</label>
                            <span id="resultTracking">TCS-98765432</span>
                        </div>
                    </div>
                    <div>
                        <div class="order-detail">
                            <label>Estimated Delivery</label>
                            <span id="resultDelivery">July 30, 2026</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Shipment Timeline</h3>
                <div class="timeline-track">
                    <div class="timeline-item completed">
                        <div class="timeline-dot">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Order Confirmed</h4>
                            <p>Your order has been received and confirmed.</p>
                            <div class="timeline-date">July 28, 2026 - 10:30 AM</div>
                        </div>
                    </div>

                    <div class="timeline-item completed">
                        <div class="timeline-dot">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Processing</h4>
                            <p>Your order is being packed and prepared for shipment.</p>
                            <div class="timeline-date">July 28, 2026 - 2:45 PM</div>
                        </div>
                    </div>

                    <div class="timeline-item completed">
                        <div class="timeline-dot">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Dispatched</h4>
                            <p>Your package has been handed over to TCS Courier.</p>
                            <div class="timeline-date">July 29, 2026 - 9:00 AM</div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot">
                            <i class="fas fa-map-location-dot"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>In Transit</h4>
                            <p>Your package is on its way to your location.</p>
                            <div class="timeline-date">Expected: July 30, 2026</div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Delivered</h4>
                            <p>Your package will be delivered to your address.</p>
                            <div class="timeline-date">Expected: July 30, 2026</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status Guide -->
            <div class="section-title" style="margin-top: 4rem;">
                <h2>Understanding Order Status</h2>
            </div>

            <div class="info-boxes">
                <div class="info-box">
                    <h3><span class="status-badge status-pending">Pending</span></h3>
                    <p>Your order has been placed but not yet confirmed by our team.</p>
                </div>
                <div class="info-box">
                    <h3><span class="status-badge status-processing">Processing</span></h3>
                    <p>Your order is being packed and prepared for shipment.</p>
                </div>
                <div class="info-box">
                    <h3><span class="status-badge status-shipped">Shipped</span></h3>
                    <p>Your package is on its way to your delivery address.</p>
                </div>
                <div class="info-box">
                    <h3><span class="status-badge status-delivered">Delivered</span></h3>
                    <p>Your order has been successfully delivered.</p>
                </div>
            </div>

            <!-- Help Section -->
            <div class="help-section">
                <h3><i class="fas fa-question-circle"></i> Need Help?</h3>
                <ul>
                    <li>Your Order ID is in the subject line of your confirmation email</li>
                    <li>Tracking numbers are sent via email and SMS after dispatch</li>
                    <li>Updates are typically sent within 24 hours of each milestone</li>
                    <li>For international orders, tracking may take 24-48 hours to appear</li>
                    <li>SMS updates may be delayed depending on your service provider</li>
                </ul>
                <p style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(212, 175, 55, 0.3);">
                    <strong>Still can't find your order?</strong><br>
                    Contact our support team at <a href="mailto:info@clothes.com" style="color: var(--accent-gold); text-decoration: none;">info@clothes.com</a> or call <a href="tel:+91-XXXXXXXXXX" style="color: var(--accent-gold); text-decoration: none;">+91-XXXXXXXXXX</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function searchOrder() {
        const orderInput = document.getElementById('orderInput').value;
        const phoneInput = document.getElementById('phoneInput').value;

        if (!orderInput && !phoneInput) {
            alert('Please enter either Order ID/Email or Phone Number');
            return;
        }

        // Show the tracking result (demo)
        const trackingResult = document.getElementById('trackingResult');
        trackingResult.classList.add('show');

        // Scroll to result
        trackingResult.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // In a real scenario, you would make an API call here
        // to fetch the order details from the backend
    }

    // Allow Enter key to search
    document.getElementById('orderInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchOrder();
        }
    });

    document.getElementById('phoneInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchOrder();
        }
    });
</script>

@endsection
