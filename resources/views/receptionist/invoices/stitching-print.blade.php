<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stitching Invoice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .invoice-container {
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .download-button {
            margin-bottom: 20px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
            color: #333;
        }
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .detail-section {
            margin-bottom: 20px;
        }
        .detail-section h6 {
            color: #666;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .detail-section p {
            margin: 5px 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
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
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .invoice-footer {
            text-align: center;
            margin-top: 30px;
            border-top: 2px solid #333;
            padding-top: 20px;
            color: #666;
        }
        @media print {
            .download-button {
                display: none;
            }
            body {
                background-color: white;
            }
            .invoice-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="download-button">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-download"></i> Download as PDF
        </button>
        <a href="{{ route('receptionist.invoices.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Invoices
        </a>
    </div>

    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Stitching Invoice</h1>
            <p>Invoice #{{ $stitchingOrder->id }}</p>
        </div>

        <div class="invoice-details">
            <div class="detail-section">
                <h6>From:</h6>
                <p><strong>{{ config('app.name', 'Clothes Shop') }}</strong></p>
                <p>Professional Stitching Services</p>
            </div>
            <div class="detail-section">
                <h6>Bill To:</h6>
                <p><strong>{{ $stitchingOrder->order->user?->name ?? 'Customer' }}</strong></p>
                <p>{{ $stitchingOrder->order->user?->email ?? 'N/A' }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $stitchingOrder->garment_type ?? 'Custom Stitching' }}</strong>
                        <br>
                        <small>{{ $stitchingOrder->service_option ?? 'Professional Stitching Service' }}</small>
                    </td>
                    <td>1</td>
                    <td>PKR {{ number_format($stitchingOrder->estimated_cost ?? 0, 2) }}</td>
                    <td>PKR {{ number_format($stitchingOrder->estimated_cost ?? 0, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total Amount:</td>
                    <td>PKR {{ number_format($stitchingOrder->estimated_cost ?? 0, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="detail-section">
            <h6>Stitching Details:</h6>
            <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $stitchingOrder->stitching_status)) }}</p>
            <p><strong>Assigned to:</strong> {{ $stitchingOrder->tailor?->name ?? 'Unassigned' }}</p>
            @if($stitchingOrder->start_date)
                <p><strong>Start Date:</strong> {{ $stitchingOrder->start_date->format('F d, Y') }}</p>
            @endif
            @if($stitchingOrder->completion_date)
                <p><strong>Completion Date:</strong> {{ $stitchingOrder->completion_date->format('F d, Y') }}</p>
            @endif
            @if($stitchingOrder->special_instructions)
                <p><strong>Special Instructions:</strong> {{ $stitchingOrder->special_instructions }}</p>
            @endif
        </div>

        <div class="invoice-footer">
            <p>Thank you for your business!</p>
            <p>Generated on: {{ now()->format('F d, Y H:i A') }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
