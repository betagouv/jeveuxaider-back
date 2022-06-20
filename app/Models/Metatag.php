<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metatag extends Model
{
    protected $fillable = [
        'properties',
        'metatagable_type',
        'metatagable_id'
    ];

    protected $casts = [
        'properties' => 'json',
    ];

    public function metatagable()
    {
        return $this->morphTo();
    }
}
