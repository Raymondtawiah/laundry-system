<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Item;

class CreateOrder extends Component
{
    public $customers = [];
    public $items = [];
    public $orderItems = [];
    public $totalAmount = 0;
    public $notes = '';

    public function mount()
    {
        $this->customers = Customer::where('laundry_id', auth()->user()->laundry_id)
            ->orderBy('name')
            ->get()
            ->toArray();
            
        $this->items = Item::where('laundry_id', auth()->user()->laundry_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->toArray();
            
        // Add one empty item row by default
        $this->addItem();
    }

    public function addItem()
    {
        $this->orderItems[] = [
            'item_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'subtotal' => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
        $this->calculateTotal();
    }

    public function onItemChange($index)
    {
        if (isset($this->orderItems[$index]['item_id']) && $this->orderItems[$index]['item_id']) {
            $item = Item::find($this->orderItems[$index]['item_id']);
            if ($item) {
                $this->orderItems[$index]['unit_price'] = (float) $item->price;
                $this->orderItems[$index]['subtotal'] = (float) $item->price * (int) $this->orderItems[$index]['quantity'];
            }
        }
        $this->calculateTotal();
    }

    public function onQuantityChange($index)
    {
        if (isset($this->orderItems[$index]['quantity'])) {
            $quantity = (int) $this->orderItems[$index]['quantity'];
            $unitPrice = (float) $this->orderItems[$index]['unit_price'];
            $this->orderItems[$index]['subtotal'] = $unitPrice * $quantity;
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalAmount = 0;
        foreach ($this->orderItems as $item) {
            if (!empty($item['item_id']) && !empty($item['quantity'])) {
                $this->totalAmount += $item['subtotal'];
            }
        }
    }

    public function render()
    {
        return view('livewire.create-order');
    }
}
