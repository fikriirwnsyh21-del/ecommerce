<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'role_id' => Role::where('slug', 'customer')->value('id') ?? 3,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '08'.fake()->numerify('##########'),
            'avatar' => null,
            'is_active' => true,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('slug', 'admin')->value('id') ?? 1,
        ]);
    }

    public function seller(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('slug', 'seller')->value('id') ?? 2,
        ]);
    }

    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('slug', 'customer')->value('id') ?? 3,
        ]);
    }
}
