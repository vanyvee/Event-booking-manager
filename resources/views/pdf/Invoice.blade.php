<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $event->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .invoice-box { border: 1px solid #ddd; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { padding: 8px; border: 1px solid #ddd; text-align: left; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Payment Invoice</h2>
        <p><strong>{{ $event->name }}</strong></p>
        <p>{{ $event->location }} — {{ $event->date->format('M d, Y') }}</p>
    </div>

    <div class="invoice-box">
        <p><strong>Invoice #:</strong> INV-{{ $booking->id }}</p>
        <p><strong>Date:</strong> {{ $booking->created_at->format('M d, Y') }}</p>
        <p><strong>Customer:</strong> {{ $user->name }} ({{ $user->email }})</p>

        <table>
            <thead>
                <tr>
                    <th>Ticket Type</th>
                    <th>Qty</th>
                    <th>Unit Price (₦)</th>
                    <th>Total (₦)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $ticketType->name }}</td>
                    <td>{{ $booking->quantity }}</td>
                    <td>{{ number_format($ticketType->price, 2) }}</td>
                    <td>{{ number_format($booking->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <p class="total">Amount Paid: ₦{{ number_format($payment->amount, 2) }}</p>
        <p>Status: <strong style="color: green;">{{ strtoupper($payment->status) }}</strong></p>
    </div>
</body>
</html>