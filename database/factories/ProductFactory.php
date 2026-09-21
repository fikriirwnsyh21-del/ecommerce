<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(fake()->numberBetween(3, 6), true);
        $price = fake()->numberBetween(25, 1200) * 10000;
        $hasDiscount = fake()->boolean(60);
        $discountPrice = $hasDiscount ? round($price * fake()->randomFloat(2, 0.65, 0.90), -3) : null;

        return [
            'shop_id' => Shop::factory(),
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('####'),
            'sku' => 'SKU-'.fake()->unique()->bothify('??-####'),
            'description' => fake()->paragraphs(3, true),
            'price' => $price,
            'discount_price' => $discountPrice,
            'stock' => fake()->numberBetween(10, 200),
            'weight' => fake()->randomElement([150, 250, 500, 1000, 1500]),
            'condition' => 'new',
            'rating_avg' => fake()->randomFloat(2, 4.2, 5.0),
            'reviews_count' => fake()->numberBetween(5, 500),
            'sales_count' => fake()->numberBetween(10, 2500),
            'views_count' => fake()->numberBetween(50, 10000),
            'is_active' => true,
        ];
    }
}
