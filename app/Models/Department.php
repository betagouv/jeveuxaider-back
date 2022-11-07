<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = false;

    protected $fillable = ['number', 'name', 'region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
