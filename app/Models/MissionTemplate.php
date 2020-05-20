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
        'priority',
        'published',
        'domaine_id'
    ];

    protected $attributes = [
        'priority' => false,
        'published' => true
    ];

    protected $casts = [
        'priority' => 'boolean',
        'published' => 'boolean',
    ];

    public function domaine()
    {
        return $this->belongsTo('App\Models\Tag');
    }
}
