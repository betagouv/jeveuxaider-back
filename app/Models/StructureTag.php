<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructureTag extends Model
{
    protected $table = 'structures_tags';

    protected $fillable = ['name', 'structure_id', 'is_generic'];

    protected $casts = [
        'is_generic' => 'boolean',
    ];

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

}
