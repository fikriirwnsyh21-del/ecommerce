<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $price = $this->product_variant_id && $this->variant
            ? (float) ($this->product->price + $this->variant->price_adjustment)
            : (float) $this->product->final_price;

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'product_slug' => $this->product->slug,
            'thumbnail_url' => $this->product->thumbnail_url,
            'shop_name' => $this->product->shop->name ?? '-',
            'variant' => $this->variant ? [
                'id' => $this->variant->id,
                'name' => $this->variant->value,
            ] : null,
            'price' => $price,
            'quantity' => (int) $this->quantity,
            'subtotal' => $price * (int) $this->quantity,
            'is_selected' => (bool) $this->is_selected,
        ];
    }
}
