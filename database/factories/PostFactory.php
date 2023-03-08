<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->realText(),
            'image' => placekitten(width: 1280, height: 720),
            'draft' => fake()->boolean(),
            'user_id' => User::factory(),
        ];
    }
}
