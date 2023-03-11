<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Genre;
use App\Models\Highlight;
use App\Models\Link;
use App\Models\Post;
use App\Models\Progression;
use App\Models\Project;
use App\Models\Server;
use App\Models\User;
use App\Models\Visit;
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
        Server::factory(10)->create();

        Project::factory(50)->create()->each(function (Project $project) {
            $genres = Genre::all()->random(rand(0, 3))->pluck('id')->toArray();
            $relatedProject = Project::all()->random(rand(0, 5))->pluck('id')->toArray();

            $project->genres()->attach($genres);
            $project->relatedProjects()->attach($relatedProject);
        });

        Link::factory(50)->create();
        Progression::factory(5)->create();
        Highlight::factory(5)->create();
        Visit::factory(300)->create();
    }
}
