<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Order #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</title>
    <link rel="icon" href="{{ asset('logo.jpg') }}" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .receipt-container { box-shadow: none !important; border: 2px solid #000 !important; }
            .receipt-header { background: #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .receipt-body { padding: 1rem !important; }
            .bg-gray-50, .total-section { background: #f5f5f5 !important; }
            .badge { background: #e5e5e5 !important; color: #000 !important; border: 1px solid #000 !important; }
        }
        
        body {
            background-color: #e8e8e8;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 2rem;
        }
        
        .receipt-container {
            background: #fafafa;
            border-radius: 0;
            box-shadow: 0 0 0 1px #000;
            max-width: 400px;
            width: 100%;
            overflow: hidden;
        }
        
        .receipt-header {
            background: #1a1a1a;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        
        .receipt-body {
            padding: 1.5rem;
        }
        
        .receipt-footer {
            background: #fafafa;
            padding: 1rem;
            text-align: center;
            border-top: 1px solid #ccc;
        }
        
        .divider {
            height: 1px;
            background: #000;
            margin: 1rem 0;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #ccc;
        }
        
        .item-row:last-child {
            border-bottom: none;
        }
        
        .total-section {
            background: #f5f5f5;
            border-radius: 0;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid #ccc;
        }
        
        .print-btn {
            background: #1a1a1a;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .print-btn:hover {
            background: #333;
            transform: translateY(-1px);
        }
        
        .badge {
            background: #f5f5f5;
            color: #000;
            border: 1px solid #000;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <div class="flex items-center justify-center gap-2 mb-2">
                <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-12 h-12 object-contain rounded-lg bg-white p-1">
                <h1 class="text-xl font-bold">LAUNDRY RECEIPT</h1>
            </div>
            <p class="text-sm opacity-90">Order #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
        </div>
        
        <!-- Body -->
        <div class="receipt-body">
            <!-- Customer Info -->
            <div style="background: #f5f5f5; padding: 0.75rem; margin-bottom: 1rem; border: 1px solid #000; border-radius: 0.5rem;">
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs">Date</p>
                        <p class="font-medium">{{ $order->created_at->format('M d, Y') }}</p>
                        <p class="text-gray-500 text-xs">{{ $order->created_at->format('h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Branch</p>
                        <p class="font-medium">{{ $order->branch ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Service Type Badge -->
            @php
                $serviceTypes = is_array($order->service_type) ? $order->service_type : json_decode($order->service_type, true);
                if (is_string($serviceTypes)) {
                    $serviceTypes = [$serviceTypes];
                }
            @endphp
            @if($serviceTypes && count($serviceTypes) > 0)
            <div class="text-center mb-4">
                @foreach($serviceTypes as $serviceType)
                    <span class="badge">{{ ucfirst(str_replace('_', ' ', $serviceType)) }}</span>
                @endforeach
            </div>
            @endif

            <div class="mb-4">
                <p class="text-gray-500 text-xs">Customer Name</p>
                <p class="font-semibold text-lg">{{ $order->customer->name }}</p>
                <p class="text-gray-600 text-sm">{{ $order->customer->phone }}</p>
            </div>
            
            <div class="divider"></div>
            
            <!-- Items -->
            <div class="mb-4">
                <p class="text-gray-500 text-xs font-semibold uppercase mb-2">Items</p>
                @foreach($order->items as $item)
                <div class="item-row">
                    <div>
                        <p class="font-medium">{{ $item->name }}</p>
                        <p class="text-gray-500 text-xs">GH₵{{ number_format($item->pivot->unit_price, 2) }} x {{ $item->pivot->quantity }}</p>
                    </div>
                    <p class="font-semibold">GH₵{{ number_format($item->pivot->subtotal, 2) }}</p>
                </div>
                @endforeach
            </div>
            
            <!-- Totals -->
            <div class="total-section">
                <div class="flex justify-between mb-1">
                    <span class="text-gray-600">Total</span>
                    <span class="font-bold">GH₵{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between mb-1">
                    <span class="text-gray-600">Paid</span>
                    <span class="text-green-600">GH₵{{ number_format($order->amount_paid, 2) }}</span>
                </div>
                <div class="divider my-2"></div>
                <div class="flex justify-between">
                    <span class="font-semibold">Balance</span>
                    <span class="font-bold text-lg {{ $order->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                        GH₵{{ number_format($order->balance, 2) }}
                    </span>
                </div>
                @if($order->mode_of_payment)
                <div class="flex justify-between mt-2">
                    <span class="font-semibold">Mode of Payment</span>
                    <span class="font-bold">
                        @if($order->mode_of_payment === 'Cash')
                            💵 Cash
                        @elseif($order->mode_of_payment === 'MoMo')
                            📱 MoMo
                        @else
                            🏦 Bank
                        @endif
                    </span>
                </div>
                @endif
            </div>
            
            <!-- Status Badge -->
            <div class="mt-4 text-center">
                <span class="badge">
                    @if($order->status === 'ready')
                        Ready for Pickup
                    @elseif($order->status === 'completed')
                        Completed
                    @else
                        {{ ucfirst($order->status) }}
                    @endif
                </span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="receipt-footer no-print">
            <p class="text-gray-600 font-medium mb-2">Thank you for your business!</p>
            <p class="text-gray-500 text-xs mb-4">Please come again</p>
            
            <button onclick="window.print()" class="print-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Receipt
            </button>
        </div>
    </div>
</body>
</html>
