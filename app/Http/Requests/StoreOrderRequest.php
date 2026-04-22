<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'delivery_type' => ['required', 'in:pickup,doorstep'],
            'pickup_type' => ['nullable', 'in:door_pick,self_pick'],
            'service_types' => ['required', 'array', 'min:1'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'mode_of_payment' => ['nullable'],
            'notes' => ['nullable'],
        ];
    }
}
