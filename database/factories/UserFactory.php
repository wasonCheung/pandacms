<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => $this->faker->dateTimeThisYear(),
            'password' => Hash::make('123456'),
            'display_name' => $this->faker->name(),
        ];
    }
}
