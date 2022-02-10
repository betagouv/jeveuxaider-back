<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Term extends Model
{
    use Searchable;

    protected $table = 'terms';

    protected $guarded = ['id'];

    protected $casts = [
        'is_published' => 'boolean',
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

    public function profiles()
    {
        return $this->morphedByMany(Profile::class, 'termable');
    }

    public function missions()
    {
        return $this->morphedByMany(Mission::class, 'termable');
    }

    public function shouldBeSearchable()
    {
        return $this->is_published;
    }

    public function searchableAs()
    {
        return config('scout.prefix') . '_covid_terms';
    }
}
