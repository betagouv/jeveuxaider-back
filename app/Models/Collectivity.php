<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collectivity extends Model
{
    protected $table = 'collectivities';

    protected $fillable = [
        'title',
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
}
