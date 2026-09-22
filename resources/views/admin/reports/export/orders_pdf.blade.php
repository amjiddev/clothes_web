<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Orders Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .download-button {
            margin-bottom: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 40px;
        }
        .summary-item strong {
            display: block;
            color: #666;
        }
        @media print {
            .download-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="download-button" style="display: none;">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-download"></i> Download as PDF
        </button>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>
    <div class="header">
        <h1>Orders Report</h1>
        <p>Generated on: {{ now()->format('F d, Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Orders:</strong>
            {{ isset($totalOrders) ? $totalOrders : 'N/A' }}
        </div>
        <div class="summary-item">
            <strong>Total Value:</strong>
            {{ isset($totalValue) ? 'PKR ' . number_format($totalValue, 2) : 'N/A' }}
        </div>
        <div class="summary-item">
            <strong>Pending Orders:</strong>
            {{ isset($pendingOrders) ? $pendingOrders : 'N/A' }}
        </div>
    </div>

    @if(isset($orders) && $orders->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->user?->name ?? 'N/A' }}</td>
                        <td>{{ $order->created_at->format('F d, Y') }}</td>
                        <td>PKR {{ number_format($order->total, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No orders data available for the selected period.</p>
    @endif
    <script>
        // Auto-print when page loads
        window.addEventListener('load', function() {
            window.print();
            
            // When print dialog is closed/cancelled, go back to orders report
            window.addEventListener('afterprint', function() {
                window.location.href = "{{ route('admin.reports.orders') }}";
            });
        });
    </script>
</body>
</html>
