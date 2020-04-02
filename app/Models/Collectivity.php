<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collectivity extends Model
{
    protected $table = 'collectivities';

    protected $fillable = [
        'name',
        'zips',
        'type',
        'description',
        'instagram',
        'facebook',
        'twitter',
        'state',
        'profile_id'
    ];

    protected $casts = [
        'zips' => 'array'
    ];

    protected $attributes = [
        'state' => 'En attente de validation'
    ];

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }
}
