<?php

namespace App\Services;

use App\Http\Requests\RecordPaymentRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function __construct(
        private OrderAuthorizationService $authService
    ) {}

    public function getOrdersForIndex(Request $request): LengthAwarePaginator
    {
        $search = $request->get('search', '');
        $branch = $request->get('branch', '');
        $status = $request->get('status', '');
        $date = $request->get('date', '');
        $payment = $request->get('payment', '');

        return Order::with(['customer', 'user', 'items'])
            ->where('laundry_id', Auth::user()->laundry_id)
            ->when(Auth::user()->role === 'staff', fn ($q) => $this->authService->getUserBranchConstraint($q))
            ->when($branch, fn ($q) => $q->where('branch', $branch))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($payment, fn ($q) => $q->where('payment_status', $payment))
            ->when($date, fn ($q) => $q->whereDate('created_at', $date))
            ->when($search, function ($query) use ($search) {
                $query->whereHas('customer', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"))
                    ->orWhere('id', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function createOrder(StoreOrderRequest $request): Order
    {
        $customer = Customer::find($request->customer_id);
        $orderBranch = $customer->branch ?? Auth::user()->branch;
        $pickupType = $request->pickup_type ?? 'door_pick';

        $totalAmount = $this->calculateTotal($request->items);

        $order = Order::create([
            'laundry_id' => Auth::user()->laundry_id,
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

        $this->attachItems($order, $request->items);

        return $order;
    }

    public function calculateTotal(array $items): float
    {
        $total = 0;
        foreach ($items as $item) {
            $itemModel = Item::find($item['item_id']);
            $total += $itemModel->price * $item['quantity'];
        }

        return $total;
    }

    public function attachItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            $itemModel = Item::find($item['item_id']);
            $subtotal = $itemModel->price * $item['quantity'];

            $order->items()->attach($item['item_id'], [
                'quantity' => $item['quantity'],
                'unit_price' => $itemModel->price,
                'subtotal' => $subtotal,
            ]);
        }
    }

    public function recordPayment(Order $order, RecordPaymentRequest $request): void
    {
        $order->amount_paid = $order->amount_paid + $request->amount;
        $order->updateBalance();
    }

    public function updateStatus(Order $order, UpdateOrderStatusRequest $request): void
    {
        $order->update(['status' => $request->status]);
    }

    public function deleteOrder(Order $order): void
    {
        $order->delete();
    }

    public function getOrderDetails(Order $order): array
    {
        $items = $order->items->map(fn ($item) => [
            'name' => $item->name,
            'quantity' => $item->pivot->quantity,
            'unit_price' => $item->pivot->unit_price,
            'subtotal' => $item->pivot->subtotal,
        ]);

        return [
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'branch' => $order->branch,
                'created_at' => $order->created_at->format('M d, Y h:i A'),
                'total_amount' => $order->total_amount,
                'paid' => $order->amount_paid,
                'balance' => $order->balance,
            ],
            'customer' => [
                'name' => $order->customer->name ?? 'N/A',
                'phone' => $order->customer->phone ?? 'N/A',
            ],
            'items' => $items,
        ];
    }
}
