<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            background: white;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 40px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
        }

        .shop-info h1 {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .shop-info p {
            color: #666;
            font-size: 11px;
            margin: 3px 0;
        }

        .invoice-title-section {
            text-align: right;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .invoice-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-block h3 {
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .info-block p {
            font-size: 11px;
            margin: 4px 0;
            color: #555;
        }

        .info-block strong {
            color: #2c3e50;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table thead {
            background: #2c3e50;
            color: white;
        }

        .items-table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #2c3e50;
        }

        .items-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 11px;
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

        /* Summary Section */
        .summary-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .payment-method-info {
            font-size: 11px;
            background: #f0f8ff;
            padding: 15px;
            border-left: 4px solid #2c3e50;
        }

        .payment-method-info p {
            margin: 5px 0;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        .summary-table .label {
            text-align: right;
            font-weight: bold;
            background: #f5f5f5;
            width: 60%;
        }

        .summary-table .value {
            text-align: right;
            font-weight: bold;
            color: #2c3e50;
        }

        .summary-table .total-row {
            background: #2c3e50;
            color: white;
        }

        .summary-table .total-row .label,
        .summary-table .total-row .value {
            background: #2c3e50;
            color: white;
            font-size: 13px;
        }

        /* Footer */
        .invoice-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .thank-you {
            text-align: center;
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .terms {
            background: #f9f9f9;
            padding: 10px;
            margin-top: 15px;
            border-left: 3px solid #2c3e50;
            font-size: 10px;
            color: #555;
        }

        /* Print specific styles */
        @media print {
            body {
                padding: 0;
            }

            .invoice-container {
                border: none;
                padding: 0;
                page-break-after: avoid;
            }
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="shop-info">
                <h1>🧵 TAILOR SHOP</h1>
                <p><strong>Professional Tailoring Services</strong></p>
                <p>📍 Address: Shop No. 123, Main Street, City</p>
                <p>📞 Phone: +92-300-1234567</p>
                <p>✉️ Email: info@tailorshop.com</p>
                <p>🕐 Hours: Mon-Sat 10AM-8PM</p>
            </div>
            <div class="invoice-title-section">
                <div class="invoice-title">INVOICE</div>
                <p style="color: #666; font-size: 12px;">
                    <strong>Invoice #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                    <strong>Time:</strong> {{ $order->created_at->format('h:i A') }}
                </p>
            </div>
        </div>

        <!-- Customer & Order Details -->
        <div class="invoice-details-grid">
            <!-- Customer Info -->
            <div class="info-block">
                <h3>BILL TO:</h3>
                <p><strong>{{ $order->user->name }}</strong></p>
                <p>Email: {{ $order->user->email }}</p>
                <p>Phone: {{ $order->user->phone ?? 'N/A' }}</p>
                <p>Address: {{ $order->user->address ?? 'N/A' }}</p>
            </div>

            <!-- Order Info -->
            <div class="info-block">
                <h3>ORDER DETAILS:</h3>
                <p><strong>Order #:</strong> {{ $order->order_number }}</p>
                <p><strong>Order Type:</strong> {{ $order->type_text }}</p>
                <p><strong>Status:</strong> <span class="badge badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">{{ $order->status_text }}</span></p>
                @if($order->delivery_date)
                <p><strong>Delivery Date:</strong> {{ $order->delivery_date->format('M d, Y') }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Description</th>
                    <th style="width: 15%" class="text-center;">Quantity</th>
                    <th style="width: 20%" class="text-right;">Unit Price</th>
                    <th style="width: 25%" class="text-right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->orderItems as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product->name ?? 'N/A' }}</strong><br>
                        <small style="color: #666;">
                            @if($item->product?->size)
                            Size: {{ $item->product->size }} |
                            @endif
                            @if($item->product?->color)
                            Color: {{ $item->product->color }}
                            @endif
                        </small>
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
                        <strong>Stitching Services</strong><br>
                        <small style="color: #666;">
                            Professional tailoring work<br>
                            @if($order->stitchingOrder->measurement)
                            Profile: {{ $order->stitchingOrder->measurement->profile_name }}
                            @endif
                        </small>
                    </td>
                    <td class="text-center">1</td>
                    <td class="text-right">PKR {{ number_format($order->stitching_charge, 2) }}</td>
                    <td class="text-right"><strong>PKR {{ number_format($order->stitching_charge, 2) }}</strong></td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-section">
            <!-- Payment Method Info -->
            <div class="payment-method-info">
                <p><strong>PAYMENT METHOD:</strong></p>
                <p>
                    @php
                        $methodIcons = [
                            'cash' => '💵',
                            'card' => '💳',
                            'bank_transfer' => '🏦',
                            'online' => '📱',
                        ];
                        $icon = $methodIcons[$order->payment_method] ?? '💰';
                    @endphp
                    {{ $icon }} {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'To be determined')) }}
                </p>
                <p style="margin-top: 10px;"><strong>PAYMENT STATUS:</strong></p>
                <p>
                    @if($order->payment_status === 'paid')
                    <span class="badge badge-success">✓ PAID</span>
                    @elseif($order->payment_status === 'pending')
                    <span class="badge badge-warning">⏳ PENDING</span>
                    @else
                    <span class="badge badge-danger">✗ FAILED</span>
                    @endif
                </p>
            </div>

            <!-- Summary Table -->
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
                    <td class="label">TOTAL AMOUNT:</td>
                    <td class="value">PKR {{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Payment History (if any) -->
        @if($order->payments->count() > 0)
        <div style="margin-top: 20px; font-size: 11px;">
            <strong>PAYMENT HISTORY:</strong>
            <table class="items-table" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th style="width: 20%;">Date</th>
                        <th style="width: 30%;">Method</th>
                        <th style="width: 20%;">Amount</th>
                        <th style="width: 30%;">Status</th>
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
        <div class="terms">
            <strong>SPECIAL INSTRUCTIONS:</strong><br>
            {{ $order->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="thank-you">🙏 Thank You for Your Business! 🙏</div>
            <p>
                This is a computer-generated invoice. No signature is required.<br>
                For any queries, contact us at info@tailorshop.com or +92-300-1234567<br>
                Generated on {{ now()->format('M d, Y h:i A') }}
            </p>
            <p style="margin-top: 10px; border-top: 1px solid #ddd; padding-top: 10px;">
                <strong>Terms & Conditions:</strong> Payment should be made as agreed. Returns/Refunds subject to shop policy.<br>
                All garments should be picked up within 30 days of completion.
            </p>
        </div>
    </div>
</body>
</html>
