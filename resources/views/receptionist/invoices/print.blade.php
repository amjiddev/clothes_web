<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- Print-only header with logo and company name -->
<div class="print-header">
    <div class="print-logo-container">
        @if(config('app.logo'))
            <img src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}" class="print-logo">
        @else
            <div class="print-logo-placeholder">
                <i class="fas fa-store"></i>
            </div>
        @endif
        <div class="print-company-info">
            <h1 class="print-company-name">{{ config('app.name', 'Laravel') }}</h1>
            <p class="print-tagline">Tailoring & Fashion Store</p>
        </div>
    </div>
    <div class="print-invoice-title">
        <h2>ORDER INVOICE</h2>
        <p class="invoice-number">{{ $invoice->invoice_number }}</p>
        <p class="invoice-date">{{ $invoice->invoice_date->format('M d, Y') }}</p>
    </div>
</div>

<!-- Customer Information -->
<div class="customer-info-section">
    <div class="info-column">
        <p class="info-label">NAME</p>
        <p class="info-value">{{ $invoice->order->user->name }}</p>
        
        <p class="info-label">EMAIL</p>
        <p class="info-value">{{ $invoice->order->user->email }}</p>
        
        <p class="info-label">PHONE</p>
        <p class="info-value">{{ $invoice->order->user->phone ?? 'Not provided' }}</p>
    </div>
    <div class="info-column">
        <p class="info-label">CITY</p>
        <p class="info-value">{{ $invoice->order->user->address?->city ?? 'Not provided' }}</p>
        
        <p class="info-label">ADDRESS</p>
        <p class="info-value">{{ $invoice->order->user->address?->address_line_1 ?? 'Not provided' }}</p>
        
        <p class="info-label">CUSTOMER ID</p>
        <p class="info-value">#{{ $invoice->order->user->id }}</p>

        @if($invoice->order->stitchingOrder)
        <p class="info-label">GARMENT TYPE</p>
        <p class="info-value">{{ $invoice->order->stitchingOrder->garment_type ?? 'Not specified' }}</p>
        
        <p class="info-label">FABRIC TYPE</p>
        <p class="info-value">{{ $invoice->order->stitchingOrder->fabric_details ?? 'Not specified' }}</p>
        @endif
    </div>
</div>

