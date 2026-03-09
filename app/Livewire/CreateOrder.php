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
    public $service_types = [];

    public function mount()
    {
        $this->customers = Customer::where('laundry_id', auth()->user()->laundry_id)
            ->when(auth()->user()->role === 'staff' && auth()->user()->branch, function($query) {
                $query->where('branch', auth()->user()->branch);
            })
            ->orderBy('name')
            ->get()
            ->toArray();
            
        $this->items = $this->getItemsByServiceTypes();
        
        // Add one empty item row by default
        $this->addItem();
    }

    public function getItemsByServiceTypes()
    {
        $query = Item::where('laundry_id', auth()->user()->laundry_id)
            ->where('is_active', true);
            
        // Filter items by selected service types/categories
        if (!empty($this->service_types)) {
            $categoryMap = [
                'washing' => 'Executive Wear',
                'ironing' => 'Native Wear',
                'drying' => 'Ladies Wear',
                'bag wash' => 'Bag Wash',
                'bedding_decor' => 'Bedding and Decor',
                'sneakers' => 'Sneakers',
                'bag' => 'Bag',
                'deep_cleaning' => 'Ironing',
            ];
            
            $categories = [];
            foreach ($this->service_types as $serviceType) {
                if (isset($categoryMap[$serviceType])) {
                    $categories[] = $categoryMap[$serviceType];
                }
            }
            
            if (!empty($categories)) {
                $query->whereIn('category', $categories);
            }
        }
        
        return $query->orderBy('name')->get()->toArray();
    }

    public function updatedServiceTypes()
    {
        // Reset items when service types change
        $this->items = $this->getItemsByServiceTypes();
        
        // Reset order items
        $this->orderItems = [];
        $this->addItem();
    }

    public function addServiceType($value)
    {
        if ($value && !in_array($value, $this->service_types)) {
            $this->service_types[] = $value;
            $this->items = $this->getItemsByServiceTypes();
            $this->orderItems = [];
            $this->addItem();
        }
    }

    public function removeServiceType($value)
    {
        $this->service_types = array_filter($this->service_types, function($item) use ($value) {
            return $item !== $value;
        });
        $this->items = $this->getItemsByServiceTypes();
        $this->orderItems = [];
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
