<?php

namespace Database\Factories;

use App\Enums\Quality;
use App\Models\Project;
use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => sprintf('Episode %d', fake()->randomNumber(nbDigits: 2)),
            'url' => fake()->url(),
            'quality' => Quality::getRandomValue(),
            'active' => fake()->boolean(),
            'project_id' => Project::factory(),
            'server_id' => Server::factory(),
        ];
    }
}
