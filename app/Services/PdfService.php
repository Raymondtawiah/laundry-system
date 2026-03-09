<?php

namespace App\Services;

use App\Models\Order;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfService
{
    /**
     * Generate PDF receipt for an order.
     */
    public function generateReceiptPdf(Order $order): string
    {
        try {
            $html = $this->getReceiptHtml($order);
            
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'Arial');
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            
            $pdfContent = $dompdf->output();
            
            $filename = 'receipt-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) . '.pdf';
            $path = 'receipts/' . $filename;
            
            Storage::disk('public')->put($path, $pdfContent);
            
            Log::info("PDF receipt generated: {$path}");
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error("PDF generation failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get the HTML content for the receipt with matching styling.
     */
    private function getReceiptHtml(Order $order): string
    {
        $itemsHtml = '';
        
        foreach ($order->items as $item) {
            $itemsHtml .= '
                <tr style="border-bottom: 1px dashed #e5e7eb;">
                    <td style="padding: 12px 8px;">
                        <div style="font-weight: 600;">' . e($item->name) . '</div>
                        <div style="color: #6b7280; font-size: 11px;">GH₵' . number_format($item->pivot->unit_price, 2) . ' x ' . $item->pivot->quantity . '</div>
                    </td>
                    <td style="padding: 12px 8px; text-align: right; font-weight: 600;">GH₵' . number_format($item->pivot->subtotal, 2) . '</td>
                </tr>
            ';
        }
        
        // Service type badge
        $serviceTypeHtml = '';
        if ($order->service_type) {
            $serviceLabels = [
                'washing' => '🧥 Executive Wear',
                'ironing' => '👘 Native Wear',
                'drying' => '👗 Ladies Wear',
                'bag wash' => '👜 Bag Wash',
                'bedding_decor' => '🛏️ Bedding and Decor',
                'sneakers' => '👟 Sneakers',
                'bag' => '🎒 Bag',
                'deep_cleaning' => '✨ Ironing',
            ];
            
            // Handle both JSON array and string
            $serviceTypes = is_array($order->service_type) ? $order->service_type : json_decode($order->service_type, true);
            if (is_string($serviceTypes)) {
                $serviceTypes = [$serviceTypes];
            }
            
            if ($serviceTypes && count($serviceTypes) > 0) {
                $badges = [];
                foreach ($serviceTypes as $serviceType) {
                    $serviceName = $serviceLabels[$serviceType] ?? ucfirst(str_replace('_', ' ', $serviceType));
                    $badges[] = '<span style="display: inline-block; background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; margin: 2px;">' . $serviceName . '</span>';
                }
                $serviceTypeHtml = '
                    <div style="text-align: center; margin-bottom: 16px;">' .
                        implode('', $badges) .
                    '</div>
                ';
            }
        }
        
        // Status badge
        $statusBadge = '';
        if ($order->status === 'ready') {
            $statusBadge = '<div style="text-align: center; margin-top: 20px;">
                <span style="display: inline-flex; align-items: center; gap: 4px; background: #dcfce7; color: #166534; padding: 6px 16px; border-radius: 9999px; font-size: 14px; font-weight: 500;">
                    ✓ Ready for Pickup
                </span>
            </div>';
        } elseif ($order->status === 'completed') {
            $statusBadge = '<div style="text-align: center; margin-top: 20px;">
                <span style="display: inline-flex; align-items: center; gap: 4px; background: #dbeafe; color: #1e40af; padding: 6px 16px; border-radius: 9999px; font-size: 14px; font-weight: 500;">
                    ✓ Completed
                </span>
            </div>';
        }
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Receipt - Order #" . str_pad($order->id, 3, '0', STR_PAD_LEFT) . "</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: 'Segoe UI', Arial, sans-serif; background: #f3f4f6; padding: 20px; min-height: 100vh; }
                .receipt-container { background: white; border-radius: 16px; max-width: 450px; margin: 0 auto; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }
                .receipt-header { background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; padding: 32px 24px; text-align: center; }
                .header-icon { width: 48px; height: 48px; margin: 0 auto 12px; }
                .receipt-header h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
                .receipt-header p { font-size: 14px; opacity: 0.9; }
                .receipt-body { padding: 24px; }
                .info-card { background: #f9fafb; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
                .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
                .info-label { color: #6b7280; font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
                .info-value { font-weight: 600; margin-top: 2px; }
                .info-value.small { font-size: 12px; color: #6b7280; }
                .section-title { color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; }
                .divider { height: 2px; background: linear-gradient(90deg, transparent, #059669, transparent); margin: 20px 0; }
                table { width: 100%; border-collapse: collapse; }
                .total-section { background: #f0fdf4; border-radius: 12px; padding: 16px; margin-top: 20px; border: 1px solid #bbf7d0; }
                .total-row { display: flex; justify-content: space-between; padding: 6px 0; }
                .total-row.main { border-top: 1px solid #bbf7d0; margin-top: 8px; padding-top: 12px; }
                .total-label { color: #374151; }
                .total-value { font-weight: 600; }
                .total-value.paid { color: #059669; }
                .total-value.balance { font-size: 18px; }
                .total-value.balance-due { color: #dc2626; }
                .receipt-footer { background: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb; }
                .footer-text { color: #6b7280; font-weight: 500; margin-bottom: 4px; }
                .footer-subtext { color: #9ca3af; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='receipt-container'>
                <div class='receipt-header'>
                    <svg class='header-icon' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'></path>
                    </svg>
                    <h1>LAUNDRY RECEIPT</h1>
                    <p>Order #" . str_pad($order->id, 3, '0', STR_PAD_LEFT) . "</p>
                </div>
                
                <div class='receipt-body'>
                    <div class='info-card'>
                        <div class='info-grid'>
                            <div>
                                <div class='info-label'>Date</div>
                                <div class='info-value'>" . $order->created_at->format('M d, Y') . "</div>
                                <div class='info-value small'>" . $order->created_at->format('h:i A') . "</div>
                            </div>
                            <div>
                                <div class='info-label'>Branch</div>
                                <div class='info-value'>" . ($order->branch ?? 'N/A') . "</div>
                            </div>
                        </div>
                    </div>
                    
                    " . $serviceTypeHtml . "
                    
                    <div style='margin-bottom: 20px;'>
                        <div class='info-label'>Customer Name</div>
                        <div style='font-weight: 700; font-size: 18px;'>" . e($order->customer->name) . "</div>
                        <div style='color: #4b5563;'>" . e($order->customer->phone) . "</div>
                    </div>
                    
                    <div class='divider'></div>
                    
                    <div style='margin-bottom: 20px;'>
                        <div class='section-title'>Items</div>
                        <table><tbody>" . $itemsHtml . "</tbody></table>
                    </div>
                    
                    <div class='total-section'>
                        <div class='total-row'>
                            <span class='total-label'>Total</span>
                            <span class='total-value'>GH₵" . number_format($order->total_amount, 2) . "</span>
                        </div>
                        <div class='total-row'>
                            <span class='total-label'>Paid</span>
                            <span class='total-value paid'>GH₵" . number_format($order->amount_paid, 2) . "</span>
                        </div>
                        <div class='total-row main'>
                            <span style='font-weight: 700;'>Balance</span>
                            <span class='total-value balance " . ($order->balance > 0 ? 'balance-due' : 'paid') . "'>GH₵" . number_format($order->balance, 2) . "</span>
                        </div>
                    </div>
                    
                    " . $statusBadge . "
                </div>
                
                <div class='receipt-footer'>
                    <div class='footer-text'>Thank you for your business!</div>
                    <div class='footer-subtext'>Please come again</div>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
