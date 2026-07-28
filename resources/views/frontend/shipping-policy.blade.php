@extends('frontend.layouts.app')

@section('title', 'Shipping Policy - CLOTHES STORE')

@section('content')
<style>
    .policy-container {
        background: #f8f5ef;
        padding: 60px 0;
        min-height: calc(100vh - 200px);
    }

    .policy-content {
        background: white;
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--accent-gold);
    }

    .policy-content h1 {
        font-size: 2.5rem;
        font-family: 'Playfair Display', serif;
        color: var(--primary-dark);
        margin-bottom: 1rem;
        border-bottom: 3px solid var(--accent-gold);
        padding-bottom: 1rem;
    }

    .policy-content h2 {
        font-size: 1.5rem;
        color: var(--primary-dark);
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .policy-content h3 {
        font-size: 1.2rem;
        color: var(--primary-dark);
        margin-top: 1.5rem;
        margin-bottom: 0.8rem;
        font-weight: 600;
    }

    .policy-content p {
        color: var(--text-muted);
        line-height: 1.8;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .policy-content ul {
        list-style: none;
        padding-left: 0;
    }

    .policy-content ul li {
        color: var(--text-muted);
        margin-bottom: 0.8rem;
        padding-left: 25px;
        position: relative;
        line-height: 1.8;
    }

    .policy-content ul li:before {
        content: "→";
        position: absolute;
        left: 0;
        color: var(--accent-gold);
        font-weight: bold;
    }

    .policy-section {
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .policy-section:last-child {
        border-bottom: none;
    }

    .highlight-box {
        background: rgba(212, 175, 55, 0.1);
        border-left: 4px solid var(--accent-gold);
        padding: 1.5rem;
        border-radius: 5px;
        margin: 2rem 0;
    }

    .highlight-box p {
        margin: 0;
        color: var(--primary-dark);
        font-weight: 500;
    }

    .shipping-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .shipping-card {
        background: #f9f9f9;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 2rem;
        transition: all 0.3s ease;
    }

    .shipping-card:hover {
        border-color: var(--accent-gold);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
        transform: translateY(-5px);
    }

    .shipping-icon {
        font-size: 2.5rem;
        color: var(--accent-gold);
        margin-bottom: 1rem;
    }

    .shipping-card h3 {
        margin-top: 0;
        margin-bottom: 0.8rem;
    }

    .shipping-details {
        font-size: 0.95rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }

    .shipping-cost {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--accent-gold);
        margin-bottom: 0.5rem;
    }

    .shipping-time {
        font-size: 0.9rem;
        color: var(--primary-dark);
        font-weight: 600;
    }

    .table-wrapper {
        overflow-x: auto;
        margin: 2rem 0;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background: var(--primary-dark);
        color: white;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
        color: var(--text-muted);
    }

    .table tr:hover {
        background: #f9f9f9;
    }

    .timeline {
        position: relative;
        padding: 2rem 0;
        margin: 2rem 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 3px;
        height: 100%;
        background: var(--accent-gold);
    }

    .timeline-item {
        margin-bottom: 3rem;
        position: relative;
    }

    .timeline-item:nth-child(odd) {
        padding-right: 52%;
        text-align: right;
    }

    .timeline-item:nth-child(even) {
        padding-left: 52%;
        text-align: left;
    }

    .timeline-content {
        background: white;
        border: 2px solid var(--accent-gold);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .timeline-content h4 {
        color: var(--primary-dark);
        font-weight: 600;
        margin-top: 0;
        margin-bottom: 0.5rem;
    }

    .timeline-content p {
        margin: 0;
        font-size: 0.95rem;
    }

    .timeline-dot {
        position: absolute;
        left: 50%;
        top: 0;
        transform: translateX(-50%);
        width: 20px;
        height: 20px;
        background: var(--accent-gold);
        border: 3px solid white;
        border-radius: 50%;
        z-index: 10;
        box-shadow: 0 0 0 3px var(--primary-dark);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .info-card {
        background: #f9f9f9;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid var(--accent-gold);
    }

    .info-card h4 {
        color: var(--primary-dark);
        margin-top: 0;
        margin-bottom: 0.8rem;
        font-weight: 600;
    }

    .info-card p {
        margin: 0;
        font-size: 0.95rem;
    }

    .contact-banner {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px;
        text-align: center;
        margin-top: 3rem;
        border: 2px solid var(--accent-gold);
    }

    .contact-banner p {
        color: white;
        margin-bottom: 0.5rem;
    }

    .contact-banner a {
        color: var(--accent-gold);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .contact-banner a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .policy-content {
            padding: 25px;
        }

        .policy-content h1 {
            font-size: 1.8rem;
        }

        .policy-content h2 {
            font-size: 1.2rem;
        }

        .shipping-options {
            grid-template-columns: 1fr;
        }

        .timeline::before {
            left: 20px;
        }

        .timeline-item:nth-child(odd),
        .timeline-item:nth-child(even) {
            padding-left: 60px;
            padding-right: 0;
            text-align: left;
        }

        .timeline-dot {
            left: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .table {
            font-size: 0.9rem;
        }

        .table th,
        .table td {
            padding: 0.75rem;
        }
    }
</style>

<div class="policy-container">
    <div class="container">
        <div class="policy-content">
            <h1>
                <i class="fas fa-truck"></i> Shipping Policy
            </h1>

            <div class="policy-section">
                <h2>Overview</h2>
                <p>
                    We are committed to delivering your orders promptly and safely. This Shipping Policy outlines our shipping methods, timelines, charges, and procedures to ensure a smooth delivery experience.
                </p>
            </div>

            <div class="policy-section">
                <h2>Shipping Options & Charges</h2>
                <p>We offer multiple shipping options to suit your needs:</p>
                <div class="shipping-options">
                    <div class="shipping-card">
                        <div class="shipping-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3>Express Delivery</h3>
                        <div class="shipping-details">Fast and reliable delivery for urgent orders</div>
                        <div class="shipping-cost">Rs. 500</div>
                        <div class="shipping-time"><i class="fas fa-clock"></i> 2-3 Business Days</div>
                    </div>

                    <div class="shipping-card">
                        <div class="shipping-icon">
                            <i class="fas fa-dolly"></i>
                        </div>
                        <h3>Standard Delivery</h3>
                        <div class="shipping-details">Standard shipping at affordable rates</div>
                        <div class="shipping-cost">Rs. 300</div>
                        <div class="shipping-time"><i class="fas fa-clock"></i> 5-7 Business Days</div>
                    </div>

                    <div class="shipping-card">
                        <div class="shipping-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <h3>Economy Delivery</h3>
                        <div class="shipping-details">Most economical shipping option</div>
                        <div class="shipping-cost">Rs. 150</div>
                        <div class="shipping-time"><i class="fas fa-clock"></i> 7-10 Business Days</div>
                    </div>

                    <div class="shipping-card">
                        <div class="shipping-icon">
                            <i class="fas fa-gift"></i>
                        </div>
                        <h3>Free Shipping</h3>
                        <div class="shipping-details">On orders above Rs. 5,000</div>
                        <div class="shipping-cost">FREE</div>
                        <div class="shipping-time"><i class="fas fa-clock"></i> 5-7 Business Days</div>
                    </div>
                </div>
            </div>

            <div class="policy-section">
                <h2>Delivery Areas</h2>
                <p>
                    We currently ship to all major cities and regions across Pakistan. Please note:
                </p>
                <ul>
                    <li>We deliver to all provinces including Punjab, Sindh, KPK, and Balochistan</li>
                    <li>Delivery to remote or rural areas may take additional 2-3 days</li>
                    <li>Some remote locations may have additional shipping charges</li>
                    <li>International shipping is not currently available</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Processing Time</h2>
                <div class="highlight-box">
                    <p><i class="fas fa-info-circle"></i> Orders are processed within 24-48 hours after confirmation. Weekends and public holidays may cause additional delays.</p>
                </div>
                <p>
                    Please note that processing time is separate from delivery time. Your order will be shipped once it's processed and packed.
                </p>
            </div>

            <div class="policy-section">
                <h2>Order Tracking</h2>
                <p>
                    Once your order is dispatched, you will receive:
                </p>
                <ul>
                    <li>A tracking number via email and SMS</li>
                    <li>Real-time updates on your order status</li>
                    <li>Estimated delivery date</li>
                    <li>Access to our online tracking system</li>
                </ul>
                <p>
                    You can track your order anytime using the tracking number from your confirmation email.
                </p>
            </div>

            <div class="policy-section">
                <h2>Delivery Process</h2>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>1. Order Confirmation</h4>
                            <p>You receive order confirmation email with details and tracking number once order is placed.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>2. Order Processing</h4>
                            <p>Our team packs and prepares your order for shipment within 24-48 hours.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>3. Dispatch</h4>
                            <p>Your package is handed over to our courier partner for delivery.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>4. In Transit</h4>
                            <p>Your package is in transit. You can track it using your tracking number.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>5. Delivery</h4>
                            <p>Package is delivered to your doorstep. Please verify the package before accepting.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="policy-section">
                <h2>Shipping Partners</h2>
                <p>
                    We work with trusted and reliable courier partners to ensure safe and timely delivery:
                </p>
                <ul>
                    <li>TCS (Talking Courier Service)</li>
                    <li>M&P Express</li>
                    <li>Leopard Courier</li>
                    <li>Daewoo Express</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Shipping Costs Conditions</h2>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order Amount</th>
                                <th>Shipping Status</th>
                                <th>Applicable Charges</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Below Rs. 2,000</td>
                                <td>Shipping Available</td>
                                <td>Economy: Rs. 150, Standard: Rs. 300, Express: Rs. 500</td>
                            </tr>
                            <tr>
                                <td>Rs. 2,000 - Rs. 4,999</td>
                                <td>Discounted Shipping</td>
                                <td>Economy: Free, Standard: Rs. 150, Express: Rs. 350</td>
                            </tr>
                            <tr>
                                <td>Rs. 5,000 or Above</td>
                                <td>Free Shipping</td>
                                <td>Economy & Standard: Free, Express: Rs. 200</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="policy-section">
                <h2>Important Shipping Information</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="fas fa-map-pin"></i> Accurate Address</h4>
                        <p>Please provide a complete and accurate delivery address to avoid delays or failed deliveries.</p>
                    </div>

                    <div class="info-card">
                        <h4><i class="fas fa-phone"></i> Contact Number</h4>
                        <p>Ensure your contact number is correct so the courier can reach you for delivery.</p>
                    </div>

                    <div class="info-card">
                        <h4><i class="fas fa-box-open"></i> Package Inspection</h4>
                        <p>Always inspect the package before accepting. Report any damage to our support team immediately.</p>
                    </div>

                    <div class="info-card">
                        <h4><i class="fas fa-lock"></i> Secure Packaging</h4>
                        <p>We use secure and eco-friendly packaging to protect your items during transit.</p>
                    </div>

                    <div class="info-card">
                        <h4><i class="fas fa-exclamation-triangle"></i> Damage Claims</h4>
                        <p>Report any damage within 24 hours with photos for claim processing.</p>
                    </div>

                    <div class="info-card">
                        <h4><i class="fas fa-ban"></i> Refused Delivery</h4>
                        <p>If you refuse delivery, return shipping charges will apply to your account.</p>
                    </div>
                </div>
            </div>

            <div class="policy-section">
                <h2>Lost or Damaged Packages</h2>
                <p>
                    In case your package is lost or damaged during transit:
                </p>
                <ul>
                    <li>Contact our support team immediately with your order number</li>
                    <li>Provide photographic evidence of the damage</li>
                    <li>For lost packages, a claim will be filed with the courier</li>
                    <li>We will investigate and provide a replacement or refund</li>
                    <li>Compensation process typically takes 5-7 business days</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Failed Delivery Attempts</h2>
                <p>
                    If the courier is unable to deliver your package:
                </p>
                <ul>
                    <li>The courier will attempt delivery 2-3 times</li>
                    <li>You will receive notifications for each failed attempt</li>
                    <li>After 3 failed attempts, the package will be returned to us</li>
                    <li>A full refund will be issued for returned packages</li>
                    <li>Ensure your address and contact number are accurate to avoid failed deliveries</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Special Handling</h2>
                <p>
                    For special items or requests:
                </p>
                <ul>
                    <li>Contact us before placing your order for custom shipping requests</li>
                    <li>We can arrange signature-required delivery for valuable items</li>
                    <li>Gift wrapping and personalized messages are available (additional charges apply)</li>
                    <li>We can schedule delivery for specific dates if requested in advance</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Delays & Liability</h2>
                <p>
                    While we strive to deliver on time:
                </p>
                <ul>
                    <li>Delivery timelines are estimates and not guaranteed</li>
                    <li>Delays may occur due to weather, traffic, or courier issues</li>
                    <li>We are not liable for delays caused by external factors</li>
                    <li>For orders delayed beyond 10 days, contact our support team for assistance</li>
                </ul>
            </div>

            <div class="contact-banner">
                <p><i class="fas fa-headset"></i> Need help with shipping?</p>
                <p>Contact our support team at <a href="mailto:info@clothes.com">info@clothes.com</a> or call <a href="tel:+91-XXXXXXXXXX">+91-XXXXXXXXXX</a></p>
                <p style="margin-top: 1rem; font-size: 0.9rem;">Available Monday - Friday: 10 AM - 8 PM, Saturday - Sunday: 11 AM - 9 PM</p>
            </div>
        </div>
    </div>
</div>

@endsection
