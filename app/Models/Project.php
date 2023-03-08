<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected function defineSluggableField(): string
    {
        return 'title';
    }
}
