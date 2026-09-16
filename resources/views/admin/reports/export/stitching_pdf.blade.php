<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stitching Report</title>
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
    <div class="download-button">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-download"></i> Download as PDF
        </button>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>
    <div class="header">
        <h1>Stitching Report</h1>
        <p>Generated on: {{ now()->format('F d, Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Stitching Orders:</strong>
            {{ isset($totalOrders) ? $totalOrders : 'N/A' }}
        </div>
        <div class="summary-item">
            <strong>Completed:</strong>
            {{ isset($completedOrders) ? $completedOrders : 'N/A' }}
        </div>
        <div class="summary-item">
            <strong>In Progress:</strong>
            {{ isset($inProgressOrders) ? $inProgressOrders : 'N/A' }}
        </div>
    </div>

    @if(isset($stitchingOrders) && $stitchingOrders->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Tailor</th>
                    <th>Start Date</th>
                    <th>Status</th>
                    <th>Estimated Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stitchingOrders as $stitching)
                    <tr>
                        <td>{{ $stitching->id }}</td>
                        <td>{{ $stitching->tailor?->name ?? 'Unassigned' }}</td>
                        <td>{{ $stitching->start_date ? $stitching->start_date->format('F d, Y') : 'Not Started' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $stitching->stitching_status)) }}</td>
                        <td>PKR {{ number_format($stitching->estimated_cost ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No stitching orders data available for the selected period.</p>
    @endif
</body>
</html>
