<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{

    protected $table = 'terms';

    protected $guarded = ['id'];

    protected $casts = [
        'is_archived' => 'boolean',
        'properties' => 'json',
    ];

    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class);
    }

    public function parent()
    {
        return $this->belongsTo(Term::class);
    }

    public function children()
    {
        return $this->hasMany(Term::class, 'parent_id', 'id');
    }

    public function related()
    {
        return $this->hasMany(Termable::class);
    }
}
