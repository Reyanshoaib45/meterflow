<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $application->application_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
        }
        .header .date {
            text-align: right;
        }
        .details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .details h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 10px;
        }
        .details p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th {
            background: #f5f5f5;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #333;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        table tfoot td {
            font-weight: bold;
            font-size: 18px;
            border-top: 2px solid #333;
            padding-top: 15px;
        }
        .instructions {
            background: #f0f7ff;
            border: 1px solid #b3d9ff;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .instructions h3 {
            margin-top: 0;
            color: #0066cc;
        }
        .instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        @media print {
            body {
                padding: 0;
            }
            .invoice-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div>
                <h1>Invoice</h1>
                <p style="color: #666; margin-top: 5px;">Application Fee Invoice</p>
            </div>
            <div class="date">
                <p style="color: #666; margin: 0;">Invoice Date</p>
                <p style="font-size: 18px; font-weight: bold; margin-top: 5px;">{{ now()->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="details">
            <div>
                <h3>From</h3>
                <p style="font-weight: bold; font-size: 16px;">{{ $application->company->name ?? 'MEPCO' }}</p>
                @if($application->subdivision)
                <p>{{ $application->subdivision->name }}</p>
                @endif
            </div>
            <div>
                <h3>Bill To</h3>
                <p style="font-weight: bold; font-size: 16px;">{{ $application->customer_name }}</p>
                @if($application->address)
                <p>{{ $application->address }}</p>
                @endif
                @if($application->phone)
                <p>Phone: {{ $application->phone }}</p>
                @endif
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 12px; text-transform: uppercase; color: #666; margin-bottom: 15px;">Application Details</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <p style="color: #666; margin: 0; font-size: 12px;">Application Number</p>
                    <p style="font-weight: bold; margin-top: 5px;">{{ $application->application_no }}</p>
                </div>
                @if($application->meter_number)
                <div>
                    <p style="color: #666; margin: 0; font-size: 12px;">Meter Number</p>
                    <p style="font-weight: bold; margin-top: 5px;">{{ $application->meter_number }}</p>
                </div>
                @endif
                @if($application->connection_type)
                <div>
                    <p style="color: #666; margin: 0; font-size: 12px;">Connection Type</p>
                    <p style="font-weight: bold; margin-top: 5px;">{{ ucfirst($application->connection_type) }}</p>
                </div>
                @endif
                <div>
                    <p style="color: #666; margin: 0; font-size: 12px;">Status</p>
                    <p style="font-weight: bold; margin-top: 5px; color: #059669;">{{ ucfirst($application->status) }}</p>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <p style="font-weight: bold; margin: 0;">Application Fee</p>
                        <p style="color: #666; font-size: 12px; margin: 5px 0 0 0;">Connection application processing fee</p>
                    </td>
                    <td style="text-align: right; font-weight: bold; font-size: 16px;">Rs. {{ number_format($application->fee_amount, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size: 18px;">Total Amount</td>
                    <td style="text-align: right; font-size: 24px;">Rs. {{ number_format($application->fee_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="instructions">
            <h3>Payment Instructions</h3>
            <ul>
                <li>Please pay this invoice amount at the designated MEPCO office or through authorized payment channels.</li>
                <li>Bring this invoice and your application number for payment reference.</li>
                <li>After payment, your application will proceed to the next stage.</li>
            </ul>
        </div>

        <div class="footer">
            <p>This is an official invoice generated by Meter Flow Nation (MEPCO).</p>
            <p style="margin-top: 5px;">For any queries, please contact your subdivision office.</p>
        </div>
    </div>
</body>
</html>

