<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send receipt summary via SMS.
     */
    public function sendReceiptSms(Order $order): bool
    {
        $customer = $order->customer;

        if (! $customer || ! $customer->phone) {
            Log::warning("SMS receipt skipped: Customer or phone not found for order #{$order->id}");

            return false;
        }

        // Format phone number
        $phone = $this->formatPhoneNumber($customer->phone);

        // Build receipt message
        $message = $this->buildReceiptMessage($order);

        // Send SMS
        return $this->sendSms($phone, $message);
    }

    /**
     * Format phone number for SMS (Ghana format).
     */
    public function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (! str_starts_with($phone, '233')) {
            if (str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }
            $phone = '233'.$phone;
        }

        return $phone;
    }

    /**
     * Build the receipt summary message.
     */
    private function buildReceiptMessage(Order $order): string
    {
        $customerName = $order->customer->name;
        $orderNumber = str_pad($order->id, 3, '0', STR_PAD_LEFT);

        $message = "LAUNDRY RECEIPT\n";
        $message .= "----------------\n";
        $message .= "Order #: {$orderNumber}\n";
        $message .= 'Date: '.$order->created_at->format('M d, Y')."\n";
        $message .= "Customer: {$customerName}\n";
        $message .= "----------------\n";

        // Items
        foreach ($order->items as $item) {
            $qty = $item->pivot->quantity;
            $price = number_format($item->pivot->unit_price, 2);
            $subtotal = number_format($item->pivot->subtotal, 2);
            $message .= "{$item->name} x{$qty} = GH₵{$subtotal}\n";
        }

        $message .= "----------------\n";
        $message .= 'Subtotal: GH₵'.number_format($order->subtotal, 2)."\n";

        if ($order->discount > 0) {
            $message .= 'Discount: -GH₵'.number_format($order->discount, 2)."\n";
        }

        $message .= 'Total: GH₵'.number_format($order->total_amount, 2)."\n";

        if ($order->paid > 0) {
            $message .= 'Paid: GH₵'.number_format($order->paid, 2)."\n";
            $message .= 'Balance: GH₵'.number_format($order->balance, 2)."\n";
        }

        $message .= "----------------\n";
        $message .= 'Status: '.ucfirst($order->status)."\n";

        $branch = $order->branch ?? 'Main Branch';
        $message .= "Branch: {$branch}\n";

        $message .= 'Thank you!';

        return $message;
    }

    /**
     * Send SMS via Africa's Talking API.
     */
    public function sendSms(string $phone, string $message): bool
    {
        try {
            // Try Africa's Talking API
            $username = config('services.africastalking.username');
            $apiKey = config('services.africastalking.key');

            if ($username && $apiKey) {
                $response = Http::withHeaders([
                    'apiKey' => $apiKey,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])->timeout(30)->post('https://api.africastalking.com/version1/messaging', [
                    'username' => $username,
                    'to' => $phone,
                    'message' => $message,
                ]);

                $responseData = $response->json();

                if ($response->successful() && isset($responseData['SMSMessageData']['Recipients'][0]['status'])
                    && $responseData['SMSMessageData']['Recipients'][0]['status'] === 'Success') {
                    Log::info("SMS sent via Africa's Talking to {$phone}");

                    return true;
                }

                Log::warning("Africa's Talking SMS failed: ".json_encode($responseData));
            }

            // Fallback: Generate SMS link (works on all phones)
            $smsUrl = $this->generateSmsUrl($phone, $message);
            Log::info("SMS URL generated (API not configured): {$smsUrl}");

            // Return true to indicate the message was generated (staff can copy the link)
            return true;

        } catch (\Exception $e) {
            Log::error('SMS sending failed: '.$e->getMessage());

            // Final fallback: Generate SMS URL
            $smsUrl = $this->generateSmsUrl($phone, $message);
            Log::info("SMS fallback URL: {$smsUrl}");

            return true;
        }
    }

    /**
     * Generate SMS URL for manual sending.
     */
    public function generateSmsUrl(string $phone, string $message): string
    {
        return "sms:{$phone}?body=".urlencode($message);
    }

    /**
     * Build a WhatsApp message for order ready notification.
     */
    public function buildOrderReadyMessage(Order $order, ?string $pdfUrl = null): string
    {
        $customerName = $order->customer->name;
        $orderNumber = str_pad($order->id, 3, '0', STR_PAD_LEFT);
        $laundryName = $order->laundry->name ?? 'Laundry';

        $message = "Hi {$customerName}!\n\n";
        $message .= "Good news! Your laundry order #{$orderNumber} is ready for pickup! 🎉\n\n";
        $message .= "📋 Order Details:\n";
        $message .= "- Order #: {$orderNumber}\n";
        $message .= '- Total: GH₵'.number_format($order->total_amount, 2)."\n";
        $message .= '- Balance: GH₵'.number_format($order->balance, 2)."\n\n";

        if ($pdfUrl) {
            $message .= "📄 Download receipt: {$pdfUrl}\n\n";
        } else {
            // Add link to view receipt online
            $receiptUrl = route('orders.receipt', $order->id);
            $message .= "📄 View receipt: {$receiptUrl}\n\n";
        }

        $message .= "📍 Please visit us to pick up your items.\n\n";
        $message .= "Thank you for choosing {$laundryName}!";

        return $message;
    }

    /**
     * Generate WhatsApp URL for sending message.
     */
    public function generateWhatsAppUrl(Order $order, ?string $pdfUrl = null): string
    {
        $customer = $order->customer;

        if (! $customer || ! $customer->phone) {
            return '';
        }

        $phone = $this->formatPhoneNumber($customer->phone);

        return "https://wa.me/{$phone}?text=";
    }

    /**
     * Send a test SMS.
     */
    public function sendTestSms(string $phone, ?string $customMessage = null): array
    {
        $phone = $this->formatPhoneNumber($phone);
        $message = $customMessage ?? 'Test SMS from your Laundry System!';

        $result = $this->sendSms($phone, $message);

        return [
            'success' => $result,
            'phone' => $phone,
            'message' => $message,
            'sms_url' => $this->generateSmsUrl($phone, $message),
        ];
    }
}
