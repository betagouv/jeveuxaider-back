<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Vocabulary extends Model
{
    use HasSlug;

    protected $table = 'vocabularies';

    protected $guarded = ['id'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function terms()
    {
        return $this->hasMany(Term::class)->orderBy('weight', 'ASC');
    }
}
