<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition(): array
    {
        $name = fake()->unique()->company().' Official';
        $cities = ['Jakarta Pusat', 'Jakarta Barat', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Tangerang', 'Bekasi', 'Yogyakarta'];

        return [
            'user_id' => User::factory()->seller(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'logo' => null,
            'banner' => null,
            'city' => fake()->randomElement($cities),
            'province' => 'DKI Jakarta',
            'district' => 'Gambir',
            'address' => fake()->streetAddress(),
            'rating' => fake()->randomFloat(2, 4.5, 5.0),
            'followers_count' => fake()->numberBetween(100, 15000),
            'is_active' => true,
        ];
    }
}
