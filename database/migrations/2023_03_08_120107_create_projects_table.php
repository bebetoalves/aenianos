<?php

use App\Enums\Category;
use App\Enums\Season;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('alternative_title')->nullable();
            $table->text('synopsis');
            $table->string('episodes');
            $table->string('year');
            $table->string('season')->default(Season::WINTER);
            $table->string('category')->default(Category::SERIES);
            $table->string('miniature');
            $table->string('cover');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
