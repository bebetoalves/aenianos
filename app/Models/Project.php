<?php

namespace App\Models;

use App\Enums\Category;
use App\Enums\Season;
use App\Models\Concerns\HasSlugField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory, HasSlugField;

    protected $fillable = [
        'title',
        'alternative_title',
        'synopsis',
        'episodes',
        'year',
        'season',
        'category',
        'miniature',
        'cover',
    ];

    protected $casts = [
        'season' => Season::class,
        'category' => Category::class,
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function highlight(): HasOne
    {
        return $this->hasOne(Highlight::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function progression(): HasOne
    {
        return $this->hasOne(Progression::class);
    }

    public function relatedProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'related_project', 'project_id', 'related_project_id');
    }

    protected function defineSluggableField(): string
    {
        return 'title';
    }
}
