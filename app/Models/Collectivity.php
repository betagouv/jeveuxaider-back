<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Collectivity extends Model
{
    protected $table = 'collectivities';

    protected $fillable = [
        'title',
        'slug',
        'zips',
        'type',
        'department',
        'description',
        'state',
        'profile_id'
    ];

    protected $casts = [
        'zips' => 'array'
    ];

    protected $attributes = [
        'state' => 'validated'
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
