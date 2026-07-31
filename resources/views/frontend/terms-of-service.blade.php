@extends('frontend.layouts.app')

@section('title', 'Terms of Service - CLOTHES STORE')

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

    .last-updated {
        text-align: center;
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e0e0e0;
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
    }
</style>

<div class="policy-container">
    <div class="container">
        <div class="policy-content">
            <h1>
                <i class="fas fa-file-contract"></i> Terms of Service
            </h1>

            <div class="policy-section">
                <h2>Agreement to Terms</h2>
                <p>
                    Welcome to CLOTHES STORE. These Terms of Service constitute a legally binding agreement between you and CLOTHES STORE. By accessing, browsing, or using our website and services, you agree to be bound by these Terms.
                </p>
                <div class="highlight-box">
                    <p><i class="fas fa-exclamation-circle"></i> If you do not agree to these Terms, you may not use our website or services.</p>
                </div>
            </div>

            <div class="policy-section">
                <h2>Use License</h2>
                <p>
                    CLOTHES STORE grants you a limited, non-exclusive, revocable license to access and use our website and services for personal, non-commercial purposes, subject to the following restrictions:
                </p>
                <ul>
                    <li>You may not modify, reproduce, or transmit any content without permission</li>
                    <li>You may not use automated tools to scrape or collect data</li>
                    <li>You may not attempt to gain unauthorized access to our systems</li>
                    <li>You may not engage in any illegal or harmful activities</li>
                    <li>You may not interfere with the normal operation of our website</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>User Accounts</h2>
                <h3>Account Registration</h3>
                <ul>
                    <li>You must provide accurate and complete information during registration</li>
                    <li>You are responsible for maintaining confidentiality of your password</li>
                    <li>You are responsible for all activities under your account</li>
                    <li>You must notify us immediately of any unauthorized access</li>
                </ul>
                <h3>Account Termination</h3>
                <ul>
                    <li>We may suspend or terminate accounts that violate these Terms</li>
                    <li>We may terminate accounts for inactive periods (notified in advance)</li>
                    <li>You may delete your account anytime through account settings</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Product Information and Pricing</h2>
                <h3>Product Description</h3>
                <ul>
                    <li>We strive to provide accurate product descriptions and images</li>
                    <li>Minor variations in color, texture, and appearance may occur</li>
                    <li>We are not liable for discrepancies between descriptions and actual products</li>
                    <li>Product specifications are subject to change without notice</li>
                </ul>
                <h3>Pricing</h3>
                <ul>
                    <li>All prices are in Pakistani Rupees (PKR)</li>
                    <li>Prices are subject to change without notice</li>
                    <li>We reserve the right to correct pricing errors</li>
                    <li>Orders are processed only after payment is confirmed</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Ordering and Payment</h2>
                <h3>Order Placement</h3>
                <ul>
                    <li>Submitting an order constitutes an offer to purchase</li>
                    <li>We reserve the right to accept or reject any order</li>
                    <li>You will receive a confirmation email upon order acceptance</li>
                    <li>Orders cannot be modified after confirmation</li>
                </ul>
                <h3>Payment</h3>
                <ul>
                    <li>We accept credit cards, debit cards, and other payment methods</li>
                    <li>All payment information is processed securely and encrypted</li>
                    <li>You authorize us to charge your payment method</li>
                    <li>Fraudulent transactions will be reported to authorities</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Shipping and Delivery</h2>
                <ul>
                    <li>Delivery times are estimates and not guaranteed</li>
                    <li>Shipping costs are calculated based on location and weight</li>
                    <li>Risk of loss transfers to you upon delivery to courier</li>
                    <li>We are not liable for delays caused by courier services</li>
                    <li>International shipping is not currently available</li>
                    <li>Address accuracy is your responsibility</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Return and Refund Policy</h2>
                <ul>
                    <li>Returns must be initiated within 10 days of purchase</li>
                    <li>Items must be unused and in original condition</li>
                    <li>Original tags and packaging must be included</li>
                    <li>Return shipping is the customer's responsibility (except defects)</li>
                    <li>Refunds are processed within 5-10 business days after inspection</li>
                    <li>Refer to our Return & Exchange Policy for full details</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Intellectual Property Rights</h2>
                <ul>
                    <li>All content on our website is our property or licensed to us</li>
                    <li>You may not reproduce, distribute, or transmit content</li>
                    <li>Trademarks, logos, and brand names are protected</li>
                    <li>Unauthorized use of our intellectual property is prohibited</li>
                    <li>User-generated content remains your property, but we may use it</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>User Conduct</h2>
                <p>You agree not to:</p>
                <ul>
                    <li>Use abusive, threatening, or harassing language</li>
                    <li>Post defamatory, obscene, or offensive content</li>
                    <li>Violate any laws or regulations</li>
                    <li>Impersonate other users or entities</li>
                    <li>Engage in phishing, hacking, or unauthorized access</li>
                    <li>Spam or send unsolicited communications</li>
                    <li>Sell or resell our products without authorization</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Limitation of Liability</h2>
                <p>
                    To the fullest extent permitted by law, CLOTHES STORE shall not be liable for:
                </p>
                <ul>
                    <li>Direct, indirect, or consequential damages</li>
                    <li>Loss of profits, data, or revenue</li>
                    <li>Business interruption or service delays</li>
                    <li>Website downtime or technical failures</li>
                    <li>Third-party actions or courier services</li>
                    <li>Delays beyond our reasonable control</li>
                </ul>
                <p style="margin-top: 1rem;">
                    Our total liability shall not exceed the amount you paid for the product or service in question.
                </p>
            </div>

            <div class="policy-section">
                <h2>Disclaimer of Warranties</h2>
                <p>
                    Our website and services are provided on an "AS IS" and "AS AVAILABLE" basis. We make no warranties, express or implied, regarding:
                </p>
                <ul>
                    <li>Uninterrupted or error-free access to the website</li>
                    <li>Accuracy or completeness of information</li>
                    <li>Product suitability for your specific purposes</li>
                    <li>Third-party products or services</li>
                </ul>
                <p style="margin-top: 1rem;">
                    All implied warranties of merchantability, fitness, and non-infringement are disclaimed to the extent permitted by law.
                </p>
            </div>

            <div class="policy-section">
                <h2>Indemnification</h2>
                <p>
                    You agree to indemnify and hold harmless CLOTHES STORE, its owners, officers, employees, and agents from any claims, damages, or liabilities arising from:
                </p>
                <ul>
                    <li>Your violation of these Terms</li>
                    <li>Your use of our website or services</li>
                    <li>Your violation of any applicable law</li>
                    <li>Your infringement of third-party rights</li>
                    <li>User-generated content you provide</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Governing Law and Dispute Resolution</h2>
                <p>
                    These Terms are governed by the laws of Pakistan and all applicable international laws. Any disputes arising from these Terms shall be resolved through:
                </p>
                <ul>
                    <li>Negotiation and good faith discussion first</li>
                    <li>Mediation if negotiation fails</li>
                    <li>Arbitration or litigation as a last resort</li>
                </ul>
                <p style="margin-top: 1rem;">
                    Both parties agree to submit to the jurisdiction of Pakistani courts.
                </p>
            </div>

            <div class="policy-section">
                <h2>Modifications to Terms</h2>
                <p>
                    We may modify these Terms at any time without prior notice. Your continued use of our website and services after changes constitutes acceptance of the modified Terms. We recommend reviewing these Terms periodically.
                </p>
            </div>

            <div class="policy-section">
                <h2>Severability</h2>
                <p>
                    If any provision of these Terms is found to be invalid or unenforceable, such provision shall be modified to the minimum extent necessary to make it valid, or if impossible, shall be severed. All other provisions remain in full effect.
                </p>
            </div>

            <div class="policy-section">
                <h2>Contact Information</h2>
                <p>
                    For questions about these Terms of Service or to report violations:
                </p>
                <ul>
                    <li><i class="fas fa-envelope"></i> <strong>Email:</strong> <a href="mailto:info@clothes.com" style="color: var(--accent-gold); text-decoration: none;">info@clothes.com</a></li>
                    <li><i class="fas fa-phone"></i> <strong>Phone:</strong> <a href="tel:+91-XXXXXXXXXX" style="color: var(--accent-gold); text-decoration: none;">+91-XXXXXXXXXX</a></li>
                    <li><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> 123 Fashion Street, City Center</li>
                </ul>
            </div>

            <div class="last-updated">
                <p style="margin-top: 1rem; font-weight: 600;">By using CLOTHES STORE, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.</p>
            </div>
        </div>
    </div>
</div>

@endsection
