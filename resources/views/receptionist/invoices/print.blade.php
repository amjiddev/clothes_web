<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }

        .print-container {
            background: white;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }

        .shop-logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .shop-logo {
            font-size: 36px;
            width: 60px;
            height: 60px;
            background: #2c3e50;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
        }

        .shop-info h1 {
            font-size: 26px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .shop-info p {
            color: #666;
            font-size: 12px;
            margin: 2px 0;
        }

        .invoice-title-area {
            text-align: right;
        }

        .invoice-title {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }

        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .detail-block {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #2c3e50;
        }

        .detail-block h3 {
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .detail-block p {
            font-size: 12px;
            margin: 5px 0;
            color: #555;
        }

        .detail-block strong {
            color: #2c3e50;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }

        .items-table thead {
            background: #2c3e50;
            color: white;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #2c3e50;
        }

        .items-table td {
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 12px;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .items-table .text-right {
            text-align: right;
        }

        .items-table .text-center {
            text-align: center;
        }

        .items-table .item-name {
            font-weight: bold;
            color: #2c3e50;
        }

        .items-table .item-desc {
            font-size: 11px;
            color: #999;
            margin-top: 3px;
        }

        /* Summary */
        .summary-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }

        .payment-info {
            background: #e8f4f8;
            padding: 20px;
            border-left: 4px solid #0288d1;
            border-radius: 3px;
        }

        .payment-info p {
            margin: 8px 0;
            font-size: 12px;
        }

        .payment-info strong {
            color: #0288d1;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table tr {
            border-bottom: 1px solid #ddd;
        }

        .summary-table td {
            padding: 12px 10px;
            font-size: 12px;
        }

        .summary-table .label {
            text-align: left;
            width: 60%;
            font-weight: 500;
        }

        .summary-table .value {
            text-align: right;
            font-weight: bold;
            color: #2c3e50;
        }

        .summary-table .total-row {
            background: #2c3e50;
            color: white;
            font-size: 14px;
        }

        .summary-table .total-row .label,
        .summary-table .total-row .value {
            background: #2c3e50;
            color: white;
            padding: 15px 10px;
            font-weight: bold;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Footer */
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }

        .footer-message {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            font-size: 11px;
            color: #666;
            text-align: center;
            margin-bottom: 20px;
        }

        .footer-note {
            background: #fffbea;
            padding: 15px;
            border-left: 4px solid #ff9800;
            font-size: 11px;
            color: #555;
            line-height: 1.6;
            margin-top: 15px;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .print-container {
                box-shadow: none;
                padding: 0;
                page-break-after: always;
            }

            .print-button {
                display: none;
            }

            a {
                text-decoration: none;
                color: inherit;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .print-button:hover {
            background: #1a252f;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print Invoice</button>

    <div class="print-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="shop-logo-area">
                <div class="shop-logo">🧵</div>
                <div class="shop-info">
                    <h1>TAILOR SHOP</h1>
                    <p>Professional Tailoring Services</p>
                    <p>📍 Shop No. 123, Main Street, City</p>
                    <p>📞 +92-300-1234567 | ✉️ info@tailorshop.com</p>
                </div>
            </div>
            <div class="invoice-title-area">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">
                    <strong>Invoice #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                    <strong>Time:</strong> {{ $order->created_at->format('h:i A') }}
                </div>
            </div>
        </div>

        <!-- Details Section -->
        <div class="details-grid">
            <div class="detail-block">
                <h3>📋 Bill To:</h3>
                <p><strong>{{ $order->user->name }}</strong></p>
                <p>{{ $order->user->email }}</p>
                <p>{{ $order->user->phone ?? 'N/A' }}</p>
                <p>{{ $order->user->address ?? 'N/A' }}</p>
            </div>

            <div class="detail-block">
                <h3>📦 Order Details:</h3>
                <p><strong>Order #:</strong> {{ $order->order_number }}</p>
                <p><strong>Type:</strong> {{ $order->type_text }}</p>
                <p><strong>Status:</strong> <span class="badge badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">{{ $order->status_text }}</span></p>
                @if($order->delivery_date)
                <p><strong>Delivery:</strong> {{ $order->delivery_date->format('M d, Y') }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Description</th>
                    <th style="width: 15%; text-align: center;">Qty</th>
                    <th style="width: 22.5%; text-align: right;">Unit Price</th>
                    <th style="width: 22.5%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->orderItems as $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item->product->name ?? 'N/A' }}</div>
                        <div class="item-desc">
                            @if($item->product?->size)Size: {{ $item->product->size }}@endif
                            @if($item->product?->color) | Color: {{ $item->product->color }}@endif
                        </div>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">PKR {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right"><strong>PKR {{ number_format($item->quantity * $item->unit_price, 2) }}</strong></td>
                </tr>
                @empty
                @endforelse

                @if($order->stitchingOrder)
                <tr>
                    <td>
                        <div class="item-name">Stitching Services</div>
                        <div class="item-desc">Professional tailoring work@if($order->stitchingOrder->measurement) | {{ $order->stitchingOrder->measurement->profile_name }}@endif</div>
                    </td>
                    <td class="text-center">1</td>
                    <td class="text-right">PKR {{ number_format($order->stitching_charge, 2) }}</td>
                    <td class="text-right"><strong>PKR {{ number_format($order->stitching_charge, 2) }}</strong></td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-container">
            <div class="payment-info">
                <p><strong style="font-size: 13px;">PAYMENT INFORMATION</strong></p>
                <p style="margin-top: 12px;">
                    <strong>Method:</strong> 
                    @php
                        $methods = [
                            'cash' => '💵 Cash',
                            'card' => '💳 Card',
                            'bank_transfer' => '🏦 Bank Transfer',
                            'online' => '📱 Online Payment',
                        ];
                    @endphp
                    {{ $methods[$order->payment_method] ?? '💰 To be Determined' }}
                </p>
                <p><strong>Status:</strong>
                    @if($order->payment_status === 'paid')
                    <span class="badge badge-success">✓ Paid</span>
                    @elseif($order->payment_status === 'pending')
                    <span class="badge badge-warning">⏳ Pending</span>
                    @else
                    <span class="badge badge-danger">✗ Failed</span>
                    @endif
                </p>
            </div>

            <table class="summary-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="value">PKR {{ number_format($order->subtotal ?? 0, 2) }}</td>
                </tr>
                @if($order->stitching_charge > 0)
                <tr>
                    <td class="label">Stitching Charge:</td>
                    <td class="value">PKR {{ number_format($order->stitching_charge, 2) }}</td>
                </tr>
                @endif
                @if($order->tax > 0)
                <tr>
                    <td class="label">Tax (GST/VAT):</td>
                    <td class="value">PKR {{ number_format($order->tax, 2) }}</td>
                </tr>
                @endif
                @if($order->discount > 0)
                <tr>
                    <td class="label">Discount:</td>
                    <td class="value">-PKR {{ number_format($order->discount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="label">TOTAL AMOUNT DUE:</td>
                    <td class="value">PKR {{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Payment History -->
        @if($order->payments->where('status', 'completed')->count() > 0)
        <div style="margin: 20px 0;">
            <h3 style="font-size: 12px; font-weight: bold; color: #2c3e50; margin-bottom: 10px;">PAYMENT HISTORY</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Date & Time</th>
                        <th style="width: 25%;">Method</th>
                        <th style="width: 25%;">Amount</th>
                        <th style="width: 25%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->payments->where('status', 'completed') as $payment)
                    <tr>
                        <td>{{ $payment->processed_at->format('M d, Y h:i A') }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        <td><strong>PKR {{ number_format($payment->amount, 2) }}</strong></td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Special Notes -->
        @if($order->notes)
        <div class="footer-note">
            <strong>📝 Special Instructions:</strong><br>
            {{ $order->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-message">🙏 Thank You for Your Business! 🙏</div>
            
            <div class="footer-content">
                <div>
                    <strong>Contact Us</strong><br>
                    info@tailorshop.com<br>
                    +92-300-1234567
                </div>
                <div>
                    <strong>Hours</strong><br>
                    Mon-Sat: 10AM-8PM<br>
                    Sunday: Closed
                </div>
                <div>
                    <strong>Generated</strong><br>
                    {{ now()->format('M d, Y') }}<br>
                    {{ now()->format('h:i A') }}
                </div>
            </div>

            <div class="footer-note" style="background: #f0f8ff; border-color: #0288d1; margin-top: 15px;">
                <strong>Terms & Conditions:</strong><br>
                • Payment should be made as per agreed terms • All garments must be picked up within 30 days of completion • Returns/Refunds subject to shop policy • This is a computer-generated invoice - no signature required
            </div>
        </div>
    </div>

    <script>
        // Auto-print for direct printing (uncomment to enable)
        // window.print();
    </script>
</body>
</html>
