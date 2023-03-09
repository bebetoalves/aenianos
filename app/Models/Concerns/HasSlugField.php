<?php

namespace App\Models\Concerns;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

trait HasSlugField
{
    use HasSlug;

    private string $databaseSlugField = 'slug';

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->defineSluggableField())
            ->saveSlugsTo($this->databaseSlugField);
    }

    public function getRouteKeyName(): string
    {
        return $this->databaseSlugField;
    }

    protected function defineSluggableField(): string
    {
        return '';
    }
}
