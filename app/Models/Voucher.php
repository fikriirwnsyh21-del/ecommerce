<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'amount',
        'min_purchase',
        'max_discount',
        'quota',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'min_purchase' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'quota' => 'integer',
            'used_count' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function isValid(float $subtotal, ?int $userId = null): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if (now()->lt($this->start_date) || now()->gt($this->end_date)) {
            return false;
        }

        if ($this->used_count >= $this->quota) {
            return false;
        }

        if ($subtotal < $this->min_purchase) {
            return false;
        }

        if ($userId) {
            $userUsage = $this->usages()->where('user_id', $userId)->count();
            if ($userUsage > 0) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->amount) / 100;
            if ($this->max_discount && $this->max_discount > 0) {
                $discount = min($discount, (float) $this->max_discount);
            }

            return round($discount, 2);
        }

        return min($subtotal, (float) $this->amount);
    }
}
