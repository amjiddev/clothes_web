<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stitching Report</title>
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
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #ffc107;
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
            border-left: 4px solid #ffc107;
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
            background-color: #ffc107;
            color: #333;
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
        <div class="print-controls" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e9ecef; display: flex; gap: 10px;">
            <button onclick="window.print()" style="padding: 8px 16px; background-color: #ffc107; color: #333; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fas fa-print"></i> Print / Save as PDF
            </button>
            <a href="{{ route('admin.reports.stitching') }}" style="padding: 8px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fas fa-arrow-left"></i> Back to Report
            </a>
            <a href="{{ route('admin.reports.index') }}" style="padding: 8px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>

        <!-- Header -->
        <div class="header">
            <h1>Stitching Report</h1>
            <p>Generated on: <strong>{{ now()->format('F d, Y \a\t h:i A') }}</strong></p>
            @if(isset($startDate) && isset($endDate))
                <p>Period: <strong>{{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</strong></p>
            @endif
        </div>

        <!-- Summary Cards -->
        @if(isset($totalStitching) || isset($completedStitching) || isset($pendingStitching))
        <div class="summary">
            @if(isset($totalStitching))
            <div class="summary-card">
                <label>Total Stitching Orders</label>
                <div class="value">{{ $totalStitching }}</div>
            </div>
            @endif

            @if(isset($completedStitching))
            <div class="summary-card">
                <label>Completed</label>
                <div class="value">{{ $completedStitching }}</div>
            </div>
            @endif

            @if(isset($pendingStitching))
            <div class="summary-card">
                <label>Pending</label>
                <div class="value">{{ $pendingStitching }}</div>
            </div>
            @endif
        </div>
        @endif

        <!-- Data Table -->
        @if(isset($stitchingOrders) && $stitchingOrders->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Tailor</th>
                        <th>Date</th>
                        <th style="text-align: right;">Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stitchingOrders as $order)
                        <tr>
                            <td><strong>{{ $order->order?->order_number ?? 'N/A' }}</strong></td>
                            <td>{{ $order->order?->user?->name ?? 'N/A' }}</td>
                            <td>
                                @if($order->tailor)
                                    {{ $order->tailor->name }}
                                @else
                                    <span style="color: #999;">Unassigned</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td style="text-align: right;"><strong>PKR {{ number_format($order->order?->stitching_charge ?? 0, 2) }}</strong></td>
                            <td>
                                @php
                                    $statusBadgeClass = match($order->status) {
                                        'pending' => 'background-color: #fff3cd; color: #856404;',
                                        'assigned' => 'background-color: #d1ecf1; color: #0c5460;',
                                        'in_progress' => 'background-color: #cfe2ff; color: #084298;',
                                        'completed' => 'background-color: #d4edda; color: #155724;',
                                        'quality_check' => 'background-color: #fff3cd; color: #856404;',
                                        'ready_for_delivery' => 'background-color: #d1e7dd; color: #0f5132;',
                                        'delivered' => 'background-color: #d4edda; color: #155724;',
                                        'cancelled' => 'background-color: #f8d7da; color: #721c24;',
                                        default => 'background-color: #e2e3e5; color: #383d41;'
                                    };
                                @endphp
                                <span style="display: inline-block; padding: 4px 8px; {{ $statusBadgeClass }} border-radius: 3px; font-size: 0.85rem; font-weight: 600;">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <p><i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i></p>
                <p>No stitching orders data available for the selected period.</p>
            </div>
        @endif
    </div>

    <script>
        // Only print when user clicks the button, not automatically
        // This allows the page to display first, then user can click Print
    </script>
</body>
</html>
