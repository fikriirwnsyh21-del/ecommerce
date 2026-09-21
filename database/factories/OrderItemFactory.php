<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $price = fake()->numberBetween(50, 500) * 1000;
        $qty = fake()->numberBetween(1, 3);

        return [
            'order_id' => Order::factory(),
            'shop_id' => Shop::factory(),
            'product_id' => Product::factory(),
            'product_variant_id' => null,
            'product_name' => fake()->words(3, true),
            'variant_name' => null,
            'price' => $price,
            'quantity' => $qty,
            'subtotal' => $price * $qty,
            'is_reviewed' => false,
        ];
    }
}
