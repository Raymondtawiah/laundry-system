<?php

namespace App\Services;

use App\Mail\OrderReceiptMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReceiptService
{
    public function __construct(
        private PdfService $pdfService,
        private OrderAuthorizationService $authService
    ) {}

    public function generatePdf(Order $order): string
    {
        $order->load(['items', 'customer', 'laundry']);

        return $this->pdfService->generateReceiptPdf($order);
    }

    public function downloadPdf(Order $order)
    {
        $path = $this->generatePdf($order);

        return response()->download(storage_path('app/public/'.$path));
    }

    public function sendEmail(Order $order): bool
    {
        $order->load(['customer', 'items']);
        $email = $order->customer->email;

        if (! $email) {
            throw new \Exception('Customer email address is missing.');
        }

        Mail::to($email)->send(new OrderReceiptMail($order));

        return true;
    }

    public function generateWhatsAppUrl(Order $order): ?string
    {
        $order->load(['items', 'customer', 'laundry']);

        $pdfUrl = null;
        try {
            $path = $this->generatePdf($order);
            $pdfUrl = asset('storage/'.$path);
        } catch (\Exception $e) {
            Log::warning('PDF generation failed for WhatsApp: '.$e->getMessage());
        }

        $smsService = new SmsService;

        return $smsService->generateWhatsAppUrlWithPdf($order, $pdfUrl);
    }

    public function generateWhatsAppUrlWithPdf(Order $order, string $pdfUrl): ?string
    {
        $customer = $order->customer;

        if (! $customer || ! $customer->phone) {
            Log::warning("WhatsApp URL skipped: Customer or phone not found for order #{$order->id}");

            return null;
        }

        // Format phone number
        $phone = $this->formatPhoneNumber($customer->phone);

        // Build message with PDF attachment
        $orderNumber = str_pad($order->id, 3, '0', STR_PAD_LEFT);
        $message = "🧾 *Receipt for Order #{$orderNumber}*\n\n"
            ."Hi {$customer->name}! Thank you for your order.\n\n"
            ."📄 Download your receipt: {$pdfUrl}\n\n"
            .'Total: GH₵'.number_format($order->total_amount, 2)."\n"
            .'Status: '.ucfirst($order->status)."\n\n"
            .'Thank you for choosing our service! 🙏';

        // Generate WhatsApp URL with message
        return "https://wa.me/{$phone}?text=".urlencode($message);
    }

    private function formatPhoneNumber(string $phone): string
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
}
