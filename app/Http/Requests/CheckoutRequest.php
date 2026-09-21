<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'address_id' => ['required', 'exists:addresses,id'],
            'shipping_courier' => ['required', 'string', 'max:150'],
            'payment_method' => ['required', 'string', 'max:100'],
            'payment_channel' => ['nullable', 'string', 'max:50'],
            'voucher_code' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'address_id.required' => 'Silakan pilih alamat pengiriman.',
            'address_id.exists' => 'Alamat pengiriman tidak valid.',
            'shipping_courier.required' => 'Pilih kurir pengiriman.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ];
    }
}
