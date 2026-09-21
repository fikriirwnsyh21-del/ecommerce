<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => (float) $this->price,
            'discount_price' => $this->discount_price ? (float) $this->discount_price : null,
            'final_price' => (float) $this->final_price,
            'discount_percent' => $this->discount_percent,
            'stock' => (int) $this->stock,
            'weight' => (int) $this->weight,
            'condition' => $this->condition,
            'rating_avg' => (float) $this->rating_avg,
            'reviews_count' => (int) $this->reviews_count,
            'sales_count' => (int) $this->sales_count,
            'thumbnail_url' => $this->thumbnail_url,
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($img) => [
                'id' => $img->id,
                'image_url' => asset('storage/'.$img->image_path),
                'is_primary' => (bool) $img->is_primary,
            ])),
            'variants' => $this->whenLoaded('variants', fn () => $this->variants->map(fn ($v) => [
                'id' => $v->id,
                'name' => $v->value,
                'sku' => $v->sku,
                'price' => (float) ($this->price + $v->price_adjustment),
                'stock' => (int) $v->stock,
            ])),
            'shop' => $this->whenLoaded('shop', fn () => [
                'id' => $this->shop->id,
                'name' => $this->shop->name,
                'slug' => $this->shop->slug,
                'city' => $this->shop->city,
                'rating' => (float) $this->shop->rating,
                'logo_url' => $this->shop->logo ? asset('storage/'.$this->shop->logo) : null,
            ]),
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
        ];
    }
}
