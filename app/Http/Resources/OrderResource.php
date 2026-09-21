<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'subtotal' => (float) $this->subtotal,
            'shipping_cost' => (float) $this->shipping_cost,
            'discount_amount' => (float) $this->discount_amount,
            'grand_total' => (float) $this->grand_total,
            'shipping_courier' => $this->shipping_courier,
            'tracking_number' => $this->tracking_number,
            'shipping_address' => $this->shipping_address,
            'payment' => $this->whenLoaded('payment', fn () => [
                'method' => $this->payment->payment_method,
                'status' => $this->payment->status,
                'paid_at' => $this->payment->paid_at?->toIso8601String(),
            ]),
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'variant_name' => $item->variant_name,
                'price' => (float) $item->price,
                'quantity' => (int) $item->quantity,
                'subtotal' => (float) $item->subtotal,
                'is_reviewed' => (bool) $item->is_reviewed,
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
