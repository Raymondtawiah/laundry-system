<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Order #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .receipt-container { box-shadow: none !important; border: none !important; }
        }
        
        body {
            background-color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 2rem;
        }
        
        .receipt-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .receipt-body {
            padding: 1.5rem;
        }
        
        .receipt-footer {
            background: #f9fafb;
            padding: 1rem;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #059669, transparent);
            margin: 1rem 0;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #e5e7eb;
        }
        
        .item-row:last-child {
            border-bottom: none;
        }
        
        .total-section {
            background: #f0fdf4;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid #bbf7d0;
        }
        
        .print-btn {
            background: #059669;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .print-btn:hover {
            background: #047857;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <div class="flex items-center justify-center gap-2 mb-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h1 class="text-xl font-bold">LAUNDRY RECEIPT</h1>
            </div>
            <p class="text-sm opacity-90">Order #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
        </div>
        
        <!-- Body -->
        <div class="receipt-body">
            <!-- Customer Info -->
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
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
                    @switch($serviceType)
                        @case('washing')
                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-lg text-sm font-semibold mr-1 mb-1">🧥 Executive Wear</span>
                            @break
                        @case('ironing')
                            <span class="inline-block bg-purple-100 text-purple-800 px-3 py-1 rounded-lg text-sm font-semibold mr-1 mb-1">👘 Native Wear</span>
                            @break
                        @case('drying')
                            <span class="inline-block bg-pink-100 text-pink-800 px-3 py-1 rounded-lg text-sm font-semibold mr-1 mb-1">👗 Ladies Wear</span>
                            @break
                        @case('bag wash')
                            <span class="inline-block bg-amber-100 text-amber-800 px-3 py-1 rounded-lg text-sm font-semibold mr-1 mb-1">👜 Bag Wash</span>
                            @break
                        @case('bedding_decor')
                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-lg text-sm font-semibold mr-1 mb-1">🛏️ Bedding and Decor</span>
                            @break
                        @case('sneakers')
                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-lg text-sm font-semibold mr-1 mb-1">👟 Sneakers</span>
                            @break
                        @case('bag')
                            <span class="inline-block bg-orange-100 text-orange-800 px-3 py-1 rounded-lg text-sm font-semibold mr-1 mb-1">🎒 Bag</span>
                            @break
                        @case('deep_cleaning')
                            <span class="inline-block bg-cyan-100 text-cyan-800 px-3 py-1 rounded-lg text-sm font-semibold mr-1 mb-1">✨ Ironing</span>
                            @break
                        @default
                            <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-lg text-sm font-semibold mr-1 mb-1">{{ ucfirst(str_replace('_', ' ', $serviceType)) }}</span>
                    @endswitch
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
            </div>
            
            <!-- Status Badge -->
            <div class="mt-4 text-center">
                @if($order->status === 'ready')
                <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Ready for Pickup
                </span>
                @elseif($order->status === 'completed')
                <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Completed
                </span>
                @else
                <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ ucfirst($order->status) }}
                </span>
                @endif
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
