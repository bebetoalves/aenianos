<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Visit extends Model
{
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
