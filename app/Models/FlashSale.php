<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'discount_price',
        'stock_quota',
        'sold_count',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_price' => 'decimal:2',
            'stock_quota' => 'integer',
            'sold_count' => 'integer',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getPercentageSoldAttribute(): int
    {
        if ($this->stock_quota <= 0) {
            return 100;
        }

        return (int) min(100, round(($this->sold_count / $this->stock_quota) * 100));
    }
}
