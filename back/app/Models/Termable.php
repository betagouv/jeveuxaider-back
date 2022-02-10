<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termable extends Model
{

    protected $table = 'termables';

    public function termables()
    {
        return $this->morphTo();
    }
}
