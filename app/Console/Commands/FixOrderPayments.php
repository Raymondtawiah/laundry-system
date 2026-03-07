<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class FixOrderPayments extends Command
{
    protected $signature = 'orders:fix-payments';

    protected $description = 'Fix payment fields for existing orders';

    public function handle()
    {
        $orders = Order::whereNull('balance')->orWhere('balance', '')->get();
        
        foreach ($orders as $order) {
            $order->balance = $order->total_amount;
            $order->amount_paid = 0;
            $order->payment_status = 'unpaid';
            $order->save();
        }
        
        $this->info('Fixed ' . $orders->count() . ' orders.');
        
        return 0;
    }
}
