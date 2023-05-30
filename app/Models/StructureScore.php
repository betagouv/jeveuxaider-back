<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructureScore extends Model
{
    protected $table = 'structures_scores';
    protected $primaryKey = null;
    protected $guarded = [];
    public $incrementing = false;

    public function structure()
    {
        return $this->belongsTo('App\Models\Structure', 'structure_id', 'id');
    }
}
