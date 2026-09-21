<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar_url' => $this->avatar ? asset('storage/'.$this->avatar) : null,
            'role' => $this->role?->slug ?? 'customer',
            'is_active' => (bool) $this->is_active,
            'shop' => $this->shop ? [
                'id' => $this->shop->id,
                'name' => $this->shop->name,
                'slug' => $this->shop->slug,
                'city' => $this->shop->city,
                'logo_url' => $this->shop->logo ? asset('storage/'.$this->shop->logo) : null,
            ] : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
