<?php

namespace App\Models;

use App\Casts\StateArray;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progression extends Model
{
    use HasFactory;

    protected $fillable = [
        'media',
        'states',
        'project_id',
    ];

    protected $casts = [
        'states' => StateArray::class,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
