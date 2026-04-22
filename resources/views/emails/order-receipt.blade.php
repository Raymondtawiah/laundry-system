<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .receipt-container {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .receipt-header {
            background: linear-gradient(135deg, #1a1a1a, #333);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .receipt-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .receipt-header p {
            margin: 8px 0 0;
            opacity: 0.8;
        }
        .receipt-body {
            padding: 30px;
        }
        .info-box {
            background: #f8f8f8;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            color: #666;
            font-size: 14px;
        }
        .info-value {
            font-weight: 500;
        }
        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 20px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th {
            text-align: left;
            padding: 10px;
            background: #f0f0f0;
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .text-right span {
            display: inline-block;
        }
        .totals-section {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-row.grand-total {
            border-top: 2px solid #333;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: 600;
        }
        .balance-due {
            color: #dc3545;
        }
        .balance-paid {
            color: #28a745;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-in_progress { background: #cce5ff; color: #004085; }
        .status-ready { background: #d4edda; color: #155724; }
        .status-completed { background: #d1e7dd; color: #0f5132; }
        .receipt-footer {
            background: #f8f8f8;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }
        .footer-text {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>LAUNDRY RECEIPT</h1>
            <p>Order #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="receipt-body">
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ $order->created_at->format('M d, Y - h:i A') }}</span>
                </div>
                @if($order->branch)
                <div class="info-row">
                    <span class="info-label">Branch:</span>
                    <span class="info-value">{{ $order->branch }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Customer:</span>
                    <span class="info-value">{{ $order->customer->name }}</span>
                </div>
                @if($order->customer->phone)
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $order->customer->phone }}</span>
                </div>
                @endif
            </div>

            @php
                $serviceTypes = is_array($order->service_type) ? $order->service_type : json_decode($order->service_type, true);
                if (is_string($serviceTypes)) {
                    $serviceTypes = [$serviceTypes];
                }
            @endphp
            @if($serviceTypes && count($serviceTypes) > 0)
            <div style="text-align: center; margin-bottom: 20px;">
                @foreach($serviceTypes as $serviceType)
                    <span style="display: inline-block; padding: 4px 10px; background: #f0f0f0; border-radius: 4px; font-size: 12px; margin: 2px;">
                        {{ ucfirst(str_replace('_', ' ', $serviceType)) }}
                    </span>
                @endforeach
            </div>
            @endif

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item:</th>
                        <th class="text-right">Price:</th>
                        <th class="text-right">Qty:</th>
                        <th class="text-right">Subtotal:</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">GH₵{{ number_format($item->pivot->unit_price, 2) }}</td>
                        <td class="text-right">{{ $item->pivot->quantity }}</td>
                        <td class="text-right">GH₵{{ number_format($item->pivot->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals-section">
                <div class="total-row">
                    <span>Total Amount:</span>
                    <span>GH₵{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Amount Paid:</span>
                    <span style="color: #28a745;">GH₵{{ number_format($order->amount_paid, 2) }}</span>
                </div>
                <div class="divider" style="margin: 10px 0;"></div>
                <div class="total-row grand-total">
                    <span>Balance:</span>
                    <span class="{{ $order->balance > 0 ? 'balance-due' : 'balance-paid' }}">
                        GH₵{{ number_format($order->balance, 2) }}
                    </span>
                </div>
                @if($order->mode_of_payment)
                <div class="total-row" style="margin-top: 10px; font-size: 14px;">
                    <span>Payment Method:</span>
                    <span>{{ $order->mode_of_payment }}</span>
                </div>
                @endif
            </div>

            <div style="text-align: center; margin-top: 25px;">
                <span class="status-badge status-{{ $order->status }}">
                    @if($order->status === 'ready')
                        Ready for Pickup
                    @elseif($order->status === 'completed')
                        Completed
                    @else
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    @endif
                </span>
            </div>
        </div>

        <div class="receipt-footer">
            <p class="footer-text">Thank you for your business!</p>
            <p class="footer-text" style="font-size: 12px; color: #999;">We look forward to serving you again.</p>
        </div>
    </div>
</body>
</html>