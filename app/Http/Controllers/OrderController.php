<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        // Only admin and staff can create orders
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Only administrators and staff can create orders.');
        }
        
        $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'delivery_type' => ['required', 'in:pickup,doorstep'],
            'service_type' => ['required', 'in:washing,ironing,drying,deep_cleaning'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $laundryId = Auth::user()->laundry_id;

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
            'status' => Order::STATUS_PENDING,
            'delivery_type' => $request->delivery_type,
            'service_type' => $request->service_type,
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
        
        $search = $request->get('search', '');
        
        $orders = Order::with(['customer', 'user', 'items'])
                    ->where('laundry_id', Auth::user()->laundry_id)
                    ->when($search, function($query) use ($search) {
                        $query->whereHas('customer', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhere('id', 'like', "%{$search}%");
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('orders.index', compact('orders', 'search'));
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
}
