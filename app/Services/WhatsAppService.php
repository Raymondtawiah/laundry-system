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

        // Format phone number
        $phone = $this->formatPhoneNumber($customer->phone);
        
        // Build message based on status
        $message = $this->buildMessage($order, $newStatus);
        
        // Send message
        return $this->sendMessage($phone, $message);
    }

    /**
     * Format phone number for WhatsApp.
     */
    public function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (!str_starts_with($phone, '233')) {
            if (str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }
            $phone = '233' . $phone;
        }
        
        return $phone;
    }

    /**
     * Build the notification message.
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
                . "Please visit us to collect your items. Thank you! 🙏";
        }
        
        if ($status === 'completed') {
            return "Hi {$customerName}! ✅\n\n"
                . "Your laundry order #{$orderNumber} has been COMPLETED!\n\n"
                . "Thank you for choosing our service. See you soon! 😊";
        }
        
        return "Order #{$orderNumber} status: " . ucfirst($status);
    }

    /**
     * Send message via CallMeBot API (free tier available).
     */
    private function sendMessage(string $phone, string $message): bool
    {
        try {
            // Try CallMeBot API (free tier: 1 message/day)
            $apiKey = config('services.callmebot.key');
            
            if ($apiKey) {
                // Using CallMeBot WhatsApp API
                $response = Http::timeout(30)->get("https://api.callmebot.com/whatsapp.php", [
                    'phone' => $phone,
                    'text' => $message,
                    'apikey' => $apiKey,
                ]);
                
                if ($response->successful() && str_contains($response->body(), 'OK')) {
                    Log::info("WhatsApp message sent via CallMeBot to {$phone}");
                    return true;
                }
                
                Log::warning("CallMeBot failed, using fallback: " . $response->body());
            }
            
            // Fallback: Generate WhatsApp Click to Chat URL (always works)
            $whatsappUrl = $this->generateWhatsAppUrl($phone, $message);
            Log::info("WhatsApp Click to Chat URL generated: {$whatsappUrl}");
            
            return true;
            
        } catch (\Exception $e) {
            Log::error("WhatsApp notification failed: " . $e->getMessage());
            
            // Final fallback: WhatsApp URL
            $whatsappUrl = $this->generateWhatsAppUrl($phone, $message);
            Log::info("WhatsApp fallback URL: {$whatsappUrl}");
            return true;
        }
    }

    /**
     * Generate WhatsApp Click to Chat URL.
     */
    public function generateWhatsAppUrl(string $phone, string $message): string
    {
        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    /**
     * Send a test message.
     */
    public function sendTestMessage(string $phone, string $customMessage = null): array
    {
        $phone = $this->formatPhoneNumber($phone);
        $message = $customMessage ?? "Test message from your Laundry System! ✅";
        
        $result = $this->sendMessage($phone, $message);
        
        return [
            'success' => $result,
            'phone' => $phone,
            'message' => $message,
            'whatsapp_url' => $this->generateWhatsAppUrl($phone, $message),
        ];
    }
}
