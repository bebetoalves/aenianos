<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class HighlightFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cover' => placekitten(width: 1280, height: 720),
            'project_id' => Project::factory(),
        ];
    }
}
