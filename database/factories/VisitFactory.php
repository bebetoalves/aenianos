<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ip_address' => $this->faker->ipv4(),
            'visitable_id' => fn (array $attributes) => $attributes['visitable_type']::all()->random()->first()->getKey(),
            'visitable_type' => $this->faker->randomElement([
                Post::class,
                Project::class,
            ]),
        ];
    }
}
