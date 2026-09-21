<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(100, 2000) * 1000;
        $shipping = fake()->randomElement([10000, 18000, 25000, 35000]);
        $discount = fake()->randomElement([0, 10000, 20000]);
        $grandTotal = max(0, $subtotal + $shipping - $discount);

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.date('Ymd').'-'.fake()->unique()->numerify('######'),
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'discount_amount' => $discount,
            'grand_total' => $grandTotal,
            'status' => fake()->randomElement(['pending', 'paid', 'processing', 'shipped', 'delivered', 'completed']),
            'shipping_address' => [
                'recipient_name' => fake()->name(),
                'phone' => '08'.fake()->numerify('##########'),
                'address_line' => fake()->streetAddress(),
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'postal_code' => '12190',
            ],
            'shipping_courier' => fake()->randomElement(['Reguler (J&T Express)', 'Hemat (SiCepat HALU)', 'Express (JNE YES)']),
            'tracking_number' => 'TRACK'.fake()->bothify('??########'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
