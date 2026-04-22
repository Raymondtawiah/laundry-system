<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,in_progress,ready,completed,cancelled'],
        ];
    }
}
