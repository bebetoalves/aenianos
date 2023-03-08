<?php

namespace App\Models;

use App\Casts\StateArray;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Progression extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'states',
        'project_id',
    ];

    protected $casts = [
        'states' => StateArray::class,
    ];

    public function project(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
}
