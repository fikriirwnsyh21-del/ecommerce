<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'discount_price',
        'stock',
        'weight',
        'condition',
        'rating_avg',
        'reviews_count',
        'sales_count',
        'views_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'rating_avg' => 'decimal:2',
            'stock' => 'integer',
            'weight' => 'integer',
            'reviews_count' => 'integer',
            'sales_count' => 'integer',
            'views_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function activeFlashSale(): HasOne
    {
        return $this->hasOne(FlashSale::class)
            ->where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now());
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get effective current price considering flash sale or discount price.
     */
    public function getFinalPriceAttribute(): float
    {
        if ($this->relationLoaded('activeFlashSale') && $this->activeFlashSale) {
            return (float) $this->activeFlashSale->discount_price;
        }

        if ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return (float) $this->discount_price;
        }

        return (float) $this->price;
    }

    /**
     * Calculate discount percentage if any.
     */
    public function getDiscountPercentAttribute(): ?int
    {
        $finalPrice = $this->final_price;
        if ($finalPrice < $this->price && $this->price > 0) {
            return (int) round((($this->price - $finalPrice) / $this->price) * 100);
        }

        return null;
    }

    /**
     * Helper to get first image or placeholder.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->relationLoaded('primaryImage') && $this->primaryImage) {
            return $this->primaryImage->url;
        }

        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            $img = $this->images->firstWhere('is_primary', true) ?? $this->images->first();
            if ($img) {
                return $img->url;
            }
        }

        if ($this->primaryImage) {
            return $this->primaryImage->url;
        }

        $firstImg = $this->images->first();
        if ($firstImg) {
            return $firstImg->url;
        }

        return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';
    }
}
