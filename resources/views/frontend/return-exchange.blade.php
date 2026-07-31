@extends('frontend.layouts.app')

@section('title', 'Return & Exchange Policy - CLOTHES STORE')

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

    .steps-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .step-card {
        background: #f9f9f9;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .step-card:hover {
        border-color: var(--accent-gold);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
    }

    .step-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        background: var(--accent-gold);
        color: var(--primary-dark);
        border-radius: 50%;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .step-card h3 {
        margin-top: 0;
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

    .faq-item {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .faq-item:last-child {
        border-bottom: none;
    }

    .faq-question {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 0.8rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .faq-icon {
        color: var(--accent-gold);
        font-size: 1.3rem;
    }

    .faq-answer {
        color: var(--text-muted);
        line-height: 1.8;
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

        .steps-container {
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
                <i class="fas fa-exchange-alt"></i> Return & Exchange Policy
            </h1>

            <div class="policy-section">
                <h2>Overview</h2>
                <p>
                    At CLOTHES STORE, we want you to be completely satisfied with your purchase. If you're not happy with your product, we offer a hassle-free return and exchange policy. Please read the policy carefully to understand the terms and conditions.
                </p>
            </div>

            <div class="policy-section">
                <h2>Return Window</h2>
                <p>
                    You have <strong>10 days</strong> from the date of purchase to return or exchange your product. Items must be returned within this timeframe to be eligible for return or exchange.
                </p>
                <div class="highlight-box">
                    <p><i class="fas fa-clock"></i> Returns must be initiated within 10 days of purchase date.</p>
                </div>
            </div>

            <div class="policy-section">
                <h2>Return & Exchange Process</h2>
                <h3>Step-by-Step Guide:</h3>
                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3>Contact Us</h3>
                        <p>Send an email to info@clothes.com with your order number and reason for return.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3>Get Approval</h3>
                        <p>Our team will verify your request and provide you with a return authorization number.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3>Ship the Item</h3>
                        <p>Pack the item securely and ship it to the address provided. Use tracking for safety.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h3>Processing</h3>
                        <p>Once received, we'll inspect the item and process your return/exchange within 7-10 business days.</p>
                    </div>
                </div>
            </div>

            <div class="policy-section">
                <h2>Eligibility Conditions</h2>
                <p>To be eligible for a return or exchange, your item must meet the following conditions:</p>
                <ul>
                    <li>Returned within 10 days of purchase</li>
                    <li>Unused and in original, unworn condition</li>
                    <li>All original tags and labels must be attached</li>
                    <li>Original packaging and documentation included</li>
                    <li>No signs of wear, damage, or alteration</li>
                    <li>Item has not been washed or dry-cleaned</li>
                    <li>No stains, odors, or defects caused by the customer</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Non-Returnable Items</h2>
                <p>The following items cannot be returned or exchanged:</p>
                <ul>
                    <li>Custom-tailored or made-to-order clothing</li>
                    <li>Items purchased during final sale or clearance events</li>
                    <li>Intimate apparel or undergarments</li>
                    <li>Items with defaced or removed labels</li>
                    <li>Items showing signs of wear or use</li>
                    <li>Products purchased more than 10 days ago</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Return & Refund Details</h2>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Scenario</th>
                                <th>Refund Amount</th>
                                <th>Processing Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Full Refund (Original Condition)</td>
                                <td>100% Product Cost</td>
                                <td>5-10 Business Days</td>
                            </tr>
                            <tr>
                                <td>Refund (Minor Wear)</td>
                                <td>80% Product Cost</td>
                                <td>5-10 Business Days</td>
                            </tr>
                            <tr>
                                <td>Defective Item (Our Fault)</td>
                                <td>Full Refund or Free Exchange</td>
                                <td>Immediate</td>
                            </tr>
                            <tr>
                                <td>Shipping Costs</td>
                                <td>Not Refunded</td>
                                <td>N/A</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="policy-section">
                <h2>Exchange Process</h2>
                <p>
                    To exchange an item for a different size, color, or style:
                </p>
                <ul>
                    <li>Contact us within 10 days of purchase with exchange details</li>
                    <li>Include your original order number and the reason for exchange</li>
                    <li>Ship the item in original condition with all tags attached</li>
                    <li>Once received and verified, we'll ship your replacement item immediately</li>
                    <li>If the new item is more expensive, you'll be charged the difference</li>
                    <li>If the new item is less expensive, no refund will be issued</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Shipping & Handling</h2>
                <ul>
                    <li>Original shipping charges are non-refundable</li>
                    <li>Return shipping is the customer's responsibility</li>
                    <li>We recommend using a tracked shipping method</li>
                    <li>Items should be insured against loss or damage</li>
                    <li>Please keep proof of return shipping for your records</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Quality Guarantee</h2>
                <p>
                    If you receive a defective or damaged item due to our fault:
                </p>
                <ul>
                    <li>Report the issue within 7 days of receipt</li>
                    <li>Provide photos of the damage as evidence</li>
                    <li>We will issue a full refund or send a replacement at no cost</li>
                    <li>Return shipping will be covered by us</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2>Frequently Asked Questions</h2>

                <div class="faq-item">
                    <div class="faq-question">
                        <span class="faq-icon"><i class="fas fa-question-circle"></i></span>
                        Can I return an item without tags?
                    </div>
                    <div class="faq-answer">
                        No, items must have original tags attached to be eligible for return. Items with removed or damaged tags may be rejected.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span class="faq-icon"><i class="fas fa-question-circle"></i></span>
                        How long does refund processing take?
                    </div>
                    <div class="faq-answer">
                        Once we receive and verify your return, refunds are typically processed within 5-10 business days. The time may vary depending on your bank.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span class="faq-icon"><i class="fas fa-question-circle"></i></span>
                        Who pays for return shipping?
                    </div>
                    <div class="faq-answer">
                        Return shipping is the customer's responsibility, except in cases where the item is defective due to our fault.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span class="faq-icon"><i class="fas fa-question-circle"></i></span>
                        Can I exchange for a different size?
                    </div>
                    <div class="faq-answer">
                        Yes, you can exchange for a different size within 10 days of purchase. The exchange process is the same as returns.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span class="faq-icon"><i class="fas fa-question-circle"></i></span>
                        What if the new size is out of stock?
                    </div>
                    <div class="faq-answer">
                        If your desired size is out of stock, we can either wait for it to be back in stock or process a full refund.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span class="faq-icon"><i class="fas fa-question-circle"></i></span>
                        Are custom tailored items returnable?
                    </div>
                    <div class="faq-answer">
                        No, custom-tailored or made-to-order items cannot be returned or exchanged as they are specially made for you.
                    </div>
                </div>
            </div>

            <div class="contact-banner">
                <p><i class="fas fa-info-circle"></i> Have questions about our Return & Exchange policy?</p>
                <p>Contact us at <a href="mailto:info@clothes.com">info@clothes.com</a> or call <a href="tel:+91-XXXXXXXXXX">+91-XXXXXXXXXX</a></p>
                <p style="margin-top: 1rem; font-size: 0.9rem;">We're here to help Monday - Friday: 10 AM - 8 PM, Saturday - Sunday: 11 AM - 9 PM</p>
            </div>
        </div>
    </div>
</div>

@endsection
