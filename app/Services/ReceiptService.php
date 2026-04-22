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

        return $smsService->generateWhatsAppUrl($order, $pdfUrl);
    }
}
