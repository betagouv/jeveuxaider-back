<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taggable extends Model
{

    protected $table = 'taggables';

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }

    public function taggables()
    {
        return $this->morphTo();
    }
}
