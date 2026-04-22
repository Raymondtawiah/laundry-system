<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
        ];
    }
}
