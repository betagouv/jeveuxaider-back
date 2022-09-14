<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termable extends Model
{
    protected $table = 'termables';

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = ['term_id', 'termable_type', 'termable_id', 'field'];

    public $timestamps = false;

    public function termables()
    {
        return $this->morphTo();
    }
}
