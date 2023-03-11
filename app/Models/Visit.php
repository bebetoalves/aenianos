<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'visitable_id',
        'visitable_type',
    ];

    public function visitable(): MorphTo
    {
        return $this->morphTo();
    }
}
