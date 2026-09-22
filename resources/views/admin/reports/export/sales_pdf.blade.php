<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
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
        <h1>Sales Report</h1>
        <p>Generated on: {{ now()->format('F d, Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Revenue:</strong>
            {{ isset($totalRevenue) ? 'PKR ' . number_format($totalRevenue, 2) : 'N/A' }}
        </div>
        <div class="summary-item">
            <strong>Total Orders:</strong>
            {{ isset($totalOrders) ? $totalOrders : 'N/A' }}
        </div>
        <div class="summary-item">
            <strong>Average Order Value:</strong>
            {{ isset($avgOrderValue) ? 'PKR ' . number_format($avgOrderValue, 2) : 'N/A' }}
        </div>
    </div>

    @if(isset($sales) && $sales->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->order_number }}</td>
                        <td>{{ $sale->created_at->format('F d, Y') }}</td>
                        <td>{{ ucfirst($sale->type) }}</td>
                        <td>PKR {{ number_format($sale->total, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $sale->payment_status)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No sales data available for the selected period.</p>
    @endif
    <script>
        // Auto-print when page loads
        window.addEventListener('load', function() {
            window.print();
            
            // When print dialog is closed/cancelled, go back to sales report
            window.addEventListener('afterprint', function() {
                window.location.href = "{{ route('admin.reports.sales') }}";
            });
        });
    </script>
</body>
</html>
