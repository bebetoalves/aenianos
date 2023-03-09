<?php

namespace App\Models\Concerns;

use Spatie\Sluggable\SlugOptions;

trait HasSlug
{
    use \Spatie\Sluggable\HasSlug;

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
