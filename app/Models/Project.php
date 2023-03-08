<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory, HasSlug;

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

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    protected function defineSluggableField(): string
    {
        return 'title';
    }
}
