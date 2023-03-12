<?php

namespace Database\Factories;

use App\Enums\Category;
use App\Enums\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(nbWords: 2),
            'alternative_title' => fake()->randomElement([null, fake()->sentence(nbWords: 2)]),
            'synopsis' => fake()->realText(maxNbChars: 500),
            'episodes' => fake()->randomNumber(nbDigits: 2),
            'year' => fake()->year(),
            'season' => Season::getRandomValue(),
            'category' => Category::getRandomValue(),
            'miniature' => placekitten(width: 640, height: 905),
            'cover' => placekitten(width: 1280, height: 720),
        ];
    }
}
