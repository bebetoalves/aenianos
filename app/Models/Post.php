<?php

namespace App\Models;

use App\Models\Concerns\HasSlugField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Post extends Model
{
    use HasFactory, HasSlugField, HasSEO;

    protected $fillable = [
        'title',
        'content',
        'image',
        'draft',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function visits(): MorphMany
    {
        return $this->morphMany(Visit::class, 'visitable');
    }

    public function defineSluggableField(): string
    {
        return 'title';
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->content,
            author: $this->user->name,
            image: image_url($this->image),
            published_time: $this->created_at,
        );
    }
}
