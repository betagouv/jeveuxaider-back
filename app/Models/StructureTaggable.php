<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructureTaggable extends Model
{
    protected $table = 'structures_taggables';

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = ['structure_tag_id', 'taggable_type', 'taggable_id'];

    public $timestamps = false;

    public function taggables()
    {
        return $this->morphTo();
    }
}
