<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipient_name' => fake()->name(),
            'phone' => '08'.fake()->numerify('##########'),
            'label' => fake()->randomElement(['Rumah', 'Kantor', 'Apartemen']),
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => fake()->numerify('12###'),
            'address_line' => fake()->streetAddress().' No. '.fake()->buildingNumber(),
            'is_primary' => false,
        ];
    }
}
