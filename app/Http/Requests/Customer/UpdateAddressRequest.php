<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->id === $this->route('address')?->user_id;
    }

    public function rules(): array
    {
        return [
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'label' => ['required', 'string', 'max:50'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'address_line' => ['required', 'string', 'max:500'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'label.required' => 'Label alamat wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'city.required' => 'Kota / Kabupaten wajib diisi.',
            'district.required' => 'Kecamatan wajib diisi.',
            'postal_code.required' => 'Kode pos wajib diisi.',
            'postal_code.max' => 'Kode pos maksimal 10 karakter.',
            'address_line.required' => 'Alamat lengkap wajib diisi.',
            'address_line.max' => 'Alamat lengkap maksimal 500 karakter.',
        ];
    }
}
