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
            body { 
                background: white !important; 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
                font-size: 10pt;
                line-height: 1.2;
            }
            .a4-container { 
                box-shadow: none !important; 
                border: 1px solid #ddd !important;
                margin: 0 !important;
                max-width: 100% !important;
                page-break-inside: avoid;
            }
            .print-header { 
                background: #1a1a1a !important; 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
                color: white !important;
            }
            .print-footer { 
                position: relative !important;
                bottom: auto !important;
                left: auto !important;
                right: auto !important;
                text-align: center; 
                font-size: 9pt;
                color: #666;
                page-break-inside: avoid;
            }
            .print-body {
                padding: 1rem !important;
            }
            .section-title {
                font-size: 11pt !important;
                margin-bottom: 0.5rem !important;
            }
            .info-card {
                padding: 0.5rem !important;
                margin-bottom: 0.5rem !important;
            }
            .items-table {
                font-size: 9pt !important;
                margin: 0.5rem 0 !important;
            }
            .items-table th,
            .items-table td {
                padding: 0.4rem !important;
            }
            .totals-card {
                padding: 1rem !important;
                margin-top: 0.5rem !important;
            }
        }
        
        @page {
            margin: 10mm;
            size: A4;
        }
        
        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            padding: 1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .a4-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden;
        }
        
        .print-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        
        .print-body {
            padding: 2rem;
        }
        
        .print-footer {
            background: #f8f9fa;
            padding: 1.5rem;
            text-align: center;
            border-top: 2px solid #667eea;
        }
        
        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .info-card {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #667eea;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
        }
        
        .items-table th {
            background: #f3f4f6;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #d1d5db;
        }
        
        .items-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .totals-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1.5rem;
        }
        
        .badge {
            display: inline-block;
            background: #ddd6fe;
            color: #5b21b6;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin: 0.25rem;
        }
        
        .print-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
        }
        
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
        }
        
        .status-ready { background: #10b981; color: white; }
        .status-completed { background: #059669; color: white; }
        .status-pending { background: #f59e0b; color: white; }
        
        /* Print-specific header text styles */
        .print-header-text h1,
        .print-header-text p {
            color: #1f2937 !important;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
    </style>
</head>
<body>
    <div class="a4-container">
        <!-- Header -->
        <div class="print-header">
            <div class="flex items-center justify-center gap-3 mb-3">
                <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-16 h-16 object-contain rounded-lg bg-white p-2 shadow-lg">
                <div class="print-header-text">
                    <h1 class="text-2xl font-bold">LAUNDRY RECEIPT</h1>
                    <p class="text-sm opacity-90">Order #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Body -->
        <div class="print-body">
            <!-- Order Information Section -->
            <div class="section-title">Order Information</div>
            <div class="info-grid">
                <div class="info-card">
                    <p class="text-gray-500 text-sm font-medium mb-1">Date & Time</p>
                    <p class="font-semibold">{{ $order->created_at->format('M d, Y - h:i A') }}</p>
                </div>
                <div class="info-card">
                    <p class="text-gray-500 text-sm font-medium mb-1">Branch</p>
                    <p class="font-semibold">{{ $order->branch ?? 'Main Branch' }}</p>
                </div>
                <div class="info-card">
                    <p class="text-gray-500 text-sm font-medium mb-1">Customer</p>
                    <p class="font-semibold">{{ $order->customer->name }}</p>
                    <p class="text-gray-600 text-sm">{{ $order->customer->phone }}</p>
                </div>
                <div class="info-card">
                    <p class="text-gray-500 text-sm font-medium mb-1">Status</p>
                    <span class="badge status-{{ $order->status }}">
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

            <!-- Service Type -->
            @php
                $serviceTypes = is_array($order->service_type) ? $order->service_type : json_decode($order->service_type, true);
                if (is_string($serviceTypes)) {
                    $serviceTypes = [$serviceTypes];
                }
            @endphp
            @if($serviceTypes && count($serviceTypes) > 0)
            <div class="section-title">Service Types</div>
            <div class="text-center mb-4">
                @foreach($serviceTypes as $serviceType)
                    <span class="badge">{{ ucfirst(str_replace('_', ' ', $serviceType)) }}</span>
                @endforeach
            </div>
            @endif

            <!-- Items Table -->
            <div class="section-title">Order Items</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td class="font-medium">{{ $item->name }}</td>
                        <td>GH₵{{ number_format($item->pivot->unit_price, 2) }}</td>
                        <td>{{ $item->pivot->quantity }}</td>
                        <td class="text-right font-semibold">GH₵{{ number_format($item->pivot->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Totals Card -->
            <div class="totals-card">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm opacity-90 mb-2">Payment Summary</p>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Total Amount:</span>
                                <span class="font-bold">GH₵{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Amount Paid:</span>
                                <span class="font-semibold text-green-200">GH₵{{ number_format($order->amount_paid, 2) }}</span>
                            </div>
                            <div class="border-t border-white/30 pt-2">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Balance:</span>
                                    <span class="font-bold text-lg {{ $order->balance > 0 ? 'text-red-200' : 'text-green-200' }}">
                                        GH₵{{ number_format($order->balance, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm opacity-90 mb-2">Payment Method</p>
                        <div class="space-y-2">
                            @if($order->mode_of_payment)
                            <div class="flex justify-between">
                                <span>Mode:</span>
                                <span class="font-semibold">
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
                            <div class="flex justify-between">
                                <span>Order Status:</span>
                                <span class="badge status-{{ $order->status }}">
                                    @if($order->status === 'ready')
                                        Ready
                                    @elseif($order->status === 'completed')
                                        Completed
                                    @else
                                        {{ ucfirst($order->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="print-footer no-print">
            <div class="a4-container">
                <div class="text-center">
                    <p class="text-gray-600 font-medium mb-2">Thank you for your business!</p>
                    <p class="text-gray-500 text-sm mb-6">We appreciate your trust in our services</p>
                    
                    <button onclick="window.print()" class="print-btn no-print">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print Receipt (A4)
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
