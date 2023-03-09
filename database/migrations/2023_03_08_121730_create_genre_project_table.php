<?php

use App\Models\Genre;
use App\Models\Project;
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
        Schema::create('genre_project', function (Blueprint $table) {
            $table->foreignIdFor(Genre::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_project');
    }
};
