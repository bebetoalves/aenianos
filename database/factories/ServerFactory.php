<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'icon' => placekitten(width: 32, height: 32),
        ];
    }
}
