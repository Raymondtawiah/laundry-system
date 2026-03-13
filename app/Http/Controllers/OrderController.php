<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Item;
use App\Services\WhatsAppService;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Show the create order form.
     */
    public function create()
    {
        // Only admin and staff can create orders
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can create orders.');
        }
        
        $customers = Customer::where('laundry_id', Auth::user()->laundry_id)
                    ->orderBy('name')
                    ->get();
        
        $items = Item::where('laundry_id', Auth::user()->laundry_id)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get();
        
        return view('orders.create', compact('customers', 'items'));
    }

    /**
     * Show order details.
     */
    public function show(Order $order)
    {
        // Only admin and staff can view orders
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can view orders.');
        }
        
        // Staff can only view their branch orders
        if (Auth::user()->role === 'staff' && $order->branch !== Auth::user()->branch) {
            abort(403, 'You can only view orders from your branch.');
        }
        
        // Load relationships
        $order->load(['customer', 'user', 'items']);
        
        return view('orders.show', compact('order'));
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        try {
            // Only admin and staff can create orders
            if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
                abort(403, 'Only administrators and staff can create orders.');
            }
            
            // Debug: Log the request data
            Log::info('Order store request:', $request->all());
            
            $request->validate([
                'customer_id' => ['required', 'exists:customers,id'],
                'delivery_type' => ['required', 'in:pickup,doorstep'],
                'pickup_type' => ['nullable', 'in:door_pick,self_pick'],
                'service_types' => ['required', 'array', 'min:1'],
                'items' => ['required', 'array', 'min:1'],
                'items.*.item_id' => ['required', 'exists:items,id'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
            ]);

            $laundryId = Auth::user()->laundry_id;
            
            // Debug: Log laundry_id
            Log::info('Laundry ID:', ['laundry_id' => $laundryId]);
            
            // Get customer to determine branch
            $customer = Customer::find($request->customer_id);
            $orderBranch = $customer->branch ?? Auth::user()->branch;
            $pickupType = $request->pickup_type ?? 'door_pick';

            // Calculate total amount
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $itemModel = Item::find($item['item_id']);
                $totalAmount += $itemModel->price * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'laundry_id' => $laundryId,
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'branch' => $orderBranch,
                'status' => Order::STATUS_PENDING,
                'delivery_type' => $request->delivery_type,
                'pickup_type' => $pickupType,
                'service_type' => json_encode($request->service_types),
                'mode_of_payment' => $request->mode_of_payment,
                'total_amount' => $totalAmount,
                'amount_paid' => 0,
                'payment_status' => Order::PAYMENT_UNPAID,
                'balance' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($request->items as $item) {
                $itemModel = Item::find($item['item_id']);
                $subtotal = $itemModel->price * $item['quantity'];
                
                $order->items()->attach($item['item_id'], [
                    'quantity' => $item['quantity'],
                    'unit_price' => $itemModel->price,
                    'subtotal' => $subtotal,
                ]);
            }

            return redirect()->route('orders.index')->with('toast', ['type' => 'success', 'message' => 'Order created successfully!']);
        } catch (\Exception $e) {
            Log::error('Order store error: ' . $e->getMessage());
            return back()->with('toast', ['type' => 'error', 'message' => 'Error creating order: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the orders list.
     */
    public function index(Request $request)
    {
        // Only admin and staff can view orders
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can view orders.');
        }
        
        // Debug: Log user info
        Log::info('Order index - User:', [
            'id' => Auth::id(),
            'role' => Auth::user()->role,
            'branch' => Auth::user()->branch,
            'laundry_id' => Auth::user()->laundry_id
        ]);
        
        $search = $request->get('search', '');
        $branch = $request->get('branch', '');
        $status = $request->get('status', '');
        $date = $request->get('date', '');
        $payment = $request->get('payment', '');
        
        $orders = Order::with(['customer', 'user', 'items'])
                    ->where('laundry_id', Auth::user()->laundry_id)
                    ->when(Auth::user()->role === 'staff', function($query) {
                        // Staff can only see orders from their branch (must have branch assigned)
                        if (Auth::user()->branch) {
                            $query->where('branch', Auth::user()->branch);
                        } else {
                            // Staff without branch cannot see any orders
                            $query->whereNull('id');
                        }
                    })
                    ->when($branch, function($query) use ($branch) {
                        $query->where('branch', $branch);
                    })
                    ->when($status, function($query) use ($status) {
                        $query->where('status', $status);
                    })
                    ->when($payment, function($query) use ($payment) {
                        $query->where('payment_status', $payment);
                    })
                    ->when($date, function($query) use ($date) {
                        $query->whereDate('created_at', $date);
                    })
                    ->when($search, function($query) use ($search) {
                        $query->whereHas('customer', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhere('id', 'like', "%{$search}%");
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
        
        return view('orders.index', compact('orders', 'search', 'branch', 'status', 'date', 'payment'));
    }

    /**
     * Show payment form for an order.
     */
    public function paymentForm(Order $order)
    {
        // Only admin and staff can record payments
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can record payments.');
        }
        
        // Make sure the order belongs to the same laundry
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot view this order.');
        }
        
        return view('orders.payment', compact('order'));
    }

    /**
     * Record a payment for an order.
     */
    public function recordPayment(Request $request, Order $order)
    {
        // Only admin and staff can record payments
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can record payments.');
        }
        
        // Make sure the order belongs to the same laundry
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot view this order.');
        }
        
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:' . $order->balance],
        ]);

        // Update amount paid and balance
        $order->amount_paid = $order->amount_paid + $request->amount;
        $order->updateBalance();

        return redirect()->route('orders.index')->with('toast', ['type' => 'success', 'message' => 'Payment of GH₵' . number_format($request->amount, 2) . ' recorded successfully!']);
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Only admin and staff can update orders
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can update orders.');
        }
        
        // Make sure the order belongs to the same laundry
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot update this order.');
        }
        
        $request->validate([
            'status' => ['required', 'in:pending,in_progress,ready,completed,cancelled'],
        ]);

        $order->update(['status' => $request->status]);

        // Send WhatsApp notification when order is ready or completed
        if (in_array($request->status, ['ready', 'completed'])) {
            $whatsappService = new WhatsAppService();
            $whatsappService->sendOrderStatusNotification($order, $request->status);
        }

        return redirect()->route('orders.index')->with('toast', ['type' => 'success', 'message' => 'Order status updated!']);
    }

    /**
     * Delete an order.
     */
    public function destroy(Order $order)
    {
        // Only admin can delete orders
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can delete orders.');
        }
        
        // Make sure the order belongs to the same laundry
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot delete this order.');
        }
        
        $order->delete();
        
        return redirect()->route('orders.index')->with('toast', ['type' => 'success', 'message' => 'Order deleted successfully!']);
    }

    /**
     * Show order receipt.
     */
    public function receipt(Order $order)
    {
        // Only admin and staff can view receipts
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can view receipts.');
        }
        
        // Make sure the order belongs to the same laundry
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot view this receipt.');
        }
        
        // Staff can only view their branch orders
        if (Auth::user()->role === 'staff' && $order->branch !== Auth::user()->branch) {
            abort(403, 'You can only view receipts from your branch.');
        }
        
        return view('orders.receipt', compact('order'));
    }

    /**
     * Download order receipt as PDF.
     */
    public function downloadPdf(Order $order)
    {
        // Only admin and staff can download receipts
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can download receipts.');
        }
        
        // Make sure the order belongs to the same laundry
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot download this receipt.');
        }
        
        // Staff can only download their branch orders
        if (Auth::user()->role === 'staff' && $order->branch !== Auth::user()->branch) {
            abort(403, 'You can only download receipts from your branch.');
        }
        
        try {
            // Load relationships needed for PDF
            $order->load(['items', 'customer', 'laundry']);
            
            $pdfService = new PdfService();
            $path = $pdfService->generateReceiptPdf($order);
            
            return response()->download(storage_path('app/public/' . $path));
        } catch (\Exception $e) {
            return back()->with('toast', ['type' => 'error', 'message' => 'Failed to generate PDF: ' . $e->getMessage()]);
        }
    }

    /**
     * Generate receipt and return URL for WhatsApp.
     */
    public function generateReceiptForWhatsApp(Order $order)
    {
        // Only admin and staff can generate receipts
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can generate receipts.');
        }
        
        // Make sure the order belongs to the same laundry
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot generate receipt for this order.');
        }
        
        try {
            // Load relationships needed for PDF
            $order->load(['items', 'customer', 'laundry']);
            
            $pdfService = new PdfService();
            $path = $pdfService->generateReceiptPdf($order);
            
            // Get the full URL to the PDF
            $url = asset('storage/' . $path);
            
            return response()->json([
                'success' => true,
                'url' => $url,
                'filename' => basename($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send receipt via SMS.
     */
    public function sendReceiptSms(Order $order)
    {
        // Only admin and staff can send receipts
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can send receipts.');
        }
        
        // Make sure the order belongs to the same laundry
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot send receipt for this order.');
        }
        
        // Staff can only send their branch orders
        if (Auth::user()->role === 'staff' && $order->branch !== Auth::user()->branch) {
            abort(403, 'You can only send receipts from your branch.');
        }
        
        try {
            $smsService = new SmsService();
            $result = $smsService->sendReceiptSms($order);
            
            if ($result) {
                return back()->with('toast', ['type' => 'success', 'message' => 'Receipt sent via SMS!']);
            } else {
                return back()->with('toast', ['type' => 'error', 'message' => 'Failed to send SMS. Customer phone number may be missing.']);
            }
        } catch (\Exception $e) {
            return back()->with('toast', ['type' => 'error', 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
        }
    }

    /**
     * Get order details as JSON for modal.
     */
    public function getOrderDetails(Order $order)
    {
        // Only admin and staff can view
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can view order details.');
        }
        
        // Make sure the order belongs to the same laundry
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot view this order.');
        }
        
        $items = $order->items->map(function($item) {
            return [
                'name' => $item->name,
                'quantity' => $item->pivot->quantity,
                'unit_price' => $item->pivot->unit_price,
                'subtotal' => $item->pivot->subtotal,
            ];
        });
        
        return response()->json([
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'branch' => $order->branch,
                'created_at' => $order->created_at->format('M d, Y h:i A'),
                'subtotal' => $order->subtotal,
                'discount' => $order->discount,
                'total_amount' => $order->total_amount,
                'paid' => $order->paid,
                'balance' => $order->balance,
            ],
            'customer' => [
                'name' => $order->customer->name ?? 'N/A',
                'phone' => $order->customer->phone ?? 'N/A',
            ],
            'items' => $items,
        ]);
    }

    /**
     * Generate WhatsApp notification URL for order ready.
     */
    public function getWhatsAppUrl(Order $order)
    {
        // Only admin and staff can send notifications
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can send notifications.');
        }
        
        // Make sure the order belongs to the same laundry
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot send notification for this order.');
        }
        
        // Staff can only notify for their branch orders
        if (Auth::user()->role === 'staff' && $order->branch !== Auth::user()->branch) {
            abort(403, 'You can only notify for orders from your branch.');
        }
        
        try {
            // Load relationships
            $order->load(['items', 'customer', 'laundry']);
            
            // Try to generate PDF first
            $pdfUrl = null;
            try {
                $pdfService = new PdfService();
                $path = $pdfService->generateReceiptPdf($order);
                $pdfUrl = asset('storage/' . $path);
            } catch (\Exception $e) {
                // PDF generation failed, continue without PDF link
                Log::warning('PDF generation failed for WhatsApp: ' . $e->getMessage());
            }
            
            // Generate WhatsApp URL
            $smsService = new SmsService();
            $whatsappUrl = $smsService->generateWhatsAppUrl($order, $pdfUrl);
            
            if (empty($whatsappUrl)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer phone number not found'
                ], 400);
            }
            
            return response()->json([
                'success' => true,
                'url' => $whatsappUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate WhatsApp URL: ' . $e->getMessage()
            ], 500);
        }
    }
}
