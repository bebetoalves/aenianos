<?php

namespace Database\Factories;

use App\Enums\State;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgressionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => sprintf('Episode %d', fake()->randomNumber(nbDigits: 2)),
            'states' => fake()->randomElements(State::asArray(), rand(1, 3)),
            'project_id' => Project::factory(),
        ];
    }
}
