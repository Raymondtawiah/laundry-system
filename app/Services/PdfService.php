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
            
            Log::info('Generating PDF for order: ' . $order->id);
            Log::info('HTML length: ' . strlen($html));
            
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'Arial');
            $options->set('isPhpEnabled', true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            
            $pdfContent = $dompdf->output();
            
            Log::info('PDF generated, size: ' . strlen($pdfContent));
            
            $filename = 'receipt-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) . '.pdf';
            $path = 'receipts/' . $filename;
            
            Storage::disk('public')->put($path, $pdfContent);
            
            Log::info("PDF receipt generated: {$path}");
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error("PDF generation failed: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Get the HTML content for the receipt with Dompdf-compatible table layout.
     */
    private function getReceiptHtml(Order $order): string
    {
        $itemsRows = '';
        
        if ($order->items && $order->items->count() > 0) {
            foreach ($order->items as $item) {
                $itemsRows .= '<tr>
                    <td style="padding:8px; border-bottom:1px dashed #e5e7eb;">
                        <strong>' . e($item->name) . '</strong><br/>
                        <span style="color:#6b7280; font-size:11px;">GH₵' . number_format($item->pivot->unit_price, 2) . ' x ' . $item->pivot->quantity . '</span>
                    </td>
                    <td style="padding:8px; text-align:right; border-bottom:1px dashed #e5e7eb; font-weight:bold;">
                        GH₵' . number_format($item->pivot->subtotal, 2) . '
                    </td>
                </tr>';
            }
        } else {
            $itemsRows = '<tr><td colspan="2" style="padding:8px; text-align:center; color:#6b7280;">No items</td></tr>';
        }
        
        // Service type badge
        $serviceTypeHtml = '';
        if ($order->service_type) {
            $serviceLabels = [
                'washing' => 'Executive Wear',
                'ironing' => 'Native Wear',
                'drying' => 'Ladies Wear',
                'bag wash' => 'Bag Wash',
                'bedding_decor' => 'Bedding and Decor',
                'sneakers' => 'Sneakers',
                'bag' => 'Bag',
                'deep_cleaning' => 'Deep Cleaning',
            ];
            
            $serviceTypes = is_array($order->service_type) ? $order->service_type : json_decode($order->service_type, true);
            if (is_string($serviceTypes)) {
                $serviceTypes = [$serviceTypes];
            }
            
            if ($serviceTypes && count($serviceTypes) > 0) {
                $badges = [];
                foreach ($serviceTypes as $serviceType) {
                    $serviceName = $serviceLabels[$serviceType] ?? ucfirst(str_replace('_', ' ', $serviceType));
                    $badges[] = $serviceName;
                }
                $serviceTypeHtml = '<tr><td colspan="2" style="text-align:center; padding:8px 0; color:#92400e; font-weight:bold; font-size:12px;">' . implode(' | ', $badges) . '</td></tr>';
            }
        }
        
        // Status badge
        $statusRow = '';
        if ($order->status === 'ready') {
            $statusRow = '<tr><td colspan="2" style="text-align:center; padding:12px 0; color:#166534; font-weight:bold;">Ready for Pickup</td></tr>';
        } elseif ($order->status === 'completed') {
            $statusRow = '<tr><td colspan="2" style="text-align:center; padding:12px 0; color:#1e40af; font-weight:bold;">Completed</td></tr>';
        }
        
        $balanceColor = $order->balance > 0 ? '#dc2626' : '#059669';
        
        $modeOfPayment = '';
        if ($order->mode_of_payment) {
            $modeLabel = $order->mode_of_payment;
            $modeRow = '<tr>
                <td style="padding:6px 0; font-weight:bold;">Mode of Payment</td>
                <td style="padding:6px 0; text-align:right; font-weight:bold;">' . e($modeLabel) . '</td>
            </tr>';
        } else {
            $modeRow = '';
        }

        $orderNumber = str_pad($order->id, 3, '0', STR_PAD_LEFT);

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Receipt - Order #' . $orderNumber . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                table { width: 100%; border-collapse: collapse; }
            </style>
        </head>
        <body>
            <table>
                <!-- Header -->
                <tr>
                    <td colspan="2" style="background:#059669; color:white; padding:24px; text-align:center;">
                        <h1 style="margin:0; font-size:22px;">LAUNDRY RECEIPT</h1>
                        <p style="margin:4px 0 0 0; font-size:14px;">Order #' . $orderNumber . '</p>
                    </td>
                </tr>
                
                <tr><td colspan="2" style="height:20px;"></td></tr>
                
                <!-- Date & Branch -->
                <tr>
                    <td style="padding:12px; background:#f9fafb; width:50%; vertical-align:top;">
                        <div style="color:#6b7280; font-size:10px; text-transform:uppercase; font-weight:bold;">Date</div>
                        <div style="font-weight:bold; margin-top:4px;">' . $order->created_at->format('M d, Y') . '</div>
                        <div style="color:#6b7280; font-size:12px;">' . $order->created_at->format('h:i A') . '</div>
                    </td>
                    <td style="padding:12px; background:#f9fafb; width:50%; vertical-align:top;">
                        <div style="color:#6b7280; font-size:10px; text-transform:uppercase; font-weight:bold;">Branch</div>
                        <div style="font-weight:bold; margin-top:4px;">' . e($order->branch ?? 'N/A') . '</div>
                    </td>
                </tr>
                
                <tr><td colspan="2" style="height:16px;"></td></tr>
                
                <!-- Service Type -->
                ' . $serviceTypeHtml . '
                
                <!-- Customer -->
                <tr>
                    <td colspan="2" style="padding:0 0 16px 0;">
                        <div style="color:#6b7280; font-size:10px; text-transform:uppercase; font-weight:bold;">Customer Name</div>
                        <div style="font-weight:bold; font-size:18px; margin-top:4px;">' . e($order->customer->name) . '</div>
                        <div style="color:#4b5563;">' . e($order->customer->phone) . '</div>
                    </td>
                </tr>
                
                <!-- Divider -->
                <tr><td colspan="2" style="border-top:2px solid #059669; height:4px;"></td></tr>
                <tr><td colspan="2" style="height:12px;"></td></tr>
                
                <!-- Items Header -->
                <tr>
                    <td colspan="2" style="color:#6b7280; font-size:10px; text-transform:uppercase; font-weight:bold; padding-bottom:8px;">
                        Items
                    </td>
                </tr>
                
                <!-- Items -->
                ' . $itemsRows . '
                
                <tr><td colspan="2" style="height:16px;"></td></tr>
                
                <!-- Totals -->
                <tr>
                    <td colspan="2" style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:16px;">
                        <table>
                            <tr>
                                <td style="padding:6px 0;">Total</td>
                                <td style="padding:6px 0; text-align:right; font-weight:bold;">GH₵' . number_format($order->total_amount, 2) . '</td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;">Paid</td>
                                <td style="padding:6px 0; text-align:right; font-weight:bold; color:#059669;">GH₵' . number_format($order->amount_paid, 2) . '</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-top:1px solid #bbf7d0; height:8px;"></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0; font-weight:bold; font-size:16px;">Balance</td>
                                <td style="padding:6px 0; text-align:right; font-weight:bold; font-size:18px; color:' . $balanceColor . ';">GH₵' . number_format($order->balance, 2) . '</td>
                            </tr>
                            ' . $modeRow . '
                        </table>
                    </td>
                </tr>
                
                <!-- Status -->
                ' . $statusRow . '
                
                <tr><td colspan="2" style="height:16px;"></td></tr>
                
                <!-- Footer -->
                <tr>
                    <td colspan="2" style="background:#f9fafb; padding:20px; text-align:center; border-top:1px solid #e5e7eb;">
                        <div style="font-weight:bold; color:#6b7280;">Thank you for your business!</div>
                        <div style="color:#9ca3af; font-size:12px;">Please come again</div>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ';
    }
}
