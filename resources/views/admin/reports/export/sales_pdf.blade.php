<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #1a1a1a;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            color: #6c757d;
            margin: 5px 0;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .summary-card {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #0d6efd;
        }

        .summary-card label {
            display: block;
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .summary-card .value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th {
            background-color: #0d6efd;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0.5in;
            }

            body {
                background-color: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }

            .print-controls {
                display: none;
            }

            .header {
                page-break-after: avoid;
            }

            table {
                page-break-inside: avoid;
                margin-bottom: 30px;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigation Buttons -->
        <div style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e9ecef; display: flex; gap: 10px;">
            <button onclick="window.print()" style="padding: 8px 16px; background-color: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fas fa-print"></i> Print / Save as PDF
            </button>
            <a href="{{ route('admin.reports.sales') }}" style="padding: 8px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fas fa-arrow-left"></i> Back to Report
            </a>
            <a href="{{ route('admin.reports.index') }}" style="padding: 8px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>

        <!-- Header -->
        <div class="header">
            <h1>Sales Report</h1>
            <p>Generated on: <strong>{{ now()->format('F d, Y \a\t h:i A') }}</strong></p>
            @if(isset($startDate) && isset($endDate))
                <p>Period: <strong>{{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</strong></p>
            @endif
        </div>

        <!-- Summary Cards -->
        @if(isset($totalRevenue) || isset($totalOrders) || isset($avgOrderValue))
        <div class="summary">
            @if(isset($totalRevenue))
            <div class="summary-card">
                <label>Total Revenue</label>
                <div class="value">PKR {{ number_format($totalRevenue, 2) }}</div>
            </div>
            @endif

            @if(isset($totalOrders))
            <div class="summary-card">
                <label>Total Orders</label>
                <div class="value">{{ $totalOrders }}</div>
            </div>
            @endif

            @if(isset($avgOrderValue))
            <div class="summary-card">
                <label>Average Order Value</label>
                <div class="value">PKR {{ number_format($avgOrderValue, 2) }}</div>
            </div>
            @endif
        </div>
        @endif

        <!-- Data Table -->
        @if(isset($sales) && $sales->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th style="text-align: right;">Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                        <tr>
                            <td><strong>{{ $sale->order_number }}</strong></td>
                            <td>{{ $sale->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($sale->type === 'ready_made')
                                    <span style="display: inline-block; padding: 4px 8px; background-color: #e3f2fd; color: #1976d2; border-radius: 3px; font-size: 0.85rem;">Cloth</span>
                                @elseif($sale->type === 'stitching')
                                    <span style="display: inline-block; padding: 4px 8px; background-color: #fff3e0; color: #f57c00; border-radius: 3px; font-size: 0.85rem;">Stitching</span>
                                @else
                                    <span style="display: inline-block; padding: 4px 8px; background-color: #f3e5f5; color: #7b1fa2; border-radius: 3px; font-size: 0.85rem;">Both</span>
                                @endif
                            </td>
                            <td style="text-align: right;"><strong>PKR {{ number_format($sale->total, 2) }}</strong></td>
                            <td>
                                @php
                                    $badgeClass = match($sale->payment_status) {
                                        'paid' => 'background-color: #d4edda; color: #155724;',
                                        'partial' => 'background-color: #fff3cd; color: #856404;',
                                        'pending' => 'background-color: #f8d7da; color: #721c24;',
                                        default => 'background-color: #e2e3e5; color: #383d41;'
                                    };
                                @endphp
                                <span style="display: inline-block; padding: 4px 8px; {{ $badgeClass }} border-radius: 3px; font-size: 0.85rem; font-weight: 600;">
                                    {{ ucfirst(str_replace('_', ' ', $sale->payment_status)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <p><i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i></p>
                <p>No sales data available for the selected period.</p>
            </div>
        @endif
    </div>

    <script>
        // Only print when user clicks the button, not automatically
        // This allows the page to display first, then user can click Print
    </script>
</body>
</html>
