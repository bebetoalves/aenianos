<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Genre;
use App\Models\Post;
use App\Models\Project;
use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        Faq::factory(10)->create();

        Post::factory(100)->create();

        Genre::factory(10)->create();

        Project::factory(100)->create()->each(function (Project $project) {
            $genres = Genre::all()->random(rand(0, 3))->pluck('id')->toArray();

            $project->genres()->attach($genres);
        });

        Server::factory(10)->create();
    }
}
