<?php

namespace App\Models\Concerns;

use Spatie\Sluggable\SlugOptions;

trait HasSlug
{
    use \Spatie\Sluggable\HasSlug;

    private string $databaseSlugField = 'slug';

    protected function defineSluggableField(): string
    {
        return '';
    }

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
}