<!-- Items Table -->
@if($invoice->order->orderItems->count() > 0)
<table class="items-table">
    <thead>
        <tr>
            <th>PRODUCT</th>
            <th>CATEGORY</th>
            <th style="text-align: center;">QUANTITY</th>
            <th style="text-align: right;">PRICE</th>
            <th style="text-align: right;">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->order->orderItems as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->product->category?->name ?? 'N/A' }}</td>
            <td style="text-align: center;">{{ $item->quantity }}</td>
            <td style="text-align: right;">Rs. {{ number_format($item->price, 2) }}</td>
            <td style="text-align: right;">Rs. {{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<!-- Totals Section -->
<div class="totals-section">
    <table class="totals-table">
        <tr>
            <td class="total-label">Subtotal</td>
            <td class="total-amount">Rs. {{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        @if($invoice->order->stitching_charge > 0)
        <tr>
            <td class="total-label">Stitching Charges</td>
            <td class="total-amount">Rs. {{ number_format($invoice->order->stitching_charge, 2) }}</td>
        </tr>
        @endif
        <tr class="grand-total-row">
            <td class="total-label"><strong>Total Amount</strong></td>
            <td class="total-amount"><strong>Rs. {{ number_format($invoice->total_amount, 2) }}</strong></td>
        </tr>
    </table>
</div>

<!-- Payment Status -->
@if($invoice->balance_due > 0)
<div class="balance-due-box">
    <p class="balance-label">AMOUNT DUE</p>
    <p class="balance-amount">Rs. {{ number_format($invoice->balance_due, 2) }}</p>
</div>
@else
<div class="paid-full-box">
    PAID IN FULL
</div>
@endif

<!-- Payment History -->
@if($invoice->payments->count() > 0)
<div class="payment-history-section">
    <h3>PAYMENT HISTORY</h3>
    @foreach($invoice->payments as $payment)
    <div class="payment-item">
        Rs. {{ number_format($payment->amount, 2) }} - {{ $payment->payment_method_text }} - {{ $payment->payment_date->format('M d, Y') }}
    </div>
    @endforeach
</div>
@endif

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    background: #ffffff !important;
}

@page { 
    size: A4; 
    margin: 0.5cm; 
}

body {
    font-family: Arial, sans-serif;
    font-size: 10pt;
    line-height: 1.4;
    color: #000;
    background: #ffffff !important;
    padding: 15px;
}

/* Print Header */
.print-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #000;
}

.print-logo-container {
    display: flex;
    align-items: center;
    gap: 12px;
}

.print-logo {
    max-width: 50px;
    max-height: 50px;
    object-fit: contain;
}

.print-logo-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    background: #000;
    border-radius: 4px;
}

.print-logo-placeholder i {
    font-size: 24px;
    color: #fff;
}

.print-company-name {
    font-size: 18pt;
    font-weight: bold;
    margin: 0;
    line-height: 1;
}

.print-tagline {
    font-size: 9pt;
    color: #0066cc;
    margin: 3px 0 0 0;
}

.print-invoice-title {
    text-align: right;
}

.print-invoice-title h2 {
    font-size: 16pt;
    font-weight: bold;
    margin: 0 0 5px 0;
}

.print-invoice-title .invoice-number,
.print-invoice-title .invoice-date {
    font-size: 9pt;
    margin: 2px 0;
}

/* Customer Info Section */
.customer-info-section {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.info-column {
    width: 48%;
}

.info-label {
    font-size: 8pt;
    color: #ff8c00;
    font-weight: bold;
    text-transform: uppercase;
    margin: 8px 0 2px 0;
}

.info-label:first-child {
    margin-top: 0;
}

.info-value {
    font-size: 9.5pt;
    margin: 0 0 0 0;
    color: #000;
}

/* Items Table */
.items-table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
}

.items-table thead {
    background: #fff;
}

.items-table th,
.items-table td {
    border: 1px solid #000;
    padding: 8px 10px;
    font-size: 9pt;
}

.items-table th {
    font-weight: bold;
    text-align: left;
    font-size: 8.5pt;
}

.items-table tbody tr:nth-child(even) {
    background: #f9f9f9;
}

/* Totals Section */
.totals-section {
    margin: 15px 0;
    text-align: right;
}

.totals-table {
    width: 350px;
    margin-left: auto;
    border-collapse: collapse;
}

.totals-table td {
    padding: 6px 10px;
    font-size: 9.5pt;
}

.total-label {
    text-align: right;
    padding-right: 20px;
}

.total-amount {
    text-align: right;
    font-weight: bold;
}

.grand-total-row td {
    border-top: 2px solid #000;
    padding-top: 10px;
    font-size: 11pt;
}

/* Payment Status Boxes */
.balance-due-box {
    margin: 20px 0;
    padding: 15px;
    background: #fff3cd;
    border: 2px solid #000;
    text-align: center;
}

.balance-label {
    font-size: 10pt;
    font-weight: bold;
    margin-bottom: 5px;
}

.balance-amount {
    font-size: 18pt;
    font-weight: bold;
}

.paid-full-box {
    margin: 20px 0;
    padding: 15px;
    background: #d4edda;
    border: 2px solid #28a745;
    text-align: center;
    font-size: 14pt;
    font-weight: bold;
    color: #155724;
}

/* Payment History */
.payment-history-section {
    margin-top: 25px;
    padding-top: 15px;
    border-top: 1px solid #ddd;
}

.payment-history-section h3 {
    font-size: 10pt;
    margin-bottom: 10px;
    font-weight: bold;
}

.payment-item {
    padding: 8px;
    margin-bottom: 6px;
    background: #f8f9fa;
    border-left: 4px solid #28a745;
    font-size: 9pt;
}

@media print {
    html, body {
        background: #ffffff !important;
        width: 100%;
        height: 100%;
    }
    
    body {
        padding: 0;
    }
}
</style>

<script>
// Auto-trigger print dialog when page loads
window.onload = function() {
    window.print();
}
</script>

</body>
</html>
