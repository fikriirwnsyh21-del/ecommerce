<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'order_item_id' => ['required', 'exists:order_items,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_item_id.required' => 'Item pesanan tidak ditemukan.',
            'order_item_id.exists' => 'Item pesanan tidak valid.',
            'rating.required' => 'Silakan pilih rating bintang 1 sampai 5.',
            'rating.integer' => 'Rating harus berupa angka bintang.',
            'rating.between' => 'Rating harus di antara 1 sampai 5 bintang.',
            'comment.max' => 'Ulasan maksimal 1.000 karakter.',
            'photo.image' => 'Berkas harus berupa file gambar.',
            'photo.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
