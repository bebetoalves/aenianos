<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Genre;
use App\Models\Post;
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
    }
}
