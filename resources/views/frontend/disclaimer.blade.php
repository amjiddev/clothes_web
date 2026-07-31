@extends('frontend.layouts.app')

@section('title', 'Disclaimer - CLOTHES STORE')

@section('content')
<style>
    .disclaimer-container {
        background: #f8f5ef;
        padding: 60px 0;
        min-height: calc(100vh - 200px);
    }

    .disclaimer-content {
        background: white;
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--accent-gold);
    }

    .disclaimer-content h1 {
        font-size: 2.5rem;
        font-family: 'Playfair Display', serif;
        color: var(--primary-dark);
        margin-bottom: 1rem;
        border-bottom: 3px solid var(--accent-gold);
        padding-bottom: 1rem;
    }

    .disclaimer-content h2 {
        font-size: 1.5rem;
        color: var(--primary-dark);
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .disclaimer-content p {
        color: var(--text-muted);
        line-height: 1.8;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .disclaimer-content ul {
        list-style: none;
        padding-left: 0;
    }

    .disclaimer-content ul li {
        color: var(--text-muted);
        margin-bottom: 0.8rem;
        padding-left: 25px;
        position: relative;
        line-height: 1.8;
    }

    .disclaimer-content ul li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: var(--accent-gold);
        font-weight: bold;
    }

    .disclaimer-section {
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .disclaimer-section:last-child {
        border-bottom: none;
    }

    .important-note {
        background: rgba(212, 175, 55, 0.1);
        border-left: 4px solid var(--accent-gold);
        padding: 1.5rem;
        border-radius: 5px;
        margin: 2rem 0;
    }

    .important-note p {
        margin: 0;
        color: var(--primary-dark);
        font-weight: 500;
    }

    .last-updated {
        text-align: center;
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #e0e0e0;
    }

    @media (max-width: 768px) {
        .disclaimer-content {
            padding: 25px;
        }

        .disclaimer-content h1 {
            font-size: 1.8rem;
        }

        .disclaimer-content h2 {
            font-size: 1.2rem;
        }
    }
</style>

<div class="disclaimer-container">
    <div class="container">
        <div class="disclaimer-content">
            <h1>
                <i class="fas fa-shield-alt"></i> Disclaimer
            </h1>

            <div class="disclaimer-section">
                <h2>General Statement</h2>
                <p>
                    All information and content available on the CLOTHES STORE website is provided on an "as is" basis. We provide this information without any warranty or condition of any kind. We try to keep all website information accurate and updated, but errors or changes may occasionally occur.
                </p>
            </div>

            <div class="disclaimer-section">
                <h2>General Information</h2>
                <p>
                    We strive to maintain accurate and up-to-date information on our website. However, please be aware:
                </p>
                <ul>
                    <li>Errors or changes may occasionally occur in product details or pricing</li>
                    <li>We continuously work to correct any inaccuracies</li>
                    <li>Information is subject to change without prior notice</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Product Accuracy</h2>
                <p>
                    Although we strive to ensure that product details on our website are accurate and up-to-date, please note:
                </p>
                <ul>
                    <li>We do not accept responsibility for any errors, omissions, or inaccuracies</li>
                    <li>Minor variations in color, size, and texture may occur</li>
                    <li>Stock availability may change in real-time</li>
                    <li>Images are provided for reference purposes only</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Product Images and Colors</h2>
                <p>
                    Product colors and appearance may vary from what you see on your screen:
                </p>
                <ul>
                    <li>Color variations may occur due to lighting conditions during photography</li>
                    <li>Screen settings and display calibration affect how colors appear</li>
                    <li>Actual product appearance may differ slightly from product images</li>
                    <li>We recommend checking product reviews for accurate color descriptions</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Fabric Variations</h2>
                <p>
                    Minor differences in fabric characteristics are normal and acceptable:
                </p>
                <ul>
                    <li>Minor variations in fabric texture are expected</li>
                    <li>Print patterns and embroidery may show slight variations</li>
                    <li>Dye and finishing processes may result in natural variations</li>
                    <li>These variations do not indicate a defect</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Ready-to-Wear Sizes</h2>
                <p>
                    Sizing can vary significantly across different brands and designs:
                </p>
                <ul>
                    <li>Sizes and fitting vary by brand, design, and cut</li>
                    <li>Always review the detailed size chart before ordering</li>
                    <li>Different fabrics may have different fits</li>
                    <li>Contact us for size recommendations if unsure</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Custom Tailoring</h2>
                <p>
                    For custom tailoring services, please note:
                </p>
                <ul>
                    <li>Incorrect or incomplete customer measurements may cause fitting issues</li>
                    <li>Accurate measurements are essential for proper fit</li>
                    <li>Alteration charges may apply for fitting adjustments</li>
                    <li>Please provide clear specifications and requirements</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Pricing and Availability</h2>
                <p>
                    All prices and availability are subject to change without notice. We:
                </p>
                <ul>
                    <li>Do not assume responsibility for changes made before or after any purchase or order</li>
                    <li>Reserve the right to not sell products that are listed in error</li>
                    <li>May correct pricing errors at any time</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Product Availability</h2>
                <p>
                    Please be aware of the following regarding product availability:
                </p>
                <ul>
                    <li>Stock and supplier availability may change unexpectedly</li>
                    <li>If an item becomes unavailable, we will offer a suitable solution</li>
                    <li>We may provide a replacement item or full refund</li>
                    <li>Customers will be notified promptly of any availability issues</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Prices and Order Confirmation</h2>
                <p>
                    Regarding pricing and order processing:
                </p>
                <ul>
                    <li>Prices, discounts, or product details may contain errors</li>
                    <li>Orders are processed only after final confirmation</li>
                    <li>Confirmation email will show the final price charged</li>
                    <li>We reserve the right to cancel orders with pricing errors</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Delivery and Processing Time</h2>
                <p>
                    Delivery timelines may vary based on several factors:
                </p>
                <ul>
                    <li>Delivery times vary depending on customer location</li>
                    <li>Stock availability affects processing time</li>
                    <li>Courier operations and weather conditions may cause delays</li>
                    <li>Custom tailoring requirements may extend processing time</li>
                    <li>Delivery times are estimates and not guaranteed</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Limitation of Liability</h2>
                <p>
                    CLOTHES STORE shall not be liable for any direct, indirect, special, or consequential damages, including but not limited to:
                </p>
                <ul>
                    <li>Loss of profits or revenue</li>
                    <li>Loss or corruption of data</li>
                    <li>Business interruption</li>
                    <li>Failure to access the website</li>
                    <li>Delays caused by events beyond our reasonable control</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Limitation of Liability and Updates</h2>
                <p>
                    Important information regarding our liability and policy updates:
                </p>
                <ul>
                    <li>We are not responsible for delays caused by events beyond our reasonable control</li>
                    <li>Force majeure events (natural disasters, pandemics, etc.) exempt us from liability</li>
                    <li>We may update this Disclaimer when necessary</li>
                    <li>Customer rights remain protected under applicable law</li>
                    <li>Policy updates will be posted on this page</li>
                    <li>Continued use of our website constitutes acceptance of updated terms</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Third-Party Links</h2>
                <p>
                    Our website may contain links to third-party websites. We:
                </p>
                <ul>
                    <li>Are not responsible for the content of external websites</li>
                    <li>Do not warrant the accuracy or integrity of those sites</li>
                    <li>Do not endorse by including links to external sites</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Third-Party Sellers and Brands</h2>
                <p>
                    Please note regarding third-party sellers:
                </p>
                <ul>
                    <li>The relevant seller may be responsible for product quality</li>
                    <li>Authenticity and warranty claims should be directed to the seller</li>
                    <li>Product fulfillment responsibility lies with the respective seller</li>
                    <li>We facilitate the transaction but are not liable for seller actions</li>
                </ul>
            </div>

            <div class="disclaimer-section">
                <h2>Independent Decision</h2>
                <p>
                    You are solely responsible for any decision or action taken based on information provided on our website.
                </p>
            </div>

            <div class="disclaimer-section">
                <h2>Governing Law</h2>
                <p>
                    This Disclaimer is governed by the laws applicable in Pakistan and all other applicable jurisdictions. If any provision of this Disclaimer is found to be unlawful, the remainder of the Disclaimer shall remain in effect.
                </p>
            </div>

            <div class="important-note">
                <p>
                    <i class="fas fa-exclamation-circle"></i>
                    This Disclaimer does not limit your rights as protected under applicable law.
                </p>
            </div>

            <div class="disclaimer-section">
                <h2>Changes to Disclaimer</h2>
                <p>
                    We reserve the right to modify this Disclaimer at any time without prior notice. Modified terms will be immediately applicable to you.
                </p>
            </div>

            <div class="disclaimer-section">
                <h2>Contact Information</h2>
                <p>
                    If you have any questions, please feel free to contact us:
                </p>
                <ul>
                    <li><i class="fas fa-envelope"></i> <strong>Email:</strong> info@clothes.com</li>
                    <li><i class="fas fa-phone"></i> <strong>Phone:</strong> +91-XXXXXXXXXX</li>
                    <li><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> 123 Fashion Street, City Center</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
