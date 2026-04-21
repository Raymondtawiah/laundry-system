<x-layouts::app :title="__('Order Details')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Order #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-sm text-gray-500">Order Details & Information</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ URL::previous() }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
                @php
                    // Format phone number for WhatsApp
                    $phone = preg_replace('/[^0-9]/', '', $order->customer->phone ?? '');
                    if (!empty($phone) && !str_starts_with($phone, '233')) {
                        if (str_starts_with($phone, '0')) {
                            $phone = substr($phone, 1);
                        }
                        $phone = '233' . $phone;
                    }
                    
                    // Build WhatsApp message
                    $orderNumber = str_pad($order->id, 3, '0', STR_PAD_LEFT);
                    // Build WhatsApp message based on delivery type
                    $deliveryMessage = ($order->delivery_type === 'pickup') 
                        ? "Please visit us to pick up your items." 
                        : "We will deliver to your doorstep.";
                    
                    $message = "Hi " . ($order->customer->name ?? 'Customer') . "!\n\n";
                    $message .= "Your laundry order #{$orderNumber} is ready for pickup!\n\n";
                    $message .= "Order Details:\n";
                    $message .= "- Order #: {$orderNumber}\n";
                    $message .= "- Total: GH₵" . number_format($order->total_amount, 2) . "\n";
                    $message .= "- Balance: GH₵" . number_format($order->balance, 2) . "\n\n";
                    $message .= $deliveryMessage . "\n";
                    $message .= "Thank you!";
                    
                    $whatsappUrl = !empty($phone) ? 'https://wa.me/' . $phone . '?text=' . urlencode($message) : '';
                @endphp
                @if(!empty($whatsappUrl))
                <a href="{{ $whatsappUrl }}" target="_blank" class="inline-flex items-center justify-center rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Send WhatsApp
                </a>
                @endif
                <a href="{{ route('orders.receipt', $order) }}" target="_blank" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View Receipt
                </a>
                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                    <a href="{{ route('orders.payment', $order) }}" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Make Payment
                    </a>
                @endif
            </div>
        </div>

        <!-- Status Banner -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    @switch($order->status)
                        @case('pending')
                            <span class="rounded-full bg-yellow-100 p-2">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Pending Order</h2>
                                <p class="text-sm text-gray-500">Awaiting processing</p>
                            </div>
                            @break
                        @case('in_progress')
                            <span class="rounded-full bg-blue-100 p-2">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">In Progress</h2>
                                <p class="text-sm text-gray-500">Order is being processed</p>
                            </div>
                            @break
                        @case('ready')
                            <span class="rounded-full bg-purple-100 p-2">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Ready for Pickup</h2>
                                <p class="text-sm text-gray-500">Order is ready</p>
                            </div>
                            @break
                        @case('completed')
                            <span class="rounded-full bg-green-100 p-2">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Delivered</h2>
                                <p class="text-sm text-gray-500">Order completed successfully</p>
                            </div>
                            @break
                        @case('cancelled')
                            <span class="rounded-full bg-red-100 p-2">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Cancelled</h2>
                                <p class="text-sm text-gray-500">Order was cancelled</p>
                            </div>
                            @break
                    @endswitch
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Created</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Customer & Service Info -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Customer -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Customer</h3>
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-lg font-bold text-gray-600">
                                    {{ strtoupper(substr($order->customer->name ?? 'N/A', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $order->customer->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->customer->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @if($order->customer->address)
                                <p class="mt-2 text-sm text-gray-600">{{ $order->customer->address }}</p>
                            @endif
                        </div>
                        
                        <!-- Order Info -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Order Info</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Branch</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $order->branch ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Created By</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Order ID</span>
                                    <span class="text-sm font-medium text-gray-900">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery & Service -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Service & Delivery</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <!-- Delivery Type -->
                        <div class="p-3 bg-gray-50 rounded-lg text-center">
                            <div class="rounded-full bg-indigo-100 p-2 w-fit mx-auto mb-2">
                                @if($order->delivery_type === 'pickup')
                                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">Delivery</p>
                            <p class="text-sm font-semibold text-gray-900">
                                @if($order->delivery_type === 'pickup')
                                    Pickup
                                @else
                                    Doorstep
                                @endif
                            </p>
                        </div>
                        
                        <!-- Pickup Type -->
                        <div class="p-3 bg-gray-50 rounded-lg text-center">
                            <div class="rounded-full bg-teal-100 p-2 w-fit mx-auto mb-2">
                                @if($order->pickup_type === 'door_pick')
                                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">Pickup</p>
                            <p class="text-sm font-semibold text-gray-900">
                                @if($order->pickup_type === 'door_pick')
                                    Door Pick
                                @else
                                    Self Pick
                                @endif
                            </p>
                        </div>
                        
                        <!-- Payment Mode -->
                        <div class="p-3 bg-gray-50 rounded-lg text-center">
                            <div class="rounded-full bg-green-100 p-2 w-fit mx-auto mb-2">
                                @if($order->mode_of_payment === 'Cash')
                                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                @elseif($order->mode_of_payment === 'MoMo')
                                    <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">Payment</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->mode_of_payment ?? 'N/A' }}</p>
                        </div>
                        
                        <!-- Service Type -->
                        <div class="p-3 bg-gray-50 rounded-lg text-center">
                            <div class="rounded-full bg-amber-100 p-2 w-fit mx-auto mb-2">
                                <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500">Service</p>
                            <p class="text-sm font-semibold text-gray-900">Laundry</p>
                        </div>
                    </div>
                    
                    <!-- Service Types -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-2">Service Types:</p>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $serviceTypes = is_array($order->service_type) ? $order->service_type : json_decode($order->service_type, true);
                                if (is_string($serviceTypes)) {
                                    $serviceTypes = [$serviceTypes];
                                }
                            @endphp
                            @if($serviceTypes && count($serviceTypes) > 0)
                                @foreach($serviceTypes as $serviceType)
                                    @switch($serviceType)
                                        @case('washing')
                                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">🧥 Executive Wear</span>
                                            @break
                                        @case('ironing')
                                            <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700">👘 Native Wear</span>
                                            @break
                                        @case('drying')
                                            <span class="rounded-full bg-pink-100 px-3 py-1 text-xs font-medium text-pink-700">👗 Ladies Wear</span>
                                            @break
                                        @case('bag wash')
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">👜 Bag Wash</span>
                                            @break
                                        @case('bedding_decor')
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">🛏️ Bedding</span>
                                            @break
                                        @case('sneakers')
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">👟 Sneakers</span>
                                            @break
                                        @case('bag')
                                            <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-700">🎒 Bag</span>
                                            @break
                                        @case('ironing')
                                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">✨ Ironing</span>
                                            @break
                                        @case('deep_cleaning')
                                            <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-medium text-cyan-700">✨ Deep Cleaning</span>
                                            @break
                                        @default
                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">{{ $serviceType }}</span>
                                    @endswitch
                                @endforeach
                            @else
                                <span class="text-gray-500 text-sm">No service type specified</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($order->notes)
                <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Notes</h3>
                    <p class="text-gray-700">{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-4 sm:space-y-6">
                <!-- Payment Summary -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Payment Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Total Amount</span>
                            <span class="text-lg font-bold text-gray-900">GH₵{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <span class="text-sm text-gray-600">Amount Paid</span>
                            <span class="text-lg font-bold text-green-600">GH₵{{ number_format($order->amount_paid, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                            <span class="text-sm text-gray-600">Balance</span>
                            <span class="text-lg font-bold text-red-600">GH₵{{ number_format($order->balance, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Payment Status</span>
                            @switch($order->payment_status)
                                @case('paid')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Paid</span>
                                    @break
                                @case('partial')
                                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Partial</span>
                                    @break
                                @default
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Unpaid</span>
                            @endswitch
                        </div>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Order Status</h3>
                    
                    <!-- Update Status Form -->
                    @if($order->status !== 'completed' && $order->status !== 'cancelled')
                    <form method="POST" action="{{ route('orders.updateStatus', $order) }}" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-2">
                            <select name="status" class="flex-1 rounded-lg border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 py-2 px-3 text-sm" required>
                                <option value="">Update Status</option>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Delivered</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Update
                            </button>
                        </div>
                    </form>
                    @endif
                    
                    <!-- Status Timeline -->
                    <div class="space-y-4">
                        @php
                            $statuses = ['pending', 'in_progress', 'ready', 'completed'];
                            $currentIndex = array_search($order->status, $statuses);
                            if ($order->status === 'cancelled') {
                                $currentIndex = -1;
                            }
                        @endphp
                        
                        @foreach($statuses as $index => $status)
                            <div class="flex items-center gap-3">
                                @if($index < $currentIndex)
                                    <div class="rounded-full bg-green-100 p-1">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @elseif($index === $currentIndex)
                                    <div class="rounded-full bg-blue-100 p-1">
                                        <svg class="h-4 w-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="4"></circle>
                                        </svg>
                                    </div>
                                @else
                                    <div class="rounded-full bg-gray-200 p-1">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="4"></circle>
                                        </svg>
                                    </div>
                                @endif
                                <span class="text-sm {{ $index === $currentIndex ? 'font-medium text-gray-900' : 'text-gray-500' }}">
                                    @switch($status)
                                        @case('pending')
                                            Pending
                                            @break
                                        @case('in_progress')
                                            In Progress
                                            @break
                                        @case('ready')
                                            Ready
                                            @break
                                        @case('completed')
                                            Delivered
                                            @break
                                    @endswitch
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
