<?php

namespace App\Models;

use App\Models\Concerns\HasSlugField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory, HasSlugField;

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

    public function defineSluggableField(): string
    {
        return 'title';
    }
}
