<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordPaymentRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Services\OrderAuthorizationService;
use App\Services\OrderService;
use App\Services\ReceiptService;
use App\Services\SmsService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private OrderAuthorizationService $authService,
        private ReceiptService $receiptService
    ) {}

    public function index(Request $request)
    {
        if (! $this->authService->canViewOrders()) {
            abort(403);
        }

        $orders = $this->orderService->getOrdersForIndex($request);
        $search = $request->get('search');
        $branch = $request->get('branch');
        $status = $request->get('status');
        $date = $request->get('date');
        $payment = $request->get('payment');

        return view('orders.index', compact('orders', 'search', 'branch', 'status', 'date', 'payment'));
    }

    public function create()
    {
        if (! $this->authService->canCreateOrders()) {
            abort(403);
        }

        $customers = Customer::where('laundry_id', Auth::user()->laundry_id)->orderBy('name')->get();
        $items = Item::where('laundry_id', Auth::user()->laundry_id)->where('is_active', true)->orderBy('name')->get();

        return view('orders.create', compact('customers', 'items'));
    }

    public function store(StoreOrderRequest $request)
    {
        if (! $this->authService->canCreateOrders()) {
            abort(403);
        }

        try {
            $order = $this->orderService->createOrder($request);

            return redirect()->route('orders.index')->with('toast', ['type' => 'success', 'message' => 'Order created successfully!']);
        } catch (\Exception $e) {
            Log::error('Order store error: '.$e->getMessage());

            return back()->with('toast', ['type' => 'error', 'message' => 'Error creating order: '.$e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        if (! $this->authService->canAccessOrder($order)) {
            abort(403);
        }

        $order->load(['customer', 'user', 'items']);

        return view('orders.show', compact('order'));
    }

    public function receipt(Order $order)
    {
        if (! $this->authService->canAccessOrder($order) || ! $this->authService->canAccessBranch($order)) {
            abort(403);
        }

        return view('orders.receipt', compact('order'));
    }

    public function downloadPdf(Order $order)
    {
        if (! $this->authService->canAccessOrder($order) || ! $this->authService->canAccessBranch($order)) {
            abort(403);
        }

        try {
            return $this->receiptService->downloadPdf($order);
        } catch (\Exception $e) {
            return back()->with('toast', ['type' => 'error', 'message' => 'Failed to generate PDF: '.$e->getMessage()]);
        }
    }

    public function generateReceiptForWhatsApp(Order $order)
    {
        if (! $this->authService->canAccessOrder($order)) {
            abort(403);
        }

        try {
            $path = $this->receiptService->generatePdf($order);
            $url = asset('storage/'.$path);

            return response()->json([
                'success' => true,
                'url' => $url,
                'filename' => basename($path),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate PDF: '.$e->getMessage()], 500);
        }
    }

    public function paymentForm(Order $order)
    {
        if (! $this->authService->canAccessOrder($order)) {
            abort(403);
        }

        return view('orders.payment', compact('order'));
    }

    public function recordPayment(RecordPaymentRequest $request, Order $order)
    {
        if (! $this->authService->canAccessOrder($order)) {
            abort(403);
        }

        $this->orderService->recordPayment($order, $request);

        return redirect()->route('orders.index')->with('toast', [
            'type' => 'success',
            'message' => 'Payment of GH₵'.number_format($request->amount, 2).' recorded successfully!',
        ]);
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        if (! $this->authService->canAccessOrder($order)) {
            abort(403);
        }

        $this->orderService->updateStatus($order, $request);

        if (in_array($request->status, ['ready', 'completed'])) {
            $whatsappService = new WhatsAppService;
            $whatsappService->sendOrderStatusNotification($order, $request->status);
        }

        return redirect()->route('orders.index')->with('toast', ['type' => 'success', 'message' => 'Order status updated!']);
    }

    public function destroy(Order $order)
    {
        if (! $this->authService->canDeleteOrder($order)) {
            abort(403);
        }

        $this->orderService->deleteOrder($order);

        return redirect()->route('orders.index')->with('toast', ['type' => 'success', 'message' => 'Order deleted successfully!']);
    }

    public function sendReceiptSms(Order $order)
    {
        if (! $this->authService->canAccessOrder($order) || ! $this->authService->canAccessBranch($order)) {
            abort(403);
        }

        try {
            $smsService = new SmsService;
            $result = $smsService->sendReceiptSms($order);

            if ($result) {
                return back()->with('toast', ['type' => 'success', 'message' => 'Receipt sent via SMS!']);
            } else {
                return back()->with('toast', ['type' => 'error', 'message' => 'Failed to send SMS. Customer phone number may be missing.']);
            }
        } catch (\Exception $e) {
            return back()->with('toast', ['type' => 'error', 'message' => 'Failed to send SMS: '.$e->getMessage()]);
        }
    }

    public function sendReceiptEmail(Order $order)
    {
        if (! $this->authService->canAccessOrder($order) || ! $this->authService->canAccessBranch($order)) {
            abort(403);
        }

        try {
            $this->receiptService->sendEmail($order);

            return back()->with('toast', ['type' => 'success', 'message' => 'Receipt sent to '.$order->customer->email.'!']);
        } catch (\Exception $e) {
            return back()->with('toast', ['type' => 'error', 'message' => 'Failed to send email: '.$e->getMessage()]);
        }
    }

    public function getOrderDetails(Order $order)
    {
        if (! $this->authService->canAccessOrder($order)) {
            abort(403);
        }

        return response()->json($this->orderService->getOrderDetails($order));
    }

    public function getWhatsAppUrl(Order $order)
    {
        if (! $this->authService->canAccessOrder($order) || ! $this->authService->canAccessBranch($order)) {
            abort(403);
        }

        try {
            $whatsappUrl = $this->receiptService->generateWhatsAppUrl($order);

            if (empty($whatsappUrl)) {
                return response()->json(['success' => false, 'message' => 'Customer phone number not found'], 400);
            }

            return response()->json(['success' => true, 'url' => $whatsappUrl]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate WhatsApp URL: '.$e->getMessage()], 500);
        }
    }
}
