<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructureTag extends Model
{
    protected $table = 'structures_tags';

    protected $guarded = ['id'];

    protected $casts = [
        //
    ];

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

}
