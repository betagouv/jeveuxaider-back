<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domainable extends Model
{
    protected $table = 'domainables';

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = ['domaine_id', 'domainable_type', 'domainable_id', 'field'];

    public $timestamps = false;

    public function domainables()
    {
        return $this->morphTo();
    }
}
