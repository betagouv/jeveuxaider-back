<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MissionTemplate extends Model
{
    protected $table = 'mission_templates';

    protected $fillable = [
        'title',
        'subtitle',
        'objectif',
        'description',
        'published'
    ];

    protected $attributes = [
        'published' => true
    ];
}
