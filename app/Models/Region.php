<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
