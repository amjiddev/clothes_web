@extends('frontend.layouts.app')

@section('title', 'Privacy Policy - CLOTHES STORE')

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
                <i class="fas fa-shield-alt"></i> Privacy Policy
            </h1>

            <div class="policy-section">
                <h2>Introduction</h2>
                <p>
                    CLOTHES STORE is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and otherwise process your information in connection with our website, mobile applications, and services.
                </p>
            </div>

            <div class="policy-section">
                <h2>Information We Collect</h2>
                <h3>1. Information You Provide Directly</h3>
                <ul>
                    <li>Account Registration: Full name, email address, phone number, password</li>
                    <li>Shipping Address: Street address, city, province, postal code</li>
                    <li>Payment Information: Credit card details, billing address (processed securely)</li>
                    <li>Communication: Emails, messages, customer support inquiries</li>
                    <li>Feedback: Survey responses, product reviews, feedback submissions</li>
                    <li>Measurements: Custom tailoring measurements and preferences</li>
                </ul>

                <h3>2. Information Collected Automatically</h3>
                <ul>
                    <li>Device Information: Device type, operating system, browser type</li>
                    <li>Usage Data: Pages visited, time spent, links clicked, search queries</li>
                    <li>Location Data: IP address, general geographic location</li>
                    <li>Cookies: Session data, preferences, browsing history</li>
                    <li>Log Data: Access times, server logs, error information</li>
                </ul>

                <h3>3. Information from Third Parties</h3>
                <ul>
                    <li>Payment Processors: Transaction verification and fraud prevention</li>
                    <li>Delivery Partners: Tracking information and delivery updates</li>
                    <li>Analytics Providers: Aggregated usage statistics</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>How We Use Your Information</h2>
                <p>We use collected information for the following purposes:</p>
                <ul>
                    <li>Order Processing: Fulfilling orders, processing payments, shipping</li>
                    <li>Account Management: User authentication, account maintenance</li>
                    <li>Communication: Order updates, customer support, promotional emails</li>
                    <li>Customization: Personalizing your shopping experience</li>
                    <li>Analytics: Understanding user behavior, improving services</li>
                    <li>Fraud Prevention: Detecting and preventing fraudulent transactions</li>
                    <li>Legal Compliance: Adhering to laws and regulations</li>
                    <li>Marketing: Sending promotional content (with consent)</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Data Security</h2>
                <p>
                    We implement industry-standard security measures to protect your personal information:
                </p>
                <ul>
                    <li>SSL/TLS Encryption: All data transmitted is encrypted</li>
                    <li>Secure Servers: Data stored on secure, protected servers</li>
                    <li>Access Control: Limited employee access to sensitive data</li>
                    <li>Regular Audits: Periodic security assessments and updates</li>
                    <li>PCI Compliance: Payment data handled per PCI DSS standards</li>
                </ul>
                <div class="highlight-box">
                    <p><i class="fas fa-info-circle"></i> No internet transmission is completely secure. While we strive to protect your data, we cannot guarantee absolute security.</p>
                </div>
            </div>

            <div class="policy-section">
                <h2>Information Sharing</h2>
                <h3>We Share Information With:</h3>
                <ul>
                    <li><strong>Service Providers:</strong> Payment processors, shipping companies, email services</li>
                    <li><strong>Legal Requirements:</strong> When required by law or court order</li>
                    <li><strong>Business Transfers:</strong> In case of merger, acquisition, or sale of assets</li>
                    <li><strong>With Consent:</strong> When you authorize information sharing</li>
                </ul>
                <h3>We Do NOT Share:</h3>
                <ul>
                    <li>Personal information for marketing to third parties without consent</li>
                    <li>Credit card details with unauthorized parties</li>
                    <li>Account passwords or sensitive credentials</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Your Rights and Choices</h2>
                <h3>Access and Control</h3>
                <ul>
                    <li>Access your personal information through your account</li>
                    <li>Request corrections to inaccurate information</li>
                    <li>Request deletion of your account and data</li>
                    <li>Export your personal data in a portable format</li>
                </ul>
                <h3>Marketing Communications</h3>
                <ul>
                    <li>Opt-out of promotional emails by clicking "Unsubscribe"</li>
                    <li>Manage communication preferences in your account settings</li>
                    <li>Request removal from mailing lists</li>
                </ul>
                <h3>Cookies</h3>
                <ul>
                    <li>Control cookies through your browser settings</li>
                    <li>Opt-out of tracking through privacy tools</li>
                    <li>Delete cookies from your device</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Data Retention</h2>
                <p>
                    We retain your personal information for as long as necessary to provide services and fulfill the purposes outlined in this policy:
                </p>
                <ul>
                    <li>Account Information: Retained until you delete your account</li>
                    <li>Order Information: Retained for 3 years for legal compliance</li>
                    <li>Marketing Data: Retained until you unsubscribe</li>
                    <li>Cookies: Session cookies deleted after session; persistent cookies expire after specified period</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Children's Privacy</h2>
                <p>
                    Our website is not intended for children under 13 years of age. We do not knowingly collect personal information from children. If we become aware that a child has provided us with personal information, we will promptly delete such information.
                </p>
            </div>

            <div class="policy-section">
                <h2>Third-Party Links</h2>
                <p>
                    Our website may contain links to third-party websites. We are not responsible for their privacy practices. Please review their privacy policies before providing any information.
                </p>
            </div>

            <div class="policy-section">
                <h2>International Data Transfers</h2>
                <p>
                    Your information may be transferred to, stored in, and processed in countries other than Pakistan. These countries may have data protection laws different from your home country. By using our services, you consent to such transfers.
                </p>
            </div>

            <div class="policy-section">
                <h2>Updates to This Policy</h2>
                <p>
                    We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated "Last Updated" date. Your continued use of our services constitutes acceptance of the updated policy.
                </p>
            </div>

            <div class="policy-section">
                <h2>Contact Us</h2>
                <p>
                    If you have questions about this Privacy Policy or our privacy practices:
                </p>
                <ul>
                    <li><i class="fas fa-envelope"></i> <strong>Email:</strong> <a href="mailto:info@clothes.com" style="color: var(--accent-gold); text-decoration: none;">info@clothes.com</a></li>
                    <li><i class="fas fa-phone"></i> <strong>Phone:</strong> <a href="tel:+91-XXXXXXXXXX" style="color: var(--accent-gold); text-decoration: none;">+91-XXXXXXXXXX</a></li>
                    <li><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> 123 Fashion Street, City Center</li>
                </ul>
            </div>

            <div class="last-updated">
                <p>For any questions or concerns about your privacy, please contact us using the information above.</p>
            </div>
        </div>
    </div>
</div>

@endsection
