<?php

namespace Database\Factories;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['percentage', 'fixed']);
        $amount = $type === 'percentage' ? fake()->randomElement([10, 15, 20, 25]) : fake()->randomElement([15000, 25000, 50000]);

        return [
            'code' => strtoupper(fake()->unique()->lexify('PROMO????')),
            'name' => 'Diskon Spesial Marketplace',
            'type' => $type,
            'amount' => $amount,
            'min_purchase' => 50000,
            'max_discount' => $type === 'percentage' ? 50000 : null,
            'quota' => 500,
            'used_count' => fake()->numberBetween(5, 50),
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(30),
            'is_active' => true,
        ];
    }
}
