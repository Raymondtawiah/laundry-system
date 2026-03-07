<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WhatsApp message to customer when order is ready or completed.
     */
    public function sendOrderStatusNotification(Order $order, string $newStatus): bool
    {
        $customer = $order->customer;
        
        if (!$customer || !$customer->phone) {
            Log::warning("WhatsApp notification skipped: Customer or phone not found for order #{$order->id}");
            return false;
        }

        // Format phone number (remove any non-numeric characters except +)
        $phone = $this->formatPhoneNumber($customer->phone);
        
        // Build message based on status
        $message = $this->buildMessage($order, $newStatus);
        
        // Send via WhatsApp Click to Chat (free, no API needed)
        return $this->sendViaClickToChat($phone, $message);
    }

    /**
     * Format phone number for WhatsApp (international format without +).
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If it doesn't start with country code, assume Ghana (233)
        if (!str_starts_with($phone, '233')) {
            // Remove leading 0 if present
            if (str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }
            $phone = '233' . $phone;
        }
        
        return $phone;
    }

    /**
     * Build the notification message based on order status.
     */
    private function buildMessage(Order $order, string $status): string
    {
        $customerName = $order->customer->name;
        $orderNumber = str_pad($order->id, 3, '0', STR_PAD_LEFT);
        
        if ($status === 'ready') {
            return "Hi {$customerName}! 👋\n\n"
                . "Great news! Your laundry order #{$orderNumber} is now READY for pickup! 🎉\n\n"
                . "Total: GH₵" . number_format($order->total_amount, 2) . "\n"
                . "Balance: GH₵" . number_format($order->balance, 2) . "\n\n"
                . "Please visit us to collect your items. Thank you for your business! 🙏";
        }
        
        if ($status === 'completed') {
            return "Hi {$customerName}! ✅\n\n"
                . "Your laundry order #{$orderNumber} has been COMPLETED!\n\n"
                . "Thank you for choosing our service. We hope to see you again soon! 😊\n\n"
                . "Rate us: [Your Feedback Link]";
        }
        
        return "Order #{$orderNumber} status updated to: " . ucfirst($status);
    }

    /**
     * Send message via WhatsApp Click to Chat (free method).
     */
    private function sendViaClickToChat(string $phone, string $message): bool
    {
        try {
            // Using WhatsApp Click to Chat API
            // This opens WhatsApp Web with pre-filled message
            $encodedMessage = urlencode($message);
            $whatsappUrl = "https://wa.me/{$phone}?text={$encodedMessage}";
            
            Log::info("WhatsApp notification prepared for {$phone}: " . substr($message, 0, 50) . "...");
            
            // In production, you might want to use Twilio or WhatsApp Business API
            // For now, we log the URL that would be used
            Log::info("WhatsApp URL: {$whatsappUrl}");
            
            // Return true to indicate message was prepared (actual sending happens via browser/app)
            return true;
        } catch (\Exception $e) {
            Log::error("WhatsApp notification failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send via Twilio WhatsApp (requires Twilio credentials).
     * Uncomment and configure if you have Twilio.
     */
    /*
    private function sendViaTwilio(string $phone, string $message): bool
    {
        try {
            $twilioSid = config('services.twilio.sid');
            $twilioToken = config('services.twilio.token');
            $twilioFrom = config('services.twilio.whatsapp_from');
            
            $response = Http::withBasicAuth($twilioSid, $twilioToken)
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$twilioSid}/Messages.json", [
                    'From' => "whapp:{$twilioFrom}",
                    'To' => "whapp:{$phone}",
                    'Body' => $message,
                ]);
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Twilio WhatsApp failed: " . $e->getMessage());
            return false;
        }
    }
    */
}
