<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'register_as_seller' => ['nullable', 'boolean'],
            'shop_name' => ['required_if:register_as_seller,1', 'nullable', 'string', 'max:255', 'unique:shops,name'],
            'shop_city' => ['required_if:register_as_seller,1', 'nullable', 'string', 'max:255'],
        ];
    }
}
